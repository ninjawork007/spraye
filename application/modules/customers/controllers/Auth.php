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

class Auth extends MY_Controller{

    //put your code here
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->library('form_validation');
        $this->load->model("Administrator");
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('Administratorsuper');
        $this->load->model('Company_subscription_model', 'CompanySubscription');
        $this->load->model('Log_modal', 'LogModal');          

		
		if ($this->session->userdata('spraye_technician_login')) {
			return redirect("admin");
		}

        if ($this->session->userdata('email')) {
            //  var_dump($this->session->userdata());   
            //  die;       
            $customer_id = $_SESSION['customer_id'];
            return redirect("customers/dashboard/$customer_id");
        }
    }

    public function index(){
        $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('customers/customers_login');
        } else {
            $post = $this->input->post();
            $email = $post['email'];
            $password = $post['password'];

            ##### NON-HASHED PASSWORD #####
            $user = $this->CustomerModel->getOneCustomerSlug(array("email" => $email, "slug" => $this->session->userdata('slug'), "password" => md5($post['password']) ));

            ##### CHECK IF CUSTOMER HAS AN ACCOUNT THEN CHECK FOR HASHED/SET PASSWORD #####
            if ($user){
               
                    $user->type = 'customers';
                    $this->session->set_userdata((array) $user);
                    $compny_details = $this->CompanyModel->getOneCompany(array('company_id' => $user->company_id));
                    $subscription_details = $this->CompanySubscription->getOneCompanySubscription(array('company_id' => $user->company_id));
                    $this->session->set_userdata('compny_details',$compny_details);
                    $this->session->set_userdata('subscription_details',$subscription_details);
                     // Get the Global 'IS_TEXT_MESSAGE' variable
                    $this->db->select('is_text_message');
                    $this->db->from('t_company');
                    $this->db->where('company_id',$user->company_id);
                    $row = $this->db->get()->row();
                    if (isset($row)) {
                        $this->session->set_userdata('is_text_message',$row->is_text_message);
                    } else {
                        $this->session->set_userdata('is_text_message', 0);
                    }
                    if (isset($post['remember']) && !empty($post['remember'])) {
                        setcookie("loginId", $email, time() + (10 * 365 * 24 * 60 * 60));
                        setcookie("loginPass", $password,  time() + (10 * 365 * 24 * 60 * 60));
                    } else {
                        setcookie("loginId", "");
                        setcookie("loginPass", "");
                    }
				
					$_SESSION['customer_id'] = $user->customer_id;

                    return redirect("customers/dashboard/$user->customer_id");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer email or password</strong> entered was not found </br> Please try again .</div>');
                return redirect('welcome/'.$this->session->userdata('slug'));
            }
        }
    }

    public function createPasswordCustomers(){
        if ($this->input->post()) {
            //getting post request form view
            $post = $this->input->post();
            ###### GETTING USER DETAILS BASED ON PROVIDED EMAIL ADDRESS ##### 

            $user_slug = $this->CustomerModel->getOneCustomerSlug(array("email" => $post['email'], "slug" => $this->session->userdata('slug') ));

            // check if email registerd with database
            if (!empty($user_slug)){
				$user = $this->CustomerModel->getOneCustomer(array("email" => $post['email'], "company_id" => $user_slug->company_id ));
                // current date time
                $date = strtotime(date('Y-m-d H:i:s'));
                // prepare parameter for update password reset link to user
                $param = array(
                    'password_reset_link' => md5(json_encode($user) . date('Ymdhis')), // set password reset ling based on md5 string.
                    // "reset_link_expire" => Date('Y-m-d H:i:s', $date + 86400) // set expired time afeter 24 hours from requrested time.
                    "reset_link_expire" => date('Y-m-d H:i:s', strtotime( '+1 day'))// set expired time afeter 24 hours from requrested time.
                );
                ##### UPDATE CUSTOMER TABLE #####
                $this->CustomerModel->updateCustomersTbl(array('customer_id'=>$user->customer_id), $param);
                //get user updated record again.

                $data['user_details'] = $this->CustomerModel->getOneCustomer(array("email" => $post['email'],  "company_id" => $user_slug->company_id ));

                $where = array('company_id' => $user->company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

                // getting email body from view
                $body = $this->load->view('customers/email/create_password_email', (array) $data, TRUE);

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }

                $res = Send_Mail_dynamic($company_email_details, $user->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email),  $body, 'Create Password Link');

                if ($res['status']) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Password</strong> reset link has been sent to your registered email id.</div>');

                    return redirect('welcome/'.$this->session->userdata('slug'));
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . $res['message'] . '</div>');
                    return redirect('customers/auth/createPasswordCustomers');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> not registerd with us </div>');

                //redirect to same page again with error meassage
                return redirect('customers/auth/createPasswordCustomers');
            }
        } else {
            // load view
            $this->load->view('customers/createPasswordCustomers');
        }
    }

    public function forgotpasswordCustomers(){
        if ($this->input->post()) {
            //getting post request form view
            $post = $this->input->post();
			 
            ###### GETTING USER DETAILS BASED ON PROVIDED EMAIL ADDRESS ##### 
            $user_slug = $this->CustomerModel->getOneCustomerSlug(array("email" => $post['email'], "slug" => $this->session->userdata('slug') ));
                        
            // check if email registerd with database
            if (!empty($user_slug)) {
				$user = $this->CustomerModel->getOneCustomer(array("email" => $post['email'], "company_id" => $user_slug->company_id ));
                // current date time
                $date = strtotime(date('Y-m-d H:i:s'));
                // prepare parameter for update password reset link to user
                $param = array(
                    'password_reset_link' => md5(json_encode($user) . date('Ymdhis')), // set password reset ling based on md5 string.
                    "reset_link_expire" => date('Y-m-d H:i:s', strtotime( '+1 day')) // set expired time afeter 24 hours from requrested time.
                );
                ##### UPDATE CUSTOMER TABLE #####
                $this->CustomerModel->updateCustomersTbl(array('customer_id'=> $user->customer_id), $param);
                //get user updated record again.

                $data['user_details'] = $this->CustomerModel->getOneCustomer(array("email" => $post['email'],  "company_id" => $user_slug->company_id ));

                $where = array('company_id' => $user_slug->company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

                // getting email body from view
                $body = $this->load->view('customers/email/forgot_password_email', (array) $data, TRUE);

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                if (!$company_email_details) {

                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }

                $res =   Send_Mail_dynamic($company_email_details, $user->email, array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email),  $body, 'Password Reset Link');

                if ($res['status']) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Password</strong> reset link has been sent to your registered email id.</div>');

                    return redirect('welcome/'.$this->session->userdata('slug'));
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">' . $res['message'] . '</div>');
                    return redirect('customers/auth/forgotpasswordCustomers');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> not registerd with us </div>');

                //redired to  same page again with error meassage
                return redirect('customers/auth/forgotPasswordCustomers');
            }
        } else {
            // load view
            $this->load->view('customers/forgotpasswordCustomers');
        }
    }

    public function resetPasswordCustomers($link = false){
        if ($this->input->post()) {
            $post = $this->input->post();
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');
            $this->form_validation->set_message('matches', 'Password does not match.');
            if ($this->form_validation->run() == false) {
                $this->load->view('customers/resetPasswordCustomers');
            } else {
                $param = array(
                    'password_reset_link' => "", // set password reset ling based on md5 string.
                    "reset_link_expire" => date('Y-m-d H:i:s', strtotime( '+1 day')), // set expired time afeter 24 hours from requrested time.
                    "password" => md5($post['password']) // set expired time afeter 24 hours from requrested time.
                );
                ##### GET USER DETAILS ACCORDING TO LINK #####
                $user = $this->CustomerModel->getOneCustomer(array("password_reset_link" => $link));
                
                ##### UPDATE ADMIN TABLE ##### 
                if ( $this->CustomerModel->updateCustomersTbl(array('customer_id'=> $user->customer_id), $param)) {

                    $var_last_query = $this->db->last_query ();
                    //set success message

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Your</strong> password has been changed successfully. Now you can login with new password.</div>');

                    // redirect to login page
                    return redirect('welcome/'.$this->session->userdata('slug'));
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went wrong, Please try agian.</div>');
                    // set error data
                    // redirect to login page. 
                    return redirect('welcome/'.$this->session->userdata('slug'));
                }
            }
        } else {
            if ($link) {
                $user = $this->CustomerModel->getOneCustomer(array("password_reset_link" => $link));

                if ($user) {
                    $currenttime = strtotime(date('Y-m-d H:i:s'));
                    $resetlinkDate = strtotime($user->reset_link_expire);
                    if ($resetlinkDate < $currenttime) {
                        show_error("Your password reset link has been expired. Please try to get new one.", 404, "Link has been expired.");
                    } else {
                        
                        $data['user_details'] = $user;
                       
                        $this->load->view('customers/resetPasswordCustomers', $data);
                    }
                } else {
                    show_error("The page you are looking for does not exists in this domain.", 404, "Invalid link");
                }
            } else {
                show_error("The page you are looking for does not exists in this domain.", 404, "Invalid link");
            }
        }
    }
    public function createPassword($link = false){
        if ($this->input->post()) {
            $post = $this->input->post();

            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');
            $this->form_validation->set_message('matches', 'Password does not match.');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('customers/createPassword');
            } else {
                $param = array(
                    'password_reset_link' => "", // set password reset ling based on md5 string.
                    "reset_link_expire" => date('Y-m-d H:i:s', strtotime( '+1 day')), // set expired time afeter 24 hours from requrested time.
                    "password" => md5($post['password']) // set expired time afeter 24 hours from requrested time.
                );

                ##### GET USER DETAILS ACCORDING TO LINK #####
                $user = $this->CustomerModel->getOneCustomer(array("password_reset_link" => $link));
                $user = $this->CustomerModel->getOneCustomerSlug(array("email" => $email, "slug" => $this->session->userdata('slug'), "password" => md5($post['password']) ));
                
                ##### UPDATE ADMIN TABLE ##### 
                if ( $this->CustomerModel->updateCustomersTbl(array('customer_id'=> $user->customer_id), $param)) {

                    $var_last_query = $this->db->last_query ();

                    //set success message

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Your</strong> password has been changed successfully. Now you can login with new password.</div>');

                    // redirect to login page
                    return redirect('welcome/'.$this->session->userdata('slug'));
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went wrong, Please try agian.</div>');
                    // set error data
                    // redirect to login page. 
                    return redirect('welcome/'.$this->session->userdata('slug'));
                }
            }
        } else {
            if ($link) {
                $user = $this->CustomerModel->getOneCustomer(array("password_reset_link" => $link));
                if ($user) {
                    $currenttime = strtotime(date('Y-m-d H:i:s'));
                    $resetlinkDate = strtotime($user->reset_link_expire);
                    if ($resetlinkDate < $currenttime) {
                        show_error("Your password reset link has been expired. Please try to get new one.", 404, "Link has been expired.");
                    } else {
                        $data['user_details'] = $user;
                        $this->load->view('customers/createPassword', $data);
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
