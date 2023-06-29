<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require FCPATH . 'vendor/autoload.php';
include APPPATH ."libraries/dompdf/autoload.inc.php";

class MassEmail extends MY_Controller {

    public function __construct() {
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

    private function loadModel() {
        $this->load->model("Administrator");
        $this->load->model('MassEmailModel', 'MassEmailModel');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('AdminTbl_program_model', 'ProgramModel');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');
    }

    public function ResendEmail() {
        $ModelID = $_GET['id'];
        $Data = $this->MassEmailModel->getMassEmailData(array("id" => $ModelID));
        $Data = $Data[0];

        $this->MassEmailModel->updateEmailData(array("status" => 1, "send_date" => date("Y-m-d")), array("id" => $ModelID));

        $CustomerArray = explode(",", $Data->cusotmer_id);
        $ProgrammArray = explode(",", $Data->programmes_id);

        $body = $Data->mail_text;
        $where_company = array('company_id' =>  $this->session->userdata['company_id']);
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
        if (!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        }

        foreach($CustomerArray as $CusData){
            $CustomerDetails = $this->CustomerModel->getOneCustomerDetail($CusData);
            if($CustomerDetails->email != ""){
                $body = str_replace('{CUSTOMER_FIRST_NAME}', $CustomerDetails->first_name, $body);
                $body = str_replace('{CUSTOMER_LAST_NAME}', $CustomerDetails->last_name, $body);
                $GetProperty = $this->PropertyModel->getAllCustomerProperties($CusData);

                $body = str_replace('{PROPERTY_NAME}', $GetProperty[0]->property_title, $body);
                $body = str_replace('{PROPERTY_ADDRESS}', $GetProperty[0]->property_address, $body);

                $AllProgrammNames = array();
                foreach($ProgrammArray as $PrmArr){
                    $IsSendEmail = 0;
                    foreach($GetProperty as $GPS){
                        $CheckAssignment = $this->ProgramModel->getAllproperty(array("property_tbl.property_id" => $GPS->property_id, "program_id" => $PrmArr));

                        if(count($CheckAssignment) > 0){
                            $IsSendEmail = 1;
                        }
                    }

                    if($IsSendEmail == 1){
                        $GetProgramName = $this->PropertyModel->getProgramList(array("program_id" => $PrmArr));
                        $AllProgrammNames[] = $GetProgramName[0]->program_name;
                    }
                }

                $body = str_replace('{PROGRAM_NAME}', implode(", ", $AllProgrammNames), $body);

                if(count($AllProgrammNames) > 0){
                    Send_Mail_dynamic(
                        $company_email_details,
                        $CustomerDetails->email,
                        array(
                            "name" => $this->session->userdata['compny_details']->company_name,
                            "email" => $this->session->userdata['compny_details']->company_email
                        ),
                        $body,
                        $Data->email_subject,
                        $CustomerDetails->secondary_email
                    );
                }
                $body = $Data->mail_text;
            }
        }
        redirect("admin/reports/emailMarketing");
    }

    public function DeleteEmails(){
        $ModelID = $_GET['id'];
        $Data = $this->MassEmailModel->deleteData(array("id" => $ModelID));
        redirect("admin/reports/emailMarketing");
    }
}