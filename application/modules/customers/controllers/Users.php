<?php

//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) { 
            return redirect('customers/auth');
        }
        $this->load->library('parser');
        $this->load->library('aws_sdk');
        $this->load->helper('text');
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
    private function loadModel() {
        $this->load->model("Administrator");
        $this->load->library('form_validation');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
      //  $this->load->model('Adsds', 'As');
       $this->load->model('Company_email_model', 'CompanyEmail'); 
       $this->load->model('Administratorsuper');
       $this->load->model('AdminTbl_company_model', 'CompanyModel');
       $this->load->model('Company_subscription_model', 'CompanySubscription');  
    }
    
   public function index() {

        $page["active_sidebar"] = "mangeUserNav";
        $page["page_name"] = "Users";
        $where = array('role_id !=' => 1,'company_id' => $this->session->userdata['company_id']);
        $data['userdata'] = $this->CustomerModel->get_all_customer($where);
        $page["page_content"] = $this->load->view("customers/user/view_user", $data, TRUE);
        $this->layout->customersTemplateTable($page);
    }

    public function addUser(){
       // $page["active_sidebar"] = "mangeUserNav";
		    $page["page_name"] = "Create Login";
        $page["page_content"] = $this->load->view("customers/user/add_user", '', TRUE);
        $this->layout->customersTemplateTable($page);
    }

// for  exiting comany manage

     public function TestingCompany($value=''){

         $result =    $this->db->select("*,t_company.company_id as company_id ")->join('t_company_subscription','t_company_subscription.company_id=t_company.company_id','left')->get('t_company')->result();
         echo "<pre>";
         // print_r($result);

         foreach ($result as $key => $value) {
            if ($value->subscription_unique_id=='') {

              $param = array(              
                'company_id' =>$value->company_id,
                'subscription_unique_id' =>'ag3bk4g4hq',
                'subscription_created_at' =>date("Y-m-d H:i:s"),
                'is_technician_count' =>500,
                'is_quickbooks_price' =>1,
              );
               $this->CompanySubscription->createCompanySubscription($param);
            }
         }
    }

    public function addUserData(){
        $data = $this->input->post();
      //print_r($_SESSION);
      //var_dump($data);
      //die(print_r($data));
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('first_name', 'first_name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last_name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');
        $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
       //$this->form_validation->set_rules('applicator_number', 'applicator_number', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
           
            $this->addUser();
        } elseif ($result = $this->CustomerModel->getOneCustomer(array('email' =>$data['email']))) {
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> already exists</div>');
          redirect("customers/users/addUser");
        } 
        else {

        //   //   echo "<pre>";
           $company_id =  $this->session->userdata['company_id'];
           $customer_id =  $this->session->userdata['customer_id'];

        //    $check_tech =   $this->checkTechCount($data);

        //    if ($check_tech) {
                 $user_id = md5(json_encode($data).date("Ymdhis"));
                  $param = array(
                      'user_id' => $user_id,
                      'company_id' => $company_id,
                      'first_name' => $data['first_name'],
                      'last_name' => $data['last_name'],
                      'email' => $data['email'],
                      'phone' => $data['phone'],
                      'password' => md5($data['password']),
                      'role_id' => $data['role_id'],                
                      //'applicator_number' => $data['applicator_number'],                
                      'created_at' => Date("Y-m-d H:i:s")
                  );

                  $result = $this->CustomerModel->CreateOneCustomer($param);

                  if ($result) {
                                  
                               switch ($data['role_id']) {
                                  case 2:
                                    $role = "Account Owner";
                                    break;
                                  case 3:
                                    $role = "Account Admin";
                                    break;
                                  case 4:
                                    $role ="Technician";
                                      break;
                                  default:
                                    $role = "No Role";
                                    break;
                              }


                              $email_array = array(
                                  'name' => $data['user_first_name'].' '.$data['user_last_name'],
                                  'email' => $data['email'],
                                  'password' => $data['password'],
                                  'role' => $role,                
                             );

                          $where = array('company_id' => $this->session->userdata['company_id']);

                          $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);
                          $body  = $this->load->view('email/user_email',$email_array,TRUE);
                          $subject =  'New '.$role.' user';

                            $where['is_smtp'] = 1;
                          
                          $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                          
                            if (!$company_email_details) {
                                          
                             $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

				                  //die(print_r($company_email_details));

                          } 
                         
                          $res =   Send_Mail_dynamic($company_email_details,$data['email'],array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, $subject);

                          $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>User </strong>added successfully</div>');
                          redirect("customers/users");
                  } else {
                      $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>User</strong>not added.</div>');
                      redirect("customers/users");
                  }
             
          //  } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Please contact support to upgrade your plan.</div>');
           redirect("customers/users");
             
          //  }
        }
    }

    public function checkTechCount($data){

      $company_id =  $this->session->userdata['company_id'];

       if ($data['role_id']==4) {  

            $where_check = array('company_id'=>$company_id,'role_id'=>4) ;
                             
            if (array_key_exists('user_id', $data)) {
               $where_check['user_id !='] = $data['user_id'];
            }

            $result = $this->Administrator->getNumberOFTechnician($where_check);
       
            $subscription_details =  $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$company_id));

             if ($result->tech_count >= $subscription_details->is_technician_count ) {
                  return FALSE;
                            
             } else {
              return true;
             }        
             
           } else {
            return true;
           } 


    }

    public function editUser($user_id){
      $where = array('user_id' =>$user_id);

     $data['user_details'] =  $this->Administrator->getOneAdmin($where);
     $page["active_sidebar"] = "mangeUserNav";
     $page["page_name"] = "Update User";
     $page["page_content"] = $this->load->view("customers/user/edit_user", $data, TRUE);
        $this->layout->customersTemplateTable($page);
    }

    public function editUserData($user_id) {

     $where = array('user_id' =>$user_id);
 
     $data =  $this->input->post();
     $this->form_validation->set_data($data);
     $this->form_validation->set_rules('user_first_name', 'first_name', 'trim|required');
     $this->form_validation->set_rules('user_last_name', 'last_name', 'trim|required');
     $this->form_validation->set_rules('email', 'email', 'trim|required');
     $this->form_validation->set_rules('phone', 'phone', 'trim|required');
     $this->form_validation->set_rules('password', 'password', 'trim|required');
     $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');
     $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
     //$this->form_validation->set_rules('applicator_number', 'applicator_number', 'trim');
        

    if ($this->form_validation->run() == FALSE){

      echo validation_errors();
  
      die();
  
      $this->editUser($user_id);
  
    } else {

          $checkArray = array(
              'email' => $data['email'],
              'user_id !=' => $user_id,
          );
            
            $check = $this->Administrator->getOneAdmin($checkArray);
            if ($check) {

                $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Email</strong> already exists please try again</div>");          
                  $this->editUser($user_id);
 
            } else {

                $data['user_id'] = $user_id;
                $check_tech  = $this->checkTechCount($data);
            
              if ($check_tech) {

                   $param = array(
                     
                      'user_id' => $user_id,
                      'user_first_name' => $data['user_first_name'],
                      'user_last_name' => $data['user_last_name'],
                      'email' => $data['email'],
                      'phone' => $data['phone'],
                      'role_id' => $data['role_id'],                
                      'applicator_number' => $data['applicator_number'],                
                      'updated_at' => Date("Y-m-d H:i:s")
                 
                 );

                    if ($data['old_password']!=$data['password']) {

                        $param['password']=md5($data['password']);
                     }


                    $result =   $this->Administrator->updateAdminTbl($where,$param);
                     if ($result) {

                    
                       $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                      <strong>User </strong>  updated successfully</div>");
                          redirect("customers/Users");
                    }
                    else
                    {
                                      
                      $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                      <strong>User not updated </strong> Please try again</div>");
                      
                          redirect("customers/Users");

                    
                    }
               
              } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Please contact support to upgrade your plan.</div>');
                redirect("customers/users");
             

               
              }         
            }
    }


    }

    public function deleteUser($user_id) {

        $where = array('user_id' => $user_id);
        $result = $this->Administrator->deleteAdmin($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("customers/Users");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>User </strong>deleted successfully</div>');
            redirect("customers/Users");
        }
    }

    public function updateProfile() {

        $where = array('user_id' =>$this->session->userdata['user_id']);
        $data['user_details'] =  $this->Administrator->getOneAdmin($where);
        $data['user_details']->user_pic = ($data['user_details']->user_pic_resized != '') ? $data['user_details']->user_pic_resized : $data['user_details']->user_pic;        
        $page["active_sidebar"] = "mangeUserNav";
		    $page["page_name"] = "Profile";
        $page["page_content"] = $this->load->view("admin/user/update_profile", $data, TRUE);
        $this->layout->customersTemplateTable($page);

    }

  public function updateProfileData() {
    $where = array('user_id' =>$this->session->userdata['user_id']);
    $data =  $this->input->post();
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('user_first_name', 'first_name', 'trim|required');
    $this->form_validation->set_rules('user_last_name', 'last_name', 'trim|required');
    $this->form_validation->set_rules('email', 'email', 'trim|required');
    $this->form_validation->set_rules('phone', 'phone', 'trim|required');
    $this->form_validation->set_rules('password', 'password', 'trim|required');
    $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');
    if ($this->form_validation->run() == FALSE){
      $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>".validation_errors()."</div>");      
      $this->updateProfile();
    }else{
      $checkArray = array(
        'email' => $data['email'],
        'user_id !=' => $this->session->userdata['user_id'],
      );            
      $check = $this->Administrator->getOneAdmin($checkArray);
      if ($check) {
        $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Email</strong> already exits please try again</div>");          
        $this->updateProfile(); 
      } else {
        $param = array(
          'user_first_name' => $data['user_first_name'],
          'user_last_name' => $data['user_last_name'],
          'applicator_number' => $data['applicator_number'],
          'email' => $data['email'],
          'phone' => $data['phone'],
          'updated_at' => Date("Y-m-d H:i:s")
        );
        if (!empty($_FILES['user_pic']['name'])) {
          $file_name_array  = explode(".", $_FILES['user_pic']['name']);
          $fileext =  end($file_name_array);
          $tmp_name   = $_FILES['user_pic']['tmp_name'];
          $file_name  = $this->session->userdata['user_id'].'_'.date("ymdhis").'.'.$fileext ;          
          $resized_file_name  = $this->session->userdata['user_id'].'_'.date("ymdhis").'_resized.'.$fileext ;          
          $key = '/uploads/profile_image/'.$file_name;
          $res=$this->aws_sdk->saveObject($key,$tmp_name);
          $resized_image = str_replace('data:image/png;base64,','',$data['resized_image']);
          $resized_image = str_replace(' ','+',$resized_image);
          $resized_image = base64_decode($resized_image);
          file_put_contents("uploads/profile_image/".$resized_file_name,$resized_image);
          $resized_image_file = 'uploads/profile_image/'.$resized_file_name;
          $resized_file_key = '/uploads/profile_image/'.$resized_file_name;
          $this->aws_sdk->saveObject($resized_file_key,$resized_image_file);
          unlink('uploads/profile_image/'.$resized_file_name);
          $param['user_pic_resized'] = $resized_file_name;
          $param['user_pic'] = $file_name;
        }
        if ($data['old_password']!=$data['password']) {
          $param['password']=md5($data['password']);
        }
        $result =   $this->Administrator->updateAdminTbl($where,$param);
        if ($result) {
          $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              <strong>User </strong> profile updated successfully</div>");
                  redirect("customers/Users/updateProfile");
        } else {                         
          $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              <strong>User  profile not updated </strong> Please try again</div>");
                  redirect("customers/Users/updateProfile");              
        }
      }
    }
  }
}
