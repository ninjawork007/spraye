<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

class Admin extends MY_Controller
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
        $this->load->helper('text');
        $this->loadModel();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
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
        $this->load->model('AdminTbl_setting_model', 'SettingModel');
        $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
    }

    public function index()
    {

        $data['need_to_reschedule'] = 0;

        $user_id = $this->session->userdata['user_id'];

        $where = array(
            'jobs.user_id' => $user_id
        );

        $page["active_sidebar"] = "dashboardnav";

        $page["page_name"] = "Dashboard";

        $data['assign_data'] = $this->DashboardModel->getAssignTechnician(array('technician_job_assign.user_id' => $user_id, 'is_job_mode' => 0));
        // 'job_assign_date <= '=>date("Y-m-d")


        $tempdata = $this->DashboardModel->getTableData($where);

        if (!empty($tempdata)) {

            foreach ($tempdata as $key => $value) {
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                );

                $assign_table_data = $this->Tech->GetOneRow($arrayName);
                $tempdata[$key]->mode = '';
                if ($assign_table_data) {

                    if ($assign_table_data->is_job_mode == 2) {
                        $tempdata[$key]->mode = 'Rescheduled';
                        $data['need_to_reschedule']++;
                    } else {
                        unset($tempdata[$key]);
                    }
                } else {

                }
            }
        }
        $data['table_data'] = array_values($tempdata);
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('role_id' => 4));

        $page["page_content"] = $this->load->view("admin/dashboard", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }


    public function tecnicianJobAssign()
    {

        $data = $this->input->post();

        if (!empty($data['group_id'])) {

            $group_id = explode(",", $data['group_id']);

            foreach ($group_id as $value) {
                $datagroup = explode(':', $value);
                $param = array(
                    'technician_id' => $data['technician_id'],
                    'user_id' => $this->session->userdata['user_id'],
                    'customer_id' => $datagroup[0],
                    'job_id' => $datagroup[1],
                    'program_id' => $datagroup[2],
                    'property_id' => $datagroup[3],
                    'job_assign_date' => $data['job_assign_date'],
                    'job_assign_notes' => $data['job_assign_notes'],
                );

                $wherearr = array(
                    'customer_id' => $param['customer_id'],
                    'job_id' => $param['job_id'],
                    'program_id' => $param['program_id'],
                    'is_job_mode' => 2
                );

                $check = $this->Tech->GetOneRow($wherearr);

                if ($check) {
                    $this->Tech->deleteJobAssign($wherearr);
                }

                $result = $this->DashboardModel->CreateOneTecnicianJob($param);

                $emaildata['email_data_details'][] = $this->Tech->getjobTechEmailData(array('customer_id' => $param['customer_id'], 'job_id' => $param['job_id']));

                // $updatearr = array('job_assign_technician' =>1);

                // $this->JobModel->updateJob($wherearr,$updatearr);

            }

            $emaildata['email_tech_details'] = $this->Administrator->getOneAdmin(array('user_id' => $data['technician_id']));
            $emaildata['setting_details'] = $this->SettingModel->getOneSetting();


            $body = $this->load->view('email/tech_email', $emaildata, true);

            $res = Send_Mail($emaildata['email_tech_details']->email, $this->config->item('admin_email'), $body, 'Spraye Job Assign');

            echo 1;

        } else {
            echo 2;
        }


    }

    public function logout()
    {
        $this->session->sess_destroy();
        $actual_link = $_SERVER[REQUEST_URI];
        $_SESSION['iniurl'] = $actual_link;
        return redirect('admin/auth');
    }

    /*//////////////////  Customer Code Section Start Here   /////////////////////////*/

    public function customerList()
    {
        $data['customer'] = $this->CustomerModel->get_all_customer();

        if (!empty($data['customer'])) {
            foreach ($data['customer'] as $key => $value) {
                $data['customer'][$key]->property_id = $this->CustomerModel->getAllproperty(array('customer_id' => $value->customer_id));
                $data['customer'][$key]->program_details = $this->CustomerModel->getAssignProgramscustomer(array('customer_id' => $value->customer_id));
            }
        }
        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Customers";
        $page["page_content"] = $this->load->view("admin/customer_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addCustomer()
    {
        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList();
        $data['propertylist'] = $this->CustomerModel->getPropertyList();
        $data['program_details'] = $this->ProgramModel->get_all_program($where);
        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Add Customer";
        $page["page_content"] = $this->load->view("admin/add_customer", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

    public function addCustomerData()
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('billing_street', 'Billing Street', 'required');
        $this->form_validation->set_rules('billing_street_2', 'Billing Street 2', 'trim');
        $this->form_validation->set_rules('billing_city', 'City', 'required');
        $this->form_validation->set_rules('billing_state', 'State', 'required');
        $this->form_validation->set_rules('billing_zipcode', 'ZipCode', 'required');
        $this->form_validation->set_rules('assign_property[]', 'Assign Property', 'trim');


        if ($this->form_validation->run() == FALSE) {

            $this->addCustomer();
        } else {

            $data = $this->input->post();
            $user_id = $this->session->userdata['user_id'];

            $param = array(
                'user_id' => $user_id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'billing_street' => $data['billing_street'],
                'billing_street_2' => $data['billing_street_2'],
                'billing_city' => $data['billing_city'],
                'billing_state' => $data['billing_state'],
                'billing_zipcode' => $data['billing_zipcode']

            );


            $check = $this->CustomerModel->checkEmail($param['email']);

            if ($check == "true") {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> Email All ready exist.</div>');
                $this->addCustomer();

            } else {


                $result1 = $this->CustomerModel->insert_customer($param);

                $count = 0;
                foreach ($data['assign_property'] as $value) {

                    $param2 = array(
                        'property_id' => $value,
                        'customer_id' => $result1

                    );
                    $result = $this->CustomerModel->assignProperty($param2);

                    $count++;
                }

                if (!empty($data['program_id_array'])) {

                    foreach ($data['program_id_array'] as $value) {
                        $this->CustomerModel->assignProgramscustomer(array('program_id' => $value, 'customer_id' => $result1));
                    }
                }

                if ($result1) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> added successfully</div>');
                    redirect("admin/customerList");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> not added.</div>');
                    redirect("admin/customerList");
                }
            }


        }
    }

    public function editCustomer($customerID = NULL)
    {

        if (!empty($customerID)) {
            $customerID = $customerID;
        } else {
            $customerID = $this->uri->segment(4);
        }
        //print_r($customerID); die();
        $data['customerData'] = $this->CustomerModel->getCustomerDetail($customerID);

        $data['propertylist'] = $this->CustomerModel->getPropertyList();
        $selecteddata = $this->CustomerModel->getSelectedProperty($customerID);

        $data['selectedpropertylist'] = array();
        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedpropertylist'][] = $value->property_id;
            }

        }

        $data['program_details'] = $this->ProgramModel->get_all_program(array('user_id' => $this->session->userdata['user_id']));

        $selectedprogramdata = $this->CustomerModel->getAssignProgramscustomer(array('customer_id' => $customerID));

        $data['selectedprogramlist'] = array();

        if (!empty($selectedprogramdata)) {
            foreach ($selectedprogramdata as $value) {
                $data['selectedprogramlist'][] = $value->program_id;
            }

        }


        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Update Customer";
        $page["page_content"] = $this->load->view("admin/edit_customer", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

    public function updateCustomer()
    {

        $user_id = $this->session->userdata['user_id'];

        $post_data = $this->input->post();
        $customerid = $this->input->post('customer_id');

        //print_r($customerid); die();     

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('billing_street', 'Billing Street', 'required');
        $this->form_validation->set_rules('billing_street_2', 'Billing Street 2', 'trim');
        $this->form_validation->set_rules('billing_city', 'City', 'required');
        $this->form_validation->set_rules('billing_state', 'State', 'required');
        $this->form_validation->set_rules('billing_zipcode', 'ZipCode', 'required');
        $this->form_validation->set_rules('assign_property[]', 'Assign Property', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->editCustomer($customerid);
        } else {
            $post_data = $this->input->post();

            $param = array(
                'user_id' => $user_id,
                'first_name' => $post_data['first_name'],
                'last_name' => $post_data['last_name'],
                'email' => $post_data['email'],
                'phone' => $post_data['phone'],
                'billing_street' => $post_data['billing_street'],

                'billing_street_2' => $post_data['billing_street_2'],
                'billing_city' => $post_data['billing_city'],
                'billing_state' => $post_data['billing_state'],
                'billing_zipcode' => $post_data['billing_zipcode']

            );

            // $where = array();
            // $check = $this->CustomerModel->checkEmailonUpdate($this->input->post('email'), $customerid);

            //  if($check == "true"){

            //     $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> Email-ID All ready exist.</div>');
            //     $this->editCustomer($customerid)

            //  }else{

            //print_r($post_data); die();
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
            $delete = $this->CustomerModel->deleteassignProgramscustomer($where);

            if (!empty($post_data['program_id_array'])) {

                foreach ($post_data['program_id_array'] as $value) {
                    $this->CustomerModel->assignProgramscustomer(array('program_id' => $value, 'customer_id' => $customerid));
                }
            }


            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("admin/customerList");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> updated successfully</div>');
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
    /*///////////////////////  Customer Section End  ////////////////////  */

    /*//////////////////////  Property Section Start ///////////////////   */

    public function propertyList()
    {

        $data['properties'] = $this->PropertyModel->get_all_property();
        if (!empty($data['properties'])) {
            foreach ($data['properties'] as $key => $value) {

                $data['properties'][$key]->customer_id = $this->PropertyModel->getAllcustomer(array('property_id' => $value->property_id));
            }

            foreach ($data['properties'] as $key => $value) {

                $data['properties'][$key]->program_id = $this->PropertyModel->getAllprogram(array('property_id' => $value->property_id));
            }

        }

        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Properties";
        $page["page_content"] = $this->load->view("admin/property_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addProperty()
    {
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList();
        $data['programlist'] = $this->PropertyModel->getProgramList();
        $data['customerlist'] = $this->PropertyModel->getCustomerList();
        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Add Property";
        $page["page_content"] = $this->load->view("admin/add_property", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }


    public function getServiceAreaOption()
    {
        $where = array('user_id' => $this->session->userdata['user_id']);

        $data = $this->ServiceArea->getAllServiceArea($where);

        echo '<option value="">Select Area</option>';

        if ($data) {
            foreach ($data as $key => $value) {
                echo '<option value="' . $value->property_area_cat_id . '">' . $value->category_area_name . '</option>';
            }
        }
    }

    public function addPropertyData()
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('property_title', 'Property Title', 'required');
        $this->form_validation->set_rules('property_address', 'Address', 'required');
        $this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
        $this->form_validation->set_rules('property_city', 'City', 'required');
        $this->form_validation->set_rules('property_state', 'State', 'required');
        $this->form_validation->set_rules('property_zip', 'Zipcode', 'required');
        $this->form_validation->set_rules('property_area', 'Area', 'required');
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
        $this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
        $this->form_validation->set_rules('property_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_program[]', 'Assign Program', 'trim');
        $this->form_validation->set_rules('assign_customer[]', 'Assign Customer', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->addProperty();
        } else {

            $data = $this->input->post();
            $user_id = $this->session->userdata['user_id'];
            $param = array(
                'user_id' => $user_id,
                'property_title' => $data['property_title'],
                'property_address' => $data['property_address'],
                'property_latitude' => $data['property_latitude'],
                'property_longitude' => $data['property_longitude'],
                'property_address_2' => $data['property_address_2'],
                'property_city' => $data['property_city'],
                'property_state' => $data['property_state'],
                'property_zip' => $data['property_zip'],
                'property_area' => $data['property_area'],
                'property_type' => $data['property_type'],
                'yard_square_feet' => $data['yard_square_feet'],
                'property_notes' => $data['property_notes'],
                //'assign_program' => 1,
                //'assign_customer' => 2
            );
            // print_r($param); die();
            $check = $this->PropertyModel->checkProperty($param);

            if ($check == "true") {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong>  Allready exist.</div>');
                $this->addProperty();

            } else {

                $result1 = $this->PropertyModel->insert_property($param);

                $count = 0;
                foreach ($data['assign_customer'] as $value) {

                    $param2 = array(
                        'property_id' => $result1,
                        'customer_id' => $value

                    );
                    $result = $this->PropertyModel->assignCustomer($param2);
                    $count++;
                }

                $count = 0;
                foreach ($data['assign_program'] as $value) {

                    $param3 = array(
                        'property_id' => $result1,
                        'program_id' => $value
                    );

                    $result = $this->PropertyModel->assignProgram($param3);
                    $count++;
                }

                if ($result1) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> added successfully</div>');
                    redirect("admin/propertyList");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> not added.</div>');
                    redirect("admin/propertyList");
                }
            }
        }
    }

    public function editProperty($propertyID = NULL)
    {

        if (!empty($propertyID)) {
            $propertyID = $propertyID;
        } else {
            $propertyID = $this->uri->segment(4);
        }

        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList();
        $data['programlist'] = $this->PropertyModel->getProgramList();
        $data['customerlist'] = $this->PropertyModel->getCustomerList();
        $data['propertyData'] = $this->PropertyModel->getPropertyDetail($propertyID);

        $selecteddata = $this->PropertyModel->getSelectedProgram($propertyID);
        $data['selectedprogramlist'] = array();

        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedprogramlist'][] = $value->program_id;
            }

        }

        $selecteddata1 = $this->PropertyModel->getSelectedCustomer($propertyID);
        $data['selectedcustomerlist'] = array();

        if (!empty($selecteddata1)) {
            foreach ($selecteddata1 as $value) {
                $data['selectedcustomerlist'][] = $value->customer_id;
            }

        }

        //print_r($data['selectedcustomerlist']); die();

        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Update Property";
        $page["page_content"] = $this->load->view("admin/edit_property", $data, TRUE);
        $this->layout->superAdminTemplate($page);

    }

    public function updateProperty()
    {

        $post_data = $this->input->post();
        $property_id = $this->input->post('property_id');

        //print_r($property_id); die();

        $this->form_validation->set_rules('property_title', 'Property Title', 'required');
        $this->form_validation->set_rules('property_address', 'Address', 'required');
        $this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
        $this->form_validation->set_rules('property_city', 'City', 'required');
        $this->form_validation->set_rules('property_state', 'State', 'required');
        $this->form_validation->set_rules('property_zip', 'Zipcode', 'required');
        $this->form_validation->set_rules('property_area', 'Area', 'required');
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
        $this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
        $this->form_validation->set_rules('property_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_program[]', 'Assign Program', 'trim');
        $this->form_validation->set_rules('assign_customer[]', 'Assign Customer', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->addProperty();
        } else {

            $post_data = $this->input->post();

            $param = array(
                'property_title' => $post_data['property_title'],
                'property_address' => $post_data['property_address'],
                'property_latitude' => $post_data['property_latitude'],
                'property_longitude' => $post_data['property_longitude'],
                'property_address_2' => $post_data['property_address_2'],
                'property_city' => $post_data['property_city'],
                'property_state' => $post_data['property_state'],
                'property_zip' => $post_data['property_zip'],
                'property_area' => $post_data['property_area'],
                'property_type' => $post_data['property_type'],
                'yard_square_feet' => $post_data['yard_square_feet'],
                'property_notes' => $post_data['property_notes']
            );


            $result = $this->PropertyModel->updateAdminTbl($property_id, $param);

            $where = array('property_id' => $property_id);
            $delete = $this->PropertyModel->deleteAssignCustomer($where);
            $count = 0;
            foreach ($post_data['assign_customer'] as $value) {

                $param2 = array(
                    'property_id' => $property_id,
                    'customer_id' => $value
                );
                $assigncustomer = $this->PropertyModel->assignCustomer($param2);
                $count++;
            }

            $where1 = array('property_id' => $property_id);
            $delete1 = $this->PropertyModel->deleteAssignProgram($where1);

            $count = 0;
            foreach ($post_data['assign_program'] as $value) {

                $param3 = array(
                    'property_id' => $property_id,
                    'program_id' => $value
                );


                $assignprogram = $this->PropertyModel->assignProgram($param3);
                $count++;
            }


            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("admin/propertyList");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> updated successfully</div>');
                redirect("admin/propertyList");
            }

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

    /*//////////////////////////////  Property Section End  ///////////////////////  */


    /*/////////////////////////////  Programm Section Start //////////////////////   */

    public function programList()
    {

        $data['programData'] = $this->ProgramModel->get_all_program();
        if (!empty($data['programData'])) {
            foreach ($data['programData'] as $key => $value) {

                $data['programData'][$key]->job_id = $this->ProgramModel->getProgramAssignJobs(array('program_id' => $value->program_id));


                $data['programData'][$key]->property_details = $this->ProgramModel->getAllproperty(array('program_id' => $value->program_id));

            }

        }


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Programs";
        $page["page_content"] = $this->load->view("admin/program_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addProgram()
    {

        $where = array('property_tbl.user_id' => $this->session->userdata['user_id']);

        $data['joblist'] = $this->ProgramModel->getJobList();
        $data['propertylist'] = $this->PropertyModel->get_all_property($where);
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList();


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Add Program";
        $page["page_content"] = $this->load->view("admin/add_program", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }


    public function addProgramData()
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job[]', 'Jobs', 'trim');


        if ($this->form_validation->run() == FALSE) {

            $this->addProgram();
        } else {

            $data = $this->input->post();
            $user_id = $this->session->userdata['user_id'];
            $param = array(
                'user_id' => $user_id,
                'program_name' => $data['program_name'],
                'program_price' => $data['program_price'],
                'program_notes' => $data['program_notes']
                //'program_job' => $data['program_job']
            );

            //print_r($param); die();


            $check = $this->ProgramModel->checkProgram($param);

            if ($check == "true") {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> Allready exist.</div>');
                $this->addProgram();

            } else {

                $result = $this->ProgramModel->insert_program($param);

                if (!empty($data['program_job'])) {

                    foreach ($data['program_job'] as $value) {

                        $param2 = array(
                            'job_id' => $value,
                            'program_id' => $result

                        );
                        $result1 = $this->ProgramModel->assignProgramJobs($param2);
                    }
                }

                if (!empty($data['propertylistarray'])) {

                    foreach ($data['propertylistarray'] as $value) {

                        $param3 = array(
                            'program_id' => $result,
                            'property_id' => $value

                        );
                        $result2 = $this->PropertyModel->assignProgram($param3);
                    }
                }

                if ($result) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> added successfully</div>');
                    redirect("admin/programList");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
                    redirect("admin/programList");
                }

            }
        }
    }

    public function editProgram($programID = NULL)
    {

        if (!empty($programID)) {
            $programID = $programID;
        } else {
            $programID = $this->uri->segment(4);
        }

        $where = array('user_id' => $this->session->userdata['user_id']);

        $data['joblist'] = $this->ProgramModel->getJobList();
        $data['propertylist'] = $this->PropertyModel->get_all_property($where);

        $data['programData'] = $this->ProgramModel->getProgramDetail($programID);

        $selecteddata = $this->ProgramModel->getSelectedJobs($programID);

        $selecteddataproperty = $this->ProgramModel->getSelectedProperty($programID);


        $data['selectedjoblist'] = array();
        $data['selectedpropertylist'] = array();

        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedjoblist'][] = $value->job_id;
            }

        }

        if (!empty($selecteddataproperty)) {
            foreach ($selecteddataproperty as $value) {
                $data['selectedpropertylist'][] = $value->property_id;
            }

        }


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Update Program";
        $page["page_content"] = $this->load->view("admin/edit_program", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }


    public function updateProgram()
    {

        $post_data = $this->input->post();
        $program_id = $this->input->post('program_id');

        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job[]', 'Jobs', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->addProgram();
        } else {

            $post_data = $this->input->post();

            $param = array(
                'program_name' => $post_data['program_name'],
                'program_price' => $post_data['program_price'],
                'program_notes' => $post_data['program_notes']
            );

            $result = $this->ProgramModel->updateAdminTbl($program_id, $param);

            $where = array('program_id' => $program_id);
            $delete = $this->ProgramModel->deleteAssignJobs($where);

            if (isset($post_data['program_job'])) {

                foreach ($post_data['program_job'] as $value) {

                    $param2 = array(
                        'job_id' => $value,
                        'program_id' => $program_id
                    );
                    $result1 = $this->ProgramModel->assignProgramJobs($param2);


                }
            }

            $this->PropertyModel->deleteAssignProgram($where);

            if (isset($post_data['propertylistarray'])) {

                foreach ($post_data['propertylistarray'] as $value) {

                    $param3 = array(
                        'property_id' => $value,
                        'program_id' => $program_id

                    );
                    $result2 = $this->PropertyModel->assignProgram($param3);
                }
            }


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
        $result = $this->ProgramModel->deleteProgram($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/programList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong>deleted successfully</div>');
            redirect("admin/programList");
        }
    }

    /*//////////////////////  Programm Section eND /////////////////////////   */

    /*/////////////////////  Product Section Start ////////////////////////   */

    public function productList()
    {

        $data['productData'] = $this->ProductModel->get_all_product();
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

        $data['joblist'] = $this->ProductModel->getJobList();
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
        $this->form_validation->set_rules('product_cost_unit', 'Cost Unit', 'required');
        $this->form_validation->set_rules('formulation', 'Formulation', 'trim');
        $this->form_validation->set_rules('formulation_per', 'Formulation Per Value', 'trim');
        $this->form_validation->set_rules('formulation_per_unit', 'Per Unit', 'required');
        $this->form_validation->set_rules('max_wind_speed', 'Wind Speed', 'required');
        $this->form_validation->set_rules('application_rate', 'Application Rate', 'trim');
        $this->form_validation->set_rules('application_per', 'Application Per', 'trim');
        $this->form_validation->set_rules('temperature_information', 'Temperature', 'trim');
        $this->form_validation->set_rules('temperature_unit', 'Temperature Unit', 'required');
        $this->form_validation->set_rules('product_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_job[]', 'Assign to Job', 'trim');

        if ($this->form_validation->run() == FALSE) {


            $this->addProduct();
        } else {

            //$uID = $userdata['user_id'];

            //print_r($uID);
            $user_id = $this->session->userdata['user_id'];
            $data = $this->input->post();

            $param = array(
                'user_id' => $user_id,
                'product_name' => $data['product_name'],
                'epa_reg_nunber' => $data['epa_reg_nunber'],
                'product_cost' => $data['product_cost'],
                'product_cost_unit' => $data['product_cost_unit'],
                'formulation' => $data['formulation'],
                'formulation_per' => $data['formulation_per'],
                'formulation_per_unit' => $data['formulation_per_unit'],
                'max_wind_speed' => $data['max_wind_speed'],
                'application_rate' => $data['application_rate'],
                'application_per' => $data['application_per'],
                'temperature_information' => $data['temperature_information'],
                'temperature_unit' => $data['temperature_unit'],
                'product_notes' => $data['product_notes']
            );

            $check = $this->ProductModel->checkProduct($param);

            if ($check == "true") {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> Allready exist.</div>');
                $this->addProduct();

            } else {

                $result1 = $this->ProductModel->insert_product($param);


                foreach ($data['active_ingredient'] as $key => $value) {

                    if ($value == "" || $data['percent_active_ingredient'][$key] == "") {

                    } else {

                        $this->ProductModel->insertActiveIngredient(array('product_id' => $result1, 'active_ingredient' => $value, 'percent_active_ingredient' => $data['percent_active_ingredient'][$key]));
                    }
                }


                $count = 0;
                foreach ($data['assign_job'] as $value) {

                    $param2 = array(
                        'job_id' => $value,
                        'product_id' => $result1

                    );
                    $result = $this->ProductModel->assignJobs($param2);

                    $count++;
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
                        'product_name' => $data[0],
                        'epa_reg_nunber' => $data[1],
                        'product_cost' => $data[2],
                        'product_cost_unit' => $data[3],
                        'formulation' => $data[4],
                        'formulation_per' => $data[5],
                        'formulation_per_unit' => $data[6],
                        'max_wind_speed' => $data[7],
                        'application_rate' => $data[8],
                        'application_per' => $data[9],
                        'application_per_unit' => $data[10],
                        'temperature_information' => $data[11],
                        'temperature_unit' => $data[12],

                        'product_notes' => $data[15]
                    );


                    $param2 = array(
                        'active_ingredient' => $data[13],
                        'percent_active_ingredient' => $data[14],
                    );
                    $param = array_filter($param);
                    $param2 = array_filter($param2);

                    if (array_key_exists("product_name", $param) && array_key_exists("product_cost", $param) && array_key_exists("product_cost_unit", $param) && array_key_exists("max_wind_speed", $param) && array_key_exists("active_ingredient", $param2) && array_key_exists("percent_active_ingredient", $param2)) {

                        $check = $this->ProductModel->checkProduct($param);


                        if ($check == "false") {
                            $result = $this->ProductModel->insert_product($param);

                            $param2['product_id'] = $result;

                            $this->ProductModel->insertActiveIngredient($param2);

                        }


                    }
                }
                fclose($handle);

                if (isset($check) && !isset($result)) {
                    echo 0;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> Allready exist.</div>');
                    //echo "already he add nahi";
                } else if (!isset($check) && isset($result)) {
                    echo 1;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> added successfully</div>');
                    //echo "already nahi result he";
                } else if (isset($check) && isset($result)) {
                    echo 3;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Some Product </strong> already exits and some added</div>');
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

        $data['joblist'] = $this->ProductModel->getJobList();
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
        $this->form_validation->set_rules('product_cost', 'Product Cost', 'required');
        $this->form_validation->set_rules('product_cost_unit', 'Cost Unit', 'required');
        $this->form_validation->set_rules('formulation', 'Formulation', 'trim');
        $this->form_validation->set_rules('formulation_per', 'Formulation Per Value', 'trim');
        $this->form_validation->set_rules('formulation_per_unit', 'Per Unit', 'required');
        $this->form_validation->set_rules('max_wind_speed', 'Wind Speed', 'required');
        $this->form_validation->set_rules('application_rate', 'Application Rate', 'trim');
        $this->form_validation->set_rules('application_per', 'Application Per', 'trim');
        $this->form_validation->set_rules('temperature_information', 'Temperature', 'trim');
        $this->form_validation->set_rules('temperature_unit', 'Temperature Unit', 'required');
        $this->form_validation->set_rules('product_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_job[]', 'Assign to Job', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->addProduct();
        } else {

            $post_data = $this->input->post();

            $param = array(
                'product_name' => $post_data['product_name'],
                'epa_reg_nunber' => $post_data['epa_reg_nunber'],
                'product_cost' => $post_data['product_cost'],
                'product_cost_unit' => $post_data['product_cost_unit'],
                'formulation' => $post_data['formulation'],
                'formulation_per' => $post_data['formulation_per'],
                'formulation_per_unit' => $post_data['formulation_per_unit'],
                'max_wind_speed' => $post_data['max_wind_speed'],
                'application_rate' => $post_data['application_rate'],
                'application_per' => $post_data['application_per'],
                'temperature_information' => $post_data['temperature_information'],
                'temperature_unit' => $post_data['temperature_unit'],

                'product_notes' => $post_data['product_notes'],
                'updated_at' => date("Y-m-d H:i:s")
            );

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

            foreach ($post_data['active_ingredient'] as $key => $value) {
                if ($value == "" || $post_data['percent_active_ingredient'][$key] == "") {

                } else {

                    $this->ProductModel->insertActiveIngredient(array('product_id' => $product_id, 'active_ingredient' => $value, 'percent_active_ingredient' => $post_data['percent_active_ingredient'][$key]));
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

        $productData = $this->ProductModel->get_all_product();
        if (!empty($productData)) {

            foreach ($productData as $value) {
                echo '<option value="' . $value->product_id . '">' . $value->product_name . '</option>';
            }
        }
    }

    public function propertyListAjax()
    {

        $propertyData = $this->PropertyModel->get_all_property();
        if (!empty($propertyData)) {

            foreach ($propertyData as $value) {
                echo '<option value="' . $value->property_id . '">' . $value->property_title . '</option>';
            }
        }
    }

    public function customerListAjax()
    {

        $customerData = $this->CustomerModel->get_all_customer();
        if (!empty($customerData)) {

            foreach ($customerData as $value) {
                echo '<option value="' . $value->customer_id . '">' . $value->first_name . '' . $value->last_name . '</option>';
            }
        }
    }

    public function programListAjax()
    {

        $programData = $this->ProgramModel->get_all_program();
        if (!empty($programData)) {

            foreach ($programData as $value) {
                echo '<option value="' . $value->program_id . '">' . $value->program_name . '</option>';
            }
        }
    }

    /*//////////////////////// Ajax Code End Here  ///////////// */

}
