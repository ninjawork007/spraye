<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/sms/Send_Text.php';
require FCPATH . 'vendor/autoload.php';
require_once APPPATH . '/third_party/stripe-php/init.php';
require FCPATH . 'vendor/autoload.php';
ini_set('memory_limit', '-1');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;


class Welcome extends MY_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('parser');
        $this->load->library('encryption');
        $this->load->helper('text');
        $this->load->model('Invoice_model', 'INV');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Assign_job', 'AssignJobs');
        $this->load->model('Superadmin_model', 'Superadmin');
        $this->load->model('Customer_model', 'Customer');
        $this->load->helper('invoice_helper');
        $this->load->model('Company_sub_model', 'CompanySub');
        $this->load->helper('invoice_helper');
        $this->load->helper('cardconnect_helper');
        $this->load->model('../modules/admin/models/Cardconnect_model', 'CardConnect');
        $this->load->model('../modules/admin/models/payment_invoice_logs_model', 'PartialPaymentModel');
        $this->load->helper('estimate_helper');
        $this->load->model('Estimate_model', 'EstimateModal');
        $this->load->helper('report_helper');
        $this->load->model('../modules/admin/models/AdminTbl_coupon_model', 'CouponModel');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');
        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('../modules/admin/models/Administrator', 'Administrator');
        $this->load->model('../modules/admin/models/Refund_invoice_logs_model', 'RefundPaymentModel');
        $this->load->model('../modules/admin/models/AdminTbl_program_model', 'ProgramModel');
        $this->load->model('PurchasesModel', 'PurchasesModel');
        $this->load->model('../modules/admin/models/Company_email_model', 'CompanyEmail');
        $this->load->model('Logs_model', 'Log');

        $this->load->model('../modules/admin/models/AdminTbl_servive_area_model', 'ServiceArea');
        $this->load->model('../modules/admin/models/Sales_tax_model', 'SalesTax');
        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Reports_model', 'ReportsModel');
        $this->load->model('../modules/admin/models/Cardconnect_model', 'CardConnectModel');



        // $this->load->model('Customer_statement_model', 'CustomerStatement');

    }

    public function index()
    {
        $company_name = $this->uri->segment(2);
        $company_details = $this->CompanyModel->getOneCompany(array('slug' => $company_name));
        //die(print_r($company_details));
        if (!empty($company_details)) {
            $this->session->set_userdata('company_logo', $company_details->company_resized_logo);
            $this->session->set_userdata('invoice_color', $company_details->invoice_color);
            $this->session->set_userdata('slug', $company_name);
            $this->session->set_userdata('company_email', $company_details->company_email);
            $this->load->view('customers/customers_login');
        } else {
            #put back in 404
            show_error("The page you are looking for does not exists in this domain.", 404, "Invalid link");
        }
    }

    /**
     * Database backup.
     *  */
    public function dbSave($value = '')
    {
        $curent_date = date("Y-m-d");
        $days_ago = date('Y-m-d', strtotime('-5 days', strtotime($curent_date)));
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
        );
        $backup = $this->dbutil->backup($prefs);
        $db_name = 'spraye_db_' . $curent_date . '.sql';
        echo "db_name: " . $db_name;
        $old_db_name = 'spraye_db_' . $days_ago . '.sql';
        echo "<><> old_db_name: " . $db_name;
        // $save = 'upload/'.$db_name;
        $this->load->helper('file');
        $fp = fopen('databasefile' . '/' . $db_name, 'w+');
        if (($result = fwrite($fp, $backup))) {
            echo "Back Up Writing";
            if (file_exists('databasefile' . '/' . $old_db_name)) {
                echo "Removing old backup";
                unlink('databasefile/' . $old_db_name);
            }
            // echo "Backup file created '--$backup_file_name' ($result)";
        }
        fclose($fp);
        $body = "dbSave called at: " . date('Y-m-d H:i:s');
        $body .= "<><>One day prior: " . date('Y-m-d', strtotime(' -1 day'));
        $smtp_details = $this->Superadmin->getOneSuperAdmin();
        Send_Mail_dynamic($smtp_details, "mpatel@topdevzh.com", array("name" => "Test Company", "email" => "mpatel@topdevzh.com"), $body, 'Test dbSave Lambda');
    }
    /*** TRACK OPENED EMAILS FOR INVOICE ***/
    public function openedInvoicePixel()
    {

        if (!empty($_GET['invoice_id'])) {
            $invoice_id = $_GET['invoice_id'];
            $getInvoice = $this->INV->getOneInvoice($invoice_id);
            if (empty($getInvoice->opened_date)) {
                $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('opened_date' => date('Y-m-d H:i:s'), 'status' => 2));
            }
        }
    }
    /*** TRACK OPENED EMAILS FOR INVOICE ***/
    public function openedMultInvoicePixel()
    {

        if (!empty($_GET['invoice_id'])) {
            $invoice_ids = explode(',', $_GET['invoice_id']);
            foreach ($invoice_ids as $invoice_id) {
                $getInvoice = $this->INV->getOneInvoice($invoice_id);
                if (empty($getInvoice->opened_date)) {
                    $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('opened_date' => date('Y-m-d H:i:s'), 'status' => 2));
                }
            }
        }
    }

    public function checkPastDue()
    {

        //get all active, sent(or opened), unpaid invoices
        $where = array(
            'payment_status' => 0,
            'status !=' => 0,
            'is_archived' => 0,
        );

        $unpaid = $this->INV->checkPastDue($where);

        foreach ($unpaid as $key => $value) {
            $invoice_date = $value['invoice_date'];

            $payment_status = $value['payment_status'];
            //get payment terms
            // 1 = Due on Receipt, 2 = Net 7, 3 = Net 10, 4 = Net 14, 5 = Net 15, 6 = Net 20, 7 = Net 30, 8 = Net 45, 9 = Net 60, 10 = Net 90
            switch ($value['payment_terms']) {
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
            //if first sent date exists the due date is based on this date, otherwise due date is based on invoice date
            if (isset($value['first_sent_date'])) {
                $due_date = date('Y-m-d', strtotime($value['first_sent_date'] . '+ ' . $payment_terms . ' day'));
            } else {
                $due_date = date('Y-m-d', strtotime($invoice_date . '+ ' . $payment_terms . ' day'));
            }

            if (strtotime($due_date) < time()) {
                $payment_status = 3;
            }

            $update_arr = array(
                'payment_status' => $payment_status,
                'last_modify' => date('Y-m-d H:i:s'),
            );

            $result = $this->INV->updateInvoive(array('invoice_id' => $value['invoice_id']), $update_arr);
            if ($result) {
                echo "Success " . $value['invoice_id'] . "<br>";
            } else {
                echo "ERROR: " . $value['invoice_id'] . "<br>";
            }
        }

        echo "END OF SCRIPT";
        //die(print_r($unpaid));
    }

    /**
     * Unassign uncompleted jobs
     *  */
    public function managePastJob($value = '')
    {

        $where = array(
            'is_job_mode' => 0,
            'is_complete' => 0,
            'job_assign_date' => date('Y-m-d', strtotime(' -1 day')),
        );
        $param = array(
            "is_job_mode" => 2,
            "reschedule_message" => "Unassigned by System",
            "job_assign_updated_date" => date('Y-m-d H:i:s'),
        );
        $this->db->where($where);
        $this->db->update('technician_job_assign', $param);
        $result = $this->db->affected_rows();
        $body = "managePastJob called at: " . date('Y-m-d H:i:s');
        $body .= "<><>One day prior: " . date('Y-m-d', strtotime(' -1 day')) . " Affected Rows: " . $result;
        $smtp_details = $this->Superadmin->getOneSuperAdmin();
        Send_Mail_dynamic($smtp_details, "mpatel@topdevzh.com", array("name" => "Test Company", "email" => "mpatel@topdevzh.com"), $body, 'Test managePastJob Lambda');
    }

    public function pdfInvoiceOLD($company_id, $invoice_id)
    {

        $where = array(
            'invoice_id' => $invoice_id,
        );

        $data['invoice_details'] = $this->INV->getOneInvoive($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));

        $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));

        $data['all_sales_tax'] = $this->INV->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));

        $data['report_details'] = $this->INV->getOneRepots(array('report_id' => $data['invoice_details']->report_id));

        $this->load->view('admin/invoice/pdf_invoice', $data);

        $html = $this->output->get_output();

        //  // Load pdf library
        $this->load->library('pdf');

        //  // Load HTML content
        $this->dompdf->loadHtml($html);

        //  // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');

        //  // Render the HTML as PDF
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $data['setting_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        //  // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
    }

    public function pdfInvoice($company_id, $invoice_id)
    {

        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Cardconnect_model', 'CardConnectModel');
        $this->load->model('Basys_request_modal', 'BasysRequest');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');


        $where_company = array('company_id' => $company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);


        $invoiceID = $invoice_id;
        $where = array(
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.invoice_id' => $invoiceID,
        );
        $invoice_details = $this->INV->getOneInvoive($where);

        //die(print_r($invoice_details));
        // echo '<br><br>';
        // echo $this->db->last_query();

        $invoice_details->all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoiceID));

        $invoice_details->report = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));
        //die(print_r($invoice_details));
        //var_dump($this->db->last_query());
        //var_dump($invoice_details->report);
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
        $data['invoice_details'] = $invoice_details;
        //die(print_r($data["invoice_details"]));
        // INVOICE WIDE COUPONS
        // $data['coupon_invoice'][] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoiceID));

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
        $this->dompdf->render();

        $companyName = str_replace(" ", "", $data['setting_details']->company_name);
        // $companyName = str_replace(" ","",$this->session->userdata['compny_details']->company_name);

        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)

        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
    }

    public function printInvoice($company_id, $invoice_ids)
    {

        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Cardconnect_model', 'CardConnectModel');
        $this->load->model('Basys_request_modal', 'BasysRequest');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');


        $where_company = array('company_id' => $company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $invoice_ids = explode(",", $invoice_ids);
        foreach ($invoice_ids as $key => $value) {
            $invoiceID = (int) $value;
            $where = array(
                'invoice_tbl.company_id' => $company_id,
                'invoice_tbl.invoice_id' => $invoiceID,
            );
            $invoice_details = $this->INV->getOneInvoive($where);

            //die(print_r($invoice_details));
            // echo '<br><br>';
            // echo $this->db->last_query();

            $invoice_details->all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoiceID));

            $invoice_details->report = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));
            //die(print_r($invoice_details));
            //var_dump($this->db->last_query());
            //var_dump($invoice_details->report);
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

    public function displayDailyInvoice($hashstring)
    {

        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Job_model', 'JobModel');
        if ($hashstring && $hashstring != "") {

            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            
            if ($invoice_ids != "") {
                $payall_data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];

                $payall_data['invoice_ids'] = $invoice_ids;
                $invoice_ids = explode(",", $invoice_ids);
                $payall_data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                foreach ($invoice_ids as $key => $value) {
                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if ($invoice_details->payment_status != 2) {
                        $invoice_details->all_sales_tax = $this->INV->getAllInvoiceSalesTax(array('invoice_id' => $value));
                        $invoice_details->report_details = $this->INV->getOneRepots(array('report_id' => $invoice_details->report_id));

                        // get coupon info

                        ////////////////////////////////////
                        // START INVOICE CALCULATION COST //

                        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
                        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

                        // invoice cost
                        // $invoice_total_cost = $invoice->cost;

                        // cost of all services (with price overrides) - service coupons
                        $job_cost_total = 0;
                        $total_coupon_amount = 0;
                        $where = array(
                            'property_program_job_invoice.invoice_id' => $invoice_details->invoice_id,
                        );
                        $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                                        $coupon_job_amm_total = 0;
                                        $coupon_job_amm = $coupon->coupon_amount;
                                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                                        if ($coupon_job_calc == 0) { // flat amm
                                            $coupon_job_amm_total = (float) $coupon_job_amm;
                                        } else { // percentage
                                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                        }

                                        $job_cost = $job_cost - $coupon_job_amm_total;
                                        $total_coupon_amount += $coupon_job_amm_total;

                                        if ($job_cost < 0) {
                                            $job_cost = 0;
                                        }
                                    }
                                }

                                $job_cost_total += $job_cost;
                            }
                        } else {
                            $job_cost_total = $invoice_details->cost;
                        }
                        $invoice_total_cost = $job_cost_total;

                        // check price override -- any that are not stored in just that ^^.

                        // - invoice coupons
                        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
                        foreach ($coupon_invoice_details as $coupon_invoice) {
                            if (!empty($coupon_invoice)) {
                                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                if ($coupon_invoice_amm_calc == 0) { // flat amm
                                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                } else { // percentage
                                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                    $invoice_total_cost -= $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                }
                                if ($invoice_total_cost < 0) {
                                    $invoice_total_cost = 0;
                                }
                            }
                        }

                        // + tax cost
                        $invoice_total_tax = 0;
                        $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice_details->invoice_id));
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

                        $invoice_details->total_amount_minus_partial = number_format($invoice_total_cost, 2);

                        $payall_data['invoice_details_all'][] = $invoice_details;
                    }
                }
            } else {
                echo "Invalid access";
            }

            // BELOW THIS LINE WAS ALREADY HERE MAKING THE PDF WORK
            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $all_invoice_paid = true;
            if ($invoice_ids != "") {

                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];
                $data['invoice_ids'] = $invoice_ids;
                $data['company_id'] = $company_id;
                $invoice_ids = explode(",", $invoice_ids);

                foreach ($invoice_ids as $key => $value) {
                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );

                    // die(print_r($value));

                    $invoice_details =  $this->INV->getOneInvoive($where);
                    
                    $invoice_details->all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $value));

                    $invoice_details->report_details = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));



                    // echo $value . ' -- ' . json_encode($invoice_details->report_details);
                    // echo "<br><br>";

                    //get job details
                    $jobs = array();

                    $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $value));

                    // die(print_r($job_details));
                    if ($job_details) {



                        foreach ($job_details as $detail) {

                            $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $value));

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

                    // Code to take into account an instance where report_id is not set
                    else if (!$job_details && $invoice_details->json) {
                        $json = json_decode($invoice_details->json, true);

                        if (isset($json['manual_invoice']) && $json['manual_invoice'] == 1) {
                            $invoice_details->manual_invoice = 1;
                        }
                        if (is_array($json['jobs'])) {
                            foreach ($json['jobs'] as $job) {
                                $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                                //print_r($job);
                                //get job details

                                // SERVICE WIDE COUPONS
                                $arry = array(
                                    'customer_id' => $invoice_details->customer_id,
                                    'program_id' => $invoice_details->program_id,
                                    'property_id' => $invoice_details->property_id,
                                    'job_id' => $job['job_id']
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

                                $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));

                                if (isset($job_details->report_id)) {
                                    $report = $this->RP->getOneRepots(array('report_id' => $job_details->report_id));
                                } else {
                                    $report = '';
                                }
                                $jobs[] = array(
                                    'job_id' => $job['job_id'],
                                    'job_name' => $job_details->job_name,
                                    'job_description' => $job_details->job_description,
                                    'job_cost' => $job['job_cost'],
                                    'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                    'program_name' => isset($job['program_name']) ? $job['program_name'] : '',
                                    'job_report' => isset($report) ? $report : "",
                                    'coupon_job_amm' => $coupon_job_amm,
                                    'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                    'coupon_job_code' => $coupon_job_code,
                                );
                            }
                        }
                    }
                    $invoice_details->jobs = $jobs;
                    $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $value));
                    $data['invoice_details'][] = $invoice_details;
                }

                $where_company = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

                $where_arr = array(
                    'company_id' => $company_id,
                    'status' => 1,
                );
                $data['all_invoice_paid'] = $all_invoice_paid;
                $data['payall_data'] = $payall_data;
                $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect($where_arr);
                $data['basys_details'] = $this->CompanyModel->getOneBasysRequest($where_arr);

                // die();
                
                $this->load->view('daily_multiple_pdf_invoice', $data);
                $html = $this->output->get_output();

                // Load pdf library
                $this->load->library('pdf');
                // Load HTML content
                $this->dompdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4', 'portrate');
                // Render the HTML as PDF
                $this->dompdf->render();

                $companyName = str_replace(" ", "", $data['setting_details']->company_name);
                // $companyName = str_replace(" ","",$this->session->userdata['compny_details']->company_name);
                
                $customerName = $invoice_details->first_name . $invoice_details->last_name;
                $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                // Output the generated PDF (1 = download and 0 = preview)
                $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                echo $html;
                exit;
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function displayInvoice($company_id, $invoice_id)
    {

        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Cardconnect_model', 'CardConnect');
        $this->load->model('Basys_request_modal', 'BasysRequest');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');

        $where = array(
            "invoice_tbl.company_id" => $company_id,
            'invoice_tbl.invoice_id' => $invoice_id,
        );
        $data['invoice_details'] = $this->INV->getOneInvoive($where);
        $data['invoice_late_fee'] = $this->INV->getLateFee($invoice_id);

        $jobs = array();
        if (empty($data['invoice_details']->job_id)) {

            //get invoice details from property_program_job_invoice
            $param = array(
                'property_program_job_invoice.invoice_id' => $invoice_id,
            );
            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            if ($details) {
                foreach ($details as $detail) {
                    $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));
                    if (isset($detail['report_id'])) {
                        $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
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
                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));
                        //print_r($job);
                        //get job details
                        $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));
                        $jobs[] = array(
                            'job_id' => $job['job_id'],
                            'job_name' => $job_details->job_name,
                            'job_description' => $job_details->job_description,
                            'job_cost' => $job['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                        );
                    }

                    $data['invoice_details']->jobs = $jobs;
                }
            }
        } else {
            // SERVICE WIDE COUPONS
            $arry = array(
                'customer_id' => $data['invoice_details']->customer_id,
                'program_id' => $data['invoice_details']->program_id,
                'property_id' => $data['invoice_details']->property_id,
                'job_id' => $data['invoice_details']->job_id,
            );
            $data['invoice_details']->coupon_job = $this->CouponModel->getAllCouponJob($arry);
        }
        // die(print_r($data));
        // die(print_r($data['invoice_details']));

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

        $where_company = array('company_id' => $company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );

        $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect($where_arr);
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['all_sales_tax'] = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));

        // SERVICE WIDE COUPONS
        $arry = array(
            'customer_id' => $data['invoice_details']->customer_id,
            'program_id' => $data['invoice_details']->program_id,
            'property_id' => $data['invoice_details']->property_id,
        );
        $data['coupon_job'] = $this->CouponModel->getAllCouponJob($arry);

        $data['group_billing_info'] = $this->PropertyModel->getGroupBillingByProperty($data['invoice_details']->property_id);

        // INVOICE WIDE COUPONS
        $data['coupon_invoice'] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
        $data['coupon_customer'] = array();

        $this->load->view('admin/invoice/pdf_invoice', $data);
        /* $html = $this->output->get_output();
        echo $html;
        exit;  */


        /* // Load pdf library
        $this->load->library('pdf');
        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        // Render the HTML as PDF
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $data['setting_details']->company_name);
        // $companyName = str_replace(" ","",$this->session->userdata['compny_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0)); */
    }

    public function pdfEstimate($estimate_id)
    {
        $estimate_id = base64_decode($estimate_id);

        $where = array(
            'estimate_id' => $estimate_id,
        );

        $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);

        $data['job_details'] = GetOneEstimatAllJobPrice(array('estimate_id' => $estimate_id));

        // $data['customer_details'] = $this->Customer->getOneCustomerDetail($data['estimate_details']['customer_id']);
        $data['customer_details'] = $this->Customer->getOneCustomerDetail($data['estimate_details']->customer_id);

        $where = array('company_id' => $data['estimate_details']->company_id, 'role_id' => 1);
        $data['user_details'] = $this->CompanyModel->getOneCompanyUser($where);

        $where_company = array('company_id' => $data['estimate_details']->company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        // ESTIMATE COUPONS
        $data['coupon_estimate'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

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

    public function pdfEstimateSignWell($estimate_id)
    {
        $estimate_id = base64_decode($estimate_id);

        $where = array(
            'estimate_id' => $estimate_id,
        );

        $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);

        $data['job_details'] = GetOneEstimatAllJobPrice(array('estimate_id' => $estimate_id));

        // $data['customer_details'] = $this->Customer->getOneCustomerDetail($data['estimate_details']['customer_id']);
        $data['customer_details'] = $this->Customer->getOneCustomerDetail($data['estimate_details']->customer_id);

        $where = array('company_id' => $data['estimate_details']->company_id, 'role_id' => 1);
        $data['user_details'] = $this->CompanyModel->getOneCompanyUser($where);

        $where_company = array('company_id' => $data['estimate_details']->company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        // ESTIMATE COUPONS
        $data['coupon_estimate'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

        // die(print_r($data));

        $this->load->view('admin/estimate/pdf_estimate_signwell', $data);

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

    public function estimateAccept($estimate_id)
    {
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Job_model', 'JobModel');

        $estimate_id = base64_decode($estimate_id);

        $where = array(
            'estimate_id' => $estimate_id,
        );

        $estimate_details = $this->EstimateModal->getOneEstimate($where);
        // die(print_r($this->db->last_query()));
        // die(print_r($estimate_details));
        $company_id = $estimate_details->company_id;

        if ($estimate_details && $estimate_details->status != 2) {

            $param = array('status' => 2, 'estimate_update' => date("Y-m-d H:i:s"));

            $result = $this->EstimateModal->updateEstimate($where, $param);
            if ($result) {
                ##### ADDED 3/1/22 #####
                $property_status = $this->PropertyModel->updateAdminTbl($estimate_details->property_id, array('property_status' => '1'));
                // die(print_r($this->db->last_query()));
                ####
                // if one time program invoiceing
                if ($estimate_details->program_pricing == "1" || $estimate_details->program_pricing == 1 || $estimate_details->program_price == "1" || $estimate_details->program_price == 1) {
                    $user_id = $estimate_details->user_id;
                    $company_id = $estimate_details->company_id;
                    $customer_id = $estimate_details->customer_id;
                    $property_id = $estimate_details->property_id;
                    $estimate_id = $estimate_id;
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
                    //$program_id = $estimate_details->program_id;
                    $date = date('Y-m-d', time());
                    $date_time = date('Y-m-d H:m:s', time());

                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                    $property_details = $this->PropertyModel->getOneProperty(array('property_id' => $property_id));

                    // get estimate total cost
                    $total_estimate_cost = 0;
                    $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
                    // die(print_r($estimate_price_overide_data));
                    // echo "<pre>";
                    // print_r($estimate_price_overide_data);
                    // die();
                    $invoice_total_per_program = array();
                    $total_for_services = 0;
                    // need to set all the keys for the above and set that to 0 so we can add to it in the loop below
                    if($program_ids == "") {
                        // this means we need to handle this the historic way of doing it
                        $invoice_total_per_program[$estimate_details->program_id] = 0;
                    } else {
                        foreach ($estimate_price_overide_data as $es_job) {
                            if(!in_array($es_job->program_id,array_keys($program_ids_to_services))) {
                                $invoice_total_per_program[$es_job->program_id] = 0;
                            }
                        }
                    }
                    foreach ($estimate_price_overide_data as $es_job) {

                        if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                            $job_cost = $es_job->price_override;
                        } else {

                            $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $es_job->program_id));

                            if (isset($priceOverrideData->is_price_override_set) && $priceOverrideData->is_price_override_set == 1) {
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

                        $coup_job_param = array(
                            'cost' => $job_cost,
                            'job_id' => $es_job->job_id,
                            'customer_id' => $customer_id,
                            'property_id' => $property_id,
                            'program_id' => $es_job->program_id
                        );

                        $job_cost_w_coupon = $this->calculateServiceCouponCost($coup_job_param);

                        @$total_estimate_cost += $job_cost_w_coupon;

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
                            if($program_ids == "" || empty($program_ids)) {
                                $jobs = $this->ProgramModel->getSelectedJobs($estimate_details->program_id);
                
                                foreach ($jobs as $key3 => $value3) {
                                    $job_id = $value3->job_id;
                
                                    $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));
                
                                    $description = $job_details->job_name . " ";
                
                                    $QBO_description[] = $job_details->job_name;
                                    $actual_description_for_QBO[] = $job_details->job_description;
                                }
                            } else {
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
                $data = array('status' => 200, 'subject' => 'Thank You', 'description' => 'Estimate accepted successfully');

                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $estimate_details->company_id));

                $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $estimate_details->company_id, 'status' => 1));

                $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $estimate_details->company_id, 'status' => 1));

                $data['estimate_details'] = $estimate_details;

                // New estimate accept message code here
                $property = $this->EstimateModal->getOneProperty(array('property_id' => $estimate_details->property_id));
                $customer_id = $this->Customer->getOnecustomerPropert(array('property_id' => $estimate_details->property_id));
                $emaildata['customerData'] = $this->Customer->getOneCustomer(array('customer_id' => $customer_id->customer_id));
                $emaildata['email_data_details'] = $this->EstimateModal->getProgramPropertyEmailData(array('customer_id' => $customer_id->customer_id, 'is_email' => 1, 'property_id' => $estimate_details->property_id));
                
                $this->load->model('Job_model', 'JobModel');
                
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

                $emaildata['service_names'] = implode(", ", $service_names_array);


                if($emaildata['program_names'] == "") {
                    // now we need to use the old way of doing this
                    $emaildata['program_names'] = $estimate_details->old_program_name;
                }
                $where = array('company_id' => $company_id);
                $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                #send email to company admin
                $emaildata['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));
                $emaildata['company_email_details'] = $this->CompanyModel->getOneCompanyEmail($where);
                $emaildata['accepted_date'] = date("Y-m-d H:i:s");

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }





                $body = $this->load->view('estimate_accepted_mail', $emaildata, true);
                $emaildata['is_admin_email'] = 1;
                $emaildata['estimate_id'] = $estimate_id;
                $emaildata['property_title'] = $property->property_title;
                $adminBody = $this->load->view('estimate_accepted_mail', $emaildata, true);

                if ($emaildata['company_email_details']->estimate_accepted_status == 1) {
                    $res = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $body, 'Estimate Accepted', $emaildata['customerData']->secondary_email);
                    #admin email
                    // $res2 = Send_Mail_dynamic($company_email_details, $emaildata['user_details']->email,  array("name" =>  $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $adminBody, 'Estimate Accepted');

                    #### send email for price Override

                    if ((isset($estimate_price_overide_data) && $estimate_price_overide_data[0]->is_price_override_set != '') || (!empty($coupon_estimates))) {

                        $res3 = Send_Mail_dynamic($company_email_details, $emaildata['user_details']->email, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $adminBody, 'Estimate Accepted: [Review Required - Price Override/Coupon]');
                    } else {
                        #admin email
                        $res2 = Send_Mail_dynamic($company_email_details, $emaildata['user_details']->email, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $adminBody, 'Estimate Accepted');
                    }
                }

                if ($data['setting_details']->is_text_message && $emaildata['company_email_details']->estimate_accepted_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {
                    // $email_details['job_details']->is_mobile_text
                    //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->estimate_accepted_text);

                    $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->estimate_accepted_text, 'Estimate Accepted');
                    #admin text
                    $text_res2 = Send_Text_dynamic($emaildata['user_details']->phone, 'An Estimate has been accepted! Estimate #: ' . $estimate_id . '. Property: ' . $property->property_title . ', ' . $emaildata['email_data_details']->property_address . '.', 'Estimate Accepted');
                }

                // end New estimate accept message code here

            } else {
                $data = array('status' => 400, 'subject' => 'Thank You', 'description' => 'Something went wrong');
            }

            $where_company = array('company_id' => $estimate_details->company_id);

            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        } else if ($estimate_details && $estimate_details->status == 2) {
            $data = array('status' => 400, 'subject' => 'No Further Action Required', 'description' => 'This estimate was already accepted');

            $data['setting_details'] = false;
        } else {

            $data = array('status' => 400, 'subject' => 'Thank You', 'description' => 'Estimate not found');

            $data['setting_details'] = false;
        }
        $this->load->view('success_message', $data);
    }

    public function estimatePayment($estimate_id)
    {

        $estimate_id = base64_decode($estimate_id);
        $where = array('estimate_id' => $estimate_id);

        $estimate_details = $this->EstimateModal->getOneEstimate($where);

        $data = array(
            'estimate_details' => false,
            'setting_details' => false,
            'cardconnect_details' => false,
            'basys_details' => false,
        );

        if ($estimate_details) {

            $data['coupon_details'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

            $data['estimate_details'] = $estimate_details;
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $estimate_details->company_id));
            $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $estimate_details->company_id, 'status' => 1));
            $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $estimate_details->company_id, 'status' => 1));

            if ($data['basys_details']) {
                $this->load->view('estimate_payment', $data);
            } else if ($data['cardconnect_details']) {
                $this->load->view('clover_estimate_payment', $data);
            }
        } else {

            // echo 'hi';die();
            $this->load->view('clover_estimate_payment', $data);
        }
    }

    public function sendEmailOneDayPrior()
    {
        $tomorrow = date("Y-m-d", time() + 86400);
        $where = array('job_assign_date' => $tomorrow);
        $allData = $this->AssignJobs->getAllJobAssign(array('job_assign_date' => $tomorrow));
        foreach ($allData as $key => $value) {
            if ($value->is_email == 1) {
                $data['email_send_details'] = $value;
                $body = $this->load->view('tech_email', $data, true);
                if ($value->is_smtp == 1) {
                    $smtp_details = array(
                        'smtp_host' => $value->smtp_host,
                        'smtp_port' => $value->smtp_port,
                        'smtp_username' => $value->smtp_username,
                        'smtp_password' => $value->smtp_password,
                    );
                } else {
                    $smtp_details = $this->Superadmin->getOneSuperAdmin();
                }
                $res = Send_Mail_dynamic($smtp_details, $value->email, array("name" => $value->company_name, "email" => $value->company_email), $body, 'One Day Prior Job', $value->secondary_email);
            }
        }
    }

    public function unSubscibeEmail($customer_id)
    {

        $where = array('customer_id' => $customer_id);
        $param = array('is_email' => 0);
        $this->Customer->updateCustomerTbl($where, $param);
        // echo $this->db->last_query();

        $this->load->view('message');
    }

    public function quickBookWebHook($value = '')
    {

        $payLoad = file_get_contents("php://input");

        if ($this->isValidJSON($payLoad)) {

            $is_verified = true;
            $payLoad_data = json_decode($payLoad, true);

            $req_dump = print_r($payLoad_data, true);
            $fp = fopen('request.log', 'a');
            fwrite($fp, $req_dump);
            fclose($fp);

            foreach ($payLoad_data['eventNotifications'] as $event_noti) {
                $realmId = $event_noti['realmId'];

                $company_details = $this->checkQuickbook($realmId);

                if ($company_details) {

                    $dataService = DataService::Configure(
                        array(
                            'auth_mode' => 'oauth2',
                            'ClientID' => $company_details->quickbook_client_id,
                            'ClientSecret' => $company_details->quickbook_client_secret,
                            'accessTokenKey' => $company_details->access_token_key,
                            'refreshTokenKey' => $company_details->refresh_token_key,
                            'QBORealmID' => $company_details->qbo_realm_id,
                            'baseUrl' => "Production",
                        )
                    );

                    $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                    foreach ($event_noti['dataChangeEvent']['entities'] as $entries) {

                        if ($entries['name'] == 'Customer' || $entries['name'] == 'Invoice' || $entries['name'] == 'Payment') {

                            $res = $this->manage($dataService, $entries, $company_details);

                            $req_dump = print_r($res, true);
                            $fp = fopen('request.log', 'a');
                            fwrite($fp, $req_dump);
                            fclose($fp);
                        } // check event

                    } //for entities

                } //   comapny check
            } // compant for

        }
    }

    public function testing($value = '')
    {
        $realmId = 9130347581324726;
        $company_details = $this->checkQuickbook($realmId);

        $dataService = DataService::Configure(
            array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production",
            )
        );

        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

        $entries = array(
            'name' => "Payment",
            'id' => 82,
            'operation' => "Create",
        );

        $res = $this->manage($dataService, $entries, $company_details);

        print_r($res);
    }

    public function manage($dataService, $entries, $company_details)
    {
        echo "<pre>";
        print_r($entries);

        $entities = $dataService->Query("SELECT * FROM " . $entries['name'] . " where Id='" . $entries['id'] . "'");
        $error = $dataService->getLastError();
        if ($error) {
        } else {

            if (!empty($entities)) {

                $response = reset($entities);

                switch ($entries['name']) {

                    case 'Invoice':

                        if ($entries['operation'] == 'Update') {
                            $theInvoice = $response;
                            $where = array('invoice_id' => $theInvoice->DocNumber);
                            $invoice_details = $this->INV->getOneInvoive($where);
                            $partial_payment = 0;

                            if (!empty($theInvoice->LinkedTxn)) {
                                $entities = $dataService->Query("SELECT * FROM Payment where Id='" . $theInvoice->LinkedTxn->TxnId . "'");

                                if (!empty($entities)) {
                                    $thePayment = reset($entities);

                                    $partial_payment = $thePayment->TotalAmt;
                                }
                            }

                            if ($invoice_details) {
                                $cost = ($theInvoice->TotalAmt * 100) / (100 + $invoice_details->tax_value);

                                $updateArr = array(
                                    'cost' => $cost,
                                    'tax_amount' => $cost * $invoice_details->tax_value / 100,
                                    'notes' => $theInvoice->Line[0]->Description,
                                    'invoice_date' => $theInvoice->TxnDate,
                                    'partial_payment' => $partial_payment,
                                );

                                if ($partial_payment == $updateArr['cost'] + $updateArr['tax_amount']) {
                                    $updateArr['payment_status'] = 2;
                                } elseif ($partial_payment == 0) {
                                    $updateArr['status'] = 1;
                                } else if ($partial_payment < $updateArr['cost'] + $updateArr['tax_amount']) {
                                    $updateArr['payment_status'] = 1;
                                    //KT and EE
                                    $updateArr['status'] = 2;
                                    if ($invoice_details->opened_date == '') {
                                        $updateArr['opened_date'] = date("Y-m-d H:i:s");
                                    } else {
                                        $updateArr['opened_date'] = $invoice_details->opened_date;
                                    }

                                    if ($invoice_details->sent_date == '') {
                                        $updateArr['sent_date'] = date("Y-m-d H:i:s");
                                    } else {
                                        $updateArr['sent_date'] = $invoice_details->sent_date;
                                    }

                                    //...
                                } else {
                                    $updateArr['payment_status'] = 0;
                                }

                                $invoice_details = $this->INV->updateInvoive($where, $updateArr);

                                return array('status' => 200, 'msg' => 'invoice updated');
                            }
                        }

                        break;

                    case 'Payment':

                        $thePayment = $response;

                        $entities = $dataService->Query("SELECT * FROM Invoice where Id='" . $thePayment->Line->LinkedTxn->TxnId . "'");

                        $theInvoice = reset($entities);

                        $where = array('invoice_id' => $theInvoice->DocNumber);

                        $partial_payment = $thePayment->TotalAmt;

                        $updateArr = array(
                            'partial_payment' => $partial_payment,
                            'quickbook_partial_payment_id' => $thePayment->Id,
                        );

                        if ($partial_payment == $theInvoice->TotalAmt) {
                            $updateArr['payment_status'] = 2;
                        } elseif ($partial_payment == 0) {
                            $updateArr['status'] = 1;
                        } else if ($partial_payment < $theInvoice->TotalAmt) {
                            $updateArr['payment_status'] = 1;
                            //KT and EE
                            $updateArr['status'] = 2;
                            if ($invoice_details->opened_date == '') {
                                $updateArr['opened_date'] = date("Y-m-d H:i:s");
                            } else {
                                $updateArr['opened_date'] = $invoice_details->opened_date;
                            }

                            if ($invoice_details->sent_date == '') {
                                $updateArr['sent_date'] = date("Y-m-d H:i:s");
                            } else {
                                $updateArr['sent_date'] = $invoice_details->sent_date;
                            }

                            //...
                        } else {
                            $updateArr['payment_status'] = 0;
                        }

                        $invoice_details = $this->INV->updateInvoive($where, $updateArr);

                        return array('status' => 200, 'msg' => 'payment manage', 'query' => $this->db->last_query(), 'payment' => $thePayment);

                        break;
                    // for payment

                }
            }
        }
    }

    public function checkQuickbook($qbo_realm_id)
    {
        $where = array(
            'qbo_realm_id' => $qbo_realm_id,
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

                $this->CompanyModel->updateCompany($post_data);

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

    // check JSON
    public function isValidJSON($string)
    {
        if (!isset($string) || trim($string) === '') {
            return false;
        }
        @json_decode($string);
        if (json_last_error() != JSON_ERROR_NONE) {
            return false;
        }
        return true;
    }

    // public function testingToken($value = '')
    // {

    //     \Stripe\Stripe::setApiKey(secret_key);

    //     $testingToken = \Stripe\Token::create([
    //         'card' => [
    //             'number' => '4242424242424242',
    //             'exp_month' => 11,
    //             'exp_year' => 2020,
    //             'cvc' => '314',
    //         ],
    //     ]);

    //     return $testingToken->id;

    // }

    // public function testingAll($value = '')
    // {

    //     \Stripe\Stripe::setApiKey(secret_key);
    //     echo "<pre>";
    //     $token = $this->testingToken();

    //     try {

    //         $customer = \Stripe\Customer::create(array(
    //             "description" => "Customer for spraye company",
    //             "source" => $token, // obtained with Stripe.js
    //             "email" => 'hemantrajak1@gmail.com',
    //             "name" => "Hemant rajak",

    //         ));

    //         //  $charge = \Stripe\Charge::create(array(
    //         //     "amount" => 1250,
    //         //     "currency" => "inr",
    //         //     "customer" => $customer->id,
    //         //     'description' => 'Testing ',

    //         //  ));

    // $plan =  \Stripe\Plan::create([
    //           'amount' => 1250,
    //           'currency' => 'inr',
    //           'interval' => 'month',
    //           'product' => ['name' => 'prod_GGFkpaGpyFVujo'],
    //  ]);

    //  $subscription = \Stripe\Subscription::create(array(
    //   "customer" => $customer->id,
    //   "items" => array(
    //          array(
    //             "plan" => $plan->id,
    //           ),
    //         ),
    //       ));

    //   echo "<br>";

    // $return =   array('customer_id'=>$customer->id,'charge_id'=>$charge->id,'plan_id'=>$plan->id,'subscription_id'=>$subscription->id);

    // print_r($return);

    // print_r($customer);
    // echo "<br>";
    // print_r($charge);
    // echo "<br>";
    // print_r($plan);
    // echo "<br>";
    // print_r($subscription);
    // echo "<br>";

    //     } catch (Exception $ex) {

    //         $ex = $ex->getJsonBody();

    //         print_r($ex);
    //         $error = $ex['error']['message'];

    //         echo $error;
    //         // $this->session->set_flashdata("error_message", $striperror->error->message);

    //     }
    // }

    public function stripeWebHook($value = '')
    {

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {

            case 'payment_intent.succeeded':
                $returnData = $event->data->object; // contains a \Stripe\PaymentIntent

                $param = array(
                    'message' => $returnData->charges->data[0]->outcome->seller_message,
                    'subscription_status' => 1,
                );

                $where = array(
                    'stripe_customer_id' => $returnData->customer,
                );

                $this->CompanySub->updateCompanySub($where, $param);

                echo $this->db->last_query();

                break;

            case 'payment_intent.payment_failed':
                $returnData = $event->data->object; // contains a \Stripe\PaymentIntent

                $param = array(
                    'message' => $returnData->charges->data[0]->failure_message,
                    'subscription_status' => 0,
                );

                $where = array(
                    'stripe_customer_id' => $returnData->customer,
                );

                $this->CompanySub->updateCompanySub($where, $param);

                echo $this->db->last_query();

                break;

            // ... handle other event types

            case 'charge.succeeded':
                $returnData = $event->data->object; // contains a \Stripe\PaymentIntent

                $param = array(
                    'message' => $returnData->outcome->seller_message,
                    'subscription_status' => 1,
                );

                $where = array(
                    'stripe_customer_id' => $returnData->customer,
                );

                $this->CompanySub->updateCompanySub($where, $param);

                echo $this->db->last_query();

                break;

            // ... handle other event types

            case 'charge.failed':
                $returnData = $event->data->object; // contains a \Stripe\PaymentIntent

                $param = array(
                    'message' => $returnData->failure_message,
                    'subscription_status' => 0,
                );

                $where = array(
                    'stripe_customer_id' => $returnData->customer,
                );

                $this->CompanySub->updateCompanySub($where, $param);

                echo $this->db->last_query();

                break;

            // ... handle other event types

            default:

                // Unexpected event type
                http_response_code(400);
                exit();
        }

        http_response_code(200);
    }

    public function payment($invoice_id)
    {

        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_2');
        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_2');


        $invoice_id = base64_decode($invoice_id);
        if (!$this->isActive($invoice_id)) {
            // die('si');
            die('Something has gone wrong. The Invoice you are trying to pay is not active.');
        }
        $where = array('invoice_id' => $invoice_id);
        $invoice_details = $this->INV->getOneInvoive($where);

        $data = array(
            'invoice_details' => false,
            'setting_details' => false,
            'cardconnect_details' => false,
            'basys_details' => false,
        );

        if ($invoice_details) {

            $data['invoice_details'] = $invoice_details;
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_details->company_id));
            $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $invoice_details->company_id, 'status' => 1));

            $data['tax_details'] = $this->INV->getAllInvoiceSalesTax($where);

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // invoice cost
            // $invoice_total_cost = $invoice->cost;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $total_coupon_amount = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $invoice_id,
            );
            $proprojobinv = $this->PropertyProgramJobInvoiceModel_2->getPropertyProgramJobInvoiceCoupon($where);
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
                            $coupon_job_amm_total = 0;
                            $coupon_job_amm = $coupon->coupon_amount;
                            $coupon_job_calc = $coupon->coupon_amount_calculation;

                            if ($coupon_job_calc == 0) { // flat amm
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;
                            $total_coupon_amount += $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
            } else {
                $job_cost_total = $invoice_details->cost;
            }
            $invoice_total_cost = $job_cost_total;

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                        $total_coupon_amount += $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                        $total_coupon_amount += $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax_2->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
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

            $data['actual_total_cost_miunus_partial'] = $invoice_total_cost;

            $this->load->view('basys_card_processing', $data);
        } else {

            // echo 'hi';die();
            $this->load->view('basys_card_processing', $data);
        }
    }

    public function paymentProcess($value = '')
    {

        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

        $data = $this->input->post();
        $where = array('invoice_id' => $data['invoice_id']);
        $invoice_details = $this->INV->getOneInvoive($where);
        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_details->company_id));
        $tax_details = $this->INV->getAllInvoiceSalesTax($where);

        $total_tax_amount = 0;
        if ($tax_details) {

            $total_tax_amount = array_sum(array_column($tax_details, 'tax_amount'));
        }

        $convenience_fee = number_format(($setting_details->convenience_fee * ($invoice_details->cost + $total_tax_amount - $invoice_details->partial_payment) / 100), 2);

        ////////////////////////////////////
        // START INVOICE CALCULATION COST //

        // invoice cost
        // $invoice_total_cost = $invoice->cost;

        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $total_coupon_amount = 0;
        $where = array(
            'property_program_job_invoice.invoice_id' => $data['invoice_id'],
        );
        $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                        $coupon_job_amm_total = 0;
                        $coupon_job_amm = $coupon->coupon_amount;
                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                        if ($coupon_job_calc == 0) { // flat amm
                            $coupon_job_amm_total = (float) $coupon_job_amm;
                        } else { // percentage
                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;
                        $total_coupon_amount += $coupon_job_amm_total;

                        if ($job_cost < 0) {
                            $job_cost = 0;
                        }
                    }
                }

                $job_cost_total += $job_cost;
            }
        } else {
            $job_cost_total = $invoice_details->cost;
        }
        $invoice_total_cost = $job_cost_total;

        // check price override -- any that are not stored in just that ^^.

        // - invoice coupons
        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $data['invoice_id']));
        foreach ($coupon_invoice_details as $coupon_invoice) {
            if (!empty($coupon_invoice)) {
                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                if ($coupon_invoice_amm_calc == 0) { // flat amm
                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                    $total_coupon_amount += $coupon_invoice_amm;
                } else { // percentage
                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                    $invoice_total_cost -= $coupon_invoice_amm;
                    $total_coupon_amount += $coupon_invoice_amm;
                }
                if ($invoice_total_cost < 0) {
                    $invoice_total_cost = 0;
                }
            }
        }

        // + tax cost
        $invoice_total_tax = 0;
        $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $data['invoice_id']));
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
        //$invoice_total_cost = number_format($invoice_total_cost,2);

        $total_amount = $invoice_total_cost + $convenience_fee - $invoice_details->partial_payment;
        // $total_amount = $invoice_details->cost + $total_tax_amount + $convenience_fee - $invoice_details->partial_payment - $total_coupon_amount;

        if ($total_amount < 0) {
            $total_amount = 0;
        }

        //die(print_r($total_amount));
        // $dataToLog = array($total_amount);
        // $data = implode(" - ", $dataToLog);
        // $data .= PHP_EOL;
        // file_put_contents('nr_log.txt', $data, FILE_APPEND);

        $order_id = (string) strtotime("now");
        $post = array(
            "type" => "sale",
            "amount" => round($total_amount * 100),
            "tax_exempt" => false,
            "tax_amount" => round($total_tax_amount * 100),
            "currency" => "USD",
            "description" => "Invoice for CustomerId: " . $invoice_details->customer_id,
            "order_id" => $order_id,
            //"ip_address" => $this->getClientIp(),
            "email_receipt" => true,
            "email_address" => $invoice_details->email,
            "create_vault_record" => true,
            "payment_method" => array(
                "token" => $data['token'],
            ),
            "billing_address" => array(
                "first_name" => $invoice_details->first_name,
                "last_name" => $invoice_details->last_name,
                "company" => $invoice_details->customer_company_name,
                "address_line_1" => $invoice_details->billing_street,
                "address_line_2" => isset($invoice_details->billing_street_2) ? $invoice_details->billing_street_2 : "",
                "city" => isset($invoice_details->billing_city) ? $invoice_details->billing_city : "",
                "state" => isset($invoice_details->billing_state) ? $invoice_details->billing_state : "",
                "postal_code" => isset($invoice_details->billing_zipcode) ? $invoice_details->billing_zipcode : "",
                "email" => $invoice_details->email,
                "phone" => $invoice_details->phone,
            ),
        );
        if ($convenience_fee != 0) {

            $post['payment_adjustment'] = array(
                "type" => "flat",
                "value" => round($convenience_fee * 100),
            );
        }
        //die(print_r($post));
        $basys_reasopnse = basysCurlProcess($data['api_key'], 'POST', 'transaction', $post);

        if ($basys_reasopnse['status'] == 200) {

            if ($basys_reasopnse['result']->data->response_code == 100) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');
                //KT and EE add status, opened_date, and sent date
                $updatearr = array(
                    'payment_status' => 2,
                    'basys_transaction_id' => $basys_reasopnse['result']->data->id,
                    'payment_created' => date("Y-m-d H:i:s"),
                    'partial_payment' => $invoice_details->cost + $total_tax_amount + $invoice_details->partial_payment,
                    'basys_order_id' => $order_id
                    ,
                    'status' => 2,
                    'opened_date' => $invoice_details->opened_date == '' ? date("Y-m-d H:i:s") : $invoice_details->opened_date,
                    'sent_date' => $invoice_details->sent_date == '' ? date("Y-m-d H:i:s") : $invoice_details->sent_date
                );

                $this->INV->updateInvoive(array('invoice_id' => $data['invoice_id']), $updatearr);

                $company_id = $invoice_details->company_id;

                $where = array('company_id' => $company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));

                $invoice_details->convenience_fee = $convenience_fee;
                $invoice_details->tax_amount = $total_tax_amount;

                $data['invoice_details'] = $invoice_details;

                $data['actual_total_amount'] = $total_amount;

                $body = $this->load->view('invoice_paid_mail', $data, true);

                $where['is_smtp'] = 1;

                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }
                $res = Send_Mail_dynamic($company_email_details, $data['user_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');

                $return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $basys_reasopnse['result']->data);
            } else {

                $return_arr = array('status' => 400, 'msg' => $basys_reasopnse['result']->data->response, 'result' => $basys_reasopnse['result']);
            }
        } else {
            $return_arr = array('status' => 400, 'msg' => $basys_reasopnse['message']);
        }

        echo json_encode($return_arr);
    }

    public function estimatePaymentProcess($value = '')
    {

        $data = $this->input->post();

        $where = array('estimate_id' => $data['estimate_id']);
        $estimate_details = $this->EstimateModal->getOneEstimate($where);

        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $estimate_details->company_id));

        $convenience_fee = number_format(($setting_details->convenience_fee * ($data['line_total'] + $data['total_tax_amount']) / 100), 2);

        $total_amount = $data['line_total'] + $data['total_tax_amount'] + $convenience_fee;
        //echo "<pre>"; print_r($estimate_details); exit;
        $post = array(
            "type" => "sale",
            "amount" => $total_amount * 100,
            "tax_exempt" => false,
            "tax_amount" => $data['total_tax_amount'] * 100,
            "currency" => "USD",
            "description" => "Estimate for CustomerId: " . $estimate_details->customer_id,
            "order_id" => $estimate_details->estimate_id,
            //"ip_address" => $this->getClientIp(),
            "email_receipt" => true,
            "email_address" => $estimate_details->email,
            "create_vault_record" => true,
            "payment_method" => array(
                "token" => $data['token'],
            ),
            "billing_address" => array(
                "first_name" => $estimate_details->first_name,
                "last_name" => $estimate_details->last_name,
                "company" => $estimate_details->customer_company_name,
                "address_line_1" => $estimate_details->billing_street,
                "address_line_2" => isset($estimate_details->billing_street_2) ? $estimate_details->billing_street_2 : "",
                "city" => isset($estimate_details->billing_city) ? $estimate_details->billing_city : "",
                "state" => isset($estimate_details->billing_state) ? $estimate_details->billing_state : '',
                "postal_code" => isset($estimate_details->billing_zipcode) ? $estimate_details->billing_zipcode : '',
                "email" => $estimate_details->email,
                "phone" => $estimate_details->phone,
            ),
        );

        if ($convenience_fee != 0) {

            $post['payment_adjustment'] = array(
                "type" => "flat",
                "value" => $convenience_fee * 100,
            );
        }

        $basys_reasopnse = basysCurlProcess($data['api_key'], 'POST', 'transaction', $post);
        if ($basys_reasopnse['status'] == 200) {

            if ($basys_reasopnse['result']->data->response_code == 100) {

                $param = array('status' => 3, 'estimate_update' => date("Y-m-d H:i:s"), 'basys_transaction_id' => $basys_reasopnse['result']->data->id, 'payment_created' => date("Y-m-d H:i:s"));

                $result = $this->EstimateModal->updateEstimate($where, $param);

                // SET RELATED INVOICES TO PAID HERE AS WELL... unless invoices aren't created yet?

                $company_id = $estimate_details->company_id;

                $where = array('company_id' => $company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));

                $data['estimate_details'] = $estimate_details;

                $body = $this->load->view('estimate_paid_mail', $data, true);

                $where['is_smtp'] = 1;

                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }
                $res = Send_Mail_dynamic($company_email_details, $data['user_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');

                $return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $basys_reasopnse['result']->data);
            } else {

                $return_arr = array('status' => 400, 'msg' => $basys_reasopnse['result']->data->response, 'result' => $basys_reasopnse['result']);
            }
        } else {
            $return_arr = array('status' => 400, 'msg' => $basys_reasopnse['message']);
        }

        echo json_encode($return_arr);
    }

    public function paymentSuccess($company_id)
    {

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
        $this->load->view('basys_payment_success', $data);
    }

    public function basysWebHook($value = '')
    {
        $req_dump = print_r($payLoad_data['eventNotifications'], true);
        $fp = fopen('request.log', 'a');
        fwrite($fp, $req_dump);
        fclose($fp);
    }
    /**
     * Returns client ip address
     */
    public function getClientIp()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
    /**
     * This function is being called once a day and send invoice email to customer(s)
     */


    public function sendCustomerInvoices()
    {
        // Mail should send link with date and customerId.
        //$invoice_date = date('Y-m-d', strtotime(' -1 day'));
        $where = array(
            //'invoice_date' => $invoice_date,
            //'invoice_tbl.company_id' => 44,
            // 'report_id >' => 0,
            'status' => 0,
            'is_archived' => 0,
            'email >' => '\'\''
            //'report_id !=' => 0
        );
        $completed_job_customer_detail = $this->INV->getCompletedJobCustomerDetail($where);
        
        $customer_wise_data = [];
        foreach ($completed_job_customer_detail as $detail) {
            if ($detail['report_id'] > 0) {
                if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                    array_push($customer_wise_data[$detail['customer_id']], $detail);
                } else {
                    $customer_wise_data[$detail['customer_id']][] = $detail;
                }
            } else {
                //if no report id in invoice table, check Property Program Job Invoice Table
                $PPJOBINVdata = $this->INV->getPPJOBINVdetails(array('invoice_id' => $detail['invoice_id']));
                //die(print_r($PPJOBINVdata));
                if ($PPJOBINVdata) {
                    $complete = 0;
                    foreach ($PPJOBINVdata as $job) {
                        if ($job['program_price'] != 2 || $job['report_id'] > 0) {
                            $complete = 1;
                        }
                    }
                    if ($complete == 1) {
                        if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                            array_push($customer_wise_data[$detail['customer_id']], $detail);
                        } else {
                            $customer_wise_data[$detail['customer_id']][] = $detail;
                        }
                    }
                }
            }
        }
        foreach ($customer_wise_data as $customer_id => $customer_data) {
            $email = $customer_data[0]['email'];
            $company_id = $customer_data[0]['company_id'];
            $data['customer_details'] = (object) $this->Customer->getCustomerDetail($customer_id);
            //die(print_r($data['customer_details']));
            $invoice_id_list = array_column($customer_data, 'invoice_id');
            $hashstring = md5($email . "-" . $customer_id . "-" . date("Y-m-d H:i:s"));
            $data['link'] = base_url('welcome/pdfDailyInvoice/') . $hashstring;
            $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;
            $where_company = array('company_id' => $company_id);
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyModel->getOneCompanyEmailArray(array('company_id' => $company_id));
            //            if($company_id == "44") {
            if (is_array($company_email_details) && $company_email_details['send_daily_invoice_mail'] == 1) {
                foreach ($invoice_id_list as $key => $inv) {
                    //if invoice is for program invoice method Invoice at Service Completion, check for completed services
                    $invDetails = $this->INV->getOneInvoice($inv);
                    if (isset($invDetails->program_id)) {
                        $getProgramDetails = $this->INV->getOneProgram($invDetails->program_id);
                        if ($getProgramDetails->program_price == 2) {
                            //check tech job assign table for complete status
                            $assigned_jobs = $this->AssignJobs->getAllJobAssignByInvoice(array('invoice_id' => $inv));
                            if ($assigned_jobs) {
                                foreach ($assigned_jobs as $k => $v) {
                                    if ($v['is_complete'] == 0) {
                                        //if not complete then remove from invoice list
                                        unset($invoice_id_list[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                //die(print_r($invoice_id_list));
                //echo "this customer has an invoice: ".$data['customer_details']->customer_id;
                $body = $this->load->view('invoice_daily_mail', $data, true);

                // Added hash table concept to get rid of expired link issue.
                $batch_insert_arr = array();
                $invoice_arr = array();
                foreach ($invoice_id_list as $invoice_id) {
                    $hash_tbl_arr = array();
                    $hash_tbl_arr['invoice_id'] = $invoice_id;
                    $hash_tbl_arr['company_id'] = $company_id;
                    $hash_tbl_arr['hashstring'] = $hashstring;
                    $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");
                    array_push($batch_insert_arr, $hash_tbl_arr);
                    array_push($invoice_arr, $invoice_id);
                }
                $this->db->insert_batch('invoice_hash_tbl', $batch_insert_arr);
                if ($data['customer_details']->autosend_invoices == 1 && $data['customer_details']->autosend_frequency == 'daily') {
                    $res = $this->Log->saveEmailLog(
                        'Auto Invoice send',
                        $data['setting_details']->company_id,
                        $customer_id,
                        $data['customer_details']->email,
                        $data['customer_details']->secondary_email,
                        'Invoice Details',
                        $body,
                        0,
                        '',
                        json_encode($invoice_arr)
                    );
                    
                    $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Invoice Details', $data['customer_details']->secondary_email);
                    print_r($res);
                    if ($res['status'] == true) {
                        $update_arr = array(
                            "status" => 1,
                            "last_modify" => date("Y-m-d H:i:s"),
                        );

                        $this->INV->updateInvoiceForInvoices($invoice_id_list, $update_arr);
                    }
                    //echo "sending daily invoice email to customer id = ".$data['customer_details']->customer_id;

                }
            }
            //            }
        }
        //         die('fin');
        //check day of month to determine if we should auto-send monthly invoices to customers
        $today = date('Y-m-d');
        $checkDayOfMonth = explode('-', $today);
        $sendMonthly = 0;
        if (isset($checkDayOfMonth[2]) && $checkDayOfMonth[2] == 01) {
            $sendMonthly = 1;
        }
        if ($sendMonthly == 1) {
            $sendMonthlyInvoices = $this->sendCustomerInvoicesMonthly();
        }
        return 1;
    }

    public function sendCustomerInvoices_old()
    {
        // Mail should send link with date and customerId.
        $invoice_date = date('Y-m-d', strtotime(' -1 day'));
        $where = array(
            'invoice_date' => $invoice_date,
            // 'report_id >' => 0,
            'status' => 0,
        );
        $completed_job_customer_detail = $this->INV->getCompletedJobCustomerDetail($where);
        $customer_wise_data = [];
        foreach ($completed_job_customer_detail as $detail) {
            if ($detail['report_id'] > 0) {
                if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                    array_push($customer_wise_data[$detail['customer_id']], $detail);
                } else {
                    $customer_wise_data[$detail['customer_id']][] = $detail;
                }
            } else {
                //if no report id in invoice table, check Property Program Job Invoice Table
                $PPJOBINVdata = $this->INV->getPPJOBINVdetails(array('invoice_id' => $detail['invoice_id']));
                if ($PPJOBINVdata) {
                    $complete = 0;
                    foreach ($PPJOBINVdata as $job) {
                        if ($job['report_id'] > 0) {
                            $complete = 1;
                        }
                    }
                    if ($complete == 1) {
                        if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                            array_push($customer_wise_data[$detail['customer_id']], $detail);
                        } else {
                            $customer_wise_data[$detail['customer_id']][] = $detail;
                        }
                    }
                }
            }
        }
        //die(print_r($customer_wise_data));
        foreach ($customer_wise_data as $customer_id => $customer_data) {
            $email = $customer_data[0]['email'];
            $company_id = $customer_data[0]['company_id'];
            $data['customer_details'] = (object) $this->Customer->getCustomerDetail($customer_id);
            //die(print_r($data['customer_details']));
            $invoice_id_list = array_column($customer_data, 'invoice_id');
            $hashstring = md5($email . "-" . $customer_id . "-" . date("Y-m-d H:i:s"));
            $data['link'] = base_url('welcome/pdfDailyInvoice/') . $hashstring;
            $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;
            $where_company = array('company_id' => $company_id);
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyModel->getOneCompanyEmailArray(array('company_id' => $company_id));
            //if($company_id == "117") {
            if (is_array($company_email_details) && $company_email_details['send_daily_invoice_mail'] == 1) {
                foreach ($invoice_id_list as $key => $inv) {
                    //if invoice is for program invoice method Invoice at Service Completion, check for completed services
                    $invDetails = $this->INV->getOneInvoice($inv);
                    if (isset($invDetails->program_id)) {
                        $getProgramDetails = $this->INV->getOneProgram($invDetails->program_id);
                        if ($getProgramDetails->program_price == 2) {
                            //check tech job assign table for complete status
                            $assigned_jobs = $this->AssignJobs->getAllJobAssignByInvoice(array('invoice_id' => $inv));
                            if ($assigned_jobs) {
                                foreach ($assigned_jobs as $k => $v) {
                                    if ($v['is_complete'] == 0) {
                                        //if not complete then remove from invoice list
                                        unset($invoice_id_list[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                //echo "this customer has an invoice: ".$data['customer_details']->customer_id;
                $body = $this->load->view('invoice_daily_mail', $data, true);
                $update_arr = array(
                    "status" => 1,
                    "last_modify" => date("Y-m-d H:i:s"),
                );
                $this->INV->updateInvoiceForInvoices($invoice_id_list, $update_arr);
                // Added hash table concept to get rid of expired link issue.
                $batch_insert_arr = array();
                foreach ($invoice_id_list as $invoice_id) {
                    $hash_tbl_arr = array();
                    $hash_tbl_arr['invoice_id'] = $invoice_id;
                    $hash_tbl_arr['company_id'] = $company_id;
                    $hash_tbl_arr['hashstring'] = $hashstring;
                    $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");
                    array_push($batch_insert_arr, $hash_tbl_arr);
                }
                $this->db->insert_batch('invoice_hash_tbl', $batch_insert_arr);
                if ($data['customer_details']->autosend_invoices == 1 && $data['customer_details']->autosend_frequency == 'daily') {
                    //$res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $data['setting_details']->company_name, "email" =>$data['setting_details']->company_email), $body, 'Invoice Details - ' . $invoice_date, $data['customer_details']->secondary_email);
                    //					echo "sending daily invoice email to customer id = ".$data['customer_details']->customer_id;
                }
            }
            //}
        }
        die('fin');
        //check day of month to determine if we should auto-send monthly invoices to customers
        $today = date('Y-m-d');
        $checkDayOfMonth = explode('-', $today);
        $sendMonthly = 0;
        if (isset($checkDayOfMonth[2]) && $checkDayOfMonth[2] == 01) {
            $sendMonthly = 1;
        }
        if ($sendMonthly == 1) {
            $sendMonthlyInvoices = $this->sendCustomerInvoicesMonthly();
        }
    }

    public function sendCustomerReminders()
    {
        // Test this function with the url below:
        //https://emerald-dev3.blayzer.com/welcome/sendCustomerReminders
        // Mail should send link with date and customerId.
        log_message('info', '/*****************************************************************/');
        log_message('info', 'sendCustromerReminders');
        $reminder_date = date("Y-m-d", time() + 86400); //date('Y-m-d', strtotime(' -5 day'));  date('Y-m-d', strtotime(' -76 day'));
        $email_array = array();

        //die($reminder_date );

        $where = array(
            'job_assign_date =' => $reminder_date,
            'is_complete' => 0,

        );
        $reminder_detail = $this->AssignJobs->getAllJobAssignGroup($where);
        //die($this->db->last_query() );

        //        die(print_r($reminder_detail));
        $customer_wise_data = [];
        $pre_service_notification_email = 0;
        $pre_service_notification_text = 0;
        foreach ($reminder_detail as $detail) {

            if (strpos($detail->pre_service_notification, '"2"') != 0) {
                //die("Yes, it has a preservice notification to send an email");
                $pre_service_notification_email = 1;
            }
            if (strpos($detail->pre_service_notification, '"3"') != 0) {
                //die("Yes, it has a preservice notification to send an email");
                $pre_service_notification_text = 1;
            }
            //die(print_r($pre_service_notification_email));
            $detail = (array) $detail;

            //die(print_r($detail));
            if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                array_push($customer_wise_data[$detail['customer_id']], $detail);
            } else {
                $customer_wise_data[$detail['customer_id']][] = $detail;
            }
        }
        $i = 0;
        $property_arr = array();
        foreach ($customer_wise_data as $customer_id => $customer_data) {
            $repeat_property = false;
            if (!in_array($customer_data[0]['property_id'], $property_arr)) {
                array_push($property_arr, $customer_data[0]['property_id']);
            } else {
                $repeat_property = true;
            }
            $i++;

            //die(print_r($customer_data));
            $data['group_billing'] = 0;
            $email = $customer_data[0]['email'];
            $company_id = $customer_data[0]['company_id'];
            $data['customer_details'] = (object) $this->Customer->getCustomerDetail($customer_id);
            $invoice_id_list = array_column($customer_data, 'invoice_id');
            $hashstring = md5($email . "-" . $customer_id . "-" . date("Y-m-d H:i:s"));
            $data['link'] = base_url('welcome/pdfDailyInvoice/') . $hashstring;
            $where_company = array('company_id' => $company_id);
            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
            //die($data['setting_details']->company_logo);
            //$where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where_company);
            if (is_array($company_email_details)) {
                $email_opt_in = $data['customer_details']->is_email;
                if (isset($company_email_details['one_day_prior_status']) && $company_email_details['one_day_prior_status'] == 1) {
                    if (isset($data['customer_details']->billing_type) && $data['customer_details']->billing_type == 1) {
                        #get property group billing info
                        $groupBillingDetails = $this->PropertyModel->getGroupBillingByProperty($customer_data[0]['property_id']);
                        $data['customer_details']->first_name = $groupBillingDetails['first_name'];
                        $data['customer_details']->last_name = $groupBillingDetails['last_name'];
                        $data['customer_details']->email = $groupBillingDetails['email'];
                        $data['customer_details']->is_mobile_text = $groupBillingDetails['phone_opt_in'];
                        $data['customer_details']->phone = $groupBillingDetails['phone'];
                        //die($data['customer_details']);
                        $email_opt_in = $groupBillingDetails['email_opt_in'];
                        $data['group_billing'] = 1;
                    }
                    //die(print_r($customer_data));
                    $company_email_details['one_day_prior'] = str_replace('{CUSTOMER_NAME}', $data['customer_details']->first_name . ' ' . $data['customer_details']->last_name, $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{SERVICE_NAME}', $customer_data[0]['job_name'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{PROGRAM_NAME}', $customer_data[0]['program_name'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{PROPERTY_ADDRESS}', $customer_data[0]['property_address'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{SCHEDULE_DATE}', $customer_data[0]['job_assign_date'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{PROPERTY_NAME}', $customer_data[0]['property_title'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{SERVICE_DESCRIPTION}', $customer_data[0]['job_description'], $company_email_details['one_day_prior']);
                    $company_email_details['one_day_prior'] = str_replace('{SERVICE_NOTES}', $customer_data[0]['job_notes'], $company_email_details['one_day_prior']);

                    $data['company_email_details'] = $company_email_details['one_day_prior'];

                    $body = $this->load->view('job_reminder', $data, true);
                    //die(print_r($body));
                    $res = array();
                    //if($company_id == 1){ FOR TESTING
                    //    $res =   Send_Mail_dynamic($company_email_details, 'support@blayzer.com', array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email),  $body, 'Scheduled Reminder Details - '.$customer_data[0]['job_assign_date']);
                    //echo "potential email ".$data['customer_details']->email."<bR>";
                    if ($email_opt_in == 1 && $repeat_property == false && $pre_service_notification_email == 1) {
                        $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Scheduled Reminder Details - ' . $customer_data[0]['job_assign_date']);
                        //echo "emailing ".$data['customer_details']->email."<bR><bR>";
                    }
                    $email_array[] = $data['customer_details']->email;
                    if (isset($company_email_details['one_day_prior_status_text']) && $company_email_details['one_day_prior_status_text'] == 1 && $data['customer_details']->is_mobile_text == 1 && $data['customer_details']->phone != "") {
                        //$string = str_replace("{CUSTOMER_NAME}", $data['customer_details']->first_name . ' ' .$data['customer_details']->last_name,$company_email_details['one_day_prior_text']);
                        $text_res = Send_Text_dynamic($data['customer_details']->phone, $company_email_details['one_day_prior_text'], 'Scheduled Reminder');
                    }

                    //if ($i < 5) {
                        //$res =   Send_Mail_dynamic($company_email_details, "blance@blayzer.com", array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email),  $body, 'Scheduled Reminder Details - '.$customer_data[0]['job_assign_date']);

                    //}
                    echo "emailing... ";
                    if (isset($res['status']) && $res['status'] == 1) {

                        //is good
                        print('sent<br>');
                    } else {
                        print('not sent<br>');
                    }
                    //}
                }
            }
        }
    }

    public function pdfDailyInvoice($hashstring)
    {

        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Job_model', 'JobModel');

        if ($hashstring && $hashstring != "") {

            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            
            if ($invoice_ids != "") {
                $payall_data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];

                $payall_data['invoice_ids'] = $invoice_ids;
                $invoice_ids = explode(",", $invoice_ids);
                $payall_data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                foreach ($invoice_ids as $key => $value) {
                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if ($invoice_details->payment_status != 2) {
                        $invoice_details->all_sales_tax = $this->INV->getAllInvoiceSalesTax(array('invoice_id' => $value));
                        $invoice_details->report_details = $this->INV->getOneRepots(array('report_id' => $invoice_details->report_id));

                        // get coupon info

                        ////////////////////////////////////
                        // START INVOICE CALCULATION COST //

                        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
                        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

                        // invoice cost
                        // $invoice_total_cost = $invoice->cost;

                        // cost of all services (with price overrides) - service coupons
                        $job_cost_total = 0;
                        $total_coupon_amount = 0;
                        $where = array(
                            'property_program_job_invoice.invoice_id' => $invoice_details->invoice_id,
                        );
                        $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                                        $coupon_job_amm_total = 0;
                                        $coupon_job_amm = $coupon->coupon_amount;
                                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                                        if ($coupon_job_calc == 0) { // flat amm
                                            $coupon_job_amm_total = (float) $coupon_job_amm;
                                        } else { // percentage
                                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                        }

                                        $job_cost = $job_cost - $coupon_job_amm_total;
                                        $total_coupon_amount += $coupon_job_amm_total;

                                        if ($job_cost < 0) {
                                            $job_cost = 0;
                                        }
                                    }
                                }

                                $job_cost_total += $job_cost;
                            }
                        } else {
                            $job_cost_total = $invoice_details->cost;
                        }
                        $invoice_total_cost = $job_cost_total;

                        // check price override -- any that are not stored in just that ^^.

                        // - invoice coupons
                        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
                        foreach ($coupon_invoice_details as $coupon_invoice) {
                            if (!empty($coupon_invoice)) {
                                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                if ($coupon_invoice_amm_calc == 0) { // flat amm
                                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                } else { // percentage
                                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                    $invoice_total_cost -= $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                }
                                if ($invoice_total_cost < 0) {
                                    $invoice_total_cost = 0;
                                }
                            }
                        }

                        // + tax cost
                        $invoice_total_tax = 0;
                        $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice_details->invoice_id));
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

                        $invoice_details->total_amount_minus_partial = number_format($invoice_total_cost, 2);

                        $payall_data['invoice_details_all'][] = $invoice_details;
                    }
                }
            } else {
                echo "Invalid access";
            }

            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $all_invoice_paid = true;

            if ($invoice_ids != "") {

                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];
                $data['invoice_ids'] = $invoice_ids;
                $data['company_id'] = $company_id;
                $invoice_ids = explode(",", $invoice_ids);

                foreach ($invoice_ids as $key => $value) {

                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );

                    // die(print_r($value));

                    $invoice_details =  $this->INV->getOneInvoive($where);
                    //var_dump($invoice_details);
                    //exit();
                    $invoice_details->all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $value));

                    $invoice_details->report_details = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));



                    // echo $value . ' -- ' . json_encode($invoice_details->report_details);
                    // echo "<br><br>";

                    //get job details
                    $jobs = array();

                    $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $value));

                    // die(print_r($job_details));
                    if ($job_details) {



                        foreach ($job_details as $detail) {

                            $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $value));

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

                    // Code to take into account an instance where report_id is not set
                    else if (!$job_details && $invoice_details->json) {
                        $json = json_decode($invoice_details->json, true);

                        if (isset($json['manual_invoice']) && $json['manual_invoice'] == 1) {
                            $invoice_details->manual_invoice = 1;
                        }
                        if (is_array($json['jobs'])) {
                            foreach ($json['jobs'] as $job) {
                                $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                                //print_r($job);
                                //get job details

                                // SERVICE WIDE COUPONS
                                $arry = array(
                                    'customer_id' => $invoice_details->customer_id,
                                    'program_id' => $invoice_details->program_id,
                                    'property_id' => $invoice_details->property_id,
                                    'job_id' => $job['job_id']
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

                                $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));

                                if (isset($job_details->report_id)) {
                                    $report = $this->RP->getOneRepots(array('report_id' => $job_details->report_id));
                                } else {
                                    $report = '';
                                }
                                $jobs[] = array(
                                    'job_id' => $job['job_id'],
                                    'job_name' => $job_details->job_name,
                                    'job_description' => $job_details->job_description,
                                    'job_cost' => $job['job_cost'],
                                    'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                    'program_name' => isset($job['program_name']) ? $job['program_name'] : '',
                                    'job_report' => isset($report) ? $report : "",
                                    'coupon_job_amm' => $coupon_job_amm,
                                    'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                    'coupon_job_code' => $coupon_job_code,
                                );
                            }
                        }
                    }
                    $invoice_details->jobs = $jobs;
                    $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $value));
                    $data['invoice_details'][] = $invoice_details;
                    
                }

                $where_company = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

                $where_arr = array(
                    'company_id' => $company_id,
                    'status' => 1,
                );
                $data['all_invoice_paid'] = $all_invoice_paid;
                $data['payall_data'] = $payall_data;
                $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect($where_arr);
                $data['basys_details'] = $this->CompanyModel->getOneBasysRequest($where_arr);

                // die();

                $this->load->view('daily_multiple_pdf_invoice', $data);
                $html = $this->output->get_output();
                //  // Load pdf library

                $this->load->library('pdf');
                //  // Load HTML content
                $this->dompdf->loadHtml($html);

                //  // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4', 'portrate');
                ini_set('max_execution_time', '1800');

                //  // Render the HTML as PDF
                $this->dompdf->render();

                //  // Output the generated PDF (1 = download and 0 = preview)
                $companyName = str_replace(" ", "", $data['setting_details']->company_name);
                $fileName = $companyName . "_daily_invoices_bulk_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                exit;
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function pdfDailyInvoiceOLD($hashstring)
    {
        if ($hashstring && $hashstring != "") {
            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $all_invoice_paid = true;
            if ($invoice_ids != "") {
                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];
                $data['invoice_ids'] = $invoice_ids;
                $data['company_id'] = $company_id;
                $invoice_ids = explode(",", $invoice_ids);

                foreach ($invoice_ids as $key => $value) {
                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if ($invoice_details->payment_status != 2) {
                        $all_invoice_paid = false;
                    }
                    $company_id = $invoice_details->company_id;
                    $invoice_details->all_sales_tax = $this->INV->getAllInvoiceSalesTax(array('invoice_id' => $value));
                    $invoice_details->report_details = $this->INV->getOneRepots(array('report_id' => $invoice_details->report_id));
                    $data['invoice_details'][] = $invoice_details;
                }
                $where_company = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                $where_arr = array(
                    'company_id' => $company_id,
                    'status' => 1,
                );
                $data['all_invoice_paid'] = $all_invoice_paid;
                $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect($where_arr);
                $data['basys_details'] = $this->CompanyModel->getOneBasysRequest($where_arr);
                $this->load->view('daily_multiple_pdf_invoice', $data);
                $html = $this->output->get_output();
                //  // Load pdf library
                $this->load->library('pdf');
                //  // Load HTML content
                $this->dompdf->loadHtml($html);
                //  // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4', 'portrate');
                ini_set('max_execution_time', '1800');
                //  // Render the HTML as PDF
                $this->dompdf->render();
                //  // Output the generated PDF (1 = download and 0 = preview)
                $companyName = str_replace(" ", "", $data['setting_details']->company_name);
                $fileName = $companyName . "_daily_invoices_bulk_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                exit;
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function dailyInvoiceList($hashstring)
    {
        if ($hashstring && $hashstring != "") {
            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];

            if ($invoice_ids != "") {
                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];

                $data['invoice_ids'] = $invoice_ids;
                $invoice_ids = explode(",", $invoice_ids);
                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                foreach ($invoice_ids as $key => $value) {
                    $where = array(
                        "invoice_tbl.company_id" => $company_id,
                        'invoice_id' => $value,
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if ($invoice_details->payment_status != 2) {
                        $invoice_details->all_sales_tax = $this->INV->getAllInvoiceSalesTax(array('invoice_id' => $value));
                        $invoice_details->report_details = $this->INV->getOneRepots(array('report_id' => $invoice_details->report_id));

                        // get coupon info

                        ////////////////////////////////////
                        // START INVOICE CALCULATION COST //

                        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
                        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

                        // invoice cost
                        // $invoice_total_cost = $invoice->cost;

                        // cost of all services (with price overrides) - service coupons
                        $job_cost_total = 0;
                        $total_coupon_amount = 0;
                        $where = array(
                            'property_program_job_invoice.invoice_id' => $invoice_details->invoice_id,
                        );
                        $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                                        $coupon_job_amm_total = 0;
                                        $coupon_job_amm = $coupon->coupon_amount;
                                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                                        if ($coupon_job_calc == 0) { // flat amm
                                            $coupon_job_amm_total = (float) $coupon_job_amm;
                                        } else { // percentage
                                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                        }

                                        $job_cost = $job_cost - $coupon_job_amm_total;
                                        $total_coupon_amount += $coupon_job_amm_total;

                                        if ($job_cost < 0) {
                                            $job_cost = 0;
                                        }
                                    }
                                }

                                $job_cost_total += $job_cost;
                            }
                        } else {
                            $job_cost_total = $invoice_details->cost;
                        }
                        $invoice_total_cost = $job_cost_total;

                        // check price override -- any that are not stored in just that ^^.

                        // - invoice coupons
                        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
                        foreach ($coupon_invoice_details as $coupon_invoice) {
                            if (!empty($coupon_invoice)) {
                                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                if ($coupon_invoice_amm_calc == 0) { // flat amm
                                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                } else { // percentage
                                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                    $invoice_total_cost -= $coupon_invoice_amm;
                                    $total_coupon_amount += $coupon_invoice_amm;
                                }
                                if ($invoice_total_cost < 0) {
                                    $invoice_total_cost = 0;
                                }
                            }
                        }

                        // + tax cost
                        $invoice_total_tax = 0;
                        $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice_details->invoice_id));
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

                        $invoice_details->total_amount_minus_partial = number_format($invoice_total_cost, 2);

                        $data['invoice_details'][] = $invoice_details;
                    }
                }
                if (isset($data['invoice_details'])) {
                    $this->load->view('daily_invoice_list', $data);
                } else {
                    echo "Invalid access";
                }
            } else {
                echo "Invalid access";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function dailyPayment($hashstring)
    {
        if ($hashstring != "") {
            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $unpaid_invoice_ids = [];
            
            if ($invoice_ids != "") {
                $invoice_ids_list = explode(',', $invoice_ids);
                $invoice_cost = 0;
                $total_tax_amount = 0;
                $partial_payment = 0;
                $already_paid = false;
                $company_id = $invoice_mini_list["company_id"];
                $total_amount_minus_partials = 0;

                $data = array(
                    'invoice_ids' => '',
                    'invoice_cost' => $invoice_cost,
                    'already_paid' => $already_paid,
                    'total_tax_amount' => $total_tax_amount,
                    'partial_payment' => $partial_payment,
                    'setting_details' => false,
                    'cardconnect_details' => false,
                    'basys_details' => false,
                );
                foreach ($invoice_ids_list as $invoice_id) {
                    $where = array('invoice_id' => $invoice_id);
                    if ($this->INV->getOneInvoive($where)) {
                        $invoice_details = $this->INV->getOneInvoive($where);
                        if ($invoice_details->payment_status != 2) {
                            array_push($unpaid_invoice_ids, $invoice_id);
                            $invoice_cost += $invoice_details->cost;
                            $tax_details = $this->INV->getAllInvoiceSalesTax($where);
                            $total_tax_amount += array_sum(array_column($tax_details, 'tax_amount'));
                            $partial_payment += $invoice_details->partial_payment;

                            // get coupon info

                            ////////////////////////////////////
                            // START INVOICE CALCULATION COST //

                            $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
                            $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

                            // invoice cost
                            // $invoice_total_cost = $invoice->cost;

                            // cost of all services (with price overrides) - service coupons
                            $job_cost_total = 0;
                            $total_coupon_amount = 0;
                            $where = array(
                                'property_program_job_invoice.invoice_id' => $invoice_id,
                            );
                            $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                                            $coupon_job_amm_total = 0;
                                            $coupon_job_amm = $coupon->coupon_amount;
                                            $coupon_job_calc = $coupon->coupon_amount_calculation;

                                            if ($coupon_job_calc == 0) { // flat amm
                                                $coupon_job_amm_total = (float) $coupon_job_amm;
                                            } else { // percentage
                                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                            }

                                            $job_cost = $job_cost - $coupon_job_amm_total;
                                            $total_coupon_amount += $coupon_job_amm_total;

                                            if ($job_cost < 0) {
                                                $job_cost = 0;
                                            }
                                        }
                                    }

                                    $job_cost_total += $job_cost;
                                }
                            } else {
                                $job_cost_total = $invoice_details->cost;
                            }
                            $invoice_total_cost = $job_cost_total;

                            // check price override -- any that are not stored in just that ^^.

                            // - invoice coupons
                            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
                            foreach ($coupon_invoice_details as $coupon_invoice) {
                                if (!empty($coupon_invoice)) {
                                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                                        $total_coupon_amount += $coupon_invoice_amm;
                                    } else { // percentage
                                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                        $invoice_total_cost -= $coupon_invoice_amm;
                                        $total_coupon_amount += $coupon_invoice_amm;
                                    }
                                    if ($invoice_total_cost < 0) {
                                        $invoice_total_cost = 0;
                                    }
                                }
                            }

                            // + tax cost
                            $invoice_total_tax = 0;
                            $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
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
                            @$total_amount_minus_partials += number_format($invoice_total_cost, 2);
                        }
                    }
                }
                if ($invoice_cost > 0) {
                    $invoice_ids = implode(',', $unpaid_invoice_ids);
                    $data['invoice_ids'] = $invoice_ids;
                    $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                    $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
                    if($data['basys_details'] == NULL) {
                        $data["cardconnect_details"] = $this->CardConnectModel->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));
                    }
                    
                    $data['already_paid'] = $already_paid;
                    $data['total_tax_amount'] = $total_tax_amount;
                    $data['invoice_cost'] = $invoice_cost;
                    $data['partial_payment'] = $partial_payment;
                    $data['total_amount_minus_partials'] = $total_amount_minus_partials;
                    if($data['basys_details'] == NULL) {
                        $this->load->view('daily_clover_card_processing', $data);
                    } else {
                       $this->load->view('daily_basys_card_processing', $data);
                    }
                } else {
                    $this->load->view('daily_basys_card_processing', $data);
                }
            } else {
                echo "Invalid access";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function daily_paymentProcess()
    {
        $data = $this->input->post();
        $invoice_id_list = explode(',', $data['invoice_id']);
        $total_tax_amount = 0;
        $invoice_cost = 0;
        $partial_payment = 0;
        $total_amount_minus_partials = 0;
        $invoice_details = null;
        $paid_invoice_details = [];
        if (count($invoice_id_list) > 0) {
            $loop_index = 0;
            foreach ($invoice_id_list as $invoice_id) {
                $where = array('invoice_id' => $invoice_id);
                $invoice_data = $this->INV->getOneInvoive($where);
                if ($loop_index == 0) {
                    $invoice_details = $invoice_data;
                }
                $tax_details = $this->INV->getAllInvoiceSalesTax($where);
                $tax_amount = 0;
                if ($tax_details) {
                    $tax_amount = array_sum(array_column($tax_details, 'tax_amount'));
                    $total_tax_amount += $tax_amount;
                }
                // $paid_invoice_details[$invoice_id]['tax_amount'] = $tax_amount;
                // $paid_invoice_details[$invoice_id]['cost'] = $invoice_data->cost;
                // $paid_invoice_details[$invoice_id]['partial_payment'] = $invoice_data->partial_payment;
                // $invoice_cost += $invoice_data->cost;
                // $partial_payment += $invoice_data->partial_payment;
                // $loop_index++;

                // new calc

                ////////////////////////////////////
                // START INVOICE CALCULATION COST //

                $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
                $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

                // invoice cost
                // $invoice_total_cost = $invoice->cost;

                // cost of all services (with price overrides) - service coupons
                $job_cost_total = 0;
                $total_coupon_amount = 0;
                $where = array(
                    'property_program_job_invoice.invoice_id' => $invoice_id,
                );
                $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
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
                                $coupon_job_amm_total = 0;
                                $coupon_job_amm = $coupon->coupon_amount;
                                $coupon_job_calc = $coupon->coupon_amount_calculation;

                                if ($coupon_job_calc == 0) { // flat amm
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                }

                                $job_cost = $job_cost - $coupon_job_amm_total;
                                $total_coupon_amount += $coupon_job_amm_total;

                                if ($job_cost < 0) {
                                    $job_cost = 0;
                                }
                            }
                        }

                        $job_cost_total += $job_cost;
                    }
                } else {
                    $job_cost_total = $invoice_details->cost;
                }
                $invoice_total_cost = $job_cost_total;

                // check price override -- any that are not stored in just that ^^.

                // - invoice coupons
                $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
                foreach ($coupon_invoice_details as $coupon_invoice) {
                    if (!empty($coupon_invoice)) {
                        $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                        $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                        if ($coupon_invoice_amm_calc == 0) { // flat amm
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                            $total_coupon_amount += $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                            $invoice_total_cost -= $coupon_invoice_amm;
                            $total_coupon_amount += $coupon_invoice_amm;
                        }
                        if ($invoice_total_cost < 0) {
                            $invoice_total_cost = 0;
                        }
                    }
                }

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
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

                $total_amount_minus_partials += number_format($invoice_total_cost, 2);
                $paid_invoice_details[$invoice_id]['tax_amount'] = $total_tax_amount;
                $paid_invoice_details[$invoice_id]['cost'] = $invoice_total_cost - $total_tax_amount;
                $paid_invoice_details[$invoice_id]['partial_payment'] = $invoice_data->partial_payment;
                $invoice_cost += $invoice_total_cost - $total_tax_amount;
                $partial_payment += $invoice_data->partial_payment;
                $loop_index++;
            }
        } else {
            echo "Invalid request";
            exit;
        }

        $order_id = (string) strtotime("now");
        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_details->company_id));
        // $convenience_fee = number_format(($setting_details->convenience_fee * ($invoice_cost + $total_tax_amount - $partial_payment) / 100),2);
        // $total_amount = $invoice_cost + $total_tax_amount + $convenience_fee - $partial_payment;
        $convenience_fee = number_format(($setting_details->convenience_fee * ($total_amount_minus_partials - $partial_payment) / 100), 2);
        $total_amount = $total_amount_minus_partials + $convenience_fee - $partial_payment;

        $post = array(
            "type" => "sale",
            "amount" => round($total_amount * 100),
            "tax_exempt" => false,
            "tax_amount" => round($total_tax_amount * 100),
            "currency" => "USD",
            "description" => "Invoice for CustomerId: " . $invoice_details->customer_id,
            "order_id" => $order_id,
            //"ip_address" => $this->getClientIp(),
            "email_receipt" => true,
            "email_address" => $invoice_details->email,
            "create_vault_record" => true,
            "payment_method" => array(
                "token" => $data['token'],
            ),
            "billing_address" => array(
                "first_name" => $invoice_details->first_name,
                "last_name" => $invoice_details->last_name,
                "company" => $invoice_details->customer_company_name,
                "address_line_1" => $invoice_details->billing_street,
                "address_line_2" => isset($invoice_details->billing_street_2) ? $invoice_details->billing_street_2 : "",
                "city" => isset($invoice_details->billing_city) ? $invoice_details->billing_city : "",
                "state" => isset($invoice_details->billing_state) ? $invoice_details->billing_state : "",
                "postal_code" => isset($invoice_details->billing_zipcode) ? $invoice_details->billing_zipcode : "",
                "email" => $invoice_details->email,
                "phone" => $invoice_details->phone,
            ),
        );
        if ($convenience_fee != 0) {
            $post['payment_adjustment'] = array(
                "type" => "flat",
                "value" => $convenience_fee * 100,
            );
        }
        $basys_response = basysCurlProcess($data['api_key'], 'POST', 'transaction', $post);
        if ($basys_response['status'] == 200) {
            if ($basys_response['result']->data->response_code == 100) {
                $this->session->set_flashdata('message', '<div class="alert alert-succrss alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');
                foreach ($paid_invoice_details as $invoice_id => $paid_invoice_detail) {
                    $updatearr = array(
                        'payment_status' => 2,
                        'basys_transaction_id' => $basys_response['result']->data->id,
                        'payment_created' => date("Y-m-d H:i:s"),
                        'partial_payment' => $paid_invoice_detail['partial_payment'] + ($paid_invoice_detail['cost'] + $paid_invoice_detail['tax_amount']),
                        'basys_order_id' => $order_id,
                    );
                    $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updatearr);
                }
                $company_id = $invoice_details->company_id;
                $where = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));
                $data['first_name'] = $invoice_details->first_name;
                $data['last_name'] = $invoice_details->last_name;
                $data['convenience_fee'] = $convenience_fee;
                $data['invoice_cost'] = $invoice_cost;
                $data['partial_payment'] = $partial_payment;
                $data['total_tax_amount'] = $total_tax_amount;
                $data['total_amount_total'] = $total_amount;
                $body = $this->load->view('daily_invoice_paid_mail', $data, true);
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);
                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }
                $res = Send_Mail_dynamic($company_email_details, $data['user_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');
                $return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $basys_response['result']->data);
            } else {
                $return_arr = array('status' => 400, 'msg' => $basys_response['result']->data->response, 'result' => $basys_response['result']);
            }
        } else {
            $return_arr = array('status' => 400, 'msg' => $basys_response['message']);
        }
        echo json_encode($return_arr);
    }

    public function cardConnectPayment($invoice_id)
    {

        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_2');
        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_2');

        $invoice_id = base64_decode($invoice_id);
        if (!$this->isActive($invoice_id)) {
            die('Something has gone wrong. The Invoice you are trying to pay is not active.');
        }
        $where = array('invoice_id' => $invoice_id);
        $invoice_details = $this->INV->getOneInvoive($where);

        $data = array(
            'invoice_details' => false,
            'setting_details' => false,
            'cardconnect_details' => false,
            'basys_details' => false,
        );

        if ($invoice_details) {

            $data['invoice_details'] = $invoice_details;
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_details->company_id));
            $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $invoice_details->company_id, 'status' => 1));
            $data['tax_details'] = $this->INV->getAllInvoiceSalesTax($where);

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // invoice cost
            // $invoice_total_cost = $invoice->cost;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $total_coupon_amount = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $invoice_id,
            );
            $proprojobinv = $this->PropertyProgramJobInvoiceModel_2->getPropertyProgramJobInvoiceCoupon($where);
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
                            $coupon_job_amm_total = 0;
                            $coupon_job_amm = $coupon->coupon_amount;
                            $coupon_job_calc = $coupon->coupon_amount_calculation;

                            if ($coupon_job_calc == 0) { // flat amm
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;
                            $total_coupon_amount += $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
            } else {
                $job_cost_total = $invoice_details->cost;
            }
            $invoice_total_cost = $job_cost_total;

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                        $total_coupon_amount += $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                        $total_coupon_amount += $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax_2->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
                    if (array_key_exists("tax_value", $tax)) {
                        $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                        $invoice_total_tax += $tax_amm_to_add;
                    }
                }
            }
            $late_fee = $this->INV->getLateFee($invoice_id);
            $invoice_total_cost += $invoice_total_tax + $late_fee;
            $total_tax_amount = $invoice_total_tax;

            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////

            $data['actual_total_cost_miunus_partial'] = $invoice_total_cost;

            $this->load->view('card_connect_process', $data);

        } else {

            // echo 'hi';die();
            $this->load->view('card_connect_process', $data);

        }

    }

    public function ccPaymentProcess($value = '')
    {
        $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel_3');
        $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax_3');

        $data = $this->input->post();
        // die(print_r(json_encode($data)));

        $ids_explode = explode(" ", $data['invoice_id']);
        // die(print_r($ids_explode));
        $where = array('invoice_id' => $data['invoice_id']);
        $invoice_arr = $this->INV->getAllInvoiveWhereIn('invoice_id', $ids_explode);
        // die(print_r($this->db->last_query()));
        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_arr[0]->company_id));
        $tax_details = $this->INV->getAllInvoiceSalesTaxWhereIn('invoice_id', $ids_explode);
        $cardconnect_details = $this->CardConnect->getOneCardConnect(array('company_id' => $invoice_arr[0]->company_id, 'status' => 1));
        //die($this->db->last_query());
        $customer_details = $this->Customer->getCustomerDetail($invoice_arr[0]->customer_id);


        $invoice_amt_tax = [];
        if ($tax_details) {
            foreach($tax_details as $tax){
                array_push($invoice_amt_tax, $tax['tax_amount']);
            }
            // die(print_r($invoice_amt_tax));
            $total_tax_amount = array_sum($invoice_amt_tax);
            // die(print_r($total_tax_amount));
        }

        ////////////////////////////////////
        // START INVOICE CALCULATION COST //

        // invoice cost
        // $invoice_total_cost = $invoice->cost;

        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $total_coupon_amount = 0;
        $actual_cost = [];

        $proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCouponWhereIn('invoice_id', $ids_explode);
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
                        $coupon_job_amm_total = 0;
                        $coupon_job_amm = $coupon->coupon_amount;
                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                        if ($coupon_job_calc == 0) { // flat amm
                            $coupon_job_amm_total = (float) $coupon_job_amm;
                        } else { // percentage
                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;
                        $total_coupon_amount += $coupon_job_amm_total;

                        if ($job_cost < 0) {
                            $job_cost = 0;
                        }
                    }
                }

                $job_cost_total += $job_cost;
            }
        } else {
            $job_cost_total = $invoice_details->cost;
        }
        $invoice_total_cost = $job_cost_total;

        // check price override -- any that are not stored in just that ^^.

        // - invoice coupons
        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoiceWhereIn('coupon_invoice.invoice_id', $ids_explode);
        foreach ($coupon_invoice_details as $coupon_invoice) {
            if (!empty($coupon_invoice)) {
                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                if ($coupon_invoice_amm_calc == 0) { // flat amm
                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                    $total_coupon_amount += $coupon_invoice_amm;
                } else { // percentage
                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                    $invoice_total_cost -= $coupon_invoice_amm;
                    $total_coupon_amount += $coupon_invoice_amm;
                }
                if ($invoice_total_cost < 0) {
                    $invoice_total_cost = 0;
                }
            }
        }

        // + tax cost
        $invoice_total_tax = 0;
        $invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTaxWhereIn('invoice_id', $ids_explode);
        if (!empty($invoice_sales_tax_details)) {
            foreach($invoice_sales_tax_details as $tax) {
                // die(print_r($proprojobinv));
                $tax_amm_to_add = ((float) $tax['tax_amount']);
                array_push($actual_cost, $tax_amm_to_add);
            }
            array_push($actual_cost, $invoice_total_cost);
        } else {
            array_push($actual_cost, $invoice_total_cost);
        }

        $actual_cost = array_sum($actual_cost);

        $all_payments_details = [];
        $all_payments_details = $this->PartialPaymentModel->getAllPartialPaymentWhereIn('invoice_id', $ids_explode);
        // die(print_r($all_payments_details));
        
        ##### SUM ANY PAYMENT MADE ON INVOICES #####
        $actual_payments = 0;
        foreach($all_payments_details as $amount){
            if(isset($amount->payment_applied)){
                // die(print_r($amount->payment_applied));
                $actual_payments += $amount->payment_applied;
            }
        }
        // die(print_r($actual_payments));
        // $convenience_fee = 0;
        $data['actual_total_cost_miunus_partial'] = $actual_cost - $actual_payments;
        // die(print_r($data['actual_total_cost_miunus_partial']));
        $data['convenience_fee'] = $setting_details->convenience_fee*($data['actual_total_cost_miunus_partial'])/100;
        // $data['convenience_fee'] = $convenience_fee;
        $convenience_fee = $data['convenience_fee'];
        $data['total_tax_amount'] = $total_tax_amount;
        // die(print_r($convenience_fee));
        $data['total_payment_final'] = $data['actual_total_cost_miunus_partial'] + $data['convenience_fee'];

        // END TOTAL INVOICE CALCULATION COST //
        ////////////////////////////////////////
        //$invoice_total_cost = number_format($invoice_total_cost,2);

        $total_amount = $data['total_payment_final'];
        // $total_amount = $invoice_details->cost + $total_tax_amount + $convenience_fee - $invoice_details->partial_payment - $total_coupon_amount;

        if ($total_amount < 0) {
            $total_amount = 0;
        }

        $data['requestData']['email'] = $customer_details['email'];
        $data['requestData']['ecomind'] = 'E';
        $data['requestData']['name'] = $customer_details['first_name'] . ' ' . $customer_details['last_name'];
        $data['requestData']['address'] = $customer_details['billing_street'];
        $data['requestData']['city'] = $customer_details['billing_city'];
        $data['requestData']['region'] = $customer_details['billing_state'];
        $data['requestData']['postal'] = str_replace(' ', '', $customer_details['billing_zipcode']); //strip whitespace from CA postcode
        $data['requestData']['profile'] = 'Y';
        $data['requestData']['currency'] = $setting_details->company_currency;
        $data['requestData']['amount'] = number_format($total_amount, 2, '.', '');
        $data['username'] = $cardconnect_details->username;
        $data['password'] = decryptPassword($cardconnect_details->password);
        $data['merchid'] = $cardconnect_details->merchant_id;
        $data['requestData']['merchid'] = $cardconnect_details->merchant_id;
        // die(print_r(floatval(preg_replace('/[^\d.]/','', number_format($total_amount, 2)))));
        // $dataToLog = array($total_amount);
        // $data = implode(" - ", $dataToLog);
        // $data .= PHP_EOL;
        // file_put_contents('nr_log.txt', $data, FILE_APPEND);
        
        $order_id = (string) strtotime("now");
        $cc_authorize = cardConnectAuthorize($data);
        
        if ($cc_authorize['status'] == 200) {

            if (strcmp($cc_authorize['result']->respstat, 'A') == 0) {

                $cap = array(
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'merchid' => $data['merchid'],
                    'capData' => array(
                        'merchid' => $data['merchid'],
                        'retref' => $cc_authorize['result']->retref,
                        'authcode' => $cc_authorize['result']->authcode,
                        'amount' => $data['requestData']['amount'],
                        'invoiceid' => $data['invoice_id'],
                    ),

                );

                $captured = cardConnectCapture($cap);

                // die(print_r(json_encode($captured)));

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');

                foreach($invoice_arr as $index => $invoice){
                    ////////////////////////////////////
                    // START SINGLE INVOICE CALCULATION COST //
                    
                    // invoice cost
                    // $invoice_total_cost = $invoice->cost;


                    // cost of all services (with price overrides) - service coupons
                    $single_invoice_job_cost_total = 0;
                    $single_invoice_total_coupon_amount = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $invoice->invoice_id
                    );
                    $single_invoice_proprojobinv = $this->PropertyProgramJobInvoiceModel_3->getPropertyProgramJobInvoiceCoupon($where);
                    if (!empty($single_invoice_proprojobinv)) {
                        foreach($single_invoice_proprojobinv as $job) {

                            $single_invoice_job_cost = $job['job_cost'];

                            $job_where = array(
                                'job_id' => $job['job_id'],
                                'customer_id' =>$job['customer_id'],
                                'property_id' =>$job['property_id'],
                                'program_id' =>$job['program_id']
                            );
                            $single_invoice_coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                            if (!empty($single_invoice_coupon_job_details)) {

                                foreach($single_invoice_coupon_job_details as $coupon) {
                                    $single_invoice_coupon_job_amm_total = 0;
                                    $single_invoice_coupon_job_amm = $coupon->coupon_amount;
                                    $single_invoice_coupon_job_calc = $coupon->coupon_amount_calculation;

                                    if ($single_invoice_coupon_job_calc == 0) { // flat amm
                                        $single_invoice_coupon_job_amm_total = (float) $single_invoice_coupon_job_amm;
                                    } else { // percentage
                                        $single_invoice_coupon_job_amm_total = ((float) $single_invoice_coupon_job_amm / 100) * $single_invoice_job_cost;
                                    }

                                    $single_invoice_job_cost = $single_invoice_job_cost - $single_invoice_coupon_job_amm_total;
                                    $single_invoice_total_coupon_amount += $single_invoice_coupon_job_amm_total;

                                    if ($single_invoice_job_cost < 0) {
                                        $single_invoice_job_cost = 0;
                                    }
                                }
                            }

                            $single_invoice_job_cost_total += $single_invoice_job_cost;
                        }
                    } else {
                        $single_invoice_job_cost_total = $invoice->cost;
                    }
                    $single_invoice_total_cost = $single_invoice_job_cost_total;

                    // check price override -- any that are not stored in just that ^^.

                    // - invoice coupons
                    $single_invoice_coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id));
                    foreach ($single_invoice_coupon_invoice_details as $coupon_invoice) {
                        if (!empty($coupon_invoice)) {
                            $single_invoice_coupon_invoice_amm = $coupon_invoice->coupon_amount;
                            $single_invoice_coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                            if ($single_invoice_coupon_invoice_amm_calc == 0) { // flat amm
                                $single_invoice_total_cost -= (float) $single_invoice_coupon_invoice_amm;
                                $single_invoice_total_coupon_amount += $single_invoice_coupon_invoice_amm;
                            } else { // percentage
                                $single_invoice_coupon_invoice_amm = ((float) $single_invoice_coupon_invoice_amm / 100) * $single_invoice_total_cost;
                                $single_invoice_total_cost -= $single_invoice_coupon_invoice_amm;
                                $single_invoice_total_coupon_amount += $single_invoice_coupon_invoice_amm;
                            }
                            if ($single_invoice_total_cost < 0) {
                                $single_invoice_total_cost = 0;
                            }
                        }
                    }

                    // + tax cost
                    $single_invoice_total_tax = 0;
                    $single_invoice_sales_tax_details = $this->InvoiceSalesTax_3->getAllInvoiceSalesTax(array('invoice_id' => $invoice->invoice_id));
                    if (!empty($single_invoice_sales_tax_details)) {
                        foreach($single_invoice_sales_tax_details as $tax) {
                            if (array_key_exists("tax_value", $tax)) {
                                $single_invoice_tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $single_invoice_total_cost;
                                $single_invoice_total_tax += $single_invoice_tax_amm_to_add;
                            }
                        }
                    }
                    $single_invoice_total_cost += $single_invoice_total_tax;

                    // END SINGLE INVOICE CALCULATION COST //
                    ////////////////////////////////////////
                    //$invoice_total_cost = number_format($invoice_total_cost,2);

                    $single_invoice_total_amount = $single_invoice_total_cost - $invoice->partial_payment;
                    // $total_amount = $invoice_details->cost + $total_tax_amount + $convenience_fee - $invoice_details->partial_payment - $total_coupon_amount;
                
                
                    if ($single_invoice_total_amount < 0) {
                        $single_invoice_total_amount = 0;
                    }
                    if ($invoice_details->payment_status != 2) {
                        $partial_log = $this->PartialPaymentModel->createOnePartialPayment(array(
                            'invoice_id' => $data['invoice_id'],
                            'payment_amount' => $data['requestData']['amount'] + $invoice_details->partial_payment,
                            'payment_applied' => $data['requestData']['amount'] + $invoice_details->partial_payment,
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => 4,
                            'customer_id' => $invoice_details->customer_id,
                        ));
                        //KT and EE add status, opened_date, and sent date
                        $updatearr = array(
                            'payment_status' => 2,
                            'payment_created' => date("Y-m-d H:i:s"),
                            'payment_method' => 4,
                            'partial_payment' => $data['requestData']['amount'] + $invoice_details->partial_payment,
                            'clover_order_id' => $order_id,
                            'clover_transaction_id' => $cc_authorize['result']->retref,
                            'refund_amount_total' => 0.00,
                            'status' => 2,
                            'opened_date' => $invoice_details->opened_date == '' ? date("Y-m-d H:i:s") : $invoice_details->opened_date,
                            'sent_date' => $invoice_details->sent_date == '' ?  date("Y-m-d H:i:s") : $invoice_details->sent_date
                        );
    
                        $this->INV->updateInvoive(array('invoice_id' => $invoice->invoice_id), $updatearr);
                    }
    
                }

                $company_id = $invoice_arr[0]->company_id;

                $where = array('company_id' => $company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));

                $data['convenience_fee'] = $convenience_fee;
                $data['tax_amount'] = isset($total_tax_amount) ? $total_tax_amount : 0.00;

                $data['invoice_details'] = $invoice_arr;

                $data['actual_total_amount'] = $total_amount;

                $body = $this->load->view('invoice_paid_mail', $data, true);

                $body2 = $this->load->view('clover_paid_email', $data, true);

                $where['is_smtp'] = 1;

                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }
                $res = Send_Mail_dynamic($company_email_details, $data['user_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');

                $res2 = Send_Mail_dynamic($company_email_details, $customer_details['email'], array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body2, 'Transaction Information');

                $return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $captured['result']);

            } else {

                $return_arr = array('status' => 400, 'msg' => $cc_authorize['result']->resptext, 'result' => $cc_authorize['result']);

            }

        } else {
            $return_arr = array('status' => 400, 'msg' => $cc_authorize['message']);
        }

        echo json_encode($return_arr);

    }
    public function pdfWorkStatement($hashstring)
    {

        $this->load->model('Work_statement_model', 'STATE');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Job_model', 'JobModel');

        if ($hashstring && $hashstring != "") {

            $where_arr = array("hashstring" => $hashstring);
            $statement_mini_list = $this->STATE->getStatementMiniListFromHashString($where_arr);
            $work_statement_ids = $statement_mini_list["statement_ids"];
            $all_statement_paid = true;

            if ($work_statement_ids != "") {

                $data["hashstring"] = $hashstring;
                $company_id = $statement_mini_list["company_id"];
                $data['work_statement_ids'] = $work_statement_ids;
                $data['company_id'] = $company_id;
                $work_statement_ids = explode(",", $work_statement_ids);

                foreach ($work_statement_ids as $key => $value) {

                    $where = array(
                        "completed_work_statements.company_id" => $company_id,
                        'work_statement_id' => $value,
                    );

                    // die(print_r($value));

                    $statement_details = $this->STATE->getOneWorkStatement($where);

                    $statement_details->report_details = $this->RP->getOneRepots(array('report_id' => $statement_details->report_id));



                    // echo $value . ' -- ' . json_encode($invoice_details->report_details);
                    // echo "<br><br>";

                    //get job details
                    $jobs = array();

                    $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $statement_details->invoice_id, 'property_program_job_invoice.job_id' => $statement_details->job_id));

                    // die(print_r($job_details));
                    if ($job_details) {



                        foreach ($job_details as $detail) {

                            $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $statement_details->invoice_id));

                            if (isset($detail['report_id'])) {
                                $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                            } else {
                                $report = '';
                            }

                            // SERVICE WIDE COUPONS
                            $arry = array(
                                'customer_id' => $statement_details->customer_id,
                                'program_id' => $statement_details->program_id,
                                'property_id' => $statement_details->property_id,
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

                    $statement_details->jobs = $jobs;
                    $statement_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $statement_details->invoice_id));
                    $data['statement_details'][] = $statement_details;
                }

                $where_company = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

                $where_arr = array(
                    'company_id' => $company_id,
                    'status' => 1,
                );
                $data['all_statement_paid'] = $all_statement_paid;

                // die();

                $this->load->view('completed_work_statement_pdf', $data);
                $html = $this->output->get_output();
                //  // Load pdf library

                $this->load->library('pdf');
                //  // Load HTML content
                $this->dompdf->loadHtml($html);
                // die(print_r($html));
                //  // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4', 'portrate');
                ini_set('max_execution_time', '1800');

                //  // Render the HTML as PDF
                $this->dompdf->render();

                //  // Output the generated PDF (1 = download and 0 = preview)
                $companyName = str_replace(" ", "", $data['setting_details']->company_name);
                $fileName = $companyName . "_daily_statements_bulk_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                exit;
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    /*** TRACK OPENED EMAILS FOR INVOICE ***/
    public function openedStatementPixel()
    {

        if (!empty($_GET['work_statement_id'])) {
            $work_statement_id = $_GET['work_statement_id'];
            $getStatement = $this->STATE->getOneStatement($work_statement_id);
            if (empty($getStatement->opened_date)) {
                $this->STATE->updateStatement(array('work_statement_id' => $work_statement_id), array('opened_date' => date('Y-m-d H:i:s'), 'status' => 2));
            }
        }
    }
    /*** TRACK OPENED EMAILS FOR INVOICE ***/
    public function openedMultStatementPixel()
    {

        if (!empty($_GET['work_statement_id'])) {
            $work_statement_ids = explode(',', $_GET['work_statement_id']);
            foreach ($work_statement_ids as $work_statement_id) {
                $getStatement = $this->STATE->getOneStatement($work_statement_id);
                if (empty($getStatement->opened_date)) {
                    $this->STATE->updateStatement(array('work_statement_id' => $work_statement_id), array('opened_date' => date('Y-m-d H:i:s'), 'status' => 2));
                }
            }
        }
    }
    #### ONLY RUN THIS FUNCTION TO SEED DATABASE WITH COMPANY SLUG FOR CUSTOMER PORTAL ####
    public function updateAllCompanySlugs()
    {
        $data['all_companies'] = $this->CompanyModel->getAllCompany();
        // die("IN UPDATE COMPANY SLUG FUNCTION");
        // die(print_r($data['all_companies']));
        foreach ($data['all_companies'] as $company) {

            $company_id = $company->company_id;

            // die(print_r($where));
            $city = $company->company_address;
            $dup_company_check = [];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $company->company_name)));
            //  $dup_slug_check = $this->CompanyModel->getAllCompanySlugDuplicates(array('slug' => $slug));
            $dup_company_check = $this->CompanyModel->getAllCompanySlugDuplicates(array('company_name' => $company->company_name));
            // die(print_r($dup_company_check));
            if (count($dup_company_check) > 1) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug . '-' . $city)));
                // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.$city[1])));
                // die(print_r($slug));
                $param = array(
                    // 'company_name' => $company['company_name'],
                    // 'company_address' => $company['company_address'],
                    // 'company_address_lat' => $company_geo['lat'],
                    // 'company_address_long' => $company_geo['long'],
                    // 'company_phone_number' => $company['company_phone_number'],
                    // 'company_email' => $company['company_email'],
                    // 'web_address' => $company['web_address'],
                    // 'invoice_color' => $company['invoice_color'],
                    // 'default_display_length' => $company['default_display_length'],
                    // 'time_zone' => $company['time_zone'],
                    'updated_at' => date("Y-m-d H:i:s"),
                    'slug' => $slug,
                );
                //    die(print_r($param));
                $result = $this->CompanyModel->updateCompanyTbl($company_id, $param);
            } else {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $company->company_name)));
                //  die(print_r($slug));
                $param = array(
                    // 'company_name' => $company->company_name,
                    // 'company_address' => $company->company_address,
                    // 'company_address_lat' => $company_geo->lat,
                    // 'company_address_long' => $company_geo->long,
                    // 'company_phone_number' => $company->company_phone_number,
                    // 'company_email' => $company->company_email,
                    // 'web_address' => $company->web_address,
                    // 'invoice_color' => $company->invoice_color,
                    // 'default_display_length' => $company->default_display_length,
                    // 'time_zone' => $company->time_zone,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'slug' => $slug,
                );
                // die(print_r($param));
                $result = $this->CompanyModel->updateCompanyTbl($company_id, $param);
                // die(print_r($result));
                // die('nonDuplicate');
            }
        }
    }

    public function getOpenInvoiceByCustomer($customer_id)
    {

        $post_data = $this->input->post();

        // $where = array(
        //     'invoice_tbl.customer_id' =>$customer_id,
        //     'payment_status !=' =>2
        // );

        // $data['invoice_details'] = $this->INV->getAllInvoive($where);

        // WHERE:
        $whereArr = array(
            'is_archived' => 0,
            'invoice_tbl.customer_id' => $customer_id,
            // 'payment_status !=' => 2
        );
        if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {
            $whereArr['invoice_tbl.invoice_date >='] = $post_data['start_date'];
        }
        if (isset($post_data['end_date']) && !empty($post_data['end_date'])) {
            $whereArr['invoice_tbl.invoice_date <='] = $post_data['end_date'];
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
                'invoice_tbl.invoice_date <' => $post_data['start_date'],
            );

            $data_before_period = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArrBefore, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);

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
                    $total = number_format($total, 2);
                    $total = (float) $total;
                    //var_dump($total);
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

        $data['customer_details'] = $this->Customer->getCustomerDetail($customer_id);
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        $count = 0;
        foreach ($data['invoice_details'] as $inv_deets) {

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

        $this->load->view('customer_all_pdf_invoice', $data);

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

    public function unsubscribePropertyEmail($group_billing_id)
    {
        $param = array('email_opt_in' => 0);
        $this->PropertyModel->updateGroupBilling($group_billing_id, $param);
        $this->load->view('message');
    }

    public function groupBillingPdf($hashstring)
    {
        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Job_model', 'JobModel');

        if ($hashstring && $hashstring != "") {

            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $all_invoice_paid = true;

            if ($invoice_ids != "") {

                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];
                $data['invoice_ids'] = $invoice_ids;
                $data['company_id'] = $company_id;
                $invoice_ids = explode(",", $invoice_ids);
                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

                foreach ($invoice_ids as $key => $value) {

                    $where = array(
                        "invoice_tbl.company_id" => $data['company_id'],
                        'invoice_id' => $value
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if (!empty($invoice_details->property_id)) {
                        $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
                    }
                    if (empty($invoice_details->job_id)) {
                        //get job data
                        $jobs = array();

                        $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $value));

                        if ($job_details) {
                            //print_r($job_details);
                            foreach ($job_details as $detail) {
                                $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $value));
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
                                    'job_id' => $detail['job_id']
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
                                    'job_assign_date' => isset($get_assigned_date->job_assign_date) ? $get_assigned_date->job_assign_date : '',
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

                    if (!empty($invoice_details->report_id)) {
                        $invoice_details->report_details = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));
                    }

                    $data['invoice_details'][] = $invoice_details;
                    $this->load->view('admin/invoice/multiple_pdf_invoice_print_group_billing', $data);
                    $html = $this->output->get_output();
                    //  // Load pdf library

                    $this->load->library('pdf');
                    //  // Load HTML content
                    $this->dompdf->loadHtml($html);

                    //  // (Optional) Setup the paper size and orientation
                    $this->dompdf->setPaper('A4', 'portrate');
                    ini_set('max_execution_time', '1800');

                    //  // Render the HTML as PDF
                    $this->dompdf->render();

                    //  // Output the generated PDF (1 = download and 0 = preview)
                    $companyName = str_replace(" ", "", $data['setting_details']->company_name);
                    $fileName = $companyName . "_daily_invoices_bulk_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                    $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                    exit;
                }
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    public function groupBillingView($hashstring)
    {
        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Job_model', 'JobModel');

        if ($hashstring && $hashstring != "") {

            $where_arr = array("hashstring" => $hashstring);
            $invoice_mini_list = $this->INV->getInvoiceMiniListFromHashString($where_arr);
            $invoice_ids = $invoice_mini_list["invoice_ids"];
            $all_invoice_paid = true;

            if ($invoice_ids != "") {

                $data["hashstring"] = $hashstring;
                $company_id = $invoice_mini_list["company_id"];
                $data['invoice_ids'] = $invoice_ids;
                $data['company_id'] = $company_id;
                $invoice_ids = explode(",", $invoice_ids);
                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

                foreach ($invoice_ids as $key => $value) {

                    $where = array(
                        "invoice_tbl.company_id" => $data['company_id'],
                        'invoice_id' => $value
                    );
                    $invoice_details = $this->INV->getOneInvoive($where);
                    if (!empty($invoice_details->property_id)) {
                        $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
                    }
                    if (empty($invoice_details->job_id)) {
                        //get job data
                        $jobs = array();

                        $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $value));

                        if ($job_details) {
                            //print_r($job_details);
                            foreach ($job_details as $detail) {
                                $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $value));
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
                                    'job_id' => $detail['job_id']
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
                                    'job_assign_date' => isset($get_assigned_date->job_assign_date) ? $get_assigned_date->job_assign_date : '',
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

                    if (!empty($invoice_details->report_id)) {
                        $invoice_details->report_details = $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));
                    }

                    $data['invoice_details'][] = $invoice_details;
                    $this->load->view('admin/invoice/multiple_pdf_invoice_print_group_billing', $data);
                    $html = $this->output->get_output();
                    echo $html;
                    exit;
                }
            } else {
                echo "Invalid access or Link expired";
            }
        } else {
            echo "Invalid access";
        }
    }

    /**
     * This function is being called once a month and sends invoice email to customer(s)
     */
    public function sendCustomerInvoicesMonthly()
    {
        //die("here");
        // Mail should send link with date and customerId.
        $invoice_date = date('Y-m-d', strtotime(' -1 month'));
        $where = array(
            'invoice_date >' => $invoice_date,
            'status' => 0,
            'email >' => '\'\''
        );
        $completed_job_customer_detail = $this->INV->getCompletedJobCustomerDetail($where);
        //		die(print_r($completed_job_customer_detail));
        $customer_wise_data = [];
        foreach ($completed_job_customer_detail as $detail) {
            if ($detail['report_id'] > 0) {
                if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                    array_push($customer_wise_data[$detail['customer_id']], $detail);
                } else {
                    $customer_wise_data[$detail['customer_id']][] = $detail;
                }
            } else {
                //if no report id in invoice table, check Property Program Job Invoice Table
                $PPJOBINVdata = $this->INV->getPPJOBINVdetails(array('invoice_id' => $detail['invoice_id']));
                if ($PPJOBINVdata) {
                    $complete = 0;
                    foreach ($PPJOBINVdata as $job) {
                        if ($job['report_id'] > 0) {
                            $complete = 1;
                        }
                    }
                    if ($complete == 1) {
                        if (array_key_exists($detail['customer_id'], $customer_wise_data)) {
                            array_push($customer_wise_data[$detail['customer_id']], $detail);
                        } else {
                            $customer_wise_data[$detail['customer_id']][] = $detail;
                        }
                    }
                }
            }
        }
        foreach ($customer_wise_data as $customer_id => $customer_data) {
            $email = $customer_data[0]['email'];
            $company_id = $customer_data[0]['company_id'];
            $data['customer_details'] = (object) $this->Customer->getCustomerDetail($customer_id);
            $invoice_id_list = array_column($customer_data, 'invoice_id');
            $hashstring = md5($email . "-" . $customer_id . "-" . date("Y-m-d H:i:s"));
            $data['link'] = base_url('welcome/pdfDailyInvoice/') . $hashstring;
            $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;
            $where_company = array('company_id' => $company_id);
            $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyModel->getOneCompanyEmailArray(array('company_id' => $company_id));
            //if($company_id == "117") {
            if (is_array($company_email_details) && $company_email_details['send_daily_invoice_mail'] == 1) {
                foreach ($invoice_id_list as $key => $inv) {
                    //if invoice is for program invoice method Invoice at Service Completion, check for completed services
                    $invDetails = $this->INV->getOneInvoice($inv);
                    if (isset($invDetails->program_id)) {
                        $getProgramDetails = $this->INV->getOneProgram($invDetails->program_id);
                        if ($getProgramDetails->program_price == 2) {
                            //check tech job assign table for complete status
                            $assigned_jobs = $this->AssignJobs->getAllJobAssignByInvoice(array('invoice_id' => $inv));
                            if ($assigned_jobs) {
                                foreach ($assigned_jobs as $k => $v) {
                                    if ($v['is_complete'] == 0) {
                                        //if not complete then remove from invoice list
                                        unset($invoice_id_list[$key]);
                                    }
                                }
                            }
                        }
                    }
                }

                $update_arr = array(
                    "status" => 1,
                    "last_modify" => date("Y-m-d H:i:s"),
                );
                $this->INV->updateInvoiceForInvoices($invoice_id_list, $update_arr);
                // Added hash table concept to get rid of expired link issue.
                $batch_insert_arr = array();
                foreach ($invoice_id_list as $invoice_id) {
                    $hash_tbl_arr = array();
                    $hash_tbl_arr['invoice_id'] = $invoice_id;
                    $hash_tbl_arr['company_id'] = $company_id;
                    $hash_tbl_arr['hashstring'] = $hashstring;
                    $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");
                    array_push($batch_insert_arr, $hash_tbl_arr);
                }
                $this->db->insert_batch('invoice_hash_tbl', $batch_insert_arr);
                //echo "this customer has an invoice: ".$data['customer_details']->customer_id;
                $body = $this->load->view('invoice_daily_mail', $data, true);
                if ($data['customer_details']->autosend_invoices == 1 && $data['customer_details']->autosend_frequency == 'monthly') {
                    $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Invoice Details', $data['customer_details']->secondary_email);
                    //echo "sending daily invoice email to customer id = ".$data['customer_details']->customer_id;
                }


            }
            //}
        }
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
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

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

    public function calculateServiceCouponCost($param = array())
    {
        $total_cost = $param['cost'];
        $coupon_jobs = $this->CouponModel->getAllCouponJob(
            array(
                'job_id' => $param['job_id'],
                'program_id' => $param['program_id'],
                'property_id' => $param['property_id'],
                'customer_id' => $param['customer_id']
            )
        );

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

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

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
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

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

    public function QuickBookInv($param)
    {


        $customer_details = $this->Customer->getCustomerDetail($param['customer_id']);

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

    public function getOneQuickBookCustomer($quickbook_customer_id)
    {

        $company_details = $this->checkQuickbookTwo();
        if ($company_details) {

            $dataService = DataService::Configure(
                array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' => $company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production"
                )
            );

            $dataService->setLogLocation("/QBO_log.txt");

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

    public function checkQuickbookTwo()
    {
        $where_comp = array(
            'company_id' => $this->session->userdata['company_id'],
            'is_quickbook' => 1,
            'quickbook_status' => 1
        );

        $company_details = $this->CompanyModel->getOneCompany($where_comp);

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

                $this->CompanyModel->updateCompany($post_data);

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

    public function createInvoiceInQuickBook($param)
    {


        $company_details = $this->checkQuickbookTwo();

        if ($company_details) {

            // die(print_r($company_details));

            $dataService = DataService::Configure(
                array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' => $company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production"
                )
            );

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $dataService->throwExceptionOnError(true);
            //Add a new Invoice

            // var_dump($param);
            // die();

            $details = getVisIpAddr();

            $all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $param['invoice_id']));

            $description = 'Service Name: ' . $param['job_name'] . '. Service Description: ' . $param['actual_description_for_QBO'] . '. Service Address: ' . $param['property_street'] . '.';

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

            return array('status' => 400, 'msg' => 'please integrate quickbook account', 'result' => '');
        }
    }

    public function pdfPurchaseOrder($purchase_order_id)
    {
        $purchase_order_id = base64_decode($purchase_order_id);

        $where = array(
            'purchase_order_tbl.purchase_order_id' => $purchase_order_id,
        );

        $data['purchase_order'] = $this->PurchasesModel->getOnePurchase($where);

        $param = array(
            'purchase_sent_status' => 2,
            'updated_at' => date("Y-m-d H:i:s")
        );

        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

        // $where = array('company_id' => $data['purchase_order']->company_id, 'id' => $data['purchase_order']->created_by_id);
        $data['user_details'] = $this->CompanyModel->getOneCompanyUser(array('company_id' => $data['purchase_order']->company_id, 'id' => $data['purchase_order']->created_by_id));

        $where_company = array('company_id' => $data['purchase_order']->company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        // die(print_r($data));

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
        $this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
    }

    public function PurchaseOrderAccept($purchase_order_id)
    {

        $this->load->model('VendorsModel', 'VendorsModel');

        $purchase_order_id = base64_decode($purchase_order_id);
        // die(print_r($purchase_order_id));
        $whereArr = array(
            'purchase_order_tbl.purchase_order_id' => $purchase_order_id,
        );

        $purchase_order_details = $this->PurchasesModel->getOnePurchase($whereArr);
        // die(print_r($purchase_order_details));
        $company_id = $purchase_order_details->company_id;

        if ($purchase_order_details && $purchase_order_details->purchase_order_status != 1) {

            $param = array(
                'purchase_sent_status' => 2,
                'purchase_order_status' => 1,
                'updated_at' => date("Y-m-d H:i:s"),
                'open_date' => date("Y-m-d H:i:s")
            );

            $result = $this->PurchasesModel->updatePurchaseOrder($whereArr, $param);
            // die(print_r($result));
            if ($result) {

                $data = array('status' => 200, 'subject' => 'Thank You', 'description' => 'Purchase Order accepted successfully');

                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $purchase_order_details->company_id));

                $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $purchase_order_details->company_id, 'status' => 1));

                $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $purchase_order_details->company_id, 'status' => 1));

                $data['purchase_order_details'] = $purchase_order_details;

                // New purchase order accept message code here

                $vendor_id = $this->VendorsModel->getOneVendor($purchase_order_details->vendor_id);

                $emaildata['vendorData'] = $this->VendorsModel->getOneVendor($purchase_order_details->vendor_id);

                $emaildata['purchase_data_details'] = $this->PurchasesModel->getOnePurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id, 'purchase_order_status' => 1, 'purchase_order_tbl.vendor_id' => $vendor_id->vendor_id));

                $where = array('company_id' => $company_id);
                $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                #send email to company admin
                $emaildata['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));

                $emaildata['company_email_details'] = $this->CompanyModel->getOneCompanyEmail($where);

                $emaildata['accepted_date'] = date("Y-m-d H:i:s");
                // die(print_r($emaildata));

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }

                $body = $this->load->view('purchase_order_accepted_mail', $emaildata, true);
                $emaildata['is_admin_email'] = 1;
                $emaildata['purchase_order_id'] = $purchase_order_id;
                $emaildata['location_name'] = $purchase_order_details->location_name;
                // die(print_r($emaildata));

                $adminBody = $this->load->view('purchase_order_accepted_mail', $emaildata, true);

                if ($emaildata['company_email_details']->estimate_accepted_status == 1) {
                    // $res = Send_Mail_dynamic($company_email_details, $emaildata['vendorData']->vendor_email_address, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $body, 'Purchase Order Accepted', $emaildata['customerData']->secondary_email);
                    $res = Send_Mail_dynamic($company_email_details, $emaildata['vendorData']->vendor_email_address, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $body, 'Purchase Order Accepted');
                    #admin email
                    $res2 = Send_Mail_dynamic($company_email_details, $emaildata['user_details']->email, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $adminBody, 'Purchase Order Accepted');
                }

                ##### Text Message functionality #####
                // if ($data['setting_details']->is_text_message && $emaildata['company_email_details']->estimate_accepted_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {
                //     // $email_details['job_details']->is_mobile_text
                //     //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->estimate_accepted_text);

                //     $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->estimate_accepted_text, 'Purchase Order Accepted');
                //     #admin text
                //     $text_res2 = Send_Text_dynamic($emaildata['user_details']->phone, 'An Purchase Order has been accepted! Purchase Order #: ' . $purchase_order_id . '. Location: ' . $purchase_order_details->location_name . ', ' . $emaildata['purchase_data_details']->vendor_street_address . '.', 'Purchase Order Accepted');
                // }
                #####

                // end New purchase order accept message code here

            } else {
                $data = array('status' => 400, 'subject' => 'Thank You', 'description' => 'Something went wrong');
            }

            $where_company = array('company_id' => $purchase_order_details->company_id);

            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        } else if ($purchase_order_details && $purchase_order_details->purchase_order_status == 1) {
            $data = array('status' => 400, 'subject' => 'No Further Action Required', 'description' => 'This purchase order was already accepted');

            $data['setting_details'] = false;
        } else {

            $data = array('status' => 400, 'subject' => 'Thank You', 'description' => 'Purchase Order not found');

            $data['setting_details'] = false;
        }
        $this->load->view('purchase_order_message', $data);
    }

    public function set_signwell_estimate_accepted($estimate_id)
    {
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Job_model', 'JobModel');
        $where = array('estimate_id' => $estimate_id);
        $param = array('status' => 2, 'estimate_update' => date("Y-m-d H:i:s"));
        $result = $this->EstimateModal->updateEstimate($where, $param);
        $estimate_details = $this->EstimateModal->getOneEstimate($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $_SESSION["company_id"]));
        //        die($data['setting_details']->slug);
        if ($result) {
            ##### ADDED 3/1/22 #####
            $property_status = $this->PropertyModel->updateAdminTbl($estimate_details->property_id, array('property_status' => '1'));
            // die(print_r($this->db->last_query()));
            ####
            // if one time program invoiceing
            if ($estimate_details->program_pricing == "1" || $estimate_details->program_pricing == 1 || $estimate_details->program_price == "1" || $estimate_details->program_price == 1) {
    
                $user_id = $estimate_details->user_id;
                $company_id = $estimate_details->company_id;
                $customer_id = $estimate_details->customer_id;
                $property_id = $estimate_details->property_id;
                //$program_id = $estimate_details->program_id;
                $estimate_id = $estimate_id;
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
                $date = date('Y-m-d', time());
                $date_time = date('Y-m-d H:m:s', time());

                $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                $property_details = $this->PropertyModel->getOneProperty(array('property_id' => $property_id));

                // get estimate total cost
                $total_estimate_cost = 0;
                $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
                // die(print_r($estimate_price_overide_data));
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
    
                        $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $es_job->program_id));
    
                        if (isset($priceOverrideData->is_price_override_set) && $priceOverrideData->is_price_override_set == 1) {
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

                    $coup_job_param = array(
                        'cost' => $job_cost,
                        'job_id' => $es_job->job_id,
                        'customer_id' => $customer_id,
                        'property_id' => $property_id,
                        'program_id' => $es_job->program_id
                    );

                    $job_cost_w_coupon = $this->calculateServiceCouponCost($coup_job_param);

                    @$total_estimate_cost += $job_cost_w_coupon;

                    if(array_key_exists($es_job->program_id, $invoice_total_per_program)) {
                        $invoice_total_per_program[$es_job->program_id] += $job_cost;
                    } else {
                        $total_for_services += $job_cost;
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
        header('Location: ' . base_url('welcome/' . $data['setting_details']->slug . "?estimate_accepted=true"));
    }

    public function set_signwell_estimate_rejected($estimate_id)
    {
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $_SESSION["company_id"]));
        $where = array('estimate_id' => $estimate_id);
        $param = array('status' => 5, 'estimate_update' => date("Y-m-d H:i:s"));
        $result = $this->EstimateModal->updateEstimate($where, $param);
        header('Location: ' . base_url('welcome/' . $data['setting_details']->slug . "?estimate_rejected=true"));
    }

    public function sendMonthlyInvoice()
    {
        $file = fopen("MonthlyStatementResult.txt", "w");
        fwrite($file, 'Init. Sending Monthly Statement Process. Date: ' . date("Y/m/d"));
        fclose($file);
        $actual_link = "$_SERVER[REQUEST_URI]";
        $actual_link = explode('/', $actual_link);
        $email = '';


        if (isset($actual_link[3])) {
            $email = $actual_link[3];
        } /*else {
         die('Add a valid email after the .../welcome/sendMonthlyInvoice/ on Url. example: http://'.$_SERVER['HTTP_HOST'].'/welcome/sendMonthlyInvoice/email@spraye.io');
         }*/

        $all_companies = $this->CompanyModel->getCompanyListSubscribedMonthlyStatement();
        // die(print_r($all_companies));
        $all_customers = [];
        foreach ($all_companies as $key => $value){
         //   fwrite($file, 'Company: '.$value->company_id. '\n');
            //$invoiceAgeReport = $this->invoiceAgeReport($value->company_id);
            $aux = $this->ReportsModel->ajaxDataForInvoiceAgeReport($value->company_id);
            if (!empty($aux))
                foreach ($aux as $value) {
                    array_push($all_customers, $value);
                }

        }

        //die(print_r($all_customers));
        //die('<br>exit');
        //$id_customer = '40100'; // for testing
        //$email = 'alvaro.mho2@gmail.com'; // for testing
        foreach ( /*array_slice($all_customers,0,4)*/$all_customers as $key => $value) {
            //fwrite($file, 'Customer_id: '.$value. '\n');
            //echo print_r($value);
            $resp = $this->INV->sendMonthlyInvoice($value, $email);
            //echo $resp;
            $this->load->clear_vars();
           // echo '<br>';


        }
        $file = fopen("MonthlyStatementResult.txt", "a");
        fwrite($file, 'Init. Sending Monthly Statement Process. Date: ' . date("Y/m/d"));
        fwrite($file, 'Process ended');
        die('end');


    }

    public function ajaxGetActive()
    {
        $company_id = '44';

        $tblColumns = array(
            0 => 'checkbox',
            1 => 'invoice_id',
            2 => 'customer_id',
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
            14 => 'actions',

        );


        // WHERE:
        $whereArr = array(
            'invoice_tbl.company_id' => $company_id,
            'is_archived' => 0,
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
        $data = array();

        $invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, $whereArrExclude, $whereArrExclude2, $orWhere);
        //die(print_r($invoices));


        if (!empty($invoices)) {

            //die(print_r($invoices));

            foreach ($invoices as $invoice) {


                $nestedData['customer_id'] = $invoice->customer_id;
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
                $nestedData['balance_due'] = $due;
                $nestedData['past_payments'] = 0 ? '$ 0.00' : '$ ' . number_format($paid_already, 2);

                $nestedData['checkbox'] = '<input  name="group_id" type="checkbox"  value="' . $invoice->invoice_id . ':' . $invoice->customer_id . '" invoice_id="' . $invoice->invoice_id . '" balance_due="' . $due . '" past_payments="' . $paid_already . '" class="myCheckBox" />';




                $status = "";
                $sent_date = "";
                $open_date = "";


                $nestedData['status'] = $invoice->status;
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
                $nestedData['payment_status'] = $invoice->payment_status;

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

                $data[] = $nestedData;
            }
        }


        $final_data = array();

        //echo print_r($data);
        foreach ($data as $value) {
            if ($value['status'] != 0 && $value['balance_due'] != 0) {
                //echo $value->banlance_due;
                $final_data[] = $value;
            }
        }
        return $final_data;
    }


    public function invoiceAgeReport($company_id)
    {
        // $this->load->model('AdminTbl_company_model', 'CompanyModel');

        //$company_id = $this->session->userdata['company_id'];
        #populate filter dropdowns
        $data['customers'] = $this->Customer->getCustomerList(array('company_id' => $company_id));
        $data['service_areas'] = $this->ServiceArea->getAllServiceArea(array('company_id' => $company_id));
        $data['tax_details'] = $this->SalesTax->getAllSalesTaxArea(array('company_id' => $company_id));
        #get report data
        $report_data = array();
        echo 'Company_id: ' . $company_id . '<br>';
        #get customer invoices
        $customer_invoices = array();
        $current = [];
        $aged30 = [];
        $aged60 = [];
        $aged90 = [];
        if (isset($data['customers']) && !empty($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                //echo "CUSTOMER: ".$customer->customer_id."<br>";
                $customer_invoices[$customer->customer_id] = array();

                $whereArr = array(
                    'customer_id' => $customer->customer_id,
                    'status !=' => 0,
                    //where status != unsent
                    'payment_status !=' => 2,
                    //where payment_status != paid
                    'is_archived' => 0, //where not archived
                );
                $invoices = $this->INV->getAllInvoicesReport($whereArr);
                die(print_r($invoices));
                $current_amount_due = 0;
                $aged30_amount_due = 0;
                $aged60_amount_due = 0;
                $aged90_amount_due = 0;

                foreach ($invoices as $invoice) {
                    #Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
                    #check for coupons at customer, property, job level

                    $job_cost_total = 0;
                    $invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id' => $invoice->invoice_id));

                    if (!empty($invoice_jobs)) {
                        foreach ($invoice_jobs as $job) {
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
                        $invoice_total_cost = $invoice->cost;
                    }
                    #check for coupons at invoice level
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


                    $amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

                    /*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
                    'cost'=>$invoice->cost,
                    'cost_after_coupons'=>$invoice_total_cost,
                    'tax_amount'=>$invoice->tax_amount,
                    'partial_payment'=>$invoice->partial_payment,
                    'total_amount_due'=>$amount_due,
                    ); */
                    #get age of invoice
                    $now = new DateTime('now');
                    $invoice_date = new DateTime($invoice->invoice_date);
                    $aged = $invoice_date->diff($now);
                    $aged = $aged->format('%r%a');

                    if ($aged <= 30) {
                        $current[] = $invoice->invoice_id;
                        $current_amount_due += $amount_due;
                    } elseif ($aged > 30 && $aged <= 60) {
                        $aged30[] = $invoice->invoice_id;
                        $aged30_amount_due += $amount_due;
                    } elseif ($aged > 60 && $aged <= 90) {
                        $aged60[] = $invoice->invoice_id;
                        $aged60_amount_due += $amount_due;
                    } elseif ($aged > 90) {
                        $aged90[] = $invoice->invoice_id;
                        $aged90_amount_due += $amount_due;
                    }

                    //echo "Invoice ID: ".$invoice->invoice_id." - Today: ".date('Y-m-d')." - Invoice Date: ".$invoice->invoice_date." - Aged: ".$aged."<br>";
                }

                $customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
                $customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
                $customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
                $customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
                $customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
                $customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
                $customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

                $customer_total_due = $current_amount_due + $aged30_amount_due + $aged60_amount_due + $aged90_amount_due;
                $customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

                //echo "TOTAL 0-30 Days: ".count($current)."<br>";
                //echo "TOTAL 31-60 Days: ".count($aged30)."<br>";
                //echo "TOTAL 61-90 Days: ".count($aged60)."<br>";
                //echo "TOTAL 90+ Days: ".count($aged90)."<br><br>";

                #remove customers with $0 balance
                if ($customer_total_due > 0) {
                    $report_data[] = $customer_invoices[$customer->customer_id];
                }
                if (is_array($current) && count($current) > 0) {
                    $data['current_invoices'] = implode(',', $current);
                }
                if (is_array($aged30) && count($aged30) > 0) {
                    $data['aged30_invoices'] = implode(',', $aged30);
                }
                if (is_array($aged60) && count($aged60) > 0) {
                    $data['aged60_invoices'] = implode(',', $aged60);
                }
                if (is_array($aged90) && count($aged90) > 0) {
                    $data['aged90_invoices'] = implode(',', $aged90);
                }
            }
        }
        return $data;
    }


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


    public function isActive($invoice_id)
    {

        // WHERE:
        $whereArr = array(
            //'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'invoice_tbl.invoice_id' => $invoice_id,
            'is_archived' => 0,
            'payment_status !=' => 2
            //'status !=' =>0
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



        $invoices = $this->INV->ajaxActiveInvoiceTech($whereArr, $whereArrExclude, $whereArrExclude2, $orWhere);
        //        die($this->db->last_query());
        // die(print_r($invoices));

        if (count($invoices) > 0) {
            return 1;
        } else {
            return 0;
        }


    }
}