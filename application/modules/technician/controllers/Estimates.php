<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/sms/Send_Text.php';
require FCPATH . 'vendor/autoload.php';
ini_set('memory_limit', '-1');


class Estimates extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('spraye_technician_login')) {
            return redirect('technician/auth');
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
      $this->load->model('Technician_model', 'Tech');
      $this->load->model('Invoice_model','INV');
      $this->load->library('form_validation');
      $this->load->model('AdminTbl_customer_model', 'CustomerModel');
      $this->load->model('AdminTbl_company_model', 'CompanyModel');
      $this->load->model('Job_model', 'JobModel');   
      $this->load->model('Company_email_model', 'CompanyEmail');    
      $this->load->model('Administratorsuper'); 
      $this->load->model('AdminTbl_program_model', 'ProgramModel');
      $this->load->model('AdminTbl_property_model', 'PropertyModel');
      $this->load->model('Sales_tax_model', 'SalesTax');  
      $this->load->model('Basys_request_modal', 'BasysRequest');
      $this->load->helper('estimate_helper');
      $this->load->helper('invoice_helper');
      $this->load->model('Estimate_model', 'EstimateModal');
		  $this->load->model('AdminTbl_coupon_model', 'CouponModel');
      $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
      $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
      
      $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
      $this->load->model('Source_model', 'SourceModel');


    }

    function mamagePaidDate() {
      echo "<pre>";
      $estimate_details = $this->EstimateModal->getAllEstimate(array('status'=>3));
      print_r($estimate_details);

      foreach ($estimate_details as $key => $value) {

         $wherearr = array(
                'estimate_id' => $value->estimate_id,
          );

            $updatearr = array(
                'payment_created' => $value->estimate_update,
            );


          $this->EstimateModal->updateEstimate($wherearr, $updatearr);



      }


    }


    public function index() { 
      $where = array('t_estimate.company_id' =>$this->session->spraye_technician_login->company_id);
    
      $data['estimate_details'] = $this->EstimateModal->getAllEstimate($where);
      $where = array('company_id' =>$this->session->spraye_technician_login->company_id);
      $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    
      $page["active_sidebar"] = "estimatenav";
      $page["page_name"] = 'Estimates';
      
        $company_id = $this->session->spraye_technician_login->company_id;
      
      $data['total_pipeline'] = $this->dataCalculate(getEstimateAmount(array('status'=>0,'t_estimate.company_id'=>$company_id )));
      $data['total_accepted'] = $this->dataCalculate(getEstimateAmount(array('status'=>2,'t_estimate.company_id'=>$company_id )));
      $data['total_pending'] = $this->dataCalculate(getEstimateAmount(array('status'=>1,'t_estimate.company_id'=>$company_id )));

      $counter = 0;
      foreach($data['estimate_details'] as $key => $estiamte_detail) {
          $estimate_id = $estiamte_detail->estimate_id;
          $data['estimate_details'][$key]->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
      }

      $page["page_content"] = $this->load->view("technician/estimate/view_estimate", $data, TRUE);
      $this->layout->technicianTemplateTableDash($page);
    } 

    public function dataCalculate($res){
      $where = array('company_id' =>$this->session->spraye_technician_login->company_id);
      $setting_details = $this->CompanyModel->getOneCompany($where);      

       if ($res) {
		   //die(print_r($res));
        $line_total = 0;
          foreach ($res as $key => $value) {

 
            if ($value['price_override']!=0) {
               $cost =  $value['price_override'];
            } else {

                 $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id'=>$value['property_id'],'program_id'=>$value['program_id'])); 

                  if ($priceOverrideData && $priceOverrideData->price_override!=0 ) {
                        // $price = $priceOverrideData->price_override;
                        $cost =  $priceOverrideData->price_override;

                        // die(print_r($cost));
          
                  }  else {
					   //else no price overrides, then calculate job cost
						$lawn_sqf = $value['yard_square_feet'];
						$job_price = $value['job_price'];

						//get property difficulty level
						if(isset($value['difficulty_level']) && $value['difficulty_level'] == 2){
							$difficulty_multiplier = $setting_details->dlmult_2;
						}elseif(isset($value['difficulty_level']) && $value['difficulty_level'] == 3){
							$difficulty_multiplier = $setting_details->dlmult_3;
						}else{
							$difficulty_multiplier = $setting_details->dlmult_1;
						}

						//get base fee 
						if(isset($value['base_fee_override'])){
							$base_fee = $value['base_fee_override'];
						}else{
							$base_fee = $setting_details->base_service_fee;
						}

						$cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

						//get min. service fee
						if(isset($value['min_fee_override'])){
							$min_fee = $value['min_fee_override'];
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
            }




            $line_tax_amount = 0;
            if ($setting_details->is_sales_tax==1) {     
            
               $sales_tax_details =  getAllSalesTaxByProperty($value['property_id']);

               if ($sales_tax_details) {
                  foreach ($sales_tax_details as  $property_sales_tax) {
                 //   echo $property_sales_tax->tax_name. ' ('.$property_sales_tax->tax_value.'%)<br>';
                  // echo $cost * $property_sales_tax->tax_value /100 . '<br>';
                    $line_tax_amount += $cost * $property_sales_tax->tax_value /100;
                    
                 }           
               
               } 

            } 


            $line_total += $line_tax_amount+$cost;

          }
       } else {
          $line_total = 0;
       }

        return $line_total;


    }
     public function addServiceEstimate($propertyID = NULL) {
      if (!empty($propertyID)) {
        $propertyID = $propertyID;
      } else {
        $propertyID = $this->uri->segment(4);
      }
      // die(print_r($propertyID));
      $data['propertyID'] = $propertyID;
      $where = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
      $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
      // die(print_r($data['customer_details']));
	    $data['service_details'] = $this->JobModel->getJobList($where);
      $data['propertylist'] = $this->CustomerModel->getPropertyList($where);
      // die(print_r($data['propertylist']));
      $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
      $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
      $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
      
      #### ADDED 3/22/22 (RG)
      $whereSpecial = array('property_tbl.company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
      $data['properties'] = $this->CustomerModel->getAllpropertyExt($whereSpecial);
      $selectedProperty = [];
      foreach($data['properties'] as $property){
        if($property->property_id == $propertyID){
          
          array_push($selectedProperty,$property);
        }
      }
      // die(print_r($selectedProperty));
      $data['selectedProperty'] = $selectedProperty;
      // die(print_r($selectedProperty));
      ####
      #### ADDED 2/8/22 (RG) ####
      $data['users'] = $this->Administrator->getAllAdmin($where);
      ####

        $coupon_where = array(
            'company_id' => $this->session->userdata['spraye_technician_login']->company_id,
            'type' => 0
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

      $page["active_sidebar"] = "estimatenav";
  	  $page["page_name"] = 'Add Service Estimate';
      $page["page_content"] = $this->load->view("technician/estimate/add_service_estimate",$data, TRUE);
      $this->layout->technicianTemplateTableDash($page);
    }

    public function getAllServicesByProgramServerSide($program_id = '')
    {
      $property_details =  $this->ProgramModel->getProgramAssignJobs(array('program_id' =>$program_id));
      return $property_details;
    }    

    /** Can be removed in future updates. Only being used for Reference **/
    // public function addEstimateOld() {

    //   $where = array('company_id' =>$this->session->userdata['company_id']);
    //   $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
    //   $data['program_details'] = $this->ProgramModel->get_all_program($where);
    //   $data['selectedprogramlist'] = array();
    //   $data['propertylist'] = $this->CustomerModel->getPropertyList($where);
    //   $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
    //   $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    //   $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
    //   $data['service_details'] = $this->JobModel->getJobList($where);
    //   $data['programlist'] = $this->PropertyModel->getProgramList($where);
    //   $data['selectedjoblist'] = array();
    //   $data['program_job_assign'] = $this->ProgramModel->getProgramJobAssign();
    //   $data['program_details_ext'] = array();


    //   foreach($data['program_details'] as $program)
    //   {
    //     $program_jobs = $this->getAllServicesByProgramServerSide($program->program_id);
    //     $program->program_jobs = $program_jobs;
    //     array_push($data['program_details_ext'],$program);
    //   }

    //   $coupon_where = array(
    //       'company_id' => $this->session->userdata['company_id'],
    //       'type' => 0
    //   );
    //   $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
    //   $page["active_sidebar"] = "estimatenav";
  	// 	$page["page_name"] = 'Add Program Estimate';
    //   $page["page_content"] = $this->load->view("technician/estimate/add_estimate_old",$data, TRUE);
    //   $this->layout->technicianTemplateTableDash($page);
    // }
    public function addEstimate($propertyID = NULL) {
      if (!empty($propertyID)) {
        $propertyID = $propertyID;
      } else {
        $propertyID = $this->uri->segment(4);
      }
      // die(print_r($propertyID));
      $where = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
      $whereSpecial = array('property_tbl.company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
      $data['propertylist'] = $this->CustomerModel->getAllpropertyExt($whereSpecial);
      $selectedProperty = [];
      foreach($data['propertylist'] as $property){
        if($property->property_id == $propertyID){
          array_push($selectedProperty,$property);
        }
      }
      // die(print_r($selectedProperty));
      $data['selectedProperty'] = $selectedProperty;
      // die(print_r($data['propertylist']));
      $oldPropList = $this->CustomerModel->getAllproperty($where);
      $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
      $data['program_details'] = $this->ProgramModel->get_all_program($where);
      $data['selectedprogramlist'] = array();
      $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
      $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
      $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
      $data['service_details'] = $this->JobModel->getJobList($where);
      $data['programlist'] = $this->PropertyModel->getProgramList($where);
      $data['selectedjoblist'] = array();
      $data['program_job_assign'] = $this->ProgramModel->getProgramJobAssign();
      $data['sold_by'] = $this->Administrator->getOneAdmin(array('id'=> $this->session->userdata['spraye_technician_login']->id));
      
       ##### ADDED 2/24/22 (RG) #####
      // $data['all_users'] = $this->SourceModel->getAllSource($where);
      $data['source_list'] = $this->SourceModel->getAllSource($where);
      $data['users'] = $this->Administrator->getAllAdmin($where);
      // die(print_r($data['users']));
      // $data['sources'] = array_merge($data['source_list'], $data['users']);
      $source = [];
      foreach($data['users'] as $user){
          $source = (object) array(
              'source_name' => $user->user_first_name.' '.$user->user_last_name,
              'user_id' => $user->user_id,
              'source_id' => $user->id,
          ) ;
          array_push( $data['source_list'], $source);
      }
      // die(print_r($data['source_list']));
      ####

      $data['program_details_ext'] = array();
      foreach($data['program_details'] as $program)
      {
        $program_jobs = $this->getAllServicesByProgramServerSide($program->program_id);
        $program->program_jobs = $program_jobs;
        array_push($data['program_details_ext'],$program);
      }

      $coupon_where = array(
          'company_id' => $this->session->userdata['spraye_technician_login']->company_id,
          'type' => 0
      );
      $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
      $page["active_sidebar"] = "users";
  		$page["page_name"] = 'Add Estimate';
      $page["page_content"] = $this->load->view("technician/estimate/add_estimate",$data, TRUE);
      $this->layout->technicianTemplateTableDash($page);
    }

    public function GetAllCustomerByProperty($property_id) {
     $customers   =  $this->CustomerModel->getAllCustomerByProperty(array('property_id' =>$property_id));
     $property_details = $this->PropertyModel->getPropertyDetail($property_id);
     if ($customers) {

      $return_result = array('status'=>200,'msg'=>'successfully','result'=>$customers,'property_details'=>$property_details);               
     
     } else {
      
      $return_result = array('status'=>400,'msg'=>'Faild','result'=>array(),'property_details'=>$property_details);               
     }
     echo json_encode($return_result);
  }


public function getPropertyByCustomerID() {

    $customer_id = $this->input->post('customer_id');
    $data = $this->CustomerModel->getAllproperty(array('customer_id' =>$customer_id));

    if (!empty($data)) {
       echo '<option value="">Select any property</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->property_id.'">'.$value->property_address.'</option>';
    }
   
    } else {
        echo '<option value="">No property assign</option>';
    }
}

public function addBulkEstimateData($data = null) {
  
  $backendCall = (isset($data)) ? true : false;
  $tmpData = (isset($data)) ? $data : $this->input->post();
  // die(print_r($tmpData));
  $company_id = $this->session->spraye_technician_login->company_id;
  $user_id = $this->session->spraye_technician_login->user_id;
  $tmpData['company_id'] = $company_id;
  $tmpData['user_id'] = $user_id;
  
  // die(print_r($propertyID));
  // $test = $this->ProgramModel->getSelectedJobsAnother('1038');
  // Parse the JSON strings
  $property_array = json_decode($tmpData['property_data_array']);
  $propertyID = $property_array[0]->property_id;
  // die(print_r($propertyID));
  $listarray = json_decode($tmpData['listarray']);
  $programs = $listarray->programs;
  $services = $listarray->services; 
  $priceoverridearray = (is_array($tmpData['priceoverridearray'])) ? $tmpData['priceoverridearray'] : json_decode($tmpData['priceoverridearray']);

  $unmodifiedProgram = false;
  if(count($listarray->programs) == 1 && count($listarray->services) == 0){

    $unmodifiedProgram = true;
    $originalProgramDetails = $this->ProgramModel->getProgramDetail($listarray->programs[0]->program_id);
    $originalProgramJobs = $this->ProgramModel->getSelectedJobsAnother($listarray->programs[0]->program_id);
    // $bundled_program_name = $originalProgramDetails->program_name;
  } elseif(count($listarray->programs) == 0 && count($listarray->services) == 1){

    $bundled_program_name = $this->JobModel->getOneJob(array('job_id' => $services[0]->job_id))->job_name.'-Standalone Service';
  } elseif(count($listarray->programs) == 0 && count($listarray->services) > 1){

    $job_names = array_map(function($s) {
      $r = $this->JobModel->getOneJob(array('job_id' => $s->job_id));
      return $r->job_name;
    }, $services);
    $bundled_program_name = implode('+', $job_names);
  } else if(count($listarray->programs) > 1 && count($listarray->services) == 0){

    $program_names = array_map(function($p) {
      $r = $this->ProgramModel->getProgramDetail($p->program_id);
      return explode("-",$r['program_name'])[0] ?? '';
    }, $programs);
    $bundled_program_name = implode('+', $program_names);
  } else {

    $program_names = (array)[];
    $service_names = (array)[];
    foreach($listarray->programs as $p){
      $r = $this->ProgramModel->getProgramDetail($p->program_id)['program_name'];
      $r = trim(explode('-',$r)[0]);
      array_push($program_names, $r);
    }
    foreach($listarray->services as $s){
      $r = $this->JobModel->getOneJob(array('job_id' => $s->job_id))->job_name;
       array_push($service_names, $r);
    }
    $bundled_program_name = implode('+', $program_names).'+'.implode('+', $service_names);
  }
  if($unmodifiedProgram){
    $program_price = $originalProgramDetails['program_price'];
    $bundled_program_name = $originalProgramDetails['program_name'];
  } else{
    $program_price = $tmpData['program_price'];
    $pricing_strs = array('One Time Project Invoicing', 'Invoiced at Job Completion', 'Manual Billing');
    $bundled_program_name = $bundled_program_name.' - '.$pricing_strs[$program_price - 1];
  }
  $or = (array)[];
  $price_overrides = (array)[];
  foreach ($priceoverridearray as $ovr){
    $tmp = (object)[];
    $tmp->propertyId = $ovr->propertyId;
    $tmp->price_override = $ovr->price_override;
    $tmp->program_jobs = $ovr->jobIds;
    array_push($or,$tmp);
  }
  //die(print_r(json_encode($or)));
  foreach($or as $o){
    $tmp = (object)[];
    $tmp->propertyId = $o->propertyId;
    for($i=0; $i<count($o->price_override); $i++){
      $tmp = (object)[];
      $tmp->propertyId = $o->propertyId;
      $tmp->job_id = $o->program_jobs[$i];
      $tmp->price_override = ($o->price_override[$i] != '') ? $o->price_override[$i] : null;
      $tmp->is_price_override_set = ($tmp->price_override != '') ? 1 : null;
      array_push($price_overrides, $tmp);
    } 
  }
  // die(print_r(json_encode($price_overrides)));
  $jobsAll = array();
  foreach($programs as $program){
    $jobsAll = array_unique(array_merge($jobsAll,$program->program_jobs));
  }
  foreach($services as $service){
    $jobsAll = array_unique(array_merge($jobsAll,array($service->job_id)));
  }
  $programData = array();
  $programData['company_id'] = $company_id;
  $programData['user_id'] = $user_id;
  $programData['program_name'] = $bundled_program_name;
  $programData['jobs_all'] = $jobsAll;
  $programData['program_price'] = $program_price;
  

  
  // if(count($programs) > 0) 
  // {
  //   $programData['ad_hoc'] = 0;
  // } else if(count($services) > 1) 
  // {
  //   $programData['ad_hoc'] = 0;
  // } else
  // {
  //   $programData['ad_hoc'] = 1;
  // }
  //die(print_r(json_encode($programData)));
  if($unmodifiedProgram){
    $programResults = array(
      'program_id' => $originalProgramDetails['program_id'],
      'programjob_assign_result' => $this->ProgramModel->getProgramAssignJobs(array('program_id' => $originalProgramDetails['program_id']))
    );
  } else {
    $programResults = $this->createModifiedBundledProgram($programData);
  }
  $data = (array)[];
  
  foreach($property_array as $property){
    $tmp = json_decode(json_encode(clone $property), true);
    $tmp['estimate_date'] = $tmpData['estimate_date'];
    $tmp['estimate_date_submit'] = $tmpData['estimate_date_submit'];
    $tmp['status'] = $tmpData['status'];
    $tmp["signwell_status"] = $tmpData["signwell_status"];
    $tmp['notes'] = $tmpData['notes'];
     $tmp['property_status'] = $tmpData['property_status'];
    $tmp['sales_rep'] = $tmpData['sales_rep'];
    $tmp['program_id'] = $programResults['program_id'];
    if (array_key_exists("assign_onetime_coupons",$tmpData)){
      $tmp['assign_onetime_coupons'] = $tmpData['assign_onetime_coupons'];
    }
    $tmp['joblistarray'] = (array)[];
    $jobs = $jobsAll;
    foreach($jobs as $job){
      $tmpJob = (object)[];
      $tmpJob->job_id = $job;
      foreach($price_overrides as $price_override){
        if(isset($price_override->propertyId)){
          if($tmp['property_id'] == $price_override->propertyId && $tmpJob->job_id == $price_override->job_id){
            $tmpJob->price_override = $price_override->price_override;
            $tmpJob->is_price_override_set = 1;
          }
        }
      }
      $tmpJob->is_price_override_set = (isset($tmpJob->price_override)) ? $tmpJob->is_price_override_set : null;
      $tmpJob->price_override = (isset($tmpJob->price_override)) ? $tmpJob->price_override : "";

      array_push($tmp['joblistarray'], $tmpJob);
    }
    /* --> Insert Property Program Job Price Overrides Function HERE <-- */
    $priceOverrideResults = (array)[];
    foreach($tmp['joblistarray'] as $jobOverride){
      $arr = array(
        'program_id' => $tmp['program_id'],
        'job_id' => $jobOverride->job_id,
        'property_id' => $tmp['property_id'],
        'price_override' => $jobOverride->price_override,
        'is_price_override_set' => $jobOverride->is_price_override_set
      );
      $result = $this->ProgramModel->insert_price_override($arr);
      array_push($priceOverrideResults, $result);
    }
    $tmp['joblistarray'] = json_encode($tmp['joblistarray']);
    array_push($data, $tmp);
    // die(print_r(json_encode($priceOverrideResults)));
  }
  // die(print_r(json_encode($data)));
  $return_messages = (array)[];
  foreach($data as $submission){
    // if(isset($submission['program_id']))
    // {
      $message = $this->addEstimateData($submission, true);
    // } else 
    // {
    //   die('NO!!');
      // $message = $this->addServiceEstimateData($submission, true);
    // }
    array_push($return_messages, $message);
  }
  if($backendCall == true){
    return $return_messages;
  } else {
    $success_count = 0;
    $fail_count = 0;
    $other_count = 0;
    foreach($return_messages as $return_message){
      if($return_message == '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>'){
        $success_count++;
      } elseif($return_message == '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>') {
        $fail_count++;
      } elseif($return_message != '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>' 
          && $return_message != '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>'){
        $other_count++;
      }
    }

    $success_message = '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>'.$success_count.' Estimate(s) </strong>created successfully</div>';
    $fail_message = '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>'.$fail_count.' Estimate(s) </strong>were not added. Please review submissions and try again</div>';

    if($other_count > 0){
      $final_message = implode("", $return_messages);
    } else{
      if($success_count > 0 && $fail_count > 0){
        $final_message = $success_message.'<br>'.$fail_message;
      } elseif ($success_count > 0 && $fail_count <= 0) {
        $final_message = $success_message;
      } elseif ($success_count <= 0 && $fail_count > 0) {
        $final_message = $fail_message;
      }
    }
    $this->session->set_flashdata('message', $final_message);
    redirect("technician/jobDetails/$propertyID");
  }
}

// Create New Modified / Bundled Programs for Estimates
// Destructures multiple programs and services and rebundles -
// them into a single modified program for issuing a new estimate
public function createModifiedBundledProgram($data)
{

  //create new ad_hoc program based on selected program id
    $newProgram = array(
        'user_id' => $data['user_id'],
        'company_id' => $data['company_id'],
        'program_name' => $data['program_name'],
        'program_price' => $data['program_price'],
  );

  $program_id = $this->ProgramModel->insert_program($newProgram);

  $program_jobs = $data['jobs_all'];

  //Assign jobs to program
  foreach($program_jobs as $program_job){
    $programJob = array(
        'program_id' => $program_id,
        'job_id' => $program_job,
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

public function addEstimateData($data = null, $bulk_call = false) {
  if(!isset($data)){
    $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    $this->form_validation->set_rules('property_id', 'Property', 'required');
    $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
    $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
    $this->form_validation->set_rules('program_id', 'Program', 'required');
    $this->form_validation->set_rules('joblistarray', 'job required', 'trim');
    $this->form_validation->set_rules('notes', 'notes', 'trim');
  }
  if (!isset($data) && $this->form_validation->run() == FALSE) {
    // echo validation_errors();
    $this->addEstimate();
  } else {
    $data = (isset($data)) ? $data : $this->input->post();
    // die(print_r($data));
    $company_id = $this->session->spraye_technician_login->company_id;
    $user_id = $this->session->spraye_technician_login->user_id;
    $where = array('company_id' =>$this->session->spraye_technician_login->company_id);
    // $where = array('company_id' =>$this->session->userdata['company_id']);
    // $customer_id = $this->Customer->getOnecustomerPropert(array('property_id' => $data['property_id']));
    //die(print_r(json_encode($data)));
    $check_arr = array(
        't_estimate.company_id' => $company_id,
        't_estimate.customer_id' => $data['customer_id'],
        't_estimate.property_id' => $data['property_id'],
        't_estimate.program_id' => $data['program_id'],
    );
    $check = $this->EstimateModal->getOneEstimate($check_arr);

    if ($check) {
      if($bulk_call){
        return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimates </strong> already exists</div>';
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimates </strong> already exists</div>');
        redirect("technician/Estimates/addEstimate");
      }
    } else {
          // die('through');
      $program_id = $data['program_id'];
      //check for standalone service
      // if(isset($data['standalone_job_id']) && $data['standalone_job_id'] > 0){
      //   //get program details
      //   $program_details = $this->ProgramModel->getProgramDetail($data['program_id']);
      //   $program_jobs = $this->ProgramModel->getSelectedJobs($data['program_id']);
        
      //   //create new ad_hoc program based on selected program id
      //     $newProgram = array(
      //       'user_id' => $user_id,
      //       'company_id' => $company_id,
      //       'program_name' => $program_details['program_name'],
      //       'program_price' => $program_details['program_price'],
      //   );
      //   // die(print_r(json_encode($program_details)));
      //   $program_id = $this->ProgramModel->insert_program($newProgram);
    
      //   //Assign jobs to program
      //   foreach($program_jobs as $program_job){
      //     $programJob = array(
      //       'program_id' => $program_id,
      //       'job_id' => $program_job->job_id,
      //       'priority' =>1
      //     ); 
      //     $programJobAssignResult = $this->ProgramModel->assignProgramJobs($programJob); 
      //   }
      //   $programJob2 = array(
      //       'program_id' => $program_id,
      //       'job_id' => $data['standalone_job_id'],
      //       'priority' =>1
      //   ); 
      //   $programJobAssignResult2 = $this->ProgramModel->assignProgramJobs($programJob2);
      // }

      $param = array(
          'company_id' => $company_id,
          'customer_id' => $data['customer_id'],
          'property_id' => $data['property_id'],
          'estimate_date' => $data['estimate_date'],
          'program_id' => $program_id,
          'status' => $data['status'],
          'property_status' => $data['property_status'],
          'sales_rep' => $data['sales_rep'],
          'estimate_created_date' => date("Y-m-d H:i:s"),
          'estimate_update' => date("Y-m-d H:i:s"),
          'notes' => $data['notes'],
          'signwell_status' => $data['signwell_status'],
      );
      $estimate_id = $this->EstimateModal->CreateOneEstimate($param);

      if($estimate_id){
        if(isset($data['joblistarray']) && !empty($data['joblistarray'])) {

          foreach (json_decode($data['joblistarray']) as $value){
            $param3 = array(
              'estimate_id' => $estimate_id,
              'customer_id' => $data['customer_id'],
              'property_id' => $data['property_id'],
              'program_id' => $program_id,
              'job_id' => $value->job_id,
              'price_override' => $value->price_override,
              'is_price_override_set' => $value->is_price_override_set,
              'created_at' => date("Y-m-d H:i:s")
            );
            $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);
          }
        }
        if ($data['status']==1 && $data['customer_email']!='' ){
          $company_id = $this->session->spraye_technician_login->company_id;
          $customer_id  = $data['customer_id'];
          $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
          $where_company = array('company_id' =>$company_id);
          $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
          if($data["signwell_status"] == "1") {
            $pdf_link_for_signwell = base_url('welcome/pdfEstimateSignWell/').base64_encode($estimate_id);
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.signwell.com/api/v1/documents/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                            "test_mode": '.SIGNWELL_TEST_MODE.',
                            "name": "estimate_'.$estimate_id.'",
                            "files": [
                                {
                                    "name": "estimate_'.$estimate_id.'.pdf",
                                    "file_url": "'.$pdf_link_for_signwell.'"
                                }
                            ],
                            "recipients": [
                                {
                                    "send_email": false,
                                    "id": "1",
                                    "name": "'.$email_data['customer_details']->first_name.' '.$email_data['customer_details']->last_name.'",
                                    "email": "'.$email_data['customer_details']->email.'"
                                }
                            ],
                            "draft": false,
                            "reminders": true,
                            "apply_signing_order": false,
                            "embedded_signing": false,
                            "embedded_signing_notifications": false,
                            "text_tags": true,
                            "allow_decline": true,
                            "redirect_url": "'.base_url('welcome/set_signwell_estimate_accepted/'.$estimate_id).'",
                            "decline_redirect_url": "'.base_url('welcome/set_signwell_estimate_rejected/'.$estimate_id).'"
                        }',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'content-type: application/json',
                'X-Api-Key: '.$email_data['setting_details']->signwell_api_key
            ),
            ));

            $response = curl_exec($curl);
            
            curl_close($curl);
            $response_object = json_decode($response);
            if($response_object->message == "") {
                // we should now have an ID for this document within SignWell - need to save that to the estimate in the DB
                $this->EstimateModal->updateEstimateSignWellID($estimate_id, $response_object->id);
            }
          } else {
            if(isset($data['notes']) && $data['notes'] != ''){
                $email_data['msgtext'] = $data['notes'];
            }else{
                $email_data['msgtext'] = '';
            }
            $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
            $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);
            $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;
            $body = $this->load->view('technician/estimate/estimate_email',$email_data,true);
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
            if (!$company_email_details) {
                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            } 
            $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details');

            $rep_data['sale_rep'] = $this->Administrator->getOneAdmin(array('id' => $data['sales_rep']));
            // die(print_r($rep_data));
            $body = $this->load->view('admin/estimate/assigned_email',$rep_data,true);
            $rep =   Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Assigned');
            // die(print_r($rep));
          }
        }
        // apply assigned coupons
        if (array_key_exists("assign_onetime_coupons",$data)) {
          $coupon_ids_arr = $data['assign_onetime_coupons'];
          foreach($coupon_ids_arr as $coupon_id) {
            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
            $params = array(
                'coupon_id' => $coupon_id,
                'estimate_id' => $estimate_id,
                'coupon_code' => $coupon_details->code,
                'coupon_amount' => $coupon_details->amount,
                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                'coupon_type' => $coupon_details->type,
                'expiration_date' => $coupon_details->expiration_date
            );
            $this->CouponModel->CreateOneCouponEstimate($params);
          }
        }
        // check global coupons & assign if so
        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $data['customer_id']));
        if (!empty($coupon_customers)) {
          foreach($coupon_customers as $coupon_customer) {
            $coupon_id = $coupon_customer->coupon_id;
            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
            $params = array(
                'coupon_id' => $coupon_id,
                'estimate_id' => $estimate_id,
                'coupon_code' => $coupon_details->code,
                'coupon_amount' => $coupon_details->amount,
                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                'coupon_type' => $coupon_details->type,
                'expiration_date' => $coupon_details->expiration_date
            );
            $this->CouponModel->CreateOneCouponEstimate($params);
          }
        }
        if($bulk_call) {
            if($response_object->message != "") {
                // this means that the SignWell api got an error and nothing got sent over to them
                return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: '.$response_object->message.')</div>';
            } else {
                return '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>';
            }
        } else {
            if($response_object->message != "") {
                // this means that the SignWell api got an error and nothing got sent over to them
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: '.$response_object->message.')</div>');
                redirect("technician/dashboard");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>');
                redirect("technician/dashboard");
            }
        }
      } else {
        if($bulk_call) {
            return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>';
        } else {
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>');
          redirect("technician/dashboard");
        }
      }            
    }
  }
}


   public function addServiceEstimateData($data = null, $bulk_call = false) {

    if(!isset($data)){
      $this->form_validation->set_rules('customer_id', 'Customer', 'required');
      $this->form_validation->set_rules('property_id', 'Property', 'required');
      $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
      $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
      $this->form_validation->set_rules('standalone_job_id', 'Service', 'required');
      $this->form_validation->set_rules('program_price', 'Pricing', 'required');
      $this->form_validation->set_rules('notes', 'notes', 'trim');
    }

    if (!isset($data) && $this->form_validation->run() == FALSE) {
        $this->addServiceEstimate();
    } else {

			$data = (isset($data)) ? $data : $this->input->post();
      // die(print_r($data));
      // error out if price override is incorrectly set
      if (isset($data['price_override_error']) && $data['price_override_error'] == 1) {
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Price override must be a non-zero positive price.</div>');
          redirect("technician/Estimates/addServiceEstimate");
      }

			$job_id = $data['standalone_job_id'];
			$job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));
			
			$company_id = $this->session->spraye_technician_login->company_id;
			$user_id = $this->session->spraye_technician_login->user_id;
      $sales_id = $this->session->spraye_technician_login->id;
			$property_id = $data['property_id'];
			$customer_id = $data['customer_id'];
			$job_name = $job_details->job_name;
			$job_price = $job_details->job_price;
			if(isset($data['price_override']) && $data['price_override'] > 0){
				$data['is_price_override_set'] = 1;
			}else{
				$data['is_price_override_set'] = 0;
			}
			//create program
			 $param = array(
					'user_id' => $user_id,
					'company_id' => $company_id,
					'program_name' => $job_name,
					'program_price' => $data['program_price'] ??  1,
					'ad_hoc' => 1,
				);

			$program_id = $this->ProgramModel->insert_program($param);
			
			//Assign job to program
			$param2 = array(
			  'program_id' => $program_id,
			  'job_id' => $job_id,
			  'priority' =>1
			); 

			$result1 = $this->ProgramModel->assignProgramJobs($param2); 

			//Create Estimate
			$estimateParam = array(
				'company_id' => $company_id,
				'customer_id' => $customer_id,
				'property_id' => $property_id,
				'estimate_date' => $data['estimate_date'],
				'program_id' => $program_id,
				'status' => $data['status'],
        'property_status' => $data['property_status'],
        'sales_rep' => $sales_id,
				'estimate_created_date' => date("Y-m-d H:i:s"),
				'estimate_update' => date("Y-m-d H:i:s"),
				'notes' => $data['notes'],
			);

            $estimate_id = $this->EstimateModal->CreateOneEstimate($estimateParam);
                
			//Store Estimate Price Override
            if($estimate_id) {
			  $param3 = array(
				'estimate_id' => $estimate_id,
				'customer_id' => $customer_id,
				'property_id' => $property_id,
				'program_id' => $program_id,
				'job_id' => $job_id,
				'price_override' => $data['price_override'],
				'is_price_override_set' => $data['is_price_override_set'],
				'created_at' => date("Y-m-d H:i:s")
			  );       

             $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);
			
			 //handle status if status == send estimate
        if ($data['status']==1 && $data['customer_email']!='' ) {
				  if(isset($data['notes']) && $data['notes'] != ''){
					  $email_data['msgtext'] = $data['notes'];
				  }else{
					 $email_data['msgtext'] = ''; 
				  }
              	  
				  $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
				  $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
				  $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);

                  $where_company = array('company_id' =>$company_id);

                  $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                        
				  $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;

                  $body = $this->load->view('technician/estimate/estimate_email',$email_data,true);

                      
                  $where_company['is_smtp'] = 1;
                  
				  $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                                    
				  if(!$company_email_details) {
					 $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
				  } 

            $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details');

            $rep_data['sale_rep'] = $this->Administrator->getOneAdmin(array('id' => $sales_id));
            // die(print_r($rep_data));
            $body = $this->load->view('technician/estimate/assigned_email',$rep_data,true);
            $rep =   Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Assigned');
            // die(print_r($rep));
        }


                    // apply assigned coupons
                    if (array_key_exists("assign_onetime_coupons",$data)) {
                        $coupon_ids_arr = $data['assign_onetime_coupons'];
                        foreach($coupon_ids_arr as $coupon_id) {

                            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                            $params = array(
                                'coupon_id' => $coupon_id,
                                'estimate_id' => $estimate_id,
                                'coupon_code' => $coupon_details->code,
                                'coupon_amount' => $coupon_details->amount,
                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                'coupon_type' => $coupon_details->type,
                                'expiration_date' => $coupon_details->expiration_date
                            );
                            $resp = $this->CouponModel->CreateOneCouponEstimate($params);
                        }
                    }

                    // check global coupons & assign if so
                    $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
                    if (!empty($coupon_customers)) {
                        foreach($coupon_customers as $coupon_customer) {

                            $coupon_id = $coupon_customer->coupon_id;
                            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                            $params = array(
                                'coupon_id' => $coupon_id,
                                'estimate_id' => $estimate_id,
                                'coupon_code' => $coupon_details->code,
                                'coupon_amount' => $coupon_details->amount,
                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                'coupon_type' => $coupon_details->type,
                                'expiration_date' => $coupon_details->expiration_date
                            );
                            $resp = $this->CouponModel->CreateOneCouponEstimate($params);

                            // $coupon_id = $coupon_customer->coupon_id;
                            // $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                            // $params = array(
                            //     'coupon_id' => $coupon_id,
                            //     'invoice_id' => $invoice_id,
                            //     'coupon_code' => $coupon_details->code,
                            //     'coupon_amount' => $coupon_details->amount,
                            //     'coupon_amount_calculation' => $coupon_details->amount_calculation,
                            //     'coupon_type' => $coupon_details->type
                            // );
                            // $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                        }
                    }

                  if($bulk_call)
                  {
                    return '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>';
                  } else 
                  { 
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>');
                  }
                   redirect("technician/dashboard");
                } else {
                  if($bulk_call)
                  {
                    return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>';
                  } else 
                  {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>');
                  }
                    redirect("technician/dashboard");
                }  
        }

    }

    public function getAllServicesByProgram($value=''){
      $program_id =  $this->input->post('program_id');
      $property_details =  $this->ProgramModel->getProgramAssignJobs(array('program_id' =>$program_id));
     
      if ($property_details) {
        $return_result =  array('status'=>200,'result'=>$property_details,'msg'=>'successfully');
      } else{ 
        $return_result =  array('status'=>400,'result'=>array(),'msg'=>'successfully');
      }
      echo json_encode($return_result);
      
    }

    public function getAllEstimateBySearch($status){
 
    $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);

      if($status!=4) {
        $where['status'] = $status;
      }

     $data['estimate_details'] = $this->EstimateModal->getAllEstimate($where);

    $where = array('company_id' =>$this->session->userdata['company_id']);

     $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

     $body  =  $this->load->view('estimate/ajax_data',$data,TRUE);
     echo $body;

 } 

 public function editEstimate($estimate_id) {   
    $where = array('company_id' =>$this->session->userdata['company_id']);
	$data['service_details'] = $this->JobModel->getJobList($where);
    $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
    $data['customer_details'] = $this->CustomerModel->get_all_customer(array('company_id' =>$this->session->userdata['company_id']));
    $where = array(
        't_estimate.company_id' =>$this->session->userdata['company_id'],
        'estimate_id' => $estimate_id,
    );

    $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);

    $data['property_details'] = $this->CustomerModel->getAllproperty(array('customer_id' =>$data['estimate_details']->customer_id));

    $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' =>$this->session->userdata['company_id']));
    $data['price_override_details'] = $this->EstimateModal->getOneEstimatePriceOverRide(array('estimate_id' =>$estimate_id));

    $coupon_where = array(
        'company_id' => $this->session->userdata['company_id'],
        'type' => 0
    );
    $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

    $data_temp_coupon = $this->CouponModel->getCouponEstimateIDs(array('estimate_id' => $estimate_id));
    $data['existing_coupon_estimate']  = array();
    if (!empty($data_temp_coupon)) {
        foreach ($data_temp_coupon as $value) {
            $data['existing_coupon_estimate'][] = $value->coupon_id;
        }
    }

    $data['existing_coupon_estimate_data'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
    // print_r($data['customer_one_time_discounts']);
    // echo '<br><br>';
    // print_r($data['existing_coupon_estimate']);
    // echo '<br><br>';
    // print_r($data['existing_coupon_estimate_data']);
    // echo '<br><br>';
    // die();

    $page["active_sidebar"] = "estimatenav";
   	$page["page_name"] = 'Update Estimate';
    $page["page_content"] = $this->load->view("technician/estimate/edit_estimate",$data, TRUE);
    $this->layout->technicianTemplateTableDash($page);
  }

 public function editEstimateData($estimate_id) {
        
    $data = $this->input->post();

    $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    $this->form_validation->set_rules('property_id', 'Property', 'required');
    $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
    $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
    $this->form_validation->set_rules('program_id', 'Program', 'required');
    $this->form_validation->set_rules('notes', 'notes', 'trim');



    if ($this->form_validation->run() == FALSE) {

        // echo validation_errors();
        $this->editEstimate($estimate_id);
    } else {
        $data = $this->input->post();
		$user_id = $this->session->spraye_technician_login->user_id;
      $company_id = $this->session->spraye_technician_login->company_id;
        
        $check_arr = array(
            't_estimate.company_id' => $company_id,
            't_estimate.customer_id' => $data['customer_id'],
            't_estimate.property_id' => $data['property_id'],
            't_estimate.program_id' => $data['program_id'],
            'estimate_id !=' => $estimate_id,

        );

        $check = $this->EstimateModal->getOneEstimate($check_arr);
        if ($check) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> already exists</div>');
            redirect("technician/Estimates/editEstimate/".$estimate_id);
        } else {
			$program_id = $data['program_id'];
			//check for standalone service
			if(isset($data['standalone_job_id']) && $data['standalone_job_id'] > 0){
				//die(print_r($data));
					//get program details
					$program_details = $this->ProgramModel->getProgramDetail($data['program_id']);
					$program_jobs = $this->ProgramModel->getSelectedJobs($data['program_id']);
					
					//create new ad_hoc program based on selected program id
					 $newProgram = array(
							'user_id' => $user_id,
							'company_id' => $company_id,
							'program_name' => $program_details['program_name'],
							'program_price' => $program_details['program_price'],
							'ad_hoc' => 1,
					);          

					$program_id = $this->ProgramModel->insert_program($newProgram);
			
					//Assign jobs to program
					foreach($program_jobs as $program_job){
						$programJob = array(
						  'program_id' => $program_id,
						  'job_id' => $program_job->job_id,
						  'priority' =>1
						); 
						$programJobAssignResult = $this->ProgramModel->assignProgramJobs($programJob); 
					}
					$programJob2 = array(
						  'program_id' => $program_id,
						  'job_id' => $data['standalone_job_id'],
						  'priority' =>1
					); 
					$programJobAssignResult2 = $this->ProgramModel->assignProgramJobs($programJob2);

					
			}

            $wherearr = array(
                'estimate_id' => $estimate_id,
            );

            $updatearr = array(
                'customer_id' => $data['customer_id'],
                'property_id' => $data['property_id'],
                'estimate_date' => $data['estimate_date'],
                'program_id' => $program_id,
                'estimate_update' => date("Y-m-d H:i:s"),
                'notes' => $data['notes'],
            );



            $result = $this->EstimateModal->updateEstimate($wherearr, $updatearr);
            
            if ($result) {



              $this->EstimateModal->deleteEstimatePriceOverRide($wherearr);


               if ( isset($data['joblistarray']) &&  !empty($data['joblistarray']) ) {

                   foreach (json_decode($data['joblistarray']) as $value) {
						$param3 = array(
						  'estimate_id' => $estimate_id,
						  'customer_id' => $data['customer_id'],
						  'property_id' => $data['property_id'],
						  'program_id' => $program_id,
						  'job_id' => $value->job_id,
						  'price_override' => $value->price_override,
						  'is_price_override_set' => $value->is_price_override_set,
						  'created_at' => date("Y-m-d H:i:s")
						);       

                        $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);
                    }
               }

                // UPDATE COUPON_ESTIMATES
                $new_coupons_csv = json_decode($data['assign_coupons_csv']);
                if(isset($new_coupons_csv)) {

                    // remove deleted coupons
                    $all_coupon_estimates = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
                    foreach ($all_coupon_estimates as $existing_coupon_estimate) {
                        if ( !in_array($existing_coupon_estimate->coupon_id, $new_coupons_csv) ) {
                            
                            // delete coupon if pre-existing coupon_estimate is not in the new list
                            $this->CouponModel->DeleteCouponEstimate(array("coupon_estimate_id" => $existing_coupon_estimate->coupon_estimate_id));

                        }
                    }

                    // set new coupon_estimates
                    foreach ($new_coupons_csv as $coupon_id) {

                        $coupon_details = $this->CouponModel->getOneCoupon( array('coupon_id' => $coupon_id) );
                        if ($coupon_details) {

                            // only add coupon_invoice if the coupon exists & it's type is non perm
                            if ($coupon_details->type == 0) {

                                $coupon_estimate_exists = $this->CouponModel->getOneCouponEstimate( array('coupon_id' => $coupon_id, 'estimate_id' => $estimate_id) );

                                // add coupon_invoice if it doesn't already exist
                                if (!$coupon_estimate_exists) {
                                    $param_coupon = array(
                                        'coupon_id' => $coupon_id,
                                        'estimate_id' => $estimate_id,
                                        'coupon_code' => $coupon_details->code,
                                        'coupon_amount' => $coupon_details->amount,
                                        'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                        'coupon_type' => 0,
                                        'expiration_date' => $coupon_details->expiration_date
                                    );
                                    $this->CouponModel->CreateOneCouponEstimate($param_coupon);
                                }

                            } else {
                                // cannot add perm coupons from invoices screen
                            }

                        } else {
                            // coupon doesn't exist anymore -- can't add
                        }

                    }
                }



               $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>updated successfully</div>');
               redirect("technician/dashboard");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not updated. Please try again</div>');
                redirect("technician/Estimates/editEstimate/".$estimate_id);
            }       
        }
          
      }

  }
 public function pdfEstimate($estimate_id) {

        $where = array(
            "t_estimate.company_id" => $this->session->userdata['company_id'],
            'estimate_id' =>$estimate_id 
        );    

        
        $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);
	 	$data['customer_details'] = $this->CustomerModel->getOneCustomerDetail( $data['estimate_details']->customer_id );
        $data['property_details'] = $this->PropertyModel->getOneProperty(array('property_id' => $data['estimate_details']->property_id));

        // $data['invoice_details'] = $this->INV->getOneInvoive($where);

       $data['job_details'] =  GetOneEstimatAllJobPrice(array('estimate_id'=>$estimate_id));
  
       $where = array('user_id' =>$this->session->userdata['user_id']);
       $data['user_details'] =  $this->Administrator->getOneAdmin($where);

        // this is how to get service wide for estimates -- but for now not using these - just coupon_estimates
        // SERVICE WIDE COUPONS
        // $arry = array(
        // 	'customer_id' => $data['estimate_details']->customer_id,
        // 	'program_id' => $data['estimate_details']->program_id,
        // 	'property_id' => $data['estimate_details']->property_id
        // );
        // $data['coupon_job'] = $this->CouponModel->getAllCouponJob($arry);

        // ESTIMATE COUPONS
        $data['coupon_estimate'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        // die(print_r($data));

        $this->load->view('technician/estimate/pdf_estimate',$data);


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
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }

   public function printEstimate($invoice_ids) {

      $where_company = array('company_id' =>$this->session->userdata['company_id']);
      $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

      $where = array('user_id' =>$this->session->userdata['user_id']);
      $data['user_details'] =  $this->Administrator->getOneAdmin($where);

     $invoice_ids = explode(",", $invoice_ids);
     foreach ($invoice_ids as $key => $value) {
          
        $where = array(
            "t_estimate.company_id" => $this->session->userdata['company_id'],
            'estimate_id' =>$value 
        );
  
        $estimate_details_data = $this->EstimateModal->getOneEstimate($where);

        // ESTIMATE COUPONS
        $estimate_details_data->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $value));
  
        $data['estimate_details'][] = $estimate_details_data;

      }

      $this->load->view('technician/estimate/multiple_pdf_estimate_print',$data);


  }

public function changeStatus() {

  $data =  $this->input->post();
  // $data = array(
  //   'status' => 2,
  //   'estimate_id' => 1541
  // );

  $where = array(
    'estimate_id' =>$data['estimate_id']
  );

  if ($data['status']==3 ) {

    $estimate_details =  $this->EstimateModal->getOneEstimate($where);
   
	//assign/update property to program  
    $param = array(
      'program_id'=>$estimate_details->program_id,
      'property_id'=>$estimate_details->property_id
    );

    $check = $this->EstimateModal->getOneProgramProperty($param);
   
    if ($check) {
       $result2 = $this->EstimateModal->updateProgramProperty(array('property_program_id'=>$check->property_program_id), $param);

    } else {
       $result2 = $this->EstimateModal->assignProgramProperty($param);
    }
  }


  // if accpeting estimate
  // echo "<pre>";
  if ($data['status'] == 2) {
      
    $estimate_details =  $this->EstimateModal->getOneEstimate($where);

    // if one time program invoiceing
    if ($estimate_details->program_price == 1) {

      $user_id     = $this->session->userdata['user_id'];
      $company_id  = $estimate_details->company_id;
      $customer_id = $estimate_details->customer_id;
      $property_id = $estimate_details->property_id;
      $program_id  = $estimate_details->program_id;
      $estimate_id = $data['estimate_id'];
      $date        = date('Y-m-d', time());
      $date_time   = date('Y-m-d H:m:s', time());

      $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
      $property_details = $this->PropertyModel->getOneProperty(array('property_id'=> $property_id));

      // get estimate total cost
      $total_estimate_cost = 0;
      $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
      // echo "<pre>";
      // print_r($estimate_price_overide_data);
      // die();

      foreach ($estimate_price_overide_data as $es_job) {

        if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
            $job_cost = $es_job->price_override;
        } else {

            $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id'=>$property_id,'program_id' => $program_id));

            if($priceOverrideData->is_price_override_set == 1){
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
        $total_estimate_cost += $job_cost;
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

      $total = $total_estimate_cost;

      // create invoice for estimate
      $inv_param = array(
          'user_id' => $user_id,
          'company_id' => $company_id,
          'customer_id' => $customer_id,
          'property_id' => $property_id,
          'invoice_date' => $date,
          'description' => 'Invoice From Estimate',
          'cost' => $total,
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
                  'tax_amount' => $total*$tax_details['tax_value']/100
                );
                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                $total_tax_amount +=  $invoice_tax_details['tax_amount'];
              }                          
            }
          }

          //create invoice in quickbooks
          // $QBO_fallback = false;
          // if (isset($data->job_id) && !empty($data->job_id)) {
          //     $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));
          //     if (isset($QBO_job_details) && !empty($QBO_job_details)) {
          //         $single_job_desc = $QBO_job_details->job_description;
          //         $single_job_name = $QBO_job_details->job_name;
          //         $quickbook_invoice_id = $this->QuickBookInv($invoice_details, $single_job_desc, $single_job_name);
          //     } else {
          //         $QBO_fallback = true;
          //     }
          // } else {
          //     $QBO_fallback = true;
          // }
          // if ($QBO_fallback == true) {
          //     $quickbook_invoice_id = $this->QuickBookInv($invoice_details, "", "");
          // }
          // if ($quickbook_invoice_id) {                                        
          //   $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));
          // }
          
          $assign_program_param = array(
              'property_id'           => $property_id,
              'program_id'            => $program_id,
              'price_override'        => 0,
              'is_price_override_set' => 0,
          );
          $property_program_id = $this->PropertyModel->assignProgram($assign_program_param);

          // where estimate jobs are stored
          $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
          // print_r($estimate_price_overide_data);

          foreach ($estimate_price_overide_data as $es_job) {

            if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                $job_cost = $es_job->price_override;
            } else {

                $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id'=>$property_id,'program_id' => $program_id));
    
                if($priceOverrideData->is_price_override_set == 1){
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
                'program_id'          => $program_id,
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
    } else {

      $estimate_details =  $this->EstimateModal->getOneEstimate($where);
      $param = array(
        'program_id'=>$estimate_details->program_id,
        'property_id'=>$estimate_details->property_id
      );
      $check = $this->EstimateModal->getOneProgramProperty($param);
      if ($check) {
         $result2 = $this->EstimateModal->updateProgramProperty(array('property_program_id'=>$check->property_program_id), $param);
      } else {
         $result2 = $this->EstimateModal->assignProgramProperty($param);
      }
    }
  }


  $param = array(
    'status' =>$data['status'],
    'estimate_update' => date("Y-m-d H:i:s")
  );
 
  if ($data['status']==3) {
      $param['payment_created'] = date("Y-m-d H:i:s");
  }

  $where = array(
    'estimate_id' =>$data['estimate_id']
  );

  $result = $this->EstimateModal->updateEstimate($where,$param);  
  if ($result) {
      echo "true";
  } else {
      echo "false";
  }
	
	$estimate_details =  $this->EstimateModal->getOneEstimate($where);
	
	
	// Adding Email and text logic here
	
	$property = $this->PropertyModel->getOneProperty(array('property_id'=>$estimate_details->property_id));

    $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id'=>$estimate_details->property_id));
		
    $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_id->customer_id));


      $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' =>$customer_id->customer_id,'is_email'=>1,'program_id'=>$estimate_details->program_id,'property_id' =>$estimate_details->property_id));


       $where = array('company_id' =>$this->session->userdata['company_id']);
       $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

       $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

      $emaildata['accepted_date'] = date("Y-m-d H:i:s");

      $where['is_smtp'] = 1;
      $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
	
     if (!$company_email_details) {
       $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();			
     } 

	$body  = $this->load->view('email/estimate_accepted_email',$emaildata,true);

      if ($emaildata['company_email_details']->estimate_accepted_status==1) {

        $res =   Send_Mail_dynamic($company_email_details,$emaildata['customerData']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Accepted',$emaildata['customerData']->secondary_email);
      }

      if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->estimate_accepted_status_text==1 && $emaildata['customerData']->is_mobile_text==1) {
		 
		  
          //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->estimate_accepted_text);
		   
         $text_res = Send_Text_dynamic($emaildata['customerData']->phone,$emaildata['company_email_details']->estimate_accepted_text,'Estimate Accepted');
      }

	
	
	// End Adding Email and Text logic here
}



public function sendPdfMail() {

  $company_id = $this->session->spraye_technician_login->company_id;

  $estimate_id =   $this->input->post('estimate_id');
  $customer_id  = $this->input->post('customer_id');
	
   // get second message
  $message  = $this->input->post('message');
  $data['msgtext'] =   $message[0];
	
   // get first message	
  $estimate_estimate = $this->EstimateModal->getOneEstimate(['estimate_id' => $estimate_id]);	
  $data['msgtext_one'] = $estimate_estimate->notes;

  $where = array('estimate_id' =>$estimate_id);    
  $param = array('status' =>1,'estimate_update' => date("Y-m-d H:i:s"));   
  $this->EstimateModal->updateEstimate($where,$param);


  $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
  
  $data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
  $data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);

  $where_company = array('company_id' =>$company_id);

  $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
  $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;

  $body = $this->load->view('technician/estimate/estimate_email',$data,true);


  
   $where_company['is_smtp'] = 1;
   $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
              
   if (!$company_email_details) {
       $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
     } 

     $res =   Send_Mail_dynamic($company_email_details, $data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details',$data['customer_details']->secondary_email);     
     print_r($res); 
      // echo 1; 
 

  }

    public function sendPdfMailToSelected() {
        $company_id = $this->session->spraye_technician_login->company_id;
        $group_id_array =   $this->input->post('group_id_array');
        

        $message  = $this->input->post('message');        
        $data['msgtext'] =   $message[0];

         if (!empty($group_id_array)) {
      
            foreach ($group_id_array as $key => $value) {
               $in_ct = explode(':', $value);
                $estimate_id =  $in_ct[0];
                $customer_id =  $in_ct[1];
                $where = array('estimate_id' =>$estimate_id);    
                $param = array('status' =>1,'estimate_update' => date("Y-m-d H:i:s"));   
                $this->EstimateModal->updateEstimate($where,$param);


                $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
                $data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
                $data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);

			   // get first message	
			  $estimate_estimate = $this->EstimateModal->getOneEstimate(['estimate_id' => $estimate_id]);	
			  $data['msgtext_one'] = $estimate_estimate->notes;

                $where_company = array('company_id' =>$company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
         
                $body = $this->load->view('technician/estimate/estimate_email',$data,true);

                // echo $body;

                   $where_company['is_smtp'] = 1;
                    
                    $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                    
                   if (!$company_email_details) {

                   
                         $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

                     } 

                  $res =    Send_Mail_dynamic($company_email_details,$data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details',$data['customer_details']->secondary_email);     


            } 
        }

         if (isset($res)) {
            echo 1; 
         } else {
            echo 0;
         }
    }


  public function deletemultipleEstimates($value=''){
      $estimates_ids = $this->input->post('estimates_ids');
      if (!empty($estimates_ids)) {
          foreach ($estimates_ids as $key => $value) {
            
            $where = array('estimate_id'=>$value);
            $this->EstimateModal->deleteEstimate($where); 
          }
        echo 1; 
      } else {
          echo 0;
      }
  }

  public function bulkRenewalProgramsList(){
      $where =  array('company_id' => $this->session->userdata['spraye_technician_login']->company_id, 'program_active' => 1, 'ad_hoc'=>0);

      $data['programData'] = $this->ProgramModel->get_all_program($where);
      if (!empty($data['programData'])) {
            foreach ($data['programData'] as $key => $value) {

              $data['programData'][$key]->job_id =  $this->ProgramModel->getProgramAssignJobs(array('program_id' =>$value->program_id));


                $data['programData'][$key]->property_details =  $this->ProgramModel->getAllproperty(array('program_id' =>$value->program_id));

          }
          
      }



      $page["active_sidebar"] = "estimatenav";
      $page["page_name"] = 'Bulk Program Renewal Select';
      $page["page_content"] = $this->load->view("technician/estimate/select_program_renewal", $data, TRUE);
      $this->layout->technicianTemplateTableDash($page);
  }

  	public function addBulkRenewalProgram($programID = NULL) 
    {
      if (empty($programID)) 
      {
        $programID=$this->uri->segment(4);
      }
      $where = array('property_tbl.company_id' => $this->session->userdata['company_id'] );

      $data['joblist'] = $this->ProgramModel->getJobList(array('company_id' => $this->session->userdata['company_id'] ));
      $data['propertylist'] = $this->PropertyModel->get_all_list_properties($where);
      $data['programData'] = $this->ProgramModel->getProgramDetail($programID);
      $selecteddata = $this->ProgramModel->getSelectedJobsAnother($programID);
      $data['selectedpropertylist'] = $this->ProgramModel->getSelectedPropertyForBulkEstimates($programID);
      $where2 = array('company_id' =>$this->session->userdata['company_id']);
      $data['setting_details'] = $this->CompanyModel->getOneCompany($where2);
      if(count($data['selectedpropertylist']) == 0)
      {
        $tmpPropertyIds = $this->EstimateModal->getEstimatePropertiesById($programID);
        $data['selectedpropertylist'] = array_map(function($id) {
          $r = $this->PropertyModel->getPropertyDetail($id);
          return (object)$r;
        }, $tmpPropertyIds);        
      }
      $data['selectedjobid']  = array();
      $data['selectedjobname']  = array();
      $data['selectedproperties']  = array();
      
      if (!empty($selecteddata)) 
      {
        foreach ($selecteddata as $value) 
        {
          $data['selectedjobid'][] = $value->job_id;
          $data['selectedjobname'][] = $value->job_name;
        }
      }
      if (!empty($data['selectedpropertylist'])) 
      {
        foreach ($data['selectedpropertylist'] as $value) 
        {
          $value->program_id = $programID;
          $data['selectedproperties'][] = $value->property_id;
        }

        foreach ($data['selectedproperties'] as $key => $value) 
        {
          $customerId = $this->PropertyModel->getSelectedCustomer($value);
          if(!empty($customerId))
          {
            $customer = $this->CustomerModel->getCustomerDetail($customerId[0]->customer_id);

          } else
          {
            $customer = array();
          }
          $data['selectedpropertylist'][$key]->customer_details = $customer;
        }
      }
      $coupon_where = array(
        'company_id' => $this->session->userdata['company_id'],
        'type' => 0
      );    
      $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);            
      $data['selecteddata'] = $selecteddata;

      // Code rewrite to match editProgram          
      // foreach($data['selectedpropertylist'] as $prop)
      // {
      //   if(isset($prop->customer_details['customer_id']))
      //   {
      //     $where = array(
      //       'property_id' => $prop->property_id, 
      //       'program_id' => $prop->program_id,
      //       'customer_id' => $prop->customer_details['customer_id']
      //     );
      //   } else {
      //     $where = array(
      //       'property_id' => $prop->property_id, 
      //       'program_id' => $prop->program_id
      //     );
      //   }
      //   $prop->priceOverrideData = $this->EstimateModal->getProgramPropertyJobPriceOverrides($where);
      //   // die(print_r($prop->priceOverrideData));
      // }

      $propertyJobPriceOverrides = (array)[];
      if(!empty($selecteddata) && !empty($data['selectedpropertylist']))
      {
        foreach($data['selectedpropertylist'] as $prop)
        {
          
          $tmpProp = (object)[];
          $tmpProp->property_id = $prop->property_id;
          $tmpProp->program_id = $prop->program_id;
          $tmpProp->jobs = (array)[];
          $where = array(
            'property_id' => $tmpProp->property_id,
            'program_id' => $tmpProp->program_id
          );
          $results = $this->EstimateModal->getProgramPropertyJobPriceOverrides($where);
          $price_set_flag = null;
          if(!empty($results))
          {
            foreach($selecteddata as $job)
            {
              $jobDetails = (object)[];
              $jobDetails->job_id = $job->job_id;
              foreach($results as $result)
              {
                if($result->job_id == $job->job_id && isset($result->is_price_override_set))
                {
                  $jobDetails->is_price_override_set = $result->is_price_override_set;
                  $jobDetails->price_override = $result->price_override;
                  $price_set_flag = 1;
                }
              }
              array_push($tmpProp->jobs, $jobDetails);
            }
          }
          $tmpProp->is_job_price_override_set = $price_set_flag;
          array_push($propertyJobPriceOverrides, $tmpProp);
        }
      }
      $data['propertyJobPriceOverrides'] = $propertyJobPriceOverrides;      

      //die(print_r(json_encode($data['selectedpropertylist'][0])));
      // die(print_r(json_encode($data['selecteddata'])));
      // die(print_r(json_encode($data['propertyJobPriceOverrides'][2])));

      $page["active_sidebar"] = "estimatenav";
      $page["page_name"] = "Bulk Renewal";
      $page["page_content"] = $this->load->view("technician/estimate/add_bulk_renewal", $data, TRUE);
      $this->layout->superAdminTemplate($page);
    }

    public function addBulkRenewalProgramData($program_id) 
    {
      $data = $this->input->post();
      //Validate Form Data
      $this->form_validation->set_rules('program_name', 'Name', 'required');
      $this->form_validation->set_rules('program_price', 'Price', 'required');
      $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
      $this->form_validation->set_rules('program_job', 'Service', 'trim|required');
      if ($this->form_validation->run() == FALSE) 
      {
        // echo validation_errors();
        $this->addBulkRenewalProgram($program_id);
      } else 
      {
        // die(print_r(json_encode($data)));
        if(isset($data['propertylistarray_temp']) && !is_array($data['propertylistarray_temp']))
        {
          $data['propertylistarray_temp'] = explode(',', $data['propertylistarray_temp']);
        }
        $user_id = $this->session->spraye_technician_login->user_id;
        $company_id = $this->session->spraye_technician_login->company_id;
        $tmpProgData = $this->ProgramModel->getProgramDetail($program_id);
        //Set price strings array that will be used when setting program name
        $pricing_strs = array('One Time Project Invoicing', 'Invoiced at Job Completion', 'Manual Billing');
        //Create Program array
        $program = array();
        $jobsModded = ($data['program_job_original'] !== $data['program_job']) ? true : false;
        //$priceModded = ($tmpProgData['program_price'] !== $data['program_price']) ? true : false;
        $custom_name_set = ($data['program_name'] != $data['original_program_name']) ? true : false;
        if($custom_name_set)
        {
          if(preg_match("/($pricing_strs[0]|$pricing_strs[1]|$pricing_strs[2])/i", $data['program_name']))
          {
            $program_name = $data['program_name'];
          } else 
          { 
            $program_name = $data['program_name'].' - '.$pricing_strs[$data['program_price'] - 1];
          }
        } elseif($jobsModded) 
        {
          $diffJobs = (array)[];
          //Get addtional added job names, if any
          $originalJobs = explode(',',$data['program_job_original']);
          $moddedJobs = explode(',',$data['program_job']);
          foreach($originalJobs as $job)
          {
            if(!(in_array($job, $moddedJobs))) 
            {
              array_push($diffJobs, $job);
            }
          }
          foreach($moddedJobs as $job)
          {
            if(!(in_array($job, $originalJobs)))
            {
              array_push($diffJobs, $job);
            }
          }
          if(count($diffJobs) > 0)
          {
            $jobNames = (array)[];
            foreach($diffJobs as $job)
            {
              $r = $this->JobModel->getOneJob(array('job_id' => $job));
              array_push($jobNames,$r->job_name);
            }
            $str_append = implode('+', $jobNames);
            $program_name = (strpos($data['program_name'], ' - ') !== false) ? trim(explode(' - ', $data['program_name'])[0]).'+'.$str_append.' - '.$pricing_strs[$data['program_price'] - 1].' - Copy' : $data['program_name'].'+'.$str_append.' - '.$pricing_strs[$data['program_price'] - 1].' - Copy';
          } else 
          {
            $program_name = (strpos($data['program_name'], ' - ') !== false) ? trim(explode(' - ', $data['program_name'])[0]).' - '.$pricing_strs[$data['program_price'] - 1].' - Copy' : $data['program_name'].' - '.$pricing_strs[$data['program_price'] - 1].' - Copy';
          }
        } else {
          $program_name = $data['program_name'];
        }

        $param = array(
          'user_id' => $user_id,
          'company_id' => $company_id,
          'program_name' => $program_name,
          'program_price' => $data['program_price'],
          'program_notes' => $data['program_notes']
        //'program_job' => $data['program_job']
        );
        //Create Program
        $program['program_id'] = $this->ProgramModel->insert_program($param);
        // create and add program jobs from joblistarray
        if (!empty($data['program_job'])) 
        {
          $n=1;
          if(!is_array($data['program_job'])) 
          { 
            $data['program_job'] = explode(",", $data['program_job'] );
          }
          foreach ($data['program_job'] as $k=>$val) 
          { 
            $param2 = array(
              'program_id' => $program['program_id'],
              'job_id' => $val,
              'priority' =>$n
            ); 
            //Assign jobs to program
            $result1 = $this->ProgramModel->assignProgramJobs($param2);

            $n++;
          }
        }          
        // Remove unselected properties from propertylistarray
        $data['propertylistarray'] = json_decode($data['propertylistarray']);
        $propertyListArrayRebuild = (array)[];
        foreach($data['propertylistarray'] as $property)
        {
          foreach($data['propertylistarray_temp'] as $pTemp)
          {
            if($property->property_id == $pTemp)
            {
              array_push($propertyListArrayRebuild, $property);
            }
          }
        }
        $data['propertylistarray'] = $propertyListArrayRebuild;
        $data['propertylistarray'] = json_encode(array_values($data['propertylistarray']));
        // if properties then assign program to properties
        if ( isset($data['propertylistarray']) &&  !empty($data['propertylistarray']) ) 
        {
          $program['properties'] = array();
          foreach (json_decode($data['propertylistarray']) as $value)
          {
            // Create New Estimate and Service Level Price Overrides
            $dtSelected = json_decode($data['dtSelectedRows']);
            $propCustDetails = null;
            for($z=0;$z<count($dtSelected);$z++)
            {
              if($dtSelected[$z]->property_id == $value->property_id)
              {
                $propCustDetails = $dtSelected[$z];
                break;
              }
            }
            $data['customer_email'] = $propCustDetails->customer_details->email ?? '';
            $estimateParam = array(
              'company_id' => $company_id,
              'customer_id' => $propCustDetails->customer_details->customer_id,
              'property_id' => $value->property_id,
              'estimate_date' =>  date("Y-m-d"),
              'program_id' => $program['program_id'],
              'status' => $data['status'],
              'estimate_created_date' => date("Y-m-d H:i:s"),
              'estimate_update' => date("Y-m-d H:i:s"),
              'notes' => $data['program_notes'],
            );
            $estimate_id = $this->EstimateModal->CreateOneEstimate($estimateParam);
            $messages = (array)[];
            if($estimate_id) 
            {
              if(isset($data['joblistarray']) &&  !empty($data['joblistarray'])) 
              {
                $prop_job_list = array_filter(json_decode($data['joblistarray']), function($j) use ($estimateParam)
                {
                  if($estimateParam['property_id'] == $j->property_id)
                  {
                    return $j;
                  }
                });
                foreach ($prop_job_list as $job) 
                {
                  $estimateParam2 = array(
                    'estimate_id' => $estimate_id,
                    'customer_id' => $estimateParam['customer_id'],
                    'property_id' => $job->property_id,
                    'program_id' => $program['program_id'],
                    'job_id' => $job->job_id,
                    'price_override' => $job->price_override,
                    'is_price_override_set' => $job->is_price_override_set,
                    'created_at' => date("Y-m-d H:i:s")
                  );
                  $this->EstimateModal->CreateOneEstimatePriceOverRide($estimateParam2);
                  // Check if record exists
                  $where = array(
                    'program_id' => $program['program_id'],
                    'job_id' => $job->job_id,
                    'property_id' => $job->property_id
                  );
                  $queryResult = $this->ProgramModel->getProgramPropertyJobsOverrides($where);
                  if(!empty($queryResult))
                  {
                    $updateData = array(
                      'price_override' => $job->price_override,
                      'is_price_override_set' => $job->is_price_override_set,
                    );
                    $this->ProgramModel->updateProgramPropertyJobOverrides($updateData, $where);
                  } else 
                  {
                    $createData = array(
                      'program_id' => $program['program_id'],
                      'job_id' => $job->job_id,
                      'property_id' => $job->property_id,
                      'price_override' => $job->price_override,
                      'is_price_override_set' => $job->is_price_override_set,
                    );
                    $this->ProgramModel->insert_price_override($createData);
                  }
                }
              }
			        //handle status if status == send estimate
              if ($data['status']==1 && $data['customer_email']!='' ) 
              {
                if(isset($data['program_notes']) && $data['program_notes'] != '')
                {
                  $email_data['msgtext'] = $data['program_notes'];
                } else
                {
                  $email_data['msgtext'] = ''; 
                }

                  $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($estimateParam['customer_id']);
                  $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
                  $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);
                  $where_company = array('company_id' =>$company_id);
                  $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                  $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;
                  $body = $this->load->view('technician/estimate/estimate_email',$email_data,true);
                  $where_company['is_smtp'] = 1;
                  $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

                if(!$company_email_details) 
                {
                  $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                } 
                $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details');
              }

              // Estimate End 
              // if ($data['status'] == 1 && $propCustDetails->customer_details->email != '')
              // {					   
              //   // Handle email and text notifications
              //   $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id'=>$value->property_id));
              //   $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_id->customer_id));
              //   $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' =>$customer_id->customer_id,'is_email'=>1,'program_id'=>$result,'property_id' =>$value->property_id));
              //   $where = array('company_id' =>$this->session->userdata['company_id']);
              //   $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
              //   $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
              //   $emaildata['assign_date'] = date("Y-m-d H:i:s");
              //   $where['is_smtp'] = 1;
              //   $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
              //   $body  = $this->load->view('email/program_email',$emaildata,true);
              //   if (!$company_email_details) 
              //   {
              //     $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
              //   }
              //   if ($emaildata['company_email_details']->program_assigned_status==1) 
              //   {
              //     $res =   Send_Mail_dynamic($company_email_details,$emaildata['customerData']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Program Assigned',$emaildata['customerData']->secondary_email);
              //   }
              //   if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text==1 && $emaildata['customerData']->is_mobile_text==1) 
              //   {
              //     $text_res = Send_Text_dynamic($emaildata['customerData']->phone,$emaildata['company_email_details']->program_assigned_text,'Program Assigned');
              //   }
              //   // End Email/Text
              // }
              
              // apply assigned coupons
              if (array_key_exists("assign_onetime_coupons",$data))
              {
                $coupon_ids_arr = $data['assign_onetime_coupons'];
                foreach($coupon_ids_arr as $coupon_id) 
                {
                  $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                  $params = array(
                    'coupon_id' => $coupon_id,
                    'estimate_id' => $estimate_id,
                    'coupon_code' => $coupon_details->code,
                    'coupon_amount' => $coupon_details->amount,
                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                    'coupon_type' => $coupon_details->type,
                    'expiration_date' => $coupon_details->expiration_date
                  );
                  $this->CouponModel->CreateOneCouponEstimate($params);
                }
              }
              // check global coupons & assign if so
              $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $estimateParam['customer_id']));
              if(!empty($coupon_customers)) 
              {
                foreach($coupon_customers as $coupon_customer) 
                {
                  $coupon_id = $coupon_customer->coupon_id;
                  $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                  $params = array(
                      'coupon_id' => $coupon_id,
                      'estimate_id' => $estimate_id,
                      'coupon_code' => $coupon_details->code,
                      'coupon_amount' => $coupon_details->amount,
                      'coupon_amount_calculation' => $coupon_details->amount_calculation,
                      'coupon_type' => $coupon_details->type,
                      'expiration_date' => $coupon_details->expiration_date
                  );
                  $this->CouponModel->CreateOneCouponEstimate($params);
                  // $coupon_id = $coupon_customer->coupon_id;
                  // $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                  // $params = array(
                  //     'coupon_id' => $coupon_id,
                  //     'invoice_id' => $invoice_id,
                  //     'coupon_code' => $coupon_details->code,
                  //     'coupon_amount' => $coupon_details->amount,
                  //     'coupon_amount_calculation' => $coupon_details->amount_calculation,
                  //     'coupon_type' => $coupon_details->type
                  // );
                  // $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                }
              }
              array_push($messages, '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> added successfully</div>');
            } else 
            {
              array_push($messages, '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
            }
          }
          if(isset($messages) && count($messages) > 0) 
          {
            $final_message = implode('<br>', $messages);
            $this->session->set_flashdata('message', $final_message);
            redirect("technician/dashboard");
          } else 
          {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
            redirect("technician/dashboard");
          }    
        }
      }
    } // Function End

    /** This is a test query to return program property job price override(s) **/
    /** the model will search based on any combination of columns specified **/
    /** the returned results may be viewed at: **/
    /** https://emerald-dev7.blayzer.com/admin/Estimates/testPriceOverrideReturn **/
    public function testPriceOverrideReturn()
    {
      $arr = array(
        'program_id' => '1472',
        'property_id' => '25785',
        // 'job_id' => '1186'
      );
      $results = $this->ProgramModel->getProgramPropertyJobsOverrides($arr);
      die(print_r(json_encode($results)));
      return $results;
    }
}