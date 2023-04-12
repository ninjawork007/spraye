<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author satanand
 */
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

class Auth extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->library('form_validation');
        $this->load->model("Administrator");
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('Company_email_model', 'CompanyEmail');  
        $this->load->model('Administratorsuper');
        //$this->load->model('Log_modal', 'LogModal');          
        $this->load->model('Company_subscription_model', 'CompanySubscription');          


        
            if ($this->session->userdata('spraye_technician_login')) {
                //   var_dump($this->session->userdata());   die;       
                return redirect("technician/dashboard");
            }
    }

     public function index() {

       if($this->input->post()) {        

        $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if($this->form_validation->run() == false)
        {
           $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.validation_errors().'</div>');
             $this->load->view('technician/tech_login');
        } else
        {
            $post = $this->input->post();
            $email = $post['email'];
            $password = $post['password'];
              
            $user = $this->Administrator->getOneAdmin(array("email" => $post['email'], "password" => md5($post['password'])));  
            if($user)
            {

                $active_status = $user->is_active;

                if($active_status){

              

                    $compny_details = $this->CompanyModel->getOneCompany(array('company_id'=>$user->company_id));


                    $this->CompanyModel->setLastLogin(date("Y-m-d"), $user->user_id);


                    if ($user->role_id==4) {
                        
                    $this->session->set_userdata('spraye_technician_login',$user);
                    $this->session->set_userdata('compny_details',$compny_details);


                    if( isset($post['remember']) && !empty($post['remember']) ) {
                        setcookie ("loginTechId", $email, time()+ (10 * 365 * 24 * 60 * 60));  
                        setcookie ("loginTechPass", $password,  time()+ (10 * 365 * 24 * 60 * 60));
                        } else {
                        setcookie ("loginTechId",""); 
                        setcookie ("loginTechPass","");
                        } 

                    //    return redirect("technician/dashboard/".$route);
                    return redirect("technician/dashboard/");

                    } else {
            
                        $subscription_details = $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$user->company_id));

                        /* Commented for fix of unassignment of services on login
                        $where = array(
                            'is_job_mode'=>0,
                            'is_complete'=>0,        
                            'company_id'=>$user->company_id,        
                            'job_assign_date <'=>date('Y-m-d'),
                        );
                        $this->db->where($where)->delete('technician_job_assign'); */        

                        $user->type = 'admin';
                        $this->session->set_userdata((array) $user);
                        $this->session->set_userdata('spraye_technician_login',$user);
                        $this->session->set_userdata('compny_details',$compny_details);
                        $this->session->set_userdata('subscription_details',$subscription_details);

                        if( isset($post['remember']) && !empty($post['remember']) ) {
                        setcookie ("loginTechId", $email, time()+ (10 * 365 * 24 * 60 * 60));  
                        setcookie ("loginTechPass", $password,  time()+ (10 * 365 * 24 * 60 * 60));
                        } else {
                        setcookie ("loginTechId",""); 
                        setcookie ("loginTechPass","");
                        } 


                        return redirect("technician/dashboard");
                    }
                }else{
                    $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Your account</strong> is not active. Please contact your admin for further details.</div>');
                    return redirect('technician/auth');
                }

                             
            } else
            {               
                $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> id or password entered is incorrect  Please try again.</div>');
               return redirect('technician/auth');
            }
        }

       } else {
           $this->load->view('technician/tech_login');
       } 
    }

   
    public function forgetPassword() {
        if ($this->input->post()) {
            //getting post request form view
            $post = $this->input->post();
            // getting user details besed on provided email address
            $user = $this->Administrator->getOneAdmin(array("email" => $post['email'],'role_id'=>4));
            // check if email registerd with database
            if ($user) {
                // current date time
                $date = strtotime(Date('Y-m-d H:i:s'));
                // prepare parameter for update password reset link to user
                $param = array(
                    'password_reset_link' => md5(json_encode($user) . Date('Ymdhis')), // set password reset ling based on md5 string.
                    "reset_link_expire" => Date('Y-m-d H:i:s', $date + 86400)// set expired time afeter 24 hours from requrested time.
                );
                //update admin table
                $this->Administrator->updateAdminTbl((array) $user, $param);
                //get user updated record again.
               
                    $data['user_details'] = $this->Administrator->getOneAdmin(array("email" => $post['email'],'role_id'=>4));
                    

                   $where = array('company_id' =>$user->company_id);
                   $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

               
                // getting email body from view
                $body = $this->load->view('technician/email/forgot_password_tech_email', (array) $data, TRUE); 

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
               
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                } 
               
                $res =   Send_Mail_dynamic($company_email_details,$user->email,array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email),  $body, 'Password Reset Link');   
              
                if ($res['status']) {
       
                     $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Password</strong> reset link has been sent to your registered email id.</div>');

                      return redirect('technician/auth');

                    
                } else {
                  
                   $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'. $res['message'].'</div>');
                   return redirect('technician/auth/forgetPassword');

                }
              
            } else {
                 $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> not registerd with us </div>');

                //redired to  same page again with error meassage
                return redirect('technician/auth/forgetPassword');
            }
        } else {
            // load view
            $this->load->view('technician/forgotpasswordTech');
        }
    }

    public function resetPassword($link = false) {
        if ($this->input->post()) {
            $post = $this->input->post();
//            var_dump($post);die;
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');
            $this->form_validation->set_message('matches', 'Password does not match.');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('technician/resetPasswordTech');
            } else {
                $param = array(
                    'password_reset_link' => "", // set password reset ling based on md5 string.
                    "reset_link_expire" => Date('Y-m-d H:i:s'),// set expired time afeter 24 hours from requrested time.
                    "password" => md5($post['password'])// set expired time afeter 24 hours from requrested time.
                );
                // get user details according to link
                $user = $this->Administrator->getOneAdmin(array("password_reset_link" => $link));
                //update admin table
                if ($this->Administrator->updateAdminTbl((array) $user, $param)) {
                    //set success message

                     $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Your</strong> password has been changed successfully. Now you can login with new password.</div>');



                    // redirect to login page
                    return redirect('technician/auth');
                } else {

                     $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Something went wrong, Please try agian.</div>');
                    // set error data
                    // redirect to login page. 
                    return redirect('technician/auth');
                }
            }
        } else {
            if ($link) {
                $user = $this->Administrator->getOneAdmin(array("password_reset_link" => $link));
                if ($user) {
                    $currenttime = strtotime(Date('Y-m-d H:i:s'));
                    $resetlinkDate = strtotime($user->reset_link_expire);
                    if ($resetlinkDate < $currenttime) {
                        show_error("Your password reset link has been expired. Please try to get new one.", 404, "Link has been expired.");
                    } else {
                        $data['user_details'] = $user;
                        $this->load->view('technician/resetPasswordTech',$data);
                    }
                } else {
                    show_error("The page you are looking for does not exists in this domain.", 404, "Invalid link");
                }
            } else {
                show_error("The page you are looking for does not exists in this domain.", 404, "Invalid link");
            }
        }
    }

}
