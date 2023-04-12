<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
class Superadmin extends MY_Controller {  

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('superadmin')) {
            return redirect('superadmin/auth');
        }
        $this->load->library('parser');
        $this->load->helper('text');
        $this->loadModel();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('migration');
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
    private function loadModel() {
        $this->load->model("Administrator");
        $this->load->model('Super_admin_model', 'SuperModal');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('Administratorsuper');
       $this->load->model('Company_subscription_model', 'CompanySubscription');  
       $this->load->helper('super_admin_helper');  

      
    }


    public function testing($value=''){
         

     $checksmtp =   Send_Mail('hemantrajak1@gmail.com','spraye','This mail has been made for smtp credential check, please ignore it','Check SMTP credential');
     print_r($checksmtp);


     } 

    public function index() {

	    $data['company'] = $this->SuperModal->getAllCompany();
        $data['users'] = $this->SuperModal->getAllCompanyUsers();
        $data['properties'] = $this->SuperModal->getAllCompanyProperties();
        $data['logins'] = $this->SuperModal->getAllCompanyLoginDates();


       
	    $page["active_sidebar"] = "AllCompany";
	    $page["page_name"] = "Summary dashboard";

	    $page["page_content"] = $this->load->view("all_company", $data, TRUE);
	    $this->layout->mainSuperAdminTemplateTable($page);
    }

    public function addCompany() {

	    $page["active_sidebar"] = "AllCompany";
	    $page["page_name"] = "Add Company";

	    $page["page_content"] = $this->load->view("add_company", '', TRUE);
	    $this->layout->mainSuperAdminTemplateTable($page);
    }

   

    public function addCompanyData() {
    	$data = $this->input->post();
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('company_address', 'Company Address', 'required');
        $this->form_validation->set_rules('company_email', 'Company email', 'required');
        $this->form_validation->set_rules('web_address', 'Web Address', 'trim');
        $this->form_validation->set_rules('start_location', 'Start Location', 'required');
        $this->form_validation->set_rules('end_location', 'End Location', 'required');

        if ($this->form_validation->run() == FALSE) {
           $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.validation_errors().'</div>');
            $this->addCompany();
        } else if($result = $this->SuperModal->getOneCompany(array('company_email' =>$data['company_email']))) {
        	$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company</strong> email already exits</div>');
			redirect("superadmin/addCompany");
        } else if($user = $this->Administrator->getOneAdmin(array('email' =>$data['email']))) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>User</strong> email already exits</div>');
            redirect("superadmin/addCompany");
        }else {

            $company_geo = $this->getLatLongByAddress($data['company_address']);
            $geo = $this->getLatLongByAddress($data['start_location']);
            $geo2 = $this->getLatLongByAddress($data['end_location']);
            // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));

            if (!$company_geo) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> company address. Please try again</div>');
                redirect("superadmin/addCompany");
            }else if (!$geo) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> start location. Please try again</div>');
                redirect("superadmin/addCompany");
            } else if(!$geo2) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> end location. Please try again</div>');
                redirect("superadmin/addCompany");

            } else {
                // $city = explode(',', $data['company_address']);
                $city = $data['company_address'];
                // die(print_r($city));
                $dup_company_check = [];
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 'new')));
                // $dup_slug_check = $this->SuperModal->getAllCompanySlugDuplicates(array('slug' => $slug));
                $dup_company_check = $this->SuperModal->getAllCompanySlugDuplicates(array('company_name' =>$data['company_name']));
            //    die(print_r($this->db->last_query()));
                // die(print_r($dup_company_check));
          
        //    die(print_r($dup_company_check));

            if(count($dup_company_check)>1){
               $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.'-'.$city)));
            //    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.$city[1])));
            //    die(print_r($slug));
               $param = array(
                    'company_name' =>$data['company_name'],
                    'company_address' =>$data['company_address'],
                    'company_address_lat' =>$company_geo['lat'],
                    'company_address_long' =>$company_geo['long'],
                    'company_email' =>$data['company_email'],
                    'web_address' =>$data['web_address'],
                    'start_location' => $data['start_location'],
                    'start_location_lat' => $geo['lat'],
                    'start_location_long' => $geo['long'],
                    'end_location' => $data['end_location'],
                    'end_location_lat' => $geo2['lat'],
                    'end_location_long' => $geo2['long'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),   
                    'slug' => $slug,   
              );
            //    die(print_r($param));
           } else {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                // die(print_r($slug));
                $param = array(
                    'company_name' =>$data['company_name'],
                    'company_address' =>$data['company_address'],
                    'company_address_lat' =>$company_geo['lat'],
                    'company_address_long' =>$company_geo['long'],
                    'company_email' =>$data['company_email'],
                    'web_address' =>$data['web_address'],
                    'start_location' => $data['start_location'],
                    'start_location_lat' => $geo['lat'],
                    'start_location_long' => $geo['long'],
                    'end_location' => $data['end_location'],
                    'end_location_lat' => $geo2['lat'],
                    'end_location_long' => $geo2['long'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),   
                    'slug' => $slug,   
              );
            //    die(print_r($param));
            //    die('nonDuplicate');
           }
				
				if (isset($data['is_text_message'])) {
					  $param['is_text_message'] = 1;
					} else {
					  $param['is_text_message'] = 0;
					}
           
                $done = $this->SuperModal->createCompany($param);
                // die(print_r($done));
                if ($done) {
                    
                     $user_id = md5(json_encode($data).date("Ymdhis"));
                     
                      $param2 = array(
                        'company_id' => $done,
                        'user_id' => $user_id,
                        'user_first_name' => $data['user_first_name'],
                        'user_last_name' => $data['user_last_name'],
                        'applicator_number' => $data['applicator_number'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'password' => md5($data['password']),
                        'role_id' => 1,                
                        'created_at' => Date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),   
                       );

                      $result = $this->Administrator->CreateOneAdmin($param2);
                      
                      $string ='<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p><br></p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE}</p><p><br></p><p>Thanks,<br></p><p><br> </p>';

                      $string2 = '<p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE} {TECHNICIAN_MESSAGE}<br></p><p>{ADDITIONAL_INFO}<br></p><p>has been completed successfully.<br></p><p>Thanks,<br></p><p>     <br></p>';

                      $this->CompanyEmail->createCompanyEmail(array('company_id'=>$done,'job_sheduled'=>$string,'one_day_prior'=>$string,'job_completion'=>$string2));



                       $param = array(              
                        'company_id' =>$done,
                        'subscription_unique_id' =>'ag3bk4g4hq',
                        'subscription_created_at' =>date("Y-m-d H:i:s"),
                        'is_technician_count' =>500,
                        'is_quickbooks_price' =>1,
                      );
                       
                       $this->CompanySubscription->createCompanySubscription($param);

                      
                      $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company</strong> added successfully.</div>');
                      redirect("superadmin");
     

                    
                } else {

                 $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company</strong> not added.</div>');
                 redirect("superadmin/addCompany");
     

                }

            }      	

        }
    }


   public function deleteCompany($company_id){

       $where = array('company_id' => $company_id);
       $result = $this->SuperModal->deleteCompanyDetails($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("superadmin");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company </strong>deleted successfully</div>');
            redirect("superadmin");
        }

   }

   public function inactiveCompany($company_id){

       $where = array('company_id' => $company_id);
       $result = $this->SuperModal->softDeleteCompanyDetails($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("superadmin");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company </strong>inactivated successfully</div>');
            redirect("superadmin");
        }

   }


   public function recoverDeletedCompany($company_id){

       $where = array('company_id' => $company_id);
       $result = $this->SuperModal->recoverDeleteCompany($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("superadmin");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company </strong>activate successfully</div>');
            redirect("superadmin");
        }

   }

   public function editComapny($company_id)
   {
      $where = array('company_id' => $company_id);
      $data['company_details'] = $this->SuperModal->getOneCompany($where);
      $where['role_id'] = 1;    
      $data['user_details'] = $this->Administrator->getOneAdmin($where);  

      $data['subscription_details'] = $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$company_id));
      
      $page["active_sidebar"] = "AllCompany";
      $page["page_name"] = "Update Company";

      $page["page_content"] = $this->load->view("edit_company", $data, TRUE);
      $this->layout->mainSuperAdminTemplateTable($page);
   }


     public function editCompanyDetailsData($company_id) {

        $data = $this->input->post();
        // die(print_r($data));
        $this->form_validation->set_rules('company_name', 'company_name', 'required');
        $this->form_validation->set_rules('company_address', 'company_address', 'required');
        $this->form_validation->set_rules('company_email', 'company_email', 'required');
        $this->form_validation->set_rules('web_address', 'web_address', 'trim');
        $this->form_validation->set_rules('start_location', 'start_location', 'required');
        $this->form_validation->set_rules('end_location', 'end_location', 'required');
        //$this->form_validation->set_rules('company_currency', 'company_currency', 'required');       

        if ($this->form_validation->run() == FALSE) {

             echo validation_errors();
             $this->editComapny($company_id);
        }  elseif ($check = $this->SuperModal->getOneCompany(array('company_id !=' =>$company_id,'company_email'=>$data['company_email']))) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company Email </strong> already exists </div>');
                redirect("superadmin/editComapny/".$company_id);
          
        } else {


            $company_geo = $this->getLatLongByAddress($data['company_address']);
            $geo = $this->getLatLongByAddress($data['start_location']);
            $geo2 = $this->getLatLongByAddress($data['end_location']);

            if (!$company_geo) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> company address. Please try again</div>');
                redirect("superadmin/editComapny/".$company_id);
            } else if (!$geo) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> start location. Please try again</div>');
                redirect("superadmin/editComapny/".$company_id);
            } else if(!$geo2) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid</strong> end location. Please try again</div>');
                redirect("superadmin/editComapny/".$company_id);
                } 
            else {

                // $city = explode(',', $data['company_address']);
                $city = $data['company_address'];
                // die(print_r($city));
                $dup_company_check = [];
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 'new')));
                // $dup_slug_check = $this->SuperModal->getAllCompanySlugDuplicates(array('slug' => $slug));
                $dup_company_check = $this->SuperModal->getAllCompanySlugDuplicates(array('company_name' =>$data['company_name']));
            //    die(print_r($this->db->last_query()));
                // die(print_r($dup_slug_check));
          
        //    die(print_r($dup_slug_check));
        //    die(print_r(count($dup_slug_check)));

            if(count($dup_company_check)>1){
               $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.'-'.$city)));
            //    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.$city[1])));
            //    die(print_r($slug));
               $param = array(
                    'company_name' =>$data['company_name'],
                    'company_address' =>$data['company_address'],
                    'company_address_lat' =>$company_geo['lat'],
                    'company_address_long' =>$company_geo['long'],
                    'company_email' =>$data['company_email'],
                    'web_address' =>$data['web_address'],
                    'start_location' => $data['start_location'],
                    'start_location_lat' => $geo['lat'],
                    'start_location_long' => $geo['long'],
                    'end_location' => $data['end_location'],
                    'end_location_lat' => $geo2['lat'],
                    'end_location_long' => $geo2['long'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),   
                    'slug' => $slug,
                    'company_currency' =>$data['company_currency'],
              );
            //    die(print_r($param));
           } else {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['company_name'])));
                // die(print_r($slug));
                $param = array(
                    'company_name' =>$data['company_name'],
                    'company_address' =>$data['company_address'],
                    'company_address_lat' =>$company_geo['lat'],
                    'company_address_long' =>$company_geo['long'],
                    'company_email' =>$data['company_email'],
                    'web_address' =>$data['web_address'],
                    'start_location' => $data['start_location'],
                    'start_location_lat' => $geo['lat'],
                    'start_location_long' => $geo['long'],
                    'end_location' => $data['end_location'],
                    'end_location_lat' => $geo2['lat'],
                    'end_location_long' => $geo2['long'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),   
                    'slug' => $slug,
                    'company_currency' =>$data['company_currency'],                       
              );
            //    die(print_r($param));
            //    die('nonDuplicate');
           }
				

                //     $param = array(
                //     'company_name' => $data['company_name'],
                //     'company_address' => $data['company_address'],
                //     'company_address_lat' => $company_geo['lat'],
                //     'company_address_long' => $company_geo['long'],
                //     'company_email' => $data['company_email'],
                //     'web_address' => $data['web_address'],
                //     'start_location' => $data['start_location'],
                //     'start_location_lat' => $geo['lat'],
                //     'start_location_long' => $geo['long'],
                //     'end_location' => $data['end_location'],
                //     'end_location_lat' => $geo2['lat'],
                //     'end_location_long' => $geo2['long'],   
                //     'updated_at' => date("Y-m-d H:i:s")
                //    );

              if (isset($data['is_text_message'])) {
					  $param['is_text_message'] = 1;
					} else {
					  $param['is_text_message'] = 0;
					}

                $where = array('company_id' =>$company_id);
    

              $result = $this->SuperModal->updateCompanyDetails($where,$param);

                // var_dump($result);die();

                if ($result) {

                  $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Company details </strong>updated successfully</div>');


                    redirect("superadmin");
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Settings </strong> not updated. Please try again</div>');
                    redirect("superadmin/editComapny/".$company_id);
                }

            }

           // $geo = $this->getLatLongByAddress($data['start_location']);
           // $geo2 = $this->getLatLongByAddress($data['end_location']);
           
          
        }

    }



     public function editCompanyUserProfileData($company_id) {

 
     $data =  $this->input->post();
     $where = array('user_id' =>$data['user_id']);
     $this->form_validation->set_data($data);
     $this->form_validation->set_rules('user_first_name', 'first_name', 'trim|required');
     $this->form_validation->set_rules('user_last_name', 'last_name', 'trim|required');
     $this->form_validation->set_rules('email', 'email', 'trim|required');
     $this->form_validation->set_rules('phone', 'phone', 'trim|required');
     $this->form_validation->set_rules('password', 'password', 'trim|required');
     $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');
 
    if ($this->form_validation->run() == FALSE){
        
        $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>".validation_errors()."</div>");        
      
        $this->editComapny($company_id);
        }else{

          $checkArray = array(
              'email' => $data['email'],
              'user_id !=' => $data['user_id'],
          );
            
            $check = $this->Administrator->getOneAdmin($checkArray);
            if ($check) {

                $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>User Email</strong> already exits please try again</div>");          
                  $this->editComapny($company_id);
 
            } else {
             $param = array(
                'user_first_name' => $data['user_first_name'],
                'user_last_name' => $data['user_last_name'],
                'applicator_number' => $data['applicator_number'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'updated_at' => Date("Y-m-d H:i:s")
           
           );

           

              if ($data['old_password']!=$data['password']) {
                  $param['password']=md5($data['password']);
              }

              // print_r($where);
              // print_r($param);
              // die();

              $result =   $this->Administrator->updateAdminTbl($where,$param);
               if ($result) {
                 $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Comapny user </strong> profile updated successfully</div>");
                    redirect("superadmin");
              }
              else
              {       
                         
                $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>User  profile not updated </strong> Please try again</div>");
                    redirect("superadmin/editComapny/".$company_id);

              
              }

            }
      }


    }



    public function setting() {
        
        $data['superadmin_detalis'] = $this->Administratorsuper->getOneAdmin(array("id" => $this->session->userdata['superadmin']->id));

        $page["active_sidebar"] = "";
        $page["page_name"] = "Settings";


        $page["page_content"] = $this->load->view("setting_page", $data, TRUE);

        $this->layout->mainSuperAdminTemplateTable($page);
    }

      public function updateSmtp() {
        $data = $this->input->post();
        $this->form_validation->set_rules('smtp_host', 'smtp_host', 'required');
        $this->form_validation->set_rules('smtp_port', 'smtp_port', 'required');
        $this->form_validation->set_rules('smtp_username', 'smtp_username', 'required');
        $this->form_validation->set_rules('smtp_password', 'smtp_password', 'required');
       
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.validation_errors().'</div>');
             $this->index();

        } else {

           $param = array(
                'smtp_host' => $data['smtp_host_type'].$data['smtp_host'],
                'smtp_port' => $data['smtp_port'],
                'smtp_username' => $data['smtp_username'],
                'smtp_password' => $data['smtp_password'],
           
            );

                $req_dump = print_r($param, TRUE);
                $fp = fopen('requestmail.log', 'a');
                fwrite($fp, $req_dump);
                fclose($fp);



            
             $checksmtp =   Send_Mail_dynamic($param,$this->session->userdata['superadmin']->super_admin_email, array("name" => "Spraye", "email" => $this->session->userdata['superadmin']->super_admin_email),'This mail has been made for smtp credential check, please ignore it','Check SMTP credential');

               $req_dump = print_r($checksmtp, TRUE);
                $fp = fopen('requestmail.log', 'a');
                fwrite($fp, $req_dump);
                fclose($fp);
         

             if ($checksmtp['status']) {
                $param['update_at'] = date("Y-m-d H:i:s");
                $where = array('id' =>$this->session->userdata['superadmin']->id);
                $result = $this->Administratorsuper->updateAdminTbl($where,$param);

                if ($result) {

                  $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SMTP </strong>details updated successfully</div>');
                    redirect("superadmin");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>SMTP </strong>Details not updated. Please try again</div>');
                    redirect("superadmin/setting");
                }                            
             } else {
                  $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.$checksmtp['message'].'<a href="#" data-toggle="modal" data-target="#modal_smtp_info">learn more</a>'.'</div>');
                    redirect("superadmin/setting");
             }


        }
    }



    function getLatLongByAddress($address) {
    // $address = str_replace(", ",",+",$address);
    // $address = str_replace(" ","%",$address);


   $address = urlencode($address);
// 1017%Davis%Boulevard+Sikeston+MO+USA 
// die();

    $geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=".GoogleMapKey."&address={$address}&sensor=false");

    $output= json_decode($geocode);

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

   public function logout()
    {
     $this->session->sess_destroy();
      return redirect('superadmin/auth');
    }

/*//////////////////////// Ajax Code End Here  ///////////// */

}
