<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Job extends MY_Controller
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
        $this->load->library('form_validation');
        $this->load->model('AdminTbl_product_model', 'ProductModel');
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('Job_product_model', 'JobAssginProduct');
        $this->load->model('AdminTbl_program_model', 'ProgramModel');
		$this->load->model('AdminTbl_property_model', 'PropertyModel');
		$this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
		$this->load->model('AdminTbl_customer_model', 'CustomerModel');
		$this->load->model('Invoice_model','INV'); 
		$this->load->model('Property_sales_tax_model', 'PropertySalesTax');
		$this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
		$this->load->model('AdminTbl_company_model', 'CompanyModel');
		$this->load->model('Commissions_model', 'CommissionModel');
		$this->load->model('Bonuses_model', 'BonusModel');
		$this->load->model('Service_type_model', 'ServiceTypeModel');
    }

    public function index()
    {
        $data = array();
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['job_details'] = $this->JobModel->getAllJob(array('jobs.company_id' => $this->session->userdata['company_id']));
        // die(print_r($data['job_details']));
        if (!empty($data['job_details'])) {

            foreach ($data['job_details'] as $key => $value) {

                $where['job_id'] = $value->job_id;

                $data['job_details'][$key]->product_id = $this->JobAssginProduct->getAllJobProduct($where);

                $data['job_details'][$key]->program_details = $this->ProgramModel->getJobAssignPrograms($where);
            }

        }
        // die(print_r($data));
        $page["active_sidebar"] = "jobNav";
        $page["page_name"] = 'Services';
        $page["page_content"] = $this->load->view("admin/job/job_view", $data, true);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addJob()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $data['product_details'] = $this->ProductModel->get_all_product($where);
        $data['program_details'] = $this->ProgramModel->get_all_program($where);
        $data['service_types'] = $this->ServiceTypeModel->getAllServiceType($where);
        $data['commission_types'] = $this->CommissionModel->getAllCommission($where);
        $data['bonus_types'] = $this->BonusModel->getAllBonus($where);
		$data['company_details'] = $this->CompanyModel->getOneCompany($where);
        $page["active_sidebar"] = "jobNav";
        $page["page_name"] = 'Add Service';
        $page["page_content"] = $this->load->view("admin/job/add_job", $data, true);
        $this->layout->superAdminTemplate($page);
    }

    public function addJobData()
    {
        $data = $this->input->post();
        $this->form_validation->set_rules('job_name', 'job_name', 'required');
        $this->form_validation->set_rules('job_notes', 'job_notes', 'trim');
        $this->form_validation->set_rules('job_price', 'Job Price', 'trim');
        if ($this->form_validation->run() == false) {
            //echo 'test';die;
            $this->addJob();
        } else {

            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'job_name' => $data['job_name'],
                'job_price' => $data['job_price'],
                'job_description' => $data['job_description'],
                'job_notes' => $data['job_notes'],
				'base_fee_override' => $data['base_fee_override'],
 				'min_fee_override' => $data['min_fee_override'],
 				'service_type_id' => $data['service_type_id'],
 				'commission_type' => $data['commission_type'],
 				'bonus_type' => $data['bonus_type'],
            );
            if(isset($data['base_fee_override']) && !empty($data['base_fee_override'])){
				$param['base_fee_override'] = $data['base_fee_override'];
			}elseif(isset($data['base_fee_override']) && $data['base_fee_override'] != ""){
				$param['base_fee_override'] = 0.00;
			}else{
				$param['base_fee_override'] = NULL;
			}

			if(isset($data['min_fee_override']) && !empty($data['min_fee_override'])){
				$param['min_fee_override'] = $data['min_fee_override'];
			}elseif(isset($data['min_fee_override']) && $data['min_fee_override'] != ""){
				$param['min_fee_override'] = 0.00;
			}else{
				$param['min_fee_override'] = NULL;
			}

            //print_r($param); die();

            $result = $this->JobModel->CreateOneJob($param);
            //echo $this->db->last_query();die;
            if ($result) {

                if (!empty($data['product_id_array'])) {

                    foreach ($data['product_id_array'] as $value) {
                        $this->JobAssginProduct->CreateOneJobProduct(array('job_id' => $result, 'product_id' => $value));
                    }
                }

                if (!empty($data['program_id_array'])) {

                    foreach ($data['program_id_array'] as $value) {

                        $priority = $this->ProgramModel->checkPriority(array('program_id' => $value, 'job_id' => $result));
                        if ($priority['priorityExist'] === false) {
                            $this->ProgramModel->assignProgramJobs(array('program_id' => $value, 'job_id' => $result, 'priority' => $priority['priority']));
                        }
                    }
                }

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> added successfully</div>');
                redirect("admin/job");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> not added.</div>');
                redirect("job/addJob");
            }
        }
    }
	public function addJobToProperty(){
		  $post = $this->input->post();
		  $error = 0;
		  if(is_array($post['post'])){
			  $property = $post['post'];
			  //die(print_r($data));
			  foreach($property as $key=>$data){
				  $user_id = $this->session->userdata['user_id'];
				  $company_id = $this->session->userdata['company_id'];
				  $setting_details = $this->CompanyModel->getOneCompany(array('company_id'=>$company_id));
				//create program
				 $param = array(
						'user_id' => $user_id,
						'company_id' => $company_id,
						'program_name' => $data['program_name'],
						'program_price' => $data['program_price'],
						'ad_hoc' => 1,
					);          

				//Create Program
				$program_id = $this->ProgramModel->insert_program($param);

				//Assign job to program
				$param2 = array(
				  'program_id' => $program_id,
				  'job_id' => $data['service_id'],
				  'priority' =>1
				); 

				$result1 = $this->ProgramModel->assignProgramJobs($param2); 

				//assign program to property
				 $param3 = array(
					  'program_id' => $program_id,
					  'property_id' => $data['property_id'],
					  'price_override' => $data['price_override'],
					  'is_price_override_set' => $data['is_price_override_set']  
				 );

				 $property_program_id = $this->PropertyModel->assignProgram($param3); 
				  
				  if ($property_program_id) {
				  // if program price = one time invoice then create invoice 
					  if ($data['program_price']==1){
						  //get customer info
						  $customer_property_details  = $this->CustomerModel->getAllproperty(array('customer_property_assign.property_id'=>$data['property_id']));
						  
						  if($customer_property_details){
							  $customer_id = $customer_property_details[0]->customer_id;
							  $yard_sq_ft = $customer_property_details[0]->yard_square_feet;
						  
							  //get job cost
							  if (isset($data['is_price_override_set']) && $data['is_price_override_set'] == 1  ) {
								$cost =  $data['price_override'];
							  } else {
								 //else no price overrides, then calculate job cost
								$job_details = $this->JobModel->getOneJob(array('job_id' => $data['service_id']));
								  
								$job_price = $job_details->job_price;
								  
								//get property difficulty level
								if(isset($customer_property_details[0]->difficulty_level) && $customer_property_details[0]->difficulty_level == 2){
									$difficulty_multiplier = $setting_details->dlmult_2;
								}elseif(isset($customer_property_details[0]->difficulty_level) && $customer_property_details[0]->difficulty_level == 3){
									$difficulty_multiplier = $setting_details->dlmult_3;
								}else{
									$difficulty_multiplier = $setting_details->dlmult_1;
								}

								//get base fee 
								if(isset($job_details->base_fee_override)){
									$base_fee = $job_details->base_fee_override;
								}else{
									$base_fee = $setting_details->base_service_fee;
								}

								$cost_per_sqf = $base_fee + ($job_price * $yard_sq_ft * $difficulty_multiplier)/1000;

								//get min. service fee
								if(isset($job_details->min_fee_override)){
									$min_fee = $job_details->min_fee_override;
								}else{
									$min_fee = $setting_details->minimum_service_fee;
								}

								// Compare cost per sf with min service fee
								if($cost_per_sqf > $min_fee){
									$cost = $cost_per_sqf;
								}else{
									$cost = $min_fee;
								}
								
							  }			  
						 
							  //create invoice 
							  $invParam =  array(                                
									'customer_id' => $customer_id,         
									'property_id' => $data['property_id'],
									'program_id'=> $program_id,
									'job_id'=>$data['service_id'],
									'user_id' => $user_id,
									'company_id' => $company_id,
									'invoice_date' => date("Y-m-d"),
									'description' => $data['program_name'],
									'cost' => ($cost),
									'is_created' => 2,
									'invoice_created'=> date("Y-m-d H:i:s"),
								 );	
								//create invoice 
								$invoice_id = $this->INV->createOneInvoice($invParam);
							  
							   // figure sales tax
							   if($invoice_id) {
								   
                                  if (isset($setting_details) && $setting_details->is_sales_tax==1){
                                     $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id'=>$data['property_id']));
                                        if($property_assign_tax) {
                                            foreach ($property_assign_tax as  $tax_details) {
                                                $invoice_tax_details =  array(
                                                        'invoice_id' => $invoice_id,
                                                        'tax_name' => $tax_details['tax_name'],
                                                        'tax_value' => $tax_details['tax_value'],                   
                                                        'tax_amount' => $cost*$tax_details['tax_value']/100
                                                      );
  
                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }
                                          
                                         }
                                 }

							  //store in property program job invoice table
								$newPPJOBINV = array(
									'customer_id' => $customer_id,
									'property_id' => $data['property_id'],
									'program_id' => $program_id,
									'property_program_id' => $property_program_id,
									'job_id'=>  $data['service_id'],
									'invoice_id'=> $invoice_id,
									'job_cost'=> $cost,
									'created_at'=> date("Y-m-d"),
									'updated_at'=> date("Y-m-d"),
								);

								$PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
							}
						 }
					  }
				  
				}else{
					 $error = 1;
				 }
			  }
		  }else{
			  $error = 1;
		  }
          
	
		if (isset($error) && $error == 1) {
			$post['status'] = "error";
		} else {
			$post['status'] = "success";
		}
		
		echo json_encode($post);
	}

    public function jobDelete($job_id)
    {

        $where = array('job_id' => $job_id);

        $this->priorityManage($job_id);

        $result = $this->JobModel->deleteJob($where);
        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/job");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong>deleted successfully</div>');
            redirect("admin/job");
        }
    }

    public function editJob($job_id)
    {

        $where = array('job_id' => $job_id);
        $data['product_details'] = $this->ProductModel->get_all_product(array('company_id' => $this->session->userdata['company_id']));
        $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' => $this->session->userdata['company_id']));
		$data['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
        $data['service_types'] = $this->ServiceTypeModel->getAllServiceType(array('company_id' => $this->session->userdata['company_id']));
        $data['commission_types'] = $this->CommissionModel->getAllCommission(array('company_id' => $this->session->userdata['company_id']));
        $data['bonus_types'] = $this->BonusModel->getAllBonus(array('company_id' => $this->session->userdata['company_id']));
        $data['job_details'] = $this->JobModel->getOneJob($where);
        // die(print_r($data['job_details']));
        $data['assign_product'] = $this->JobAssginProduct->getAllJobProduct($where);

        $selecteddata = $this->JobModel->getSelectedProduct($job_id);
        $selectedprogramdata = $this->ProgramModel->getSelectedProgram($job_id);
        $data['selectedproductlist'] = array();
        $data['selectedprogramlist'] = array();

        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedproductlist'][] = $value->product_id;
            }

        }

        if (!empty($selectedprogramdata)) {
            foreach ($selectedprogramdata as $value) {
                $data['selectedprogramlist'][] = $value->program_id;
            }

        }

        //print_r($data['selectedjoblist']); die();

        $page["active_sidebar"] = "jobNav";
        $page["page_name"] = 'Update Service';
        $page["page_content"] = $this->load->view("admin/job/edit_job", $data, true);
        $this->layout->superAdminTemplate($page);
    }

    public function testing($value = '')
    {

        $result = $this->db->group_by('program_id')->get('program_job_assign')->result();

        foreach ($result as $key => $value) {
            $secondDetails = $this->db->where('program_id', $value->program_id)->order_by('priority')->get('program_job_assign')->result();

            if ($secondDetails) {
                $program_job_id = array_column($secondDetails, 'program_job_id');

                $n = 1;
                foreach ($program_job_id as $key2 => $value2) {
                    $this->ProgramModel->updatePriority($value2, array('priority' => $n));
                    $n++;
                }

            }
        }

    }

    public function priorityManageForEditCase($job_id, $program_id) {
        
        $where = array('job_id' => $job_id, 'program_id' => $program_id);
        $this->ProgramModel->deleteAssignJobs($where);
        $jobAssignedProgramsByPriority = $this->ProgramModel->getJobAssignProgramsByPriority(array('program_job_assign.program_id' => $program_id));
        if ($jobAssignedProgramsByPriority) {
            $program_job_id = array_column($jobAssignedProgramsByPriority, 'program_job_id');
            $n = 1;
            foreach ($program_job_id as $key2 => $value2) {
                $this->ProgramModel->updatePriority($value2, array('priority' => $n));
                $n++;
            }
        }
    }

    public function priorityManage($job_id)
    {

        $programDetails = $this->ProgramModel->getJobAssignPrograms(array('job_id' => $job_id));

        if ($programDetails) {

            foreach ($programDetails as $key => $value) {

                $where = array('job_id' => $job_id, 'program_id' => $value->program_id);

                $this->ProgramModel->deleteAssignJobs($where);

                $secondDetails = $this->ProgramModel->getJobAssignProgramsByPriority(array('program_job_assign.program_id' => $value->program_id));

                if ($secondDetails) {
                    $program_job_id = array_column($secondDetails, 'program_job_id');

                    $n = 1;
                    foreach ($program_job_id as $key2 => $value2) {
                        $this->ProgramModel->updatePriority($value2, array('priority' => $n));
                        $n++;
                    }

                }
            }
        }

    }

    public function updateJob()
    {

        $post_data = $this->input->post();        

        $job_id = $this->input->post('job_id');
        

        $this->form_validation->set_rules('job_name', 'job_name', 'required');
        $this->form_validation->set_rules('job_notes', 'job_notes', 'trim');
        $this->form_validation->set_rules('job_price', 'Job Price', 'trim');
        $this->form_validation->set_rules('product_id_array[]', 'Assign Products', 'trim');
        if ($this->form_validation->run() == false) {
            //echo 'test';die;
            $this->editJob($job_id);
        } else {

            $post_data = $this->input->post();

            // Remove any commas to make number numeric
            $post_data['job_price'] = str_replace(',', '', $post_data['job_price']);

            $param = array(
                'job_name' => $post_data['job_name'],
                'job_price' => $post_data['job_price'],
                'job_description' => $post_data['job_description'],
                'job_notes' => $post_data['job_notes'],
                'updated_at' => date("Y-m-d H:i:s"),
                'service_type_id' => $post_data['service_type_id'],
 				'commission_type' => $post_data['commission_type'],
 				'bonus_type' => $post_data['bonus_type'],

            );
			
			if(isset($post_data['base_fee_override']) && !empty($post_data['base_fee_override'])){
				$param['base_fee_override'] = $post_data['base_fee_override'];
			}elseif(isset($post_data['base_fee_override']) && $post_data['base_fee_override'] != ""){
				$param['base_fee_override'] = 0.00;
			}else{
				$param['base_fee_override'] = NULL;
			}

			if(isset($post_data['min_fee_override']) && !empty($post_data['min_fee_override'])){
				$param['min_fee_override'] = $post_data['min_fee_override'];
			}elseif(isset($post_data['min_fee_override']) && $post_data['min_fee_override'] != ""){
				$param['min_fee_override'] = 0.00;
			}else{
				$param['min_fee_override'] = NULL;
			}

            $result = $this->JobModel->updateJobTbl($job_id, $param);

          
        $programDetails = $this->ProgramModel->getJobAssignPrograms(array('job_id' => $job_id));
        $program_id_arr = array_column($programDetails,'program_id');
        
        if( count($program_id_arr) > 0 ) {
            foreach($program_id_arr as $program_id) {
                if(!in_array($program_id, $post_data['program_id_array'])) {
                    // Call function to remove record from program_job_assign and priority reorder
                    $this->priorityManageForEditCase($job_id,$program_id);
                }
            }
        }
        

            $where = array('job_id' => $job_id);

            $delete = $this->JobModel->deleteAssignProduct($where);

            if (!empty($post_data['product_id_array'])) {

                foreach ($post_data['product_id_array'] as $value) {
                    $param2 = array(
                        'job_id' => $job_id,
                        'product_id' => $value,

                    );
                    $result = $this->JobModel->assignProduct($param2);

                }

            }

            if (!empty($post_data['program_id_array'])) {
                foreach ($post_data['program_id_array'] as $value) {
                    $priority = $this->ProgramModel->checkPriority(array('program_id' => $value, 'job_id' => $job_id));
                    if ($priority['priorityExist'] === false) {
                        $param3 = array(
                            'job_id' => $job_id,
                            'program_id' => $value,
                            'priority' => $priority['priority'],
                        );
                        $result = $this->ProgramModel->assignProgramJobs($param3);
                    }

                }
            }

            if ($result) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> updated successfully</div>');
                redirect("admin/job");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> not update.</div>');
                redirect("admin/job");
            }

        }

    }

    public function jobListAjax()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $job_details = $this->JobModel->getAllJob($where);

        echo '<option value="">Select any Service</option>';

        if (!empty($job_details)) {

            foreach ($job_details as $value) {
                echo '<option value="' . $value->job_id . '">' . $value->job_name . '</option>';
            }
        }
    }
	

}
