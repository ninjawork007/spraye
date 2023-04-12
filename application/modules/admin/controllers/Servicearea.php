<?php

//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

class Servicearea extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            $actual_link = $_SERVER[REQUEST_URI];
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
        $this->load->model('AdminTbl_setting_model', 'SettingModel');
        $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
    }
   

    public function index() {
        $page["active_sidebar"] = "serviceAreaNav";
        $page["page_name"] = "Service Areas";
        $data['area_details'] = $this->ServiceArea->getAllServiceArea();
        $page["page_content"] = $this->load->view("admin/servicearea/view_service_area",$data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addServicrArea()
    {
          $page["active_sidebar"] = "serviceAreaNav";
          $page["page_name"] = " Add Service Area";
          $page["page_content"] = $this->load->view("admin/servicearea/add_service_area",'', TRUE);
          $this->layout->superAdminTemplateTable($page);
    }

    public function addServicrAreaData() {
        $data = $this->input->post();
		//var_dump($data);
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('category_area_name', 'category_area_name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
           
            $this->addServicrArea();
        } 
			else {
        
            $param = array(
                'category_area_name' => $data['category_area_name'],
                'category_created' => Date("Y-m-d H:i:s")
            );

            $result = $this->ServiceArea->CreateOneServiceArea($param);


            if ($result) {
                    
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service area </strong>added successfully</div>');
                redirect("admin/servicearea");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service area</strong>not added.</div>');
                redirect("admin/servicearea");
            }
        }

    }


    public function editServiceArea($property_area_cat_id)
    {
       $where = array('property_area_cat_id' =>$property_area_cat_id);
        $data['area_details'] =  $this->ServiceArea->getOneServiceArea($where);
  
        $page["active_sidebar"] = "serviceAreaNav";
        $page["page_name"] = " Update Service Area";
        $page["page_content"] = $this->load->view("admin/servicearea/edit_service_area", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function editServiceAreaData($property_area_cat_id) {

     $where = array('property_area_cat_id' =>$property_area_cat_id);
 
     $data =  $this->input->post();
     $this->form_validation->set_data($data);
     $this->form_validation->set_rules('category_area_name', 'category_area_name', 'trim|required');

	    if ($this->form_validation->run() == FALSE){

	        $this->editServiceArea($property_area_cat_id);
	    }else {
	 
	             $param = array(
	                'category_area_name' => $data['category_area_name'],   
	                'category_update' => Date("Y-m-d H:i:s")
	           
	           );

	           $result =   $this->ServiceArea->updateServiceArea($where,$param);
	  
	            if ($result) {
	                 $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	                <strong>Service area </strong>  updated successfully</div>");
	                    redirect("admin/servicearea");
	            } else {
	                                
	                $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	                <strong>Service area not updated </strong> Please try again</div>");
	                
                    redirect("admin/servicearea");	              
	            }            
	    }
    }





    public function deleteServiceArea($property_area_cat_id) {

        $where = array('property_area_cat_id' => $property_area_cat_id);
        $result = $this->ServiceArea->deleteServiceArea($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/servicearea");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service area </strong>deleted successfully</div>');
            redirect("admin/servicearea");
        }
    }


    public function updateProfile() {

      $where = array('user_id' =>$this->session->userdata['user_id']);

     $data['user_details'] =  $this->Administrator->getOneAdmin($where);
        $page["active_sidebar"] = "mangeUserNav";
		$page["page_name"] = "Profile";
        $page["page_content"] = $this->load->view("admin/user/update_profile", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);

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

      echo validation_errors();
      die();
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
                'email' => $data['email'],
                'phone' => $data['phone'],
                'updated_at' => Date("Y-m-d H:i:s")
           
           );


              if (!empty($_FILES['user_pic']['name'])) {
             $file_name_array  = explode(".", $_FILES['user_pic']['name']);

             $fileext =  end($file_name_array);

                 $tmp_name   = $_FILES['user_pic']['tmp_name'];
                 $file_name  = $this->session->userdata['user_id'].'.'.$fileext ;
                 $res =   move_uploaded_file($tmp_name,"uploads/profile_image/".$file_name); 
                $param['user_pic'] = $file_name;
              }


              if ($data['old_password']!=$data['password']) {
                  $param['password']=md5($data['password']);
                }


              $result =   $this->Administrator->updateAdminTbl($where,$param);
               if ($result) {
                 $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>User </strong> profile updated successfully</div>");
                    redirect("admin/Users/updateProfile");
              }
              else
              {                                
                $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>User  profile not updated </strong> Please try again</div>");
                    redirect("admin/Users/updateProfile");

              
              }

            }
      }


    }



    public function test() {

      $geocode =  file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=22.7032903&lon=75.88462040000002&appid=9914c8e12b1d6a30eb4a6207a13ac4dd');
         $output= json_decode($geocode);
     return   $output->wind->speed;

    }
   

}
