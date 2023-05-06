<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';


class Setting extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->session->userdata('email')) {

            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }

        $this->load->library('parser');

        $this->load->library('aws_sdk');

        $this->load->helper('text');

        $this->load->helper('invoice_helper');

        $this->load->helper('cardconnect_helper');

        $this->loadModel();
    }



    /**

     * Index Page for this controller.

     *

     * Maps to the following URL

     * 		http://example.com/index.php/welcome

     * 	- or -

     * 		http://example.com/index.php/welcome/index

     * 	- or -

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

        $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');

        $this->load->model('AdminTbl_coupon_model', 'CouponModel');

        $this->load->model('Company_email_model', 'CompanyEmail');

        $this->load->model('Sales_tax_model', 'SalesTax');

        $this->load->model('Company_subscription_model', 'CompanySubscription');

        $this->load->model('Administratorsuper');

        $this->load->model('Basys_request_modal', 'BasysRequest');

        $this->load->model('Cardconnect_model', 'CardConnect');

        $this->load->model('Estimate_model', 'EstimateModal');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');
        $this->load->model('Source_model', 'SourceModel');
        $this->load->model('Service_type_model', 'ServiceTypeModel');
        $this->load->model('Commissions_model', 'CommissionModel');
        $this->load->model('Bonuses_model', 'BonusModel');
		$this->load->model('AdminTbl_tags_model', 'TagsModel');
        $this->load->model('Sales_tax_model', 'SalesTax');
    }

    public function index()
    {

		log_message('info', '/*****************************************************************/');
		log_message('info', 'settings index');

        $where = array('company_id' =>$this->session->userdata['company_id']);
        $data['customers'] = $this->CustomerModel->getCustomerEmails();
        $data['vendors'] = $this->CustomerModel->getVendorEmails();

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo) ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
        $data['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
        $data['subscription_details'] = $this->CompanySubscription->getOneCompanySubscription($where);
        $data['basys_details'] =  $this->BasysRequest->getOneBasysRequest($where);
        $data['cardconnect_details'] =  $this->CardConnect->getOneCardConnect($where);
		$data['customers_details'] =  $this->CustomerModel->get_all_customer($where);
        $data['all_commission'] = $this->CommissionModel->getAllCommission($where);
        $data['all_bonuses'] = $this->BonusModel->getAllBonus($where);
        $data['service_types'] = $this->ServiceTypeModel->getAllServiceType($where);
		$data['property_conditions'] =  $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $messageData = array(

            "job_sheduled" => "<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p><br></p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE}</p><p><br></p><p>Thanks,<br></p><p><br> </p>",

            "job_sheduled_text" => "Your lawn is scheduled to be serviced on {mm/dd/yyyy}.",

            "job_sheduled_skipped" => "<p>Due to unforeseen circumstances, we have had to cancel your service for today. We will notify you when we reschedule the service.</p>",

            "job_sheduled_skipped_text" => "Due to unforeseen circumstances, we have had to cancel your service for today. We will notify you when we reschedule the service.",

            "one_day_prior" => "<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p><br></p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE}</p><p><br></p><p>Thanks,<br></p><p><br> </p>",

            "one_day_prior_text" => "Reminder: Your lawn is scheduled to be serviced tomorrow.",

            "job_completion" => "<p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE} {TECHNICIAN_MESSAGE}<br></p><p>{ADDITIONAL_INFO}<br></p><p>has been completed successfully.<br></p><p>Thanks,<br></p><p>     <br></p>",

            "job_completion_text" => "We have completed today's lawn service at your property.",

            "program_assigned" => "<p>The following lawn program has been assigned to your property:</p><br><p>{PROGRAM_NAME}</p>",

            "program_assigned_text" => "We have assigned a lawn care program to your property. We look forward to improving the health of your lawn.",

            "estimate_accepted" => "<p>Thank you for accepting the estimate for the following lawn program:</p><br><p>{PROGRAM_NAME}</p>",

            "estimate_accepted_text" => "Your estimate has been accepted. We look forward to improving the health of your lawn.",

            "purchase_order_accepted" => "<p>Thank you for accepting the estimate for the following lawn program:</p><br><p>{PROGRAM_NAME}</p>",

            "purchase_order_accepted_text" => "Your estimate has been accepted. We look forward to improving the health of your lawn.",
            
			"email_hold_templete" => "<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your  service details.</p>",
			"hold_notification" => "Account is in Hold.",
            "late_fee_email" => "<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>This email is to notify that a late fee has been applied to the invoice: {INVOICE_ID}.</p><p><br></p><p>{INVOICE_PDF_LINK} {INVOICE_PAY_LINK}</p><p><br></p><p>Thanks,<br></p><p><br> </p>",

        );



        $emailUpdates = array();
        if (strlen($data['company_email_details']->job_sheduled) < 5) {



            $emailUpdates['job_sheduled'] = $messageData['job_sheduled'];
        }

        if (strlen($data['company_email_details']->job_sheduled_text) < 5) {



            $emailUpdates['job_sheduled_text'] = $messageData['job_sheduled_text'];
        }

        if (strlen($data['company_email_details']->job_sheduled_skipped) < 5) {

            $emailUpdates['job_sheduled_skipped'] = $messageData['job_sheduled_skipped'];
        }

        if (strlen($data['company_email_details']->job_sheduled_skipped_text) < 5) {

            $emailUpdates['job_sheduled_skipped_text'] = $messageData['job_sheduled_skipped_text'];
        }

        if (strlen($data['company_email_details']->one_day_prior) < 5) {

            $emailUpdates['one_day_prior'] = $messageData['one_day_prior'];
        }

        if (strlen($data['company_email_details']->one_day_prior_text) < 5) {

            $emailUpdates['one_day_prior_text'] = $messageData['one_day_prior_text'];
        }

        if (strlen($data['company_email_details']->job_completion) < 5) {

            $emailUpdates['job_completion'] = $messageData['job_completion'];
        }

        if (strlen($data['company_email_details']->job_completion_text) < 5) {

            $emailUpdates['job_completion_text'] = $messageData['job_completion_text'];
        }

        if (strlen($data['company_email_details']->program_assigned) < 5) {

            $emailUpdates['program_assigned'] = $messageData['program_assigned'];
        }

        if (strlen($data['company_email_details']->program_assigned_text) < 5) {

            $emailUpdates['program_assigned_text'] = $messageData['program_assigned_text'];
        } elseif (strlen($data['company_email_details']->estimate_accepted) < 5) {

            $emailUpdates['estimate_accepted'] = $messageData['estimate_accepted'];
        }

        if (strlen($data['company_email_details']->estimate_accepted_text) < 5) {

            $emailUpdates['estimate_accepted_text'] = $messageData['estimate_accepted_text'];
        }
		if (strlen($data['company_email_details']->email_hold_templete) < 5) {
        	$emailUpdates['email_hold_templete'] = $messageData['email_hold_templete'];
		}
		if (strlen($data['company_email_details']->hold_notification) < 5) {
			$emailUpdates['hold_notification'] = $messageData['hold_notification'];
		}
        if (strlen($data['company_email_details']->late_fee_email) < 5) {

            $emailUpdates['late_fee_email'] = $messageData['late_fee_email'];
        }
        if (strlen($data['company_email_details']->purchase_order_accepted) < 5) {

            $emailUpdates['purchase_order_accepted'] = $messageData['purchase_order_accepted'];
        }

        if (strlen($data['company_email_details']->purchase_order_accepted_text) < 5) {

            $emailUpdates['purchase_order_accepted_text'] = $messageData['purchase_order_accepted_text'];
        }
        if (!empty($emailUpdates)) {

            $where = array('company_id' => $this->session->userdata['company_id']);
            $result = $this->CompanyEmail->updateCompanyEmail($where, $emailUpdates);

            log_message('info', '/*****************************************************************/');

            ob_start();

            var_dump(print_r($this->db->last_query()));

            $output_resulter = ob_get_clean();

            log_message('info', $output_resulter);

            log_message('info', '/*****************************************************************/');
        }

        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);
        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasons($this->session->userdata['company_id']);
        $data['reschedule_reasons'] = $this->CustomerModel->getRescheduleReasonsList($this->session->userdata['company_id']);
        // $data['note_types'] = $this->CompanyModel->getNoteTypes(0);
        // die(print_r($data['note_types']));
		$data['company_id'] = $this->session->userdata['company_id'];
        $where = array('user_id' =>$this->session->userdata['user_id']);
        $data['user_details'] =  $this->Administrator->getOneAdmin($where);
        $page["active_sidebar"] = "invoicenav";
        $page["page_name"] = 'Settings';
        $page["page_content"] = $this->load->view("admin/setting/edit_setting", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }



    /*

    public function index() {



      $where = array('company_id' =>$this->session->userdata['company_id']);



         $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

         $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo) ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;

         $data['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

         $data['subscription_details'] = $this->CompanySubscription->getOneCompanySubscription($where);

         $data['basys_details'] =  $this->BasysRequest->getOneBasysRequest($where);

 



         $where = array('user_id' =>$this->session->userdata['user_id']);

         $data['user_details'] =  $this->Administrator->getOneAdmin($where);



         $page["active_sidebar"] = "invoicenav";

	    	 $page["page_name"] = 'Settings';

         $page["page_content"] = $this->load->view("admin/setting/edit_setting", $data, TRUE);

         

         $this->layout->superAdminTemplateTable($page);

    }

*/



    public function updateCompanyDetailsData()
    {

        $data = $this->input->post();
        // die(print_r($data));
        $this->form_validation->set_rules('company_name', 'company_name', 'required');
        $this->form_validation->set_rules('company_address', 'company_address', 'required');
        $this->form_validation->set_rules('company_phone_number', 'phone', 'trim|required');
        $this->form_validation->set_rules('company_email', 'company_email', 'required');
        $this->form_validation->set_rules('web_address', 'web_address', 'trim');
        $this->form_validation->set_rules('invoice_color', 'invoice_color', '');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            $this->index();
        } elseif ($check = $this->CompanyModel->getOneCompany(array('company_id !=' => $this->session->userdata['company_id'], 'company_email' => $data['company_email']))) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email </strong> already exists </div>');
            redirect("admin/setting");
        } else {
            $company_geo = $this->getLatLongByAddress($data['company_address']);
            if (!$company_geo) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> company address. Please try again</div>');

                redirect("admin/setting");
            } else {

                #### CODE TO ADD SLUG BY UPDATING COMPANY DETAILS ####
                $city = $data['company_address'];
                $dup_company_check = [];
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                //  $dup_slug_check = $this->CompanyModel->getAllCompanySlugDuplicates(array('slug' => $slug));
                $dup_company_check = $this->CompanyModel->getAllCompanySlugDuplicates(array('company_name' =>$data['company_name']));
                //  die(print_r($this->db->last_query()));
                //  die(print_r($dup_slug_check));
           
                // die(print_r($dup_company_check));
 
                if(count($dup_company_check)>1){
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.'-'.$city)));
                    // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.$city[1])));
                    // die(print_r($slug));
                    $param = array(
                        'company_name' => $data['company_name'],
                        'company_address' => $data['company_address'],
                        'company_address_lat' => $company_geo['lat'],
                        'company_address_long' => $company_geo['long'],
                        'company_phone_number' => $data['company_phone_number'],
                        'company_email' => $data['company_email'],
                        'web_address' => $data['web_address'],
                        'invoice_color' => $data['invoice_color'],
                        'default_display_length' => $data['default_display_length'],
                        'time_zone' => $data['time_zone'],
                        'ready_for_payment_po_email' => $data['ready_for_payment_po_email'],
                        'updated_at' => date("Y-m-d H:i:s"),
                        'slug' => $slug,   
                    );
                    //    die(print_r($param));
                } else {
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                    //  die(print_r($slug));
                    $param = array(
                        'company_name' => $data['company_name'],
                        'company_address' => $data['company_address'],
                        'company_address_lat' => $company_geo['lat'],
                        'company_address_long' => $company_geo['long'],
                        'company_phone_number' => $data['company_phone_number'],
                        'company_email' => $data['company_email'],
                        'web_address' => $data['web_address'],
                        'invoice_color' => $data['invoice_color'],
                        'default_display_length' => $data['default_display_length'],
                        'time_zone' => $data['time_zone'],
                        'updated_at' => date("Y-m-d H:i:s"),
                        'ready_for_payment_po_email' => $data['ready_for_payment_po_email'],
                        'slug' => $slug,   
                    );
                    // die(print_r($param));
                    // die('nonDuplicate');
                }
                #### END OF SLUG UPDATE ####

                // $param = array(

                //     'company_name' => $data['company_name'],
                //     'company_address' => $data['company_address'],
                //     'company_address_lat' => $company_geo['lat'],
                //     'company_address_long' => $company_geo['long'],
                //     'company_phone_number' => $data['company_phone_number'],
                //     'company_email' => $data['company_email'],
                //     'web_address' => $data['web_address'],
                //     'invoice_color' => $data['invoice_color'],
                //     'default_display_length' => $data['default_display_length'],
                //     'time_zone' => $data['time_zone'],
                //     'updated_at' => date("Y-m-d H:i:s")

                // );

                if (!empty($_FILES['company_logo']['name'])) {

                    $file_name_array  = explode(".", $_FILES['company_logo']['name']);

                    $fileext =  end($file_name_array);

                    $tmp_name   = $_FILES['company_logo']['tmp_name'];

                    $file_name  = $this->session->userdata['company_id'] . '_' . date("ymdhis") . '.' . $fileext;

                    $resized_file_name  = $this->session->userdata['company_id'] . '_' . date("ymdhis") . '_resized.' . $fileext;

                    $key = '/uploads/company_logo/' . $file_name;

                    $res = $this->aws_sdk->saveObject($key, $tmp_name);

                    //$res =   move_uploaded_file($tmp_name,"uploads/company_logo/".$file_name);

                    $param['company_logo'] = $file_name;

                    /* $resized_image = str_replace('data:image/png;base64,','',$data['resized_image']);

                    $resized_image = str_replace(' ','+',$resized_image);

                    $resized_image = base64_decode($resized_image);

                    file_put_contents("uploads/company_logo/".$resized_file_name,$resized_image);

                    $resized_image_file = 'uploads/company_logo/'.$resized_file_name;

                    $resized_file_key = '/uploads/company_logo/'.$resized_file_name;

                    $this->aws_sdk->saveObject($resized_file_key,$resized_image_file);

                    unlink('uploads/company_logo/'.$resized_file_name);

                    $param['company_resized_logo'] = $resized_file_name;    */

                    $param['company_resized_logo'] = $file_name;
                }

                $where = array('company_id' => $this->session->userdata['company_id']);





                // var_dump($param);

                // die(); 

                $result = $this->CompanyModel->updateCompany($where, $param);



                // var_dump($result);die();

                if ($result) {
                    $compny_details = $this->CompanyModel->getOneCompany($where);
                    $this->session->set_userdata('compny_details', $compny_details);
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company details </strong>updated successfully</div>');

                    redirect("admin/setting");

                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong> not updated. Please try again</div>');

                    redirect("admin/setting");
                }
            }

            // $geo = $this->getLatLongByAddress($data['start_location']);

            // $geo2 = $this->getLatLongByAddress($data['end_location']);

        }
    }

    public function updateDefaultSalesTaxArea()
    {

        $data = $this->input->post();



        $param = array(
            'default_sales_tax_area' => json_encode($data['sale_tax_area_id']),
        );
        //die(print_r($param));

        $where = array('company_id' => $this->session->userdata['company_id']);


        $result = $this->CompanyModel->updateCompany($where, $param);



        if ($result) {
            $compny_details = $this->CompanyModel->getOneCompany($where);
            $this->session->set_userdata('compny_details', $compny_details);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company details </strong>updated successfully</div>');

            redirect("admin/setting");

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong> not updated. Please try again</div>');

            redirect("admin/setting");
        }
    }

    public function SavePOEmails(){
        $data = $this->input->post();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $SettingData = $this->CompanyModel->getOneCompany($where);
        $POEmails = explode(",", $SettingData->ready_for_payment_po_email);
        $POEmails[] = $data["email"];

        $Emails = array_unique($POEmails);
        $param = array("ready_for_payment_po_email" => implode(",", $Emails));
        $result = $this->CompanyModel->updateCompany($where, $param);

        $SettingData = $this->CompanyModel->getOneCompany($where);
        $POEmails = explode(",", $SettingData->ready_for_payment_po_email);
        echo json_encode($POEmails);
    }

    public function RemovePOEmails(){
        $data = $this->input->post();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $SettingData = $this->CompanyModel->getOneCompany($where);
        $POEmails = explode(",", $SettingData->ready_for_payment_po_email);
        $POEmails = array_map('trim', $POEmails);
        unset($POEmails[$data["email"]]);

        $Emails = array_unique($POEmails);
        $param = array("ready_for_payment_po_email" => implode(",", $Emails));
        $result = $this->CompanyModel->updateCompany($where, $param);

        $SettingData = $this->CompanyModel->getOneCompany($where);
        $POEmails = explode(",", $SettingData->ready_for_payment_po_email);
        echo json_encode($POEmails);
    }

    public function updateSettingData()
    {

        $data = $this->input->post();


        $this->form_validation->set_rules('start_location', 'start_location', 'required');

        $this->form_validation->set_rules('end_location', 'end_location', 'required');

        if ($this->form_validation->run() == FALSE) {

            echo validation_errors();

            $this->index();
        } else {

            $geo = $this->getLatLongByAddress($data['start_location']);

            $geo2 = $this->getLatLongByAddress($data['end_location']);

            if (!$geo) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid </strong> start location . Please try again</div>');

                redirect("admin/setting");
            } else if (!$geo2) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid </strong> end location. Please try again</div>');

                redirect("admin/setting");
            } else {

                $param = array(

                    'start_location' => $data['start_location'],

                    'start_location_lat' => $geo['lat'],

                    'start_location_long' => $geo['long'],

                    'end_location' => $data['end_location'],

                    'end_location_lat' => $geo2['lat'],

                    'end_location_long' => $geo2['long'],

                    'updated_at' => date("Y-m-d H:i:s")

                );


                $where = array('company_id' => $this->session->userdata['company_id']);

                $result = $this->CompanyModel->updateCompany($where, $param);

                if ($result) {

                    $compny_details = $this->CompanyModel->getOneCompany($where);
                    $this->session->set_userdata('compny_details', $compny_details);

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong>updated successfully</div>');

                    redirect("admin/setting");
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong> not updated. Please try again</div>');

                    redirect("admin/setting");
                }
            }
        }
    }

    public function requsetForBasys($value = '')
    {
        $data['request_data'] = $this->input->post();

        $where = array('company_id' => $this->session->userdata['company_id']);

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

        $body = $this->load->view('admin/email/request_basys_account', (array) $data, TRUE);

        $where['is_smtp'] = 1;

        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

        if (!$company_email_details) {

            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        }

        $res =   Send_Mail_dynamic($company_email_details, EMAIL_ADDRESS, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Requset For Basys Account');

        if ($res['status']) {
            $where =  array(

                'company_id' => $this->session->userdata['company_id'],

            );
            $param =  array(

                'company_id' => $this->session->userdata['company_id'],

                'requested_date' => date("Y-m-d H:i:s"),

                'requested_update' => date("Y-m-d H:i:s"),

            );

            $already_check = $this->BasysRequest->getOneBasysRequest($where);

            if ($already_check) {



                $this->BasysRequest->updateBasysRequest($where, $param);
            } else {



                $this->BasysRequest->CreateOneBasysRequest($param);
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>BASYS </strong> requset sent successfully</div>');

            redirect("admin/setting");
        } else {



            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><' . $res['message'] . '</div>');

            redirect("admin/setting");
        }
    }

    public function quickBookStatus()
    {



        $quickbook_status = $this->input->post('quickbook_status');

        $param = array('quickbook_status' => $quickbook_status);

        $where = array('company_id' => $this->session->userdata['company_id']);



        $result =   $this->CompanyModel->updateCompany($where, $param);



        if ($result) {

            echo 1;
        } else {

            echo 0;
        }
    }

    public function updateEmailAutomated()
    {



        log_message('info', '/*****************************************************************/');



        log_message('info', 'updateEmailAutomated');



        $data = $this->input->post();

        $this->form_validation->set_rules('job_sheduled', 'job_sheduled', 'required');

        $this->form_validation->set_rules('one_day_prior', 'one_day_prior', 'required');

        $this->form_validation->set_rules('job_completion', 'job_completion', 'required');

        $this->form_validation->set_rules('job_sheduled_skipped', 'job_sheduled_skipped', 'required');

        $this->form_validation->set_rules('program_assigned', 'program_assigned', 'required');

        $this->form_validation->set_rules('estimate_accepted', 'estimate_accepted', 'required');

        $this->form_validation->set_rules('purchase_order_accepted', 'purchase_order_accepted', 'required');



        $text_message_field = $this->session->userdata['is_text_message'] == 1 ? 1 : 0;



        if ($text_message_field) {

            $this->form_validation->set_rules('job_sheduled_text', 'job_sheduled_text', 'required|max_length[160]');

            $this->form_validation->set_rules('one_day_prior_text', 'one_day_prior_text', 'required|max_length[160]');

            $this->form_validation->set_rules('job_completion_text', 'job_completion_text', 'required|max_length[160]');

            $this->form_validation->set_rules('job_sheduled_skipped_text', 'job_sheduled_skipped_text', 'required|max_length[160]');

            $this->form_validation->set_rules('program_assigned_text', 'program_assigned_text', 'required|max_length[160]');

            $this->form_validation->set_rules('estimate_accepted_text', 'estimate_accepted_text', 'required|max_length[160]');

            $this->form_validation->set_rules('purchase_order_accepted_text', 'purchase_order_accepted_text', 'required|max_length[160]');
        }





        if ($this->form_validation->run() == FALSE) {



            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . validation_errors() . '</div>');

            $this->index();
        } else {



            $param = array(

                'job_sheduled' => $data['job_sheduled'],

                'job_sheduled_text' => isset($data['job_sheduled_text']) ? $data['job_sheduled_text'] : null,

                'one_day_prior' => $data['one_day_prior'],

                'one_day_prior_text' => isset($data['one_day_prior_text']) ? $data['one_day_prior_text'] : null,

                'job_completion' => $data['job_completion'],

                'job_completion_text' => isset($data['job_completion_text']) ? $data['job_completion_text'] : null,

                'job_sheduled_skipped' => $data['job_sheduled_skipped'],

                'job_sheduled_skipped_text' => isset($data['job_sheduled_skipped_text']) ? $data['job_sheduled_skipped_text'] : null,

                'program_assigned' => $data['program_assigned'],

                'program_assigned_text' => isset($data['program_assigned_text']) ? $data['program_assigned_text'] : null,

                'estimate_accepted' => $data['estimate_accepted'],

                'estimate_accepted_text' => isset($data['estimate_accepted_text']) ? $data['estimate_accepted_text'] : null,
				'email_scheduling_indays' => $data['email_scheduling_indays'],
				'email_hold_templete' => isset($data['email_hold_templete']) ? $data['email_hold_templete'] : null,
				'hold_notification' => isset($data['hold_notification']) ? $data['hold_notification'] : null,

                'purchase_order_accepted' => $data['purchase_order_accepted'],

                'purchase_order_accepted_text' => isset($data['purchase_order_accepted_text']) ? $data['purchase_order_accepted_text'] : null,

                'updated_at' => date("Y-m-d H:i:s")

            );





            if (array_key_exists('job_sheduled_status', $data)) {

                $param['job_sheduled_status'] = 1;
            } else {

                $param['job_sheduled_status'] = 0;
            }



            if (array_key_exists('job_sheduled_status_text', $data)) {

                $param['job_sheduled_status_text'] = 1;
            } else {

                $param['job_sheduled_status_text'] = 0;
            }



            if (array_key_exists('job_sheduled_skipped_status', $data)) {

                $param['job_sheduled_skipped_status'] = 1;
            } else {

                $param['job_sheduled_skipped_status'] = 0;
            }



            if (array_key_exists('job_sheduled_skipped_status_text', $data)) {

                $param['job_sheduled_skipped_status_text'] = 1;
            } else {

                $param['job_sheduled_skipped_status_text'] = 0;
            }



            if (array_key_exists('one_day_prior_status', $data)) {

                $param['one_day_prior_status'] = 1;
            } else {

                $param['one_day_prior_status'] = 0;
            }



            if (array_key_exists('one_day_prior_status_text', $data)) {

                $param['one_day_prior_status_text'] = 1;
            } else {

                $param['one_day_prior_status_text'] = 0;
            }



            if (array_key_exists('job_completion_status', $data)) {

                $param['job_completion_status'] = 1;
            } else {

                $param['job_completion_status'] = 0;
            }



            if (array_key_exists('job_completion_status_text', $data)) {

                $param['job_completion_status_text'] = 1;
            } else {

                $param['job_completion_status_text'] = 0;
            }



            if (array_key_exists('program_assigned_status', $data)) {

                $param['program_assigned_status'] = 1;
            } else {

                $param['program_assigned_status'] = 0;
            }



            if (array_key_exists('program_assigned_status_text', $data)) {

                $param['program_assigned_status_text'] = 1;
            } else {

                $param['program_assigned_status_text'] = 0;
            }



            if (array_key_exists('estimate_accepted_status', $data)) {

                $param['estimate_accepted_status'] = 1;
            } else {

                $param['estimate_accepted_status'] = 0;
            }



            if (array_key_exists('estimate_accepted_status_text', $data)) {

                $param['estimate_accepted_status_text'] = 1;
            } else {

                $param['estimate_accepted_status_text'] = 0;
            }

			if (array_key_exists('is_email_scheduling_indays', $data)) {
				$param['is_email_scheduling_indays'] = 1;
			} else {
				$param['is_email_scheduling_indays'] = 0;
			}
			if (array_key_exists('is_hold_notification', $data)) {
				$param['is_hold_notification'] = 1;
			} else {
				$param['is_hold_notification'] = 0;
			}
			if (array_key_exists('is_email_hold_templete', $data)) {
				$param['is_email_hold_templete'] = 1;
			} else {
				$param['is_email_hold_templete'] = 0;
			}

            if (array_key_exists('purchase_order_accepted_status', $data)) {

                $param['purchase_order_accepted_status'] = 1;
            } else {

                $param['purchase_order_accepted_status'] = 0;
            }



            if (array_key_exists('purchase_order_accepted_status_text', $data)) {

                $param['purchase_order_accepted_status_text'] = 1;
            } else {

                $param['purchase_order_accepted_status_text'] = 0;
            }



            if (array_key_exists('is_product_name', $data)) {

                $param['is_product_name'] = 1;
            } else {

                $param['is_product_name'] = 0;
            }



            if (array_key_exists('is_epa', $data)) {

                $param['is_epa'] = 1;
            } else {

                $param['is_epa'] = 0;
            }



            if (array_key_exists('is_active_ingredients', $data)) {

                $param['is_active_ingredients'] = 1;
            } else {

                $param['is_active_ingredients'] = 0;
            }



            if (array_key_exists('is_application_rate', $data)) {

                $param['is_application_rate'] = 1;
            } else {

                $param['is_application_rate'] = 0;
            }



            if (array_key_exists('is_estimated_chemical_used', $data)) {

                $param['is_estimated_chemical_used'] = 1;
            } else {

                $param['is_estimated_chemical_used'] = 0;
            }



            if (array_key_exists('is_chemical_type', $data)) {

                $param['is_chemical_type'] = 1;
            } else {

                $param['is_chemical_type'] = 0;
            }



            if (array_key_exists('is_re_entry_time', $data)) {

                $param['is_re_entry_time'] = 1;
            } else {

                $param['is_re_entry_time'] = 0;
            }



            if (array_key_exists('is_weed_pest_prevented', $data)) {

                $param['is_weed_pest_prevented'] = 1;
            } else {

                $param['is_weed_pest_prevented'] = 0;
            }



            if (array_key_exists('is_application_type', $data)) {

                $param['is_application_type'] = 1;
            } else {

                $param['is_application_type'] = 0;
            }



            if (array_key_exists('is_wind_speed', $data)) {

                $param['is_wind_speed'] = 1;
            } else {

                $param['is_wind_speed'] = 0;
            }



            if (array_key_exists('is_wind_direction', $data)) {

                $param['is_wind_direction'] = 1;
            } else {

                $param['is_wind_direction'] = 0;
            }



            if (array_key_exists('is_temperature', $data)) {

                $param['is_temperature'] = 1;
            } else {

                $param['is_temperature'] = 0;
            }



            if (array_key_exists('is_applicator_name', $data)) {

                $param['is_applicator_name'] = 1;
            } else {

                $param['is_applicator_name'] = 0;
            }



            if (array_key_exists('is_applicator_number', $data)) {

                $param['is_applicator_number'] = 1;
            } else {

                $param['is_applicator_number'] = 0;
            }



            if (array_key_exists('is_applicator_phone', $data)) {

                $param['is_applicator_phone'] = 1;
            } else {

                $param['is_applicator_phone'] = 0;
            }



            if (array_key_exists('is_property_address', $data)) {

                $param['is_property_address'] = 1;
            } else {

                $param['is_property_address'] = 0;
            }



            if (array_key_exists('is_property_size', $data)) {

                $param['is_property_size'] = 1;
            } else {

                $param['is_property_size'] = 0;
            }



            if (array_key_exists('is_date', $data)) {

                $param['is_date'] = 1;
            } else {

                $param['is_date'] = 0;
            }



            if (array_key_exists('is_time', $data)) {

                $param['is_time'] = 1;
            } else {

                $param['is_time'] = 0;
            }


            $where = array('company_id' => $this->session->userdata['company_id']);



            $result = $this->CompanyEmail->updateCompanyEmail($where, $param);



            if ($result) {



                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong>updated successfully</div>');

                redirect("admin/setting");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong> not updated. Please try again</div>');

                redirect("admin/setting");
            }
        }
    }

    public function updateSmtp()
    {

        $data = $this->input->post();

        $this->form_validation->set_rules('smtp_host', 'smtp_host', 'required');

        $this->form_validation->set_rules('smtp_port', 'smtp_port', 'required');

        $this->form_validation->set_rules('smtp_username', 'smtp_username', 'required');

        $this->form_validation->set_rules('smtp_password', 'smtp_password', 'required');



        if ($this->form_validation->run() == FALSE) {



            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . validation_errors() . '</div>');

            $this->index();
        } else {



            $param = array(

                'smtp_host' => $data['smtp_host_type'] . $data['smtp_host'],

                'smtp_port' => $data['smtp_port'],

                'smtp_username' => $data['smtp_username'],

                'smtp_password' => $data['smtp_password'],



            );





            $checksmtp =   Send_Mail_dynamic($param, $this->session->userdata['compny_details']->company_email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), 'This mail has been made for smtp credential check, please ignore it', 'Check SMTP credential');





            if ($checksmtp['status']) {

                $where = array('company_id' => $this->session->userdata['company_id']);

                $param['is_smtp'] = 1;

                $param['updated_at'] = date('Y-m-d H:i:s');



                $result = $this->CompanyEmail->updateCompanyEmail($where, $param);



                if ($result) {



                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SMTP </strong>details updated successfully</div>');

                    redirect("admin/setting");
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SMTP </strong>Details not updated. Please try again</div>');

                    redirect("admin/setting");
                }
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . $checksmtp['message'] . '<a href="http://support.spraye.io/support/solutions/articles/47001135041-smtp-settings-how-to-send-emails-from-your-company-domain" target="_blank" class="info"  >learn more</a>' . '</div>');

                redirect("admin/setting");
            }
        }
    }

    public function invoiceFees()
    {
        $data = $this->input->post();
        $data['late_fee_is_recurring'] = !isset($data['late_fee_is_recurring'])? 0 : 1;
        $data['late_fee_email_status'] = !isset($data['late_fee_email_status'])? 0 : 1;

        // print_r($data);
        $company_email_param = [];

        $company_update = $data;
        
        $email_data = ['late_fee_email_status' => $data['late_fee_email_status'], 'late_fee_email' => $data['late_fee_email']];

        unset(
            $company_update['late_fee_email_status'],
            $company_update['late_fee_email']
        );

        $result_email = $this->CompanyEmail->updateCompanyEmail(['company_id' => $this->session->userdata['company_id']], $email_data);

         $result = $this->CompanyModel->updateCompany(['company_id' => $this->session->userdata['company_id']], $company_update);

        if ($result or $result_email) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>Late Fee updated successfully</div>');
            redirect("admin/setting");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>Late Fee not updated. Please try again</div>');
            redirect("admin/setting");
        }
/*
        if (array_key_exists('send_daily_invoice_mail', $data)) {

            $company_email_param['send_daily_invoice_mail'] = 1;
        } else {

            $company_email_param['send_daily_invoice_mail'] = 0;
        }



        $param = array(

            'payment_terms' => $data['payment_terms'],

            'default_invoice_message' => $data['default_invoice_message'],

            'convenience_fee' => $data['convenience_fee'],

            'updated_at' => date('Y-m-d H:i:s'),

        );

        $where = array('company_id' => $this->session->userdata['company_id']);

        $this->CompanyEmail->updateCompanyEmail($where, $company_email_param);

        $result = $this->CompanyModel->updateCompany($where, $param);


        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>details updated successfully</div>');
            redirect("admin/setting");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>Details not updated. Please try again</div>');
            redirect("admin/setting");
        }*/
    }

    public function updateInvoiceDetails()
    {



        $data = $this->input->post();

        $company_email_param = [];

        if (array_key_exists('send_daily_invoice_mail', $data)) {

            $company_email_param['send_daily_invoice_mail'] = 1;
        } else {

            $company_email_param['send_daily_invoice_mail'] = 0;
        }



        $param = array(

            'payment_terms' => $data['payment_terms'],

            'default_invoice_message' => $data['default_invoice_message'],

            'convenience_fee' => $data['convenience_fee'],

            'updated_at' => date('Y-m-d H:i:s'),

        );



        if (array_key_exists('is_sales_tax', $data)) {

            $param['is_sales_tax'] = 1;
        } else {

            $param['is_sales_tax'] = 0;
        }

        if (array_key_exists('tech_add_standalone_service', $data)) {

            $param['tech_add_standalone_service'] = 1;
        } else {

            $param['tech_add_standalone_service'] = 0;
        }

        if (array_key_exists('send_monthly_invoice_statement', $data)) {

            $param['send_monthly_invoice_statement'] = 1;
        } else {

            $param['send_monthly_invoice_statement'] = 0;
        }



        if (array_key_exists('pay_now_btn', $data)) {

            $param['pay_now_btn'] = 1;

            $param['pay_now_btn_link'] = $data['pay_now_btn_link'];
        } else {

            $param['pay_now_btn'] = 0;
        }







        if (array_key_exists('is_product_name', $data)) {

            $param['is_product_name'] = 1;
        } else {

            $param['is_product_name'] = 0;
        }





        if (array_key_exists('is_epa', $data)) {

            $param['is_epa'] = 1;
        } else {

            $param['is_epa'] = 0;
        }



        if (array_key_exists('is_active_ingredients', $data)) {

            $param['is_active_ingredients'] = 1;
        } else {

            $param['is_active_ingredients'] = 0;
        }



        if (array_key_exists('is_application_rate', $data)) {

            $param['is_application_rate'] = 1;
        } else {

            $param['is_application_rate'] = 0;
        }



        if (array_key_exists('is_estimated_chemical_used', $data)) {

            $param['is_estimated_chemical_used'] = 1;
        } else {

            $param['is_estimated_chemical_used'] = 0;
        }



        if (array_key_exists('is_chemical_type', $data)) {

            $param['is_chemical_type'] = 1;
        } else {

            $param['is_chemical_type'] = 0;
        }



        if (array_key_exists('is_re_entry_time', $data)) {

            $param['is_re_entry_time'] = 1;
        } else {

            $param['is_re_entry_time'] = 0;
        }



        if (array_key_exists('is_weed_pest_prevented', $data)) {

            $param['is_weed_pest_prevented'] = 1;
        } else {

            $param['is_weed_pest_prevented'] = 0;
        }



        if (array_key_exists('is_application_type', $data)) {

            $param['is_application_type'] = 1;
        } else {

            $param['is_application_type'] = 0;
        }



        if (array_key_exists('is_wind_speed', $data)) {

            $param['is_wind_speed'] = 1;
        } else {

            $param['is_wind_speed'] = 0;
        }



        if (array_key_exists('is_wind_direction', $data)) {

            $param['is_wind_direction'] = 1;
        } else {

            $param['is_wind_direction'] = 0;
        }



        if (array_key_exists('is_temperature', $data)) {

            $param['is_temperature'] = 1;
        } else {

            $param['is_temperature'] = 0;
        }



        if (array_key_exists('is_applicator_name', $data)) {

            $param['is_applicator_name'] = 1;
        } else {

            $param['is_applicator_name'] = 0;
        }



        if (array_key_exists('is_applicator_number', $data)) {

            $param['is_applicator_number'] = 1;
        } else {

            $param['is_applicator_number'] = 0;
        }



        if (array_key_exists('is_applicator_phone', $data)) {

            $param['is_applicator_phone'] = 1;
        } else {

            $param['is_applicator_phone'] = 0;
        }



        if (array_key_exists('is_property_address', $data)) {

            $param['is_property_address'] = 1;
        } else {

            $param['is_property_address'] = 0;
        }



        if (array_key_exists('is_property_size', $data)) {

            $param['is_property_size'] = 1;
        } else {

            $param['is_property_size'] = 0;
        }



        if (array_key_exists('is_date', $data)) {

            $param['is_date'] = 1;
        } else {

            $param['is_date'] = 0;
        }



        if (array_key_exists('is_time', $data)) {

            $param['is_time'] = 1;
        } else {

            $param['is_time'] = 0;
        }

















        $where = array('company_id' => $this->session->userdata['company_id']);

        $this->CompanyEmail->updateCompanyEmail($where, $company_email_param);

        $result = $this->CompanyModel->updateCompany($where, $param);





        if ($result) {



            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>details updated successfully</div>');

            redirect("admin/setting");
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>Details not updated. Please try again</div>');

            redirect("admin/setting");
        }
    }

    public function updateEstimateDetails()
    {



        $data = $this->input->post();



        $param = array(

            'tearm_condition' => $data['tearm_condition'],

        );



        $where = array('company_id' => $this->session->userdata['company_id']);





        $result = $this->CompanyModel->updateCompany($where, $param);





        if ($result) {



            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate</strong> details updated successfully</div>');

            redirect("admin/setting");
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate</strong> Details not updated. Please try again</div>');

            redirect("admin/setting");
        }
    }

    function getLatLongByAddress($address)
    {



        $address = urlencode($address);



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

    /* COUPON SECTION */

    public function getCoupon()
    {

        $data = array();

        $company_id = $this->session->userdata['company_id'];

        $where = array('company_id' => $company_id);

        $data['coupon_details'] = $this->CouponModel->getAllCoupon($where);

        $body = $this->load->view("admin/setting/view_coupon_area", $data, false);

        echo $body;
    }

    public function addCouponData()
    {

        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];

        if (empty($company_id)) {

            echo "Something went wrong - company ID not found";
        }



        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('coupon_code', 'coupon_code', 'trim|required');

        $this->form_validation->set_rules('coupon_amount', 'coupon_amount', 'trim|required|numeric');

        $this->form_validation->set_rules('coupon_amount_type', 'coupon_amount_type', 'required');

        $this->form_validation->set_rules('coupon_type', 'coupon_type', 'required');



        if ($this->form_validation->run() == FALSE) {

            echo json_encode(validation_errors());
        } else {

            $param = array(

                'code' => $data['coupon_code'],

                'amount' => $data['coupon_amount'],

                'amount_calculation' => $data['coupon_amount_type'],

                'type' => $data['coupon_type'],

                'expiration_date' => $data['coupon_expire_date'],

                'description' => $data['coupon_description'],

                'company_id' => $company_id

            );

            $result = $this->CouponModel->CreateOneCoupon($param);

            if ($result) {

                echo 1;
            } else {

                echo 0;
            }
        }
    }

    public function editCoupon($coupon_id)
    {

        $where = array('coupon_id' => $coupon_id);

        $data =  $this->CouponModel->getOneCoupon($where);

        $return_arr = array(

            'coupon_id' => $data->coupon_id,

            'code' => $data->code,

            'amount' => $data->amount,

            'amount_calculation' => $data->amount_calculation,

            'type' => $data->type,

            'description' => $data->description,

            'expiration_date' => $data->expiration_date,

        );

        echo json_encode($return_arr);
    }

    public function editCouponData()
    {

        $data = $this->input->post();

        $where = array(
            't_estimate.company_id' => $this->session->userdata['company_id']
        );

        // Grab all Estimates for current company to query for coupons
        $estimate_details = $this->EstimateModal->getAllEstimate($where);

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('coupon_code', 'coupon_code', 'trim|required');

        $this->form_validation->set_rules('coupon_amount', 'coupon_amount', 'trim|required|numeric');

        $this->form_validation->set_rules('coupon_amount_type', 'coupon_amount_type', 'required');

        $this->form_validation->set_rules('coupon_description', 'coupon_description', 'required');

        $this->form_validation->set_rules('coupon_type', 'coupon_type', 'required');



        if ($this->form_validation->run() == FALSE) {

            echo json_encode(validation_errors());
        } else {

            $param = array(

                'code' => $data['coupon_code'],

                'amount' => $data['coupon_amount'],

                'amount_calculation' => $data['coupon_amount_type'],

                'type' => $data['coupon_type'],

                'description' => $data['coupon_description'],

                'expiration_date' => $data['coupon_expire_date']

            );

            $where = array('coupon_id' => $data['coupon_id']);



            $result =  $this->CouponModel->updateCoupon($where, $param);


            // Check if estimates exist for company
            if ($estimate_details) {
                // Iterate through each estimate
                foreach ($estimate_details as $est_deet) {
                    // Check if estimate status is Draft or Sent
                    if ($est_deet->status == 0 || $est_deet->status == 1) {
                        // Parameters for fetching Coupon Estimates
                        $coop_where = array(
                            'coupon_id' => $data['coupon_id'],
                            'estimate_id' => $est_deet->estimate_id
                        );

                        // Parameters for Updating Coupon Estimates
                        $coop_param = array(

                            'coupon_code' => $data['coupon_code'],

                            'coupon_amount' => $data['coupon_amount'],

                            'coupon_amount_calculation' => $data['coupon_amount_type'],

                            'coupon_type' => $data['coupon_type'],

                            'description' => $data['coupon_description'],

                            'expiration_date' => $data['coupon_expire_date']

                        );

                        // Fetch instance of current Coupon attached to current estimate
                        $coupon_estimate = $this->CouponModel->getAllCouponEstimate($coop_where);
                        // Check if coupon exists for any of company's estimates that are in Draft or Sent status
                        if ($coupon_estimate) {
                            // Update Coupon estimate information
                            $res_est = $this->CouponModel->UpdateCouponEstimate($coop_where, $coop_param);
                        }
                    }
                }
            }


            if ($result) {

                echo 1;
            } else {

                echo 0;
            }
        }
    }

    public function deleteCoupon($coupon_id)
    {

        $where = array('coupon_id' => $coupon_id);

        $where_est = array(
            't_estimate.company_id' => $this->session->userdata['company_id']
        );

        // Grab all Estimates for current company to query for coupons
        $estimate_details = $this->EstimateModal->getAllEstimate($where_est);


        $result = $this->CouponModel->deleteCoupon($where);

        // Check if estimates exist for company
        if ($estimate_details) {
            // Iterate through each estimate
            foreach ($estimate_details as $est_deet) {
                // Check if estimate status is Draft or Sent
                if ($est_deet->status == 0 || $est_deet->status == 1) {
                    // Parameters for fetching Coupon Estimates
                    $coop_where = array(
                        'coupon_id' => $coupon_id,
                        'estimate_id' => $est_deet->estimate_id
                    );

                    // Fetch instance of current Coupon attached to current estimate
                    $coupon_estimate = $this->CouponModel->getAllCouponEstimate($coop_where);
                    // Check if coupon exists for any of company's estimates that are in Draft or Sent status
                    if ($coupon_estimate) {
                        // Update Coupon estimate information
                        $res_est = $this->CouponModel->DeleteCouponEstimate($coop_where);
                    }
                }
            }
        }

        if (!$result) {

            echo 0;
        } else {

            echo 1;
        }
    }

    public function markAsASAP()
    {

        $data = $this->input->post();


        $job_data_csv = json_decode($data['job_data']);



        if (!isset($job_data_csv) || empty($job_data_csv)) {

            echo json_encode("Please select a service!");

            die();
        }



        if (isset($coupon_id) && $coupon_id != '' && $coupon_id != 'REMOVE-ALL') {



            // ASSIGN COUPON TO SERVICES HERE

            foreach ($job_data_csv as $job) {



                // customer_id, job_id, program_id, property_id

                $data_arr = array();

                $data_arr[] = str_getcsv($job);



                $job_customer_id = $data_arr[0][0];

                $job_job_id = $data_arr[0][1];

                $job_program_id = $data_arr[0][2];

                $job_property_id = $data_arr[0][3];



                $where = array(

                    'coupon_id' => $coupon_id,

                    'job_id' => $job_job_id,

                    'customer_id' => $job_customer_id,

                    'program_id' => $job_program_id,

                    'property_id' => $job_property_id

                );

                $already_exists = $this->CouponModel->getCouponJob($where);



                if (!$already_exists) {

                    $coupon_data = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));



                    $where = array(

                        'coupon_id' => $coupon_id,

                        'job_id' => $job_job_id,

                        'coupon_code' => $coupon_data->code,

                        'coupon_amount' => $coupon_data->amount,

                        'coupon_amount_calculation' => $coupon_data->amount_calculation,

                        'customer_id' => $job_customer_id,

                        'program_id' => $job_program_id,

                        'property_id' => $job_property_id

                    );

                    $result = $this->CouponModel->CreateOneCouponJob($where);
                }
            }
        } else if (isset($coupon_id) && $coupon_id == 'REMOVE-ALL') {



            // REMOVE ALL COUPONS FROM SERVICES

            foreach ($job_data_csv as $job) {



                // customer_id, job_id, program_id, property_id

                $data_arr = array();

                $data_arr[] = str_getcsv($job);



                $job_customer_id = $data_arr[0][0];

                $job_job_id = $data_arr[0][1];

                $job_program_id = $data_arr[0][2];

                $job_property_id = $data_arr[0][3];



                $where = array(

                    'job_id' => $job_job_id,

                    'customer_id' => $job_customer_id,

                    'program_id' => $job_program_id,

                    'property_id' => $job_property_id

                );



                $result = $this->CouponModel->DeleteCouponJob($where);
            }
        } else {

            echo json_encode("<p>Please select a discount!</p>");

            die();
        }



        // echo json_encode($data_arr[0][0]);

        echo 1;
    }

    public function applyCouponData()
    {

        $data = $this->input->post();

        $coupon_id = $data['coupon_id'];

        $job_data_csv = json_decode($data['job_data']);



        if (!isset($job_data_csv) || empty($job_data_csv)) {

            echo json_encode("Please select a service!");

            die();
        }



        if (isset($coupon_id) && $coupon_id != '' && $coupon_id != 'REMOVE-ALL') {



            // ASSIGN COUPON TO SERVICES HERE

            foreach ($job_data_csv as $job) {



                // customer_id, job_id, program_id, property_id

                $data_arr = array();

                $data_arr[] = str_getcsv($job);



                $job_customer_id = $data_arr[0][0];

                $job_job_id = $data_arr[0][1];

                $job_program_id = $data_arr[0][2];

                $job_property_id = $data_arr[0][3];



                $where = array(

                    'coupon_id' => $coupon_id,

                    'job_id' => $job_job_id,

                    'customer_id' => $job_customer_id,

                    'program_id' => $job_program_id,

                    'property_id' => $job_property_id

                );

                $already_exists = $this->CouponModel->getCouponJob($where);



                if (!$already_exists) {

                    $coupon_data = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));



                    $where = array(

                        'coupon_id' => $coupon_id,

                        'job_id' => $job_job_id,

                        'coupon_code' => $coupon_data->code,

                        'coupon_amount' => $coupon_data->amount,

                        'coupon_amount_calculation' => $coupon_data->amount_calculation,

                        'customer_id' => $job_customer_id,

                        'program_id' => $job_program_id,

                        'property_id' => $job_property_id

                    );

                    $result = $this->CouponModel->CreateOneCouponJob($where);
                }
            }
        } else if (isset($coupon_id) && $coupon_id == 'REMOVE-ALL') {



            // REMOVE ALL COUPONS FROM SERVICES

            foreach ($job_data_csv as $job) {



                // customer_id, job_id, program_id, property_id

                $data_arr = array();

                $data_arr[] = str_getcsv($job);



                $job_customer_id = $data_arr[0][0];

                $job_job_id = $data_arr[0][1];

                $job_program_id = $data_arr[0][2];

                $job_property_id = $data_arr[0][3];



                $where = array(

                    'job_id' => $job_job_id,

                    'customer_id' => $job_customer_id,

                    'program_id' => $job_program_id,

                    'property_id' => $job_property_id

                );



                $result = $this->CouponModel->DeleteCouponJob($where);
            }
        } else {

            echo json_encode("<p>Please select a discount!</p>");

            die();
        }



        // echo json_encode($data_arr[0][0]);

        echo 1;
    }

    /* END COUPON SECTION */

    public function getServiceArea()
    {

        $data = array();

        $where = array('user_id' => $this->session->userdata['user_id']);

        $data['area_details'] = $this->ServiceArea->getAllServiceArea($where);

        $body = $this->load->view("admin/setting/view_service_area", $data, false);

        echo $body;
    }

    public function searchServiceArea(){
        $data = $this->input->post();
        $term = $data['term'];
        $result = $this->ServiceArea->getServiceArea($term, $this->session->userdata['company_id']);
        echo json_encode($result);
    }

    public function addServicrAreaData()
    {

        $data = $this->input->post();



        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('category_area_name', 'category_area_name', 'trim|required');
        $this->form_validation->set_rules('category_description', 'category_description', 'trim');

        if ($this->form_validation->run() == FALSE) {

            echo validation_errors();
        } else {

            
            $param = array(

                'user_id' => $this->session->userdata['user_id'],

                'company_id' => $this->session->userdata['company_id'],

                'category_area_name' => $data['category_area_name'],

                'service_area_polygon' => $data['service_area_polygon'],

                'category_created' => Date("Y-m-d H:i:s"),

                'category_description' => (isset($data['category_description'])?$data['category_description']:"")

            );

            if(!empty($data['property_area_cat_id'])){
                $result = $this->ServiceArea->updateServiceArea(['property_area_cat_id' => $data['property_area_cat_id']], $param);
            }else{
                $result = $this->ServiceArea->CreateOneServiceArea($param);
            }

            if ($result) {

                echo 1;
            } else {

                echo 0;
            }
        }
    }
    
    public function addSalesTaxAreaData(){
        $data = $this->input->post();
        $param = array(
            'user_id' => $this->session->userdata['user_id'],
            'company_id' => $this->session->userdata['company_id'],
            'tax_name' => $data['tax_name'],
            'tax_value' => $data['tax_value'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $result = $this->SalesTax->CreateOneSalesTaxArea($param);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getSalesTaxArea(){

        $data = array();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $body = $this->load->view("admin/setting/view_sales_tax_area", $data, false);

        echo $body;
    }

    public function editSalesTaxArea($sale_tax_area_id){
        $where = array(
            'sale_tax_area_id' => $sale_tax_area_id
        );
        $data =  $this->SalesTax->getOneSalesTaxArea($where);

        echo json_encode($data);
    }

    public function editSalesTaxAreaData($value = ''){
        $data = $this->input->post();
        $param = array(
            'tax_name' => $data['tax_name'],
            'tax_value' => $data['tax_value'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $where = array(
            'sale_tax_area_id' => $data['sale_tax_area_id']
        );
        $result =  $this->SalesTax->updateSalesTaxArea($where, $param);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deleteSalesTaxArea($sale_tax_area_id){
        $where = array('sale_tax_area_id' => $sale_tax_area_id);
        $result = $this->SalesTax->deleteSalesTaxArea($where);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function editServiceArea($property_area_cat_id)

    {



        $where = array(

            'property_area_cat_id' => $property_area_cat_id

        );



        $data =  $this->ServiceArea->getOneServiceArea($where);



        $return_arr = array('property_area_cat_id' => $data->property_area_cat_id, 'category_area_name' => $data->category_area_name, 'category_description' => $data->category_description);

        echo json_encode($return_arr);
    }

    public function editServicrAreaData()
    {

        $data = $this->input->post();

        $where = array('property_area_cat_id' => $data['property_area_cat_id']);

        $param = array(

            'category_area_name' => $data['category_area_name'],
            'category_description' => $data['category_description'],

            'category_update' => Date("Y-m-d H:i:s")

        );

        $result =   $this->ServiceArea->updateServiceArea($where, $param);

        if ($result) {

            echo 1;
        } else {

            echo 0;
        }
    }

    public function deleteServiceArea($property_area_cat_id)
    {
        $where = array('property_area_cat_id' => $property_area_cat_id);
        $result = $this->ServiceArea->deleteServiceArea($where);
        if(!$result){
            echo 0;
        }else{
            echo 1;
        }
    }

    public function checkBasysApi()
    {
        $data = $this->input->post();
        $result =   basysCurlProcess($data['api_key'], 'GET', 'user/apikeys');

        if ($result['status'] == 200) {



            $public_api_key = '';



            foreach ($result['result']->data as $key => $value) {



                if ($value->type == 'public') {

                    $public_api_key = $value->api_key;
                }
            }



            if ($public_api_key == '') {

                $post = array('name' => 'public api key', 'type' => 'public');



                $create_api =   basysCurlProcess($data['api_key'], 'POST', 'user/apikey', $post);

                if ($create_api['status'] == 200) {



                    $public_api_key =  $create_api['result']->data->api_key;
                } else {



                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . $create_api['message'] . '</div>');

                    redirect("admin/setting");
                }
            } else {

                //echo $public_api_key;

            }



            $wherearr = array(

                'company_id' => $this->session->userdata['company_id'],



            );



            $updatearr = array(

                'api_key' => $data['api_key'],

                'publuc_key' => $public_api_key,

                'status' => 1,

                'requested_update' => date("Y-m-d H:i:s")

            );





            $basys_check =  $this->BasysRequest->getOneBasysRequest($wherearr);





            if ($basys_check) {



                $result =  $this->BasysRequest->updateBasysRequest($wherearr, $updatearr);
            } else {



                $updatearr['company_id'] =   $this->session->userdata['company_id'];

                $result =  $this->BasysRequest->CreateOneBasysRequest($updatearr);
            }





            if ($result) {

                $cc_check = $this->CardConnect->getOneCardConnect($wherearr);
                if ($cc_check) {
                    $cardConnect = $this->CardConnect->updateCardConnect($wherearr, array('status' => 0));
                }



                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Basys </strong>account configure successfully</div>');

                redirect("admin/setting");
            } else {



                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong>went wrong</div>');

                redirect("admin/setting");
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . $result['message'] . '</div>');

            redirect("admin/setting");
        }
    }

    public function setDifficultyMultipliers()
    {

        $data = $this->input->post();

        //die(var_dump($data));

        // $where = array('property_area_cat_id' =>$data['property_area_cat_id']); 



        $this->form_validation->set_rules('dlmult_1', 'dlmult_1', 'required');

        $this->form_validation->set_rules('dlmult_2', 'dlmult_2', 'required');

        $this->form_validation->set_rules('dlmult_3', 'dlmult_3', 'required');



        if ($this->form_validation->run() == FALSE) {



            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . validation_errors() . '</div>');

            $this->index();
        } else {

            $param = array(

                'dlmult_1' => $data['dlmult_1'],

                'dlmult_2' => $data['dlmult_2'],

                'dlmult_3' => $data['dlmult_3']

            );



            $where = array('company_id' => $this->session->userdata['company_id']);



            $result = $this->CompanyModel->updateCompany($where, $param);





            if ($result) {



                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Difficulty Multipliers </strong>updated successfully.</div>');

                redirect("admin/setting");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Difficulty Multipliers </strong> not updated. Please try again.</div>');

                redirect("admin/setting");
            }
        }
    }

    public function setServiceFees()
    {

        $data = $this->input->post();

        // $where = array('property_area_cat_id' =>$data['property_area_cat_id']); 



        //$this->form_validation->set_rules('base_service_fee', 'base_service_fee', 'required');

        //$this->form_validation->set_rules('minimum_service_fee', 'minimum_service_fee', 'required');





        $param = array(

            'base_service_fee' => $data['base_service_fee'] == '' ? NULL : $data['base_service_fee'],

            'minimum_service_fee' => $data['minimum_service_fee'] == '' ? NULL : $data['minimum_service_fee']

        );



        $where = array('company_id' => $this->session->userdata['company_id']);



        $result = $this->CompanyModel->updateCompany($where, $param);

        if ($result) {

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service Fees </strong>updated successfully.</div>');

            redirect("admin/setting");
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service Fees </strong> not updated. Please try again.</div>');

            redirect("admin/setting");
        }
    }

	public function updateCompanyNoteSetting()
    {
        $data = $this->input->post();
        //print_r($data);
        $note_required = (isset($data['is_tech_customer_note_required'])) ? $data['is_tech_customer_note_required'] : 0;
        $inspection_required = (isset($data['is_tech_vehicle_inspection_required'])) ? $data['is_tech_vehicle_inspection_required'] : 0;
        $where = array('company_id'=>$this->session->userdata['company_id']);
		$param = array('is_tech_customer_note_required' => $note_required);
        $param = array(
            'is_tech_customer_note_required' => $note_required,
            'is_tech_vehicle_inspection_required' => $inspection_required
        );
        $result = $this->CompanyModel->updateCompany($where,$param);
        if($result)
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note Settings </strong>updated successfully.</div>');
            redirect("admin/setting");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note Settings </strong> not updated. Please try again.</div>');
            redirect("admin/setting");
        }        
    }    

	public function checkCardConnectApi(){
		$data = $this->input->post();

		$api_arr = array(
			'merchant_id'=> $data['cardconnect_mid'],
			'username'=> $data['cardconnect_username'],
			'password'=> $data['cardconnect_password'],
		);
		## check api credentials 
      	$result = cardConnectInquireMerchant($api_arr);

		if($result['status']==200){
			##encrypt password using openssl_encrypt
			$password = encryptPassword($data['cardconnect_password']);

            //die(print_r($this->decryptPassword($password)));

            // $token = cardConnectTokenizeAccount("4444333322221111");

            //print_r($token['result']->token);
            //die();
			
			##if successful then create new cardconnect 
			$create_arr = array(
				'company_id'=> $this->session->userdata['company_id'],
				'merchant_id'=> $data['cardconnect_mid'],
                'status' => 1,
				'username'=> $data['cardconnect_username'],
				'password'=> $password,
				'created_at'=> date("Y-m-d H:i:s"),
			);

            $where = array('company_id'=>$this->session->userdata['company_id']);

            $basys_check = $this->BasysRequest->getOneBasysRequest($where);

            $cc_check = $this->CardConnect->getOneCardConnect($where);

            if ($basys_check){
                $basys_update = $this->BasysRequest->updateBasysRequest($where, array('status' => 0));
            }

            if ($cc_check){
                $updatearr = array(
                    'company_id'=> $this->session->userdata['company_id'],
				    'merchant_id'=> $data['cardconnect_mid'],
                    'status' => 1,
				    'username'=> $data['cardconnect_username'],
				    'password'=> $password
                );

                $cardConnect = $this->CardConnect->updateCardConnect($where, $updatearr);

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Card Connect Information </strong>updated successfully.</div>');
                redirect("admin/setting");

            } else {
                
                $cardConnect = $this->CardConnect->CreateOneCardConnect($create_arr);

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Card Connect Information </strong>added successfully.</div>');
                redirect("admin/setting");
			
            }			
			
		} else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.$result['message'].'</div>');
        redirect("admin/setting");
      }
      
    }

    ##### ADDED 2/18/22 (RG) #####
    ### SOURCE ###
    public function addSourceData(){
        $data = $this->input->post();
        $param = array(
            'company_id' => $this->session->userdata['company_id'],
            'user_id' => $this->session->userdata['user_id'],
            'source_name' => $data['source_name'],
            'source_type' => $data['source_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $result = $this->SourceModel->CreateOneSource($param);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getSource(){

        $data = array();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['source_details'] = $this->SourceModel->getAllSource($where);
        // die(print_r($data['source_details']));
        $body = $this->load->view("admin/setting/view_source", $data, false);

        echo $body;
    }

    public function editSource($source_id){
        $where = array(
            'source_id' => $source_id
        );
        $data =  $this->SourceModel->getOneSource($where);

        echo json_encode($data);
    }

    public function editSourceData($value = ''){
        $data = $this->input->post();
        $param = array(
            'source_name' => $data['source_name'],
            'source_type' => $data['source_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $where = array(
            'source_id' => $data['source_id']
        );
        $result =  $this->SourceModel->updateSource($where, $param);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deleteSource($source_id){
        $where = array('source_id' => $source_id);
        $result = $this->SourceModel->deleteSource($where);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    }
    ##### ADDED 2/21/22 (RG) #####
    ### SERVICE TYPE ###
    public function addServiceTypeData(){
        $data = $this->input->post();
        $param = array(
            'company_id' => $this->session->userdata['company_id'],
            'user_id' => $this->session->userdata['user_id'],
            'service_type_name' => $data['service_type_name'],
            'service_type' => $data['service_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $result = $this->ServiceTypeModel->CreateOneServiceType($param);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getServiceType(){

        $data = array();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['service_type'] = $this->ServiceTypeModel->getAllServiceType($where);
        // die(print_r($data['source_details']));
        $body = $this->load->view("admin/setting/view_service_type", $data, false);

        echo $body;
    }

    public function editServiceType($service_type_id){
        $where = array(
            'service_type_id' => $service_type_id
        );
        $data =  $this->ServiceTypeModel->getOneServiceType($where);

        echo json_encode($data);
    }

    public function editServiceTypeData($value = ''){
        $data = $this->input->post();
        // die(print_r($data));
        $param = array(
            'service_type_name' => $data['service_type_name'],
            'service_type' => $data['service_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $where = array(
            'service_type_id' => $data['service_type_id']
        );
        $result =  $this->ServiceTypeModel->updateServiceType($where, $param);
        die(print_r($result));
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deleteServiceType($service_type_id){
        $where = array('service_type_id' => $service_type_id);
        $result = $this->ServiceTypeModel->deleteServiceType($where);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    }
    ##### ADDED 2/17/22 (RG) #####
    ### PRIMARY SERVICES COMMISSION % ###
    public function addCommissionData(){
        $data = $this->input->post();
        $param = array(
            'company_id' => $this->session->userdata['company_id'],
             'user_id' => $this->session->userdata['user_id'],
            'commission_name' => $data['commission_name'],
            'commission_value' => $data['commission_value'],
            'commission_type' => $data['commission_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $result = $this->CommissionModel->CreateOneCommission($param);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getCommission(){

        $data = array();
		$data['disable_primary'] = 0;
		$data['disable_secondary'] = 0;
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['commissions'] = $this->CommissionModel->getAllCommission($where);
        if(count($data['commissions']) > 0){
			foreach($data['commissions'] as $commission){
				if(isset($commission->commission_type) && $commission->commission_type == 1){
					$data['disable_primary'] = 1;
				}
				if(isset($commission->commission_type) && $commission->commission_type == 2){
					$data['disable_secondary'] = 1;
				}
			}
		}
        $body = $this->load->view("admin/setting/view_commission", $data, false);

        echo $body;
    }

    public function editCommission($commission_id){
        $where = array(
            'commission_id' => $commission_id
        );
        $data =  $this->CommissionModel->getOneCommission($where);

        echo json_encode($data);
    }

    public function editCommissionData($value = ''){
        $data = $this->input->post();
        // die(print_r($data));
        $param = array(
            'commission_name' => $data['commission_name'],
            'commission_value' => $data['commission_value'],
             'commission_type' => $data['commission_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $where = array(
            'commission_id' => $data['commission_id']
        );
        $result =  $this->CommissionModel->updateCommission($where, $param);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deleteCommission($commission_id){
        $where = array('commission_id' => $commission_id);
        $result = $this->CommissionModel->deleteCommission($where);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    }
    
    ##### ADDED 2/17/22 (RG) #####
    ### SERVICES BONUS  % ###
     public function addBonusData(){
        $data = $this->input->post();
        $param = array(
            'user_id' => $this->session->userdata['user_id'],
            'company_id' => $this->session->userdata['company_id'],
            'bonus_name' => $data['bonus_name'],
            'bonus_value' => $data['bonus_value'],
            'bonus_type' => $data['bonus_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $result = $this->BonusModel->CreateOneBonus($param);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getBonus(){

        $data = array();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['bonuses'] = $this->BonusModel->getAllBonus($where);
        $body = $this->load->view("admin/setting/view_bonus", $data, false);

        echo $body;
    }

    public function editBonus($bonus_id){
        $where = array(
            'bonus_id' => $bonus_id
        );
        $data =  $this->BonusModel->getOneBonus($where);

        echo json_encode($data);
    }

    public function editBonusData($value = ''){
        $data = $this->input->post();
        $param = array(
            'bonus_name' => $data['bonus_name'],
            'bonus_value' => $data['bonus_value'],
            'bonus_type' => $data['bonus_type'],
            'created_at' => Date("Y-m-d H:i:s")
        );
        $where = array(
            'bonus_id' => $data['bonus_id']
        );
        $result =  $this->BonusModel->updateBonus($where, $param);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deleteBonus($bonus_id){
        $where = array('bonus_id' => $bonus_id);
        $result = $this->BonusModel->deleteBonus($where);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    }

  
	public function addPropertyConditions(){
		$post_data = $this->input->post();
		if(isset($post_data['in_email'])){
			$in_email = 1;
		}else{
			$in_email = 0;
		}
		$param = array(
			'company_id'=> $this->session->userdata['company_id'],
			'condition_name'=>$post_data['condition_name'],
			'message'=>$post_data['message'],
			'in_email'=>$in_email,
		);
		$result = $this->PropertyModel->addPropertyCondition($param);
		if($result){
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property Condition </strong>added successfully.</div>');
            redirect("admin/setting");
        }else{
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property Condition</strong> not added. Please try again.</div>');
            redirect("admin/setting");
        }
	}
    public function editPropertyCondition($property_condition_id){
        $getPropertyCondition = $this->PropertyModel->getOnePropertyCondition(array('property_condition_id'=>$property_condition_id));
        $return_arr = array(
            'property_condition_id' => $getPropertyCondition->property_condition_id,
            'condition_name' => $getPropertyCondition->condition_name,
            'message' => $getPropertyCondition->message,
            'in_email' => $getPropertyCondition->in_email,
        );
        echo json_encode($return_arr);
    }
    public function updatePropertyConditions(){
		$post_data = $this->input->post();
        if(isset($post_data['property_condition_id'])){
            $where = array(
                'property_condition_id'=>$post_data['property_condition_id']
            );
            if(isset($post_data['in_email'])){
                $in_email = 1;
            }else{
                $in_email = 0;
            }
            $param = array(
                'condition_name'=>$post_data['condition_name'],
                'message'=>$post_data['message'],
                'in_email'=>$in_email,
            );
            $result = $this->PropertyModel->updatePropertyCondition($where,$param);
            if($result){
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property Condition </strong>updated successfully.</div>');
                redirect("admin/setting");
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property Condition</strong> not updated. Please try again.</div>');
                redirect("admin/setting");
            }
        }
	}
    public function deletePropertyCondition(){
        $post_data = $this->input->post();
        if(isset($post_data['property_condition_id'])){
            $handleDelete = $this->PropertyModel->deletePropertyCondition(array('property_condition_id'=>$post_data['property_condition_id']));
            if($handleDelete){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }
///////////////////////////////////////************* TAGS Section Start *********************//////////////////////////////////
    public function addTagsData(){

        $data = $this->input->post();

        $this->form_validation->set_rules('tags_title', 'tags', 'required');
       // $this->form_validation->set_rules('include_in_tech_view', 'tags', 'required');
        if ($this->form_validation->run() == FALSE) {


            $this->addTags();
        } else {

            //$uID = $userdata['user_id'];

           // print_r($uID);
            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];
            $data = $this->input->post();
            $include_in_tech_view=0;
            if(isset($data['include_in_tech_view'])){
                $include_in_tech_view=1;
            }
            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'tags_title' => $data['tags_title'],
                'include_in_tech_view' => $include_in_tech_view
            );
      // print_r($param );die();

            $where_pro_check =  $param;

            $where_pro_check = array_filter($where_pro_check);


            $check  = $this->TagsModel->getOneTag($where_pro_check);


            if ($check >0) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Tag </strong> Already exist.</div>');
                $this->addTags();
            } else {



                $result1 = $this->TagsModel->insert_tags($param);
            }
            if ($result1) {
                 echo "1";

                // $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> added successfully</div>');
                // redirect("admin/tagList");
    }
    }
    }

    public function getTags()
    {

        $data = array();

        $data['TagData'] = $this->TagsModel->get_all_tags(array('company_id' => $this->session->userdata['company_id']));

        $page = $this->load->view("admin/setting/view_tags1", $data, false);

        echo $page;

    }

    public function tagDelete($id)
    {

        $where = array('id' => $id);
        $result = $this->TagsModel->deletetags($where);

        if (!$result) {
            echo "0";

           // $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

           // redirect("admin/setting/getTags");
        } else {
            echo "1";
           // $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong>deleted successfully</div>');
           // redirect("admin/setting/getTags");
        }
    }

    public function editTag($id)
    {

        $where = array('id' => $id);

        $data =  $this->TagsModel->getOneTag($where);

        $return_arr = array(

            'id' => $data->id,

            'tags_title' => $data->tags_title,
            'include_in_tech_view' => $data->include_in_tech_view,



        );

        echo json_encode($return_arr);
    }

    public function editTagData()
    {


        $data = $this->input->post();

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('tags_title', 'tags_title', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(validation_errors());
        }
        else
        {
            $include_in_tech_view=0;
            if(isset($data['include_in_tech_view'])){
                $include_in_tech_view=1;
            }
            $param = array(
                'tags_title' => $data['tags_title'],
                'include_in_tech_view' => $include_in_tech_view
            );

            $where = array('id' => $data['id']);
            $result =  $this->TagsModel->updateTag($where, $param);
         }

         if ($result) { echo 1; }
         else  { echo 0; }

     }

     public function setSignwellAPI()
    {

        $data = $this->input->post();

        // $where = array('property_area_cat_id' =>$data['property_area_cat_id']); 



        //$this->form_validation->set_rules('base_service_fee', 'base_service_fee', 'required');

        //$this->form_validation->set_rules('minimum_service_fee', 'minimum_service_fee', 'required');





        $param = array(
            'signwell_api_key' => $data['signwell_api_key'] == '' ? "" : $data['signwell_api_key'],
        );



        $where = array('company_id' => $this->session->userdata['company_id']);



        $result = $this->CompanyModel->updateCompany($where, $param);

        if ($result) {

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SignWell API Key </strong>updated successfully.</div>');

            redirect("admin/setting");
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SignWell API Key </strong> not updated. Please try again.</div>');

            redirect("admin/setting");
        }
    }

}
