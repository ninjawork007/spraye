<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/geoplugin/geoplugin.class.php';

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require_once APPPATH . '/third_party/sms/Send_Text.php';

require FCPATH . 'vendor/autoload.php';


use QuickBooksOnline\API\Core\ServiceContext;

use QuickBooksOnline\API\DataService\DataService;

use QuickBooksOnline\API\PlatformService\PlatformService;

use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

use QuickBooksOnline\API\Facades\Customer;

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

use QuickBooksOnline\API\Facades\Invoice;

use QuickBooksOnline\API\Facades\Payment;







class Technician extends MY_Controller {

    public function __construct() {



        parent::__construct();



        if (!$this->session->userdata('spraye_technician_login')) {

            return redirect('technician/auth');

        }

        $this->load->library('parser');

        $this->load->library('aws_sdk');

        $this->load->helper('text');

        $this->load->helper('cardconnect_helper');

        $this->load->helper('inventory_helper');

        $this->loadModel();

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        //$this->load->library('lbs');

        $this->load->helper('time_zone_date_time_helper');
    }

    private function loadModel() {

          $this->load->model("Administrator");

          $this->load->model('Technician_model', 'Tech');

          $this->load->model('Job_model', 'JobModel');

          $this->load->model('Invoice_model','INV');

          $this->load->model('AdminTbl_company_model', 'CompanyModel');

          $this->load->model('Company_email_model', 'CompanyEmail');

          $this->load->model('Administratorsuper');

          $this->load->model('AdminTbl_customer_model', 'CustomerModel');

          $this->load->model('Reports_tech_model', 'RP');

          $this->load->model('Sales_tax_model', 'SalesTax');

          $this->load->helper('report_helper');

          $this->load->helper('invoice_helper');

          $this->load->helper('estimate_helper');

          $this->load->model('Basys_request_modal', 'BasysRequest');

        $this->load->model('Cardconnect_model', 'CardConnectModel');

          $this->load->model('../../admin/models/Cardconnect_model', 'CardConnect');

          $this->load->model('Property_sales_tax_model', 'PropertySalesTax');

          $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');

		  $this->load->model('Property_program_job_invoice_model','PropertyProgramJobInvoiceModel');

		  $this->load->model('AdminTbl_program_model', 'ProgramModel');

		  $this->load->model('AdminTbl_property_model', 'PropertyModel');

          $this->load->model('AdminTbl_coupon_model', 'CouponModel');

          $this->load->model('Work_statement_model', 'STATE');
		  $this->load->model('Source_model', 'SourceModel');
		  $this->load->model('Estimate_model', 'EstimateModel');
		  $this->load->model('AdminTbl_tags_model', 'TagsModel');
          $this->load->model('AdminTbl_product_model', 'ProductModel');
          $this->load->model('../modules/admin/models/payment_invoice_logs_model', 'PartialPaymentModel');


    }

    public function timecheck($value='')

        {

          echo date_default_timezone_get();

          echo "<br>";

          echo date("Y-m-d H:i:s");

        }

    public function dashboard($route_id='') {

        $user_id = $this->session->userdata['spraye_technician_login']->user_id;

        $where = array('user_id'=>$user_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id'=>$this->session->userdata['spraye_technician_login']->company_id));

        //Get all COMPLETED services assigned to logged Tech user for "today" where job

        $where_arr_check =  array(

                'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,

                'job_assign_date'=>date("Y-m-d"),

                'is_job_mode'=>1,

                'is_complete' =>1

                );





        $job_assign_details_check = $this->Tech->getAllJobAssignCheck($where_arr_check);
        $data['job_assign_details_check'] = $job_assign_details_check;

		//if this Tech user has completed services "today" then assign current address

         if ($job_assign_details_check) {



           $data['currentaddress'] =    $job_assign_details_check->property_address;

           $data['currentlat'] =    $job_assign_details_check->property_latitude;

           $data['currentlong'] =    $job_assign_details_check->property_longitude;



         } else {
            // anywhere we use the start location we need to check to make sure the tech that is logged in doesn't have a start/end override - if they do that needs to be checked here
            $data['currentaddress'] = $data['setting_details']->start_location;
            $data['currentlat'] =  $data['setting_details']->start_location_lat;
            $data['currentlong'] = $data['setting_details']->start_location_long;
            if($this->session->userdata['spraye_technician_login']->start_location != "") {
                $data['currentaddress'] = $this->session->userdata['spraye_technician_login']->start_location;
            }
            if($this->session->userdata['spraye_technician_login']->start_location_lat != "") {
                $data['currentlat'] = $this->session->userdata['spraye_technician_login']->start_location_lat;
            }
            if($this->session->userdata['spraye_technician_login']->start_location_long != "") {
                $data['currentlong'] = $this->session->userdata['spraye_technician_login']->start_location_long;
            }

         }


		//Get all routes for incomplete services assigned to logged Tech for "today"

        $where_arr =  array(

            'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,

            'technician_job_assign.job_assign_date'=>date("Y-m-d"),

            'is_job_mode'=>0,

        );



        $data['routeDetails'] = $this->Tech->getRoutsByJobAssign($where_arr);



        if ($route_id=='') {



            if ($data['routeDetails']) {

                $where_arr['route_id'] = $data['routeDetails'][0]['route_id'];

                $data['current_route'] = $where_arr['route_id']; // blank

            } else {

                $data['current_route'] = $route_id;

            }

        } else {

            $where_arr['route_id'] = $route_id;

            $data['current_route'] = $route_id;

        }





		//Get all incomplete services assigned to logged Tech for "today"

        $get_job_assign_details = $this->Tech->getAllJobAssign($where_arr);

        $data['routeJobCount'] = $this->Tech->getAllRouteJobsCount($data['current_route']);
        $data['is_first_job'] = ( is_array($get_job_assign_details) && $data['routeJobCount'] == count($get_job_assign_details) ) ? 1 : 0;

		$job_assign_details = array();



		foreach($get_job_assign_details as $key=>$job){

			//filter services by property_id

			if(is_array($job_assign_details) && array_key_exists($job['property_id'], $job_assign_details)){

				$job_assign_details[$job['property_id']][] = $job;



			}else{

				$job_assign_details[$job['property_id']] = array();

				$job_assign_details[$job['property_id']][] = $job;



			}

		}



		// die(print_r($job_assign_details));

		  $data['job_assign_details'] = $job_assign_details;

          $data['job_assign_details'] = $job_assign_details;

        $driver_id = $this->session->userdata['spraye_technician_login']->id;
        $data['assigned_vehicle'] = $this->RP->getTechAssignedVehicle($driver_id);
        $company_id = $this->session->userdata['spraye_technician_login']->company_id;
        $data['vehicles'] = $this->CompanyModel->getAllFleetVehicles($company_id);
        $data['note_types'] = $this->CompanyModel->getNoteTypes($company_id);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $page["active_sidebar"] = "dashboardnav";
        $page["page_name"] = "Day at a Glance";
        $page["page_content"] = $this->load->view("technician/dashboard", $data, TRUE);
        $this->layout->technicianTemplateTableDash($page);

    }

    public function dashboardJsonData($route_id=''){





          $OptimizedStops  =   $this->input->post('OptimizedStops');

          array_shift($OptimizedStops);

          array_pop($OptimizedStops);

          $tmp  =  array();



		//get routes for incomplete services assigned to logged tech and scheduled for "today"

          $where_arr =  array(

            'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,

            'technician_job_assign.job_assign_date'=>date("Y-m-d"),

            'is_job_mode'=>0,

            );



          $data['routeDetails'] = $this->Tech->getRoutsByJobAssign($where_arr);



           if ($route_id=='') {

              if ($data['routeDetails']) {

                  $where_arr['route_id'] = $data['routeDetails'][0]['route_id'];

              }

          } else {

              $where_arr['route_id'] = $route_id;

          }





        $job_assign_details_array = $this->Tech->getAllJobAssign($where_arr);
        if (!empty($job_assign_details_array)) {
			$temp_job_assign_details_array=[];
			foreach ($job_assign_details_array as $job_assign_detail) {
				// get/set tags names by id
				$temp_tags=$this->PropertyModel->getTags_Name_By_Id($job_assign_detail['tags']);
				$tags=$this->PropertyModel->getTags_Name_By_Id($job_assign_detail['tags']);
				$tagHtml = '<div class="wrapper-tags" style="padding-top:5px">';
				if(!empty($tags)){
					$tags = explode(',',$tags);
					foreach($tags as $tag){
						if(isset($tag) && $tag=="New Customer"){
							$tagHtml .= '<span class="badge badge-success">'.$tag.'</span>&nbsp;&nbsp;';
						}else{
							$tagHtml .= '<span class="badge badge-primary">'.$tag.'</span>&nbsp;&nbsp;';
						}

					}
				}
				$tagHtml .= '</div>';
				$job_assign_detail['tags'] = $tagHtml;
				$temp_job_assign_details_array[]=$job_assign_detail;
			}
			$job_assign_details_array=$temp_job_assign_details_array;

            // $OptimizedStops is an array of the addresses for that days work
            // this data for the address currently just looks up the propery info based on the technician_job_assign entry
            foreach ($OptimizedStops as $key => $value) {



                  $res = $this->searchForAddress($value,$job_assign_details_array);


                  $tmp[] = $res['value'];

                  unset($job_assign_details_array[$res['key']]);

                  array_values($job_assign_details_array);

            }



        }



        echo json_encode($tmp);



    }


    function searchForAddress($id, $array) {

    // var_dump($id);


    foreach ($array as $key => $val) {

        // echo $val['property_address'];

        $array[$key]['date'] = date("d", strtotime($val['job_assign_date']));
        $array[$key]['day'] = date("D", strtotime($val['job_assign_date']));
            $array[$key]['phone'] = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $val['phone']);

      //customer notification flags
      $notify_array = $val['pre_service_notification'] ? json_decode($val['pre_service_notification']):[];
      $array[$key]['pre_service_notification'] = "";
      if(is_array($notify_array) && in_array(1,$notify_array)){
          $array[$key]['pre_service_notification'] = "<div class='label label-primary myspan' style='padding: 0 2px; margin-right: 0.5rem'>Call</div>";
      }
      if(is_array($notify_array) && in_array(4,$notify_array)){
          $array[$key]['pre_service_notification'] .= "<div class='label label-success myspan' style='padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
      }
       if(is_array($notify_array) && (in_array(2,$notify_array) ||in_array(3,$notify_array))){
           $array[$key]['pre_service_notification'] .= "<div class='label label-info myspan' style='padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
       }


                $array[$key]['date'] = date("d", strtotime($val['job_assign_date']));



                $array[$key]['day'] = date("D", strtotime($val['job_assign_date']));



                $array[$key]['phone'] = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $val['phone']);



        if ($val['property_address'] == $id) {

            // echo "<br>t<br>";

            return array('key'=>$key,'value'=>$array[$key]);

        }

    }

        return null;



    }

    public function getJobDetailsByAjax() {
    	$property_address  = $this->input->post('property_address');
    	$data = $this->Tech->getAllJobAssignbyAjax($property_address);
    	echo $this->db->last_query();
    }

    public function jobDetails($property_id) {

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['product_details'] = $this->ProductModel->get_all_product($where);
        //die(print_r($data['product_details']));
        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $where = array('user_id'=>$this->session->userdata['spraye_technician_login']->user_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id'=>$this->session->userdata['spraye_technician_login']->company_id));
        $where_arr_check =  array(
            'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,
            'job_assign_date'=>date("Y-m-d"),
            'is_job_mode'=>1,
            'is_complete' =>1
        );

        $job_assign_details_check = $this->Tech->getAllJobAssignCheck($where_arr_check);

        if ($job_assign_details_check) {
        $data['currentaddress'] =    $job_assign_details_check->property_address;
        $data['currentlat'] =    $job_assign_details_check->property_latitude;
        $data['currentlong'] =    $job_assign_details_check->property_longitude;
        } else {
        $data['currentaddress'] = $data['setting_details']->start_location;
        $data['currentlat'] =  $data['setting_details']->start_location_lat;
        $data['currentlong'] = $data['setting_details']->start_location_long;
        }

        $where2 = array(
            'technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,
            'technician_job_assign.property_id'=> $property_id,
            'job_assign_date'=>date("Y-m-d"),
            'is_job_mode' => 0
        );

    if ($job_assign_details_check) {
        $data['currentaddress'] =    $job_assign_details_check->property_address;
        $data['currentlat'] =    $job_assign_details_check->property_latitude;
        $data['currentlong'] =    $job_assign_details_check->property_longitude;
    } else {
        $data['currentaddress'] = $data['setting_details']->start_location;
        $data['currentlat'] =  $data['setting_details']->start_location_lat;
        $data['currentlong'] = $data['setting_details']->start_location_long;
        if($this->session->userdata['spraye_technician_login']->start_location != "") {
            $data['currentaddress'] = $this->session->userdata['spraye_technician_login']->start_location;
        }
        if($this->session->userdata['spraye_technician_login']->start_location_lat != "") {
            $data['currentlat'] = $this->session->userdata['spraye_technician_login']->start_location_lat;
        }
        if($this->session->userdata['spraye_technician_login']->start_location_long != "") {
            $data['currentlong'] = $this->session->userdata['spraye_technician_login']->start_location_long;
        }
    }


    $data['job_assign_details'] = $this->Tech->getAllJobAssign($where2);
	#tags
    if (!empty($data['job_assign_details'])) {
		$temp_job_assign_details_array=[];
		foreach ($data['job_assign_details'] as $job_assign_detail) {
		// get/set tags names by id
			$job_assign_detail['tags']=$this->PropertyModel->getTags_Name_By_Id($job_assign_detail['tags']);
			$temp_job_assign_details_array[]=$job_assign_detail;
		}
		$job_assign_details_array=$temp_job_assign_details_array;
		$data['job_assign_details_tag']=$job_assign_details_array;
	}else{
		//die(print_r($data['job_assign_details']));
		redirect('technician/dashboard/');
	}
    //customer notification flags
    $notify_array = $data['job_assign_details'][0]['pre_service_notification'] ? json_decode($data['job_assign_details'][0]['pre_service_notification']):[];

    $data['job_assign_details'][0]['pre_service_notification'] = "";
    if(isset($notify_array) && in_array(1,$notify_array)){
      $data['job_assign_details'][0]['pre_service_notification'] .= "<div class='label label-primary myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div>";
    }
    if(isset($notify_array) && in_array(4,$notify_array)){
      $data['job_assign_details'][0]['pre_service_notification'] .= "<div class='label label-success myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
    }
      if(is_array($notify_array) && (in_array(2,$notify_array) ||  in_array(3,$notify_array))){
          $data['job_assign_details'][0]['pre_service_notification'] .= "<div class='label label-info myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
      }


        if (!$data['job_assign_details']) {
        redirect('technician/dashboard/');
        }

        $data['property_details'] = $this ->Tech->getPropertyDetails(array('property_id' =>$property_id));

        $wind = $this->getWindSpeed($data['property_details']->property_latitude,$data['property_details']->property_longitude);

        $data['current_wind_speed'] =$wind['speed'];

		//get all services for property

		$services = array();
		$service_ids = array();
		$tech_ids = array();

		foreach($data['job_assign_details'] as $key=>$row){

			$where3 = array(
                'technician_job_assign_id' => $row['technician_job_assign_id'],
                'is_job_mode' => 0,
            );

			$jobAssign = $this->Tech->GetOneRow($where3);

			$service_ids[] = $jobAssign->job_id;

			$product_details = $this->Tech->getAllProductDetails(array('job_id' =>$jobAssign->job_id));

			//check windspeed

            if ($product_details) {
                foreach ($product_details as $key2 => $value2) {
                    if ( $data['current_wind_speed'] > $value2->max_wind_speed ) {
                        $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>The </strong>wind speed is above the max wind speed for a product you are applying on this service </div>');
                    }
                }
            }

            $services[$key]['technician_job_assign_id'] = $row['technician_job_assign_id'];
            $services[$key]['product_details'] = $product_details;
            $services[$key]['product_details_for_cal'] = $this->Tech->getAllProductDetails(array('job_id' =>$jobAssign->job_id,'mixture_application_rate !='=>0,'application_rate !='=>0 ));
            $services[$key]['product_details_dif'] = $this->Tech->getAllProductDetails_diff(array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id));

            $services[$key]['job_details'] = $this->Tech->getJobDetails(array('job_id' =>$jobAssign->job_id));
            $services[$key]['program_details']= array(
                'program_name'=>$row['program_name'],
                'program_price'=>$row['program_price'],
                'program_notes'=>$row['program_notes']
            );

            $tech_ids[] = $row['technician_job_assign_id'];
        }

		$data['tech_assign_ids'] = implode(',',$tech_ids);
		$data['services'] = $services;
 		$data['allservicelist'] = $this->JobModel->getJobList(array('company_id' => $this->session->userdata['spraye_technician_login']->company_id));

        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['spraye_technician_login']->company_id));
        $data['selectedpropertyconditions'] = array();
        $getAssignedConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$property_id));
        if(!empty($getAssignedConditions)){
            foreach($getAssignedConditions as $condition){
                $data['selectedpropertyconditions'][]=$condition->property_condition_id;
            }
        }
        $data['property_conditions'] = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$property_id));
	  	$customerQueryResults = $this->CustomerModel->getOnecustomerPropert(array('property_id' => $property_id));

     	$customer_id = $customerQueryResults->customer_id;


        $customer_id = $customerQueryResults->customer_id;
        $company_id = ($isTech) ? $this->session->userdata['spraye_technician_login']->company_id : $this->session->userdata['company_id'];
        $data['customerData'] = $this->CustomerModel->getCustomerDetail($customer_id);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect(array('company_id' => $company_id));
        $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
        $data['property_alerts_json'] = $data['property_details']->alerts;
        //var_dump($data['property_alerts_json']);
        if($customer_id) {
            $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
            $data['customer_alerts_json'] = @$customer_details['alert'];
            // var_dump($data['customer_alerts_json']);
        }
		$data['customerData'] = $this->CustomerModel->getCustomerDetail($customer_id);
		$data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect(array('company_id' => $company_id));
		$data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
		$data['property_alerts_json'] = $data['property_details']->alerts;
		if($customer_id) {
            $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
                //echo print_r($customer_details);
            $data['customer_alerts_json'] = $customer_details['alerts'];
		}

         /* Get Note Task Types */

        $data['note_types'] = $this->CompanyModel->getNoteTypes($company_id);
        $service_specific_id = "";
        foreach($data['note_types'] as $type){
            if($type->type_name == "Service-Specific" && $type->type_company_id ==0){
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;

        $all_notes = (array)[];

        $where = array(

            'note_property_id' => $property_id,

            'note_category' => 0

        );

        $property_notes = $this->CompanyModel->getPropertyTechViewNotes($where);
        $next_service_only_note_ids = [];
        foreach($property_notes as $note){
            #if note type = service specific, then only show notes related to stop related services
            if(isset($note->note_type) && $note->note_type == $service_specific_id){
                if(isset($note->note_assigned_services) && !in_array($note->note_assigned_services,$service_ids)){
                    continue;
                }
                if(isset($note->note_status) && $note->note_status != 1){
                    continue;
                }
                if(isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 2){
                    $next_service_only_note_ids[] = $note->note_id;
                }
            }
            $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
            $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
            array_push($all_notes, $note);
        }
        $data['next_service_only_note_ids'] = implode(',',$next_service_only_note_ids);
        $data['enhanced_notes'] = $all_notes;

        usort($data['enhanced_notes'], function($a, $b) {
            if($a->note_created_at > $b->note_created_at){
                return -1;
            } else {
                return 1;
            }
        });

        $data['company_id'] = ($isTech) ? $this->session->userdata['spraye_technician_login']->company_id : $this->session->userdata['company_id'];

        /** Get Company Users For Note Assignments **/
        $data['userdata'] = $this->Administrator->getAllCompanyUsers(array('company_id' => $data['company_id']));

        $companyDetails = $this->CompanyModel->getOneCompany(array('company_id' => $data['company_id']));
        $data['is_tech_customer_note_required'] = $companyDetails->is_tech_customer_note_required;
        $data['currentUser'] = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
        $data['isTech'] = $isTech;

        $data['reschedule_reasons'] = $this->CustomerModel->getRescheduleReasonsList2($company_id);

        //get past completed service of property
        $data['report_details'] = $this->RP->getAllRepots($property_id);
        //die(print_r($data));


        /* Get Note Task Types */
        $data['note_types'] = $this->CompanyModel->getNoteTypes($data['company_id']);
        $page["active_sidebar"] = "jobDetails";
        $page["page_name"] = $this->session->userdata['spraye_technician_login']->user_first_name.$this->session->userdata['spraye_technician_login']->user_last_name;
        $page["page_content"] = $this->load->view("technician/techPropDetails", $data, TRUE);
        $this->layout->technicianTemplateTableDash($page);

    }

    public function jobDetailsOld($technician_job_assign_id) {

        //this function only passed 1 service at a time to the job details view

        $where = array('user_id'=>$this->session->userdata['spraye_technician_login']->user_id);





        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id'=>$this->session->userdata['spraye_technician_login']->company_id));





         $where_arr_check =  array(

            'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,

            'job_assign_date'=>date("Y-m-d"),

            'is_job_mode'=>1,

            'is_complete' =>1

         );





         $job_assign_details_check = $this->Tech->getAllJobAssignCheck($where_arr_check);



          if ($job_assign_details_check) {



           $data['currentaddress'] =    $job_assign_details_check->property_address;

           $data['currentlat'] =    $job_assign_details_check->property_latitude;

           $data['currentlong'] =    $job_assign_details_check->property_longitude;



         } else {
            $data['currentaddress'] = $data['setting_details']->start_location;
            $data['currentlat'] =  $data['setting_details']->start_location_lat;
            $data['currentlong'] = $data['setting_details']->start_location_long;
            if($this->session->userdata['spraye_technician_login']->start_location != "") {
                $data['currentaddress'] = $this->session->userdata['spraye_technician_login']->start_location;
            }
            if($this->session->userdata['spraye_technician_login']->start_location_lat != "") {
                $data['currentlat'] = $this->session->userdata['spraye_technician_login']->start_location_lat;
            }
            if($this->session->userdata['spraye_technician_login']->start_location_long != "") {
                $data['currentlong'] = $this->session->userdata['spraye_technician_login']->start_location_long;
            }
         }



     $where2 = array(

            'technician_job_assign_id' => $technician_job_assign_id,

            'is_job_mode' => 0

          );



         $data['job_assign_details'] = $this->Tech->getOneJobAssign($where2);





         if (!$data['job_assign_details']) {



             redirect('technician/dashboard/');

        }



         $jobAssign = $this->Tech->GetOneRow($where2);



         $data['product_details'] = $this->Tech->getAllProductDetails(array('job_id' =>$jobAssign->job_id));

         $data['product_details_for_cal'] = $this->Tech->getAllProductDetails(array('job_id' =>$jobAssign->job_id,'mixture_application_rate !='=>0,'application_rate !='=>0 ));



         $data['job_details'] = $this->Tech->getJobDetails(array('job_id' =>$jobAssign->job_id));



         $data['property_details'] = $this ->Tech->getPropertyDetails(array('property_id' =>$jobAssign->property_id));



         $wind = $this->getWindSpeed($data['property_details']->property_latitude,$data['property_details']->property_longitude);





         $data['current_wind_speed'] =$wind['speed'];











         if ($data['product_details']) {



            foreach ($data['product_details'] as $key => $value) {



              if ( $data['current_wind_speed'] > $value->max_wind_speed ) {



                 $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>The </strong>wind speed is above the max wind speed for a product you are applying on this service </div>');

              }



            }



         }

		//die(print_r($data));



         $page["active_sidebar"] = "jobDetails";

         $page["page_name"] = $this->session->userdata['spraye_technician_login']->user_first_name.$this->session->userdata['spraye_technician_login']->user_last_name;

         $page["page_content"] = $this->load->view("technician/techJobDetails", $data, TRUE);

         $this->layout->technicianTemplateTableDash($page);

    }

    public function addJobToPropertyToday(){

		$post = $this->input->post();

		 // die(print_r($post));

		$error = 0;

		if(is_array($post['post'])){

			  $property = $post['post'];

			  //die(print_r($data));

			  foreach($property as $key=>$data){

				  $user_id = $this->session->userdata['spraye_technician_login']->user_id;

				  $company_id = $this->session->userdata['spraye_technician_login']->company_id;

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

				  //create technician job assign

					 $param4 = array(

						 'technician_id' => $data['technician_id'],

						 'user_id' => $user_id,

						 'company_id' => $company_id,

						 'customer_id' => $data['customer_id'],

						 'job_id' => $data['service_id'],

						 'program_id' => $program_id,

						 'property_id' => $data['property_id'],

						 'job_assign_date' => date("Y-m-d"),

						 'route_id' => $data['route_id'],

					  );



					  $technician_job_assign_id = $this->JobModel->CreateOneTecnicianJob($param4);



				  // if program price is not manual

					  if ($data['program_price']!=3){

						  //get customer info

						  $customer_property_details  = $this->CustomerModel->getAllproperty(array('customer_property_assign.property_id'=>$data['property_id']));



						  if($customer_property_details){

							  $customer_id = $data['customer_id'];

							  $yard_sq_ft = $customer_property_details[0]->yard_square_feet;



							  //get job cost

							  if (isset($data['is_price_override_set']) && $data['is_price_override_set'] == 1  ) {

								$cost =  $data['price_override'];

							  } else {

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

									$min_fee = $setting_details->base_service_fee;

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

								   //update technician job assign table

								  $updateTechAssign = $this->JobModel->updateAssignJob(array('technician_job_assign_id'=>$technician_job_assign_id), array('invoice_id'=>$invoice_id));



 								  $setting_details = $this->CompanyModel->getOneCompany(array('company_id'=>$company_id));



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

    public function getRescheduleReasons(){

        $company_id = $this->session->userdata['company_id'];
        $reschedule_reasons =  $this->CustomerModel->getRescheduleReasonsList2($company_id);
        return $reschedule_reasons;
    }

    public function addJobToPropertyFuture(){

		$post = $this->input->post();

		  //die(print_r($post));

		$error = 0;

		if(is_array($post['post'])){

			  $property = $post['post'];

			  //die(print_r($data));

			  foreach($property as $key=>$data){

				  $user_id = $this->session->userdata['spraye_technician_login']->user_id;

				  $company_id = $this->session->userdata['spraye_technician_login']->company_id;

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

									$min_fee = $setting_details->base_service_fee;

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

 								  $setting_details = $this->CompanyModel->getOneCompany(array('company_id'=>$company_id));



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

    public function users() {

      //  $data['users'] = $this->User->getUserdetails();

        $page["active_sidebar"] = "users";

        $page["page_content"] = $this->load->view("admin/users", '', TRUE);

        $this->layout->technicianTemplateTableDash($page);

    }

    public function SendCustomerEmail() {



        $email = $this->input->post('email');

        $message =  trim($this->input->post('message'));

        $customer_id =  $this->input->post('customer_id');

        $where = array('company_id'=>$this->session->userdata['spraye_technician_login']->company_id);

        $emaildata = $this->CompanyModel->getOneCompany($where);



        $subject =  $emaildata->company_name.' - Technician message';



        $body = '<!DOCTYPE html>

            <html>

                <head>

                    <style type="text/css">

                        p{
                            font-size: 16px;
                        }
                        .button_old {

                        background-color: #f44336;

                        border: none;

                        color: white;

                        padding: 15px 32px;

                        text-align: center;

                        text-decoration: none;

                        display: inline-block;

                        font-size: 16px;

                        margin: 4px 2px;

                        cursor: pointer;

                        }

                    </style>

                </head>

                <body>';



                    $body .= '<b>'.$this->session->userdata['spraye_technician_login']->user_first_name.$this->session->userdata['spraye_technician_login']->user_last_name.'</b> just sent you a message  <br> "'.$message.'"<br>';



                    $body .= '<a href="'.base_url('welcome/unSubscibeEmail/').$customer_id.'" target="_blank" >Unsubscribe</a>';

                    $body .='
                </body>
            </html>';



            $where['is_smtp'] = 1;



            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);



            if (!$company_email_details) {

                //  echo "defalt settind send";
                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            }



            $res =   Send_Mail_dynamic($company_email_details,$email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, $subject);


            if ($res) {

                echo "true";
            } else {

                echo "false";

            }



    }

	public function jobCompletionText($data) {
        $sendText = [];
        $textdata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' =>$data->company_id));
        if(isset($textdata['company_email_details']->job_completion_status) && $textdata['company_email_details']->job_completion_status == 1){
            #check if customer billing type is Group Billing
            if(isset($data->billing_type) && $data->billing_type == 1){
                  $groupBillingDetails = $this->PropertyModel->getGroupBillingByProperty($data->property_id);
                  $textdata['contactData'] = array(
                      'phone_opt_in'=>$groupBillingDetails['phone_opt_in'],
                      'phone'=>$groupBillingDetails['phone'],
                  );
            }else{
                  $textdata['contactData'] = array(
                      'phone_opt_in'=>$data->is_mobile_text,
                      'phone'=>$data->phone,
                  );
            }

          if($textdata['contactData']['phone_opt_in'] == 1){
              $sendText = Send_Text_dynamic($textdata['contactData']['phone'],$textdata['company_email_details']->job_completion_text,'Job Completion');
          }
        }

        return $sendText;
    }

  public function jobCompletionEmail($data, $report_details) {
        $sendEmail = [];
        //die(print_r($data));
        if(!empty($data) && !empty($report_details)){
            $tech_job_assign_details = $this->Tech->getOneJobAssign(array('technician_job_assign_id'=>$data->technician_job_assign_id));
            if(!empty($tech_job_assign_details)){
                $emaildata['email_data_details'] =  $data;
                $emaildata['report_details'] =  $report_details;
                $emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' =>$tech_job_assign_details->company_id));
                $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' =>$tech_job_assign_details->company_id));


                #check if customer billing type is Group Billing
                if(isset($data->billing_type) && $data->billing_type == 1){
				    $groupBillingDetails = $this->PropertyModel->getGroupBillingByProperty($data->property_id);
                    $emaildata['contactData'] = array(
                        'first_name' => $groupBillingDetails['first_name'],
                        'last_name' => $groupBillingDetails['last_name'],
                        'contact_id'=> $groupBillingDetails['group_billing_id'],
                        'email_opt_in'=>$groupBillingDetails['email_opt_in'],
                        'email'=>$groupBillingDetails['email'],
                    );
                }else{
                    $emaildata['contactData'] = array(
                        'first_name' => $data->first_name,
                        'last_name' => $data->last_name,
                        'contact_id'=> $data->customer_id,
                        'email_opt_in'=>$data->is_email,
                        'email'=>$data->email,
                    );
                }
				#get property conditions
				$emaildata['property_conditions'] = [];
				$getPropertyConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$tech_job_assign_details->property_id));
				if(!empty($getPropertyConditions)){
					foreach($getPropertyConditions as $assigned){
						#only include property conditions that were added during stop
						if(in_array($assigned->property_condition_id,$data->new_assigned_property_conditions)){
							if(isset($assigned->in_email) && $assigned->in_email == 1){
								$emaildata['property_conditions'][] = $assigned->condition_name." - ".$assigned->message;
							}
						}
					}
				}
                //var_dump($emaildata);
                $body  = $this->load->view('email/job_completion_email',$emaildata,true);
               // die($body);
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' =>$tech_job_assign_details->company_id,'is_smtp'=>1));
                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }
                //die($emaildata['email_data_details']->secondary_email);
                if($emaildata['company_email_details']->job_completion_status == 1 && $emaildata['contactData']['email_opt_in'] == 1) {
                #send email to customer
                    if (isset($emaildata['email_data_details']->secondary_email))
                        $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $emaildata['company_details']->company_name, "email" =>$emaildata['company_details']->company_email),  $body, 'Service Completion', $emaildata['email_data_details']->secondary_email);
                    else
                        $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $emaildata['company_details']->company_name, "email" =>$emaildata['company_details']->company_email),  $body, 'Service Completion');
                }
            }
        }


        return $sendEmail;
    }

	public function jobCompletionEmailMultiple($email_data = array(), $reports_data = array()) {



	   $emaildata_all['reports_data'] =  $reports_data;



       $where = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);

       $emaildata_all['company_details'] = $this->CompanyModel->getOneCompany($where);

       $emaildata_all['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

       $emaildata_all['customer_data']= $email_data['customer_data'];



       $body  = $this->load->view('email/job_completion_email',$emaildata_all,true);





      $where['is_smtp'] = 1;



      $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);



      if (!$company_email_details) {

             $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

      }

	  if($emaildata_all['company_email_details']->job_completion_status == 1){

		   $res =   Send_Mail_dynamic($company_email_details,$emaildata_all['customer_data']['email'],array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Job Completion',$emaildata_all['customer_data']['secondary_email']);

	  }else{

		  $res = array();

	  }





      return $res;





    }

	public function jobStartMultiple($technician_job_assign_ids){

		if(isset($technician_job_assign_ids)){

			$technician_job_assign_array = explode(',', trim($technician_job_assign_ids));

			$status = "";

			foreach($technician_job_assign_array as $technician_job_assign_id){

				$result = $this->jobStart($technician_job_assign_id);



				if($result){

					$res = json_decode($result);

					if($res->status != 200){

						echo $result;

						return;

					}else{

						$status = $result;

					}

				}

			}

			if(!empty($status)){

				echo $status;

			}

		}else{

			$array_return =   array("status"=>400,"message"=>"something went wrong","result"=>"");

			echo json_encode($array_return);

		}

	}

    public function jobStart($technician_job_assign_id){

        // Company TimeZone
        $current_time = getCompanyTimeNow($this->getCompanyTimeZoneString());



      $where = array(

            'technician_job_assign_id' => $technician_job_assign_id

       );





         $data  = $this->Tech->getOneJobAssign($where);



         $wind = $this->getWindSpeed($data->property_latitude,$data->property_longitude);



           $wherearr = array(

            'degree_from <=' =>$wind['deg'],

            'degree_to  >=' =>$wind['deg'],

            );



           $directionresult = $this->Tech->getWindDirection($wherearr);

           if($directionresult) {

            $direction = $directionresult->direction;

           } else {

            $direction = 'N';

           }



      $param = array(

            'job_start_time' => $current_time,

            'wind_speed' => $this->input->post('wind_speed'),

            'direction' => $direction,

            'temp' => $wind['temp'],

       );



       $result = $this->Tech->updateJobAssign($where,$param);



       if ($result) {

        $array_return =   array("status"=>200,"message"=>"successfully","result"=>$current_time);



       } else {

         $array_return =   array("status"=>400,"message"=>"something went wrong","result"=>"");

       }



       return json_encode($array_return);





    }

	public function completeJobMultiple($technician_job_assign_ids) {
		ob_start();
		$return_data = array();
 		$return_data['url'] = base_url()."technician/dashboard/";

		if(isset($technician_job_assign_ids)){
			$technician_job_assign_array = explode(',', trim($technician_job_assign_ids));
			$message =  $this->input->post('message');
	  		$post_data = $this->input->post();

			$existingPC = [];
			$newPC = [];
			#check and remove new customer tag only if property status != 2 (prospect)
      	  	if(isset($post_data['property_id'])){
				$property_id = $post_data['property_id'];
			  	$property_details = $this->PropertyModel->getOneProperty(array('property_id'=>$property_id));
				if(!empty($property_details->property_status) && $property_details->property_status != 2){
					$tags = $property_details->tags;
					if(!empty($tags)){
						$tags_array=explode(',',$tags);
						if(in_array("1",$tags_array)){
							$updated_tags=[];
							foreach($tags_array as $tag){
								if(!empty($tag) && $tag != "1"){
									$updated_tags[]= $tag;
								}
							}
						$tags_str = implode(',',$updated_tags);
						$update_result = $this->PropertyModel->updateAdminTbl($property_id, array('tags'=>$tags_str));
						}
					}
				}
		  	}
            #handle property conditions
            if(!empty($post_data['property_conditions'])){
                if(is_array($post_data['property_conditions']) && isset($post_data['property_id'])){
                    $property_id = $post_data['property_id'];

                    #get existing conditions for this property
                    $getAssignedConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$property_id));

                    foreach($getAssignedConditions as $existing){
                        $existingPC[]=$existing->property_condition_id;
                    }
                    #remove conditions from property
                    $deleteAssignedConditions = $this->PropertyModel->deleteAssignedPropertyCondition(array('property_id'=>$property_id));
                    foreach($post_data['property_conditions'] as $condition){
                        $handleAssignConditions = $this->PropertyModel->assignPropertyCondition(array('property_id'=>$property_id,'property_condition_id'=>$condition));
                        $condition_arr = $this->PropertyModel->getOnePropertyCondition(array('property_condition_id' => $condition));
                        $note_customer_id = $this->PropertyModel->getSelectedCustomer($property_id);
                        // die(print_r($note_customer_id));
                        $note_contents = 'The Condition of ' . $condition_arr->condition_name . ' has been added to this property.';
                        $tech_user = $this->session->userdata['spraye_technician_login'];
                        $note_param = array(
                        'note_user_id' => $tech_user->id,
                        'note_company_id' => $tech_user->company_id,
                        'note_category' => 0,
                        'note_property_id' => $property_id,
                        'note_customer_id' => $note_customer_id[0]->customer_id,

                        'note_contents' => $note_contents,
                        'note_due_date' => NULL,
                        'note_assigned_user' => $tech_user->id,
                        'note_type' => 0,
                        'include_in_tech_view' => 1
                        );

                        $noteId = $this->CompanyModel->addNote($note_param);

                        if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) {

                        $fileStatusMsg = $this->addNoteFiles($noteId);

                        }

                    }
                    #get assigned conditions for this property after update
                    $getAssignedConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$property_id));
                    foreach($getAssignedConditions as $assigned){
                        #figure out which conditions are newly assigned
                        if(!in_array($assigned->property_condition_id,$existingPC)){
                        $newPC[]=$assigned->property_condition_id;
                        }
                    }
                }
            }


			$email_data['technician_message'] = $message;
			$reports_data = array();
			$print_invoices = array();
            $print_statements = array();
			$customers = array();
			$state_customers = array();
			$return_data['error'] = false;
			$return_data['status'] = 200;

			foreach($technician_job_assign_array as $technician_job_assign_id){
				$return_data[$technician_job_assign_id] = array();
				$data = $this->Tech->getOneJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));
				$data->new_assigned_property_conditions = $newPC;
				$data->technician_message = $message;

				//set variables
				$company_id = $data->company_id;
				$customer_id = $data->customer_id;
				$property_id = $data->property_id;
				$program_id = $data->program_id;
				$job_id = $data->job_id;
				$setting_details = $this->CompanyModel->getOneCompany(array('company_id'=>$company_id));

                // Company TimeZone
                $companyTimeZone = $this->getCompanyTimeZoneString();
                $companyCurrentTime = getCompanyTimeNow($companyTimeZone);
                $companyCurrentDate = getCompanyDateNow($companyTimeZone);
                
				//update tech job assign table
				$updateArr = array (
					'is_job_mode' => 1,
					'is_complete' => 1,
					'job_completed_date'=> $companyCurrentDate,
					'job_completed_time'=> $companyCurrentTime                    
                );

                $result = $this->Tech->updateJobAssign(array('technician_job_assign_id' => $technician_job_assign_id),$updateArr);
                // die(print_r($this->db->last_query()));
                // die(print_r($result));
				if ($result) {
					$return_data[$technician_job_assign_id]['status'] = 200;
                    // get details && create report
					$oneJobDetails = $this->Tech->getOneJobAllDetails(array('technician_job_assign_id' => $technician_job_assign_id));
                    //   die($this->db->last_query());
                    // die(print_r($oneJobDetails));

                    $company_id = $this->session->userdata['spraye_technician_login']->company_id;

					if ($oneJobDetails) {
                        $report_data =   array(
							'technician_job_assign_id' => $oneJobDetails->technician_job_assign_id,
							'company_id' => $company_id,
							'user_first_name' => $oneJobDetails->user_first_name,
							'user_last_name' => $oneJobDetails->user_last_name,
							'applicator_number' => $oneJobDetails->applicator_number,
							'applicator_phone_number'=>$oneJobDetails->phone,
							'first_name' => $oneJobDetails->first_name,
							'last_name' => $oneJobDetails->last_name,
							'property_title' => $oneJobDetails->property_title,
							'property_city' => $oneJobDetails->property_city,
							'yard_square_feet' => $oneJobDetails->yard_square_feet,
							'property_state' => $oneJobDetails->property_state,
							'property_zip' => $oneJobDetails->property_zip,
							'property_address' => $oneJobDetails->property_address,
							'wind_speed' => $oneJobDetails->wind_speed,
							'direction' => $oneJobDetails->direction,
							'temp' => $oneJobDetails->temp,
							'job_completed_date' => $oneJobDetails->job_completed_date,
							'job_completed_time' => $oneJobDetails->job_completed_time,
							'property_status' => $oneJobDetails->property_status,
							'source' => $oneJobDetails->source,
							'estimate_id' => $oneJobDetails->estimate_id,
							'estimate_date' => $oneJobDetails->estimate_created_date,
							'estimate_status' => $oneJobDetails->status,
							'estimate_property_status' => $oneJobDetails->property_status,
							'sales_rep' => $oneJobDetails->sales_rep,
							'program_id' => $oneJobDetails->program_id,
							'invoice_id' => $oneJobDetails->invoice_id,
							'service_type_id' => $oneJobDetails->service_type_id,
							'commission_type' => $oneJobDetails->commission_type,
							'bonus_type' => $oneJobDetails->bonus_type,
							'job_id' => $oneJobDetails->job_id,
							'user_id' => $oneJobDetails->id,
							'report_created_date' => date("Y-m-d H:i:s")
                        );


                        //create report
                        $report_id =  $this->RP->createOneReport($report_data);
                        // die(print_r($report_id));
                        $data->report_id = $report_id;
                        $report_data['job_id'] = $data->job_id;
                        $report_data['job_name'] = $data->job_name;
                        $report_data['program_name'] = $data->program_name;
                        $report_data['job_assign_date'] = $data->job_assign_date;

                        $reports_data[] = $report_data;
                        //update PPJOBINV TABLE WITH REPORT ID HERE?
                        $where = array(
                            'invoice_id' => $oneJobDetails->invoice_id,
                        );

                        $param = array(
                            'report_id' => $report_id
                        );
                        $result = $this->INV->updateInvoive($where, $param);

            // get products for this one service & generate report
            $product_details_assigned = $this->Tech->getAllProductDetails(array('job_id' =>$oneJobDetails->job_id,'mixture_application_rate !='=>0,'application_rate !='=>0));
            $assigned_products = [];
            foreach ($product_details_assigned as $key => $produ ){
                array_push($assigned_products, $produ->product_id );
            }

            $extra_products = [];
            foreach ($post_data[$technician_job_assign_id] as $key => $prod ){
                if (!in_array($key,$assigned_products))
                    array_push($extra_products, $key );
            }


            $product_details = $this->Tech->getAllProductDetails_new(array('job_id' =>$oneJobDetails->job_id), $extra_products);
            //die(print_r($product_details));
            if ($product_details) {
              foreach ($product_details as $key => $value) {
                $param = array (
                      'report_id' => $report_id,
                      'product_id' => $value->product_id,
                      'product_name' => $value->product_name,
                      'epa_reg_nunber' => $value->epa_reg_nunber,
                      'weed_pest_prevented' => $value->weed_pest_prevented,
                      're_entry_time' => $value->re_entry_time,
                      'restricted_product' => $value->restricted_product
                    );
                    if ($value->application_method==1) {
                      $param['application_method'] = 'Broadcast';
                    } else if($value->application_method==2) {
                      $param['application_method'] = 'Spot Spray';
                    } elseif ($value->application_method==3) {
                      $param['application_method'] = 'Granular';
                    }
                    if($value->chemical_type==1) {
                      $param['chemical_type'] = 'Herbicide';
                    } else if($value->chemical_type==2) {
                      $param['chemical_type'] = 'Fungicide';
                    } else if($value->chemical_type==3) {
                      $param['chemical_type'] = 'Insecticide';
                    } else if($value->chemical_type==4) {
                      $param['chemical_type'] = 'Fertilizer';
                    } else if($value->chemical_type==5) {
                      $param['chemical_type'] = 'Wetting Agent';
                    } else if($value->chemical_type==6) {
                      $param['chemical_type'] = 'Surfactant/Tank Additive';
                    } else if($value->chemical_type==7) {
                      $param['chemical_type'] = 'Aquatics';
                    } else if($value->chemical_type==8) {
                      $param['chemical_type'] = 'Growth Regulator';
                    } else if($value->chemical_type==9) {
                      $param['chemical_type'] = 'Biostimulants';
                    }
                    $active_ingredients = array();
                    $ingredientDatails = getActiveIngredient(array('product_id'=>$value->product_id));
                    if ($ingredientDatails) {
                      foreach ($ingredientDatails as $key2 => $value2) {
                        $active_ingredients[] =   $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' %';
                      }
                    }
                    $param['active_ingredients'] =  implode(', ', $active_ingredients);
                    if (!empty($value->application_rate) && $value->application_rate != 0) {
                      $param['application_rate'] = $value->application_rate . ' ' . $value->application_unit . ' / ' . $value->application_per;
                }
                $param['estimate_of_pesticide_used'] = amountOfChemicalUsed($value, $post_data[$technician_job_assign_id], $oneJobDetails->yard_square_feet);
                $param['amount_of_mixture_applied'] =  $post_data[$technician_job_assign_id][$value->product_id] . ' ' . $value->mixture_application_unit;

                                $item_info = $this->Tech->getItemInfoByProductId($value->product_id);

                                $tech_id = $oneJobDetails->id;

                    $fleet_info = $this->Tech->getFleetInfoByAssignedUser($tech_id);
                    // die(print_r($fleet_info));
                    $sub_info = '';

                    $fleet_id = '';

                    if(!empty($fleet_info)){
                        $fleet_id = $fleet_info->fleet_id;
                    }

                    if($fleet_id != ''){
                                    $sub_info = $this->Tech->getFleetSubLocationInfo($fleet_id);
                                }

                                // die(print_r($item_info));
                                // die(print_r($sub_info));
                                if(!empty($sub_info)){


                                    if(!empty($item_info)){
                                        foreach($item_info as $info){

                                            $item_id = $info->item_id;
                                            $sub_id = $sub_info->sub_location_id;
                                $item_unit_type = $info->unit_type;
                                $item_unit_amount = $info->unit_amount;
                                $product_type = $info->product_type;
                                $product_unit_type = $info->application_unit;
                                //   die(print_r($item_unit_type));
                                $product_app_rate = $info->application_rate;
                                $product_mix_rate = $info->mixture_application_rate;

                                $amount_used = explode(' ', $param['amount_of_mixture_applied'])[0];

                                $current_quantity = $this->Tech->getCurrentItemQuantityInSubLocation($item_id, $sub_id);

                                if($current_quantity->quantity > 0){

                                    $amount_converted = ($amount_used/$product_mix_rate) * $product_app_rate;

                                    $amount_item_converted = unitConversion($amount_converted, $item_unit_type, $product_unit_type, $product_type);
                                    $new_quantity = $current_quantity->quantity - ($amount_item_converted / $item_unit_amount);
                                    $this->db->where('quantity_id', $current_quantity->quantity_id);
                                    $this->db->update('quantities', array('quantity' => $new_quantity));

                                }
                            }
                        }
                    }
                    $this->db->insert("report_product",$param);
                    //die(print_r($param));
                    //$this->modify_product_inventory($param['product_id'], $param['estimate_of_pesticide_used']);

                }

            }
                    }

                    // Handle invoices
                    $whereArrPaidEstimate = array(
                        'property_id' => $property_id,
                        'program_id'=> $program_id,
                        'customer_id' => $customer_id,
                        'status' => 3,
                    );
                    $estimate_paid =   GetOneEstimateDetails($whereArrPaidEstimate);
                    //check for invoice
                    if($data->invoice_id){
                        $invoice_id = $data->invoice_id;
                        $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));
                        $data->invoice_details = $invoice_details;
                        //var_dump($data->report_id);
                        //if program_price = at completion (2) && invoice is not paid && estimate not paid
                        if($data->program_price == 2 && $invoice_details->payment_status != 2 && !$estimate_paid) {
                            //check customer setting for Basys autocharge
                            $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);

                            if($customer_details['clover_autocharge'] == 1){
                                $clover_response = $this->cloverAutocharge($invoice_id);
                            } else if($customer_details['basys_autocharge'] == 1){
                                //process payment via basys
                                $basys_response = $this->basysAutocharge($invoice_id);

                            }
                        }

                        //get job cost for report
                        $get_ppjobinv  = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails(array('property_id'=>$property_id, 'program_id'=>$program_id, 'job_id'=>$job_id,'invoice_id'=>$invoice_id));

                        if($get_ppjobinv){
                            //update report cost
                            $updatearr = array('cost' => ($get_ppjobinv->job_cost));
                            $this->RP->updateReport(array('report_id'=>$report_id), $updatearr);

						    //update PPJOBINV table
						    $update_PPJOBINV = $this->PropertyProgramJobInvoiceModel->updatePropertyProgramJobInvoice(array('property_program_job_invoice_id'=>$get_ppjobinv->property_program_job_invoice_id), array('report_id'=>$report_id, 'updated_at'=>date("Y-m-d H:i:s")));
					    }
					    //if paid estimate
					    if($estimate_paid) {
					        $total_tax_amount = getAllSalesTaxSumByInvoice($invoice_id)->total_tax_amount;
					        $updateInvoiceParam['payment_status'] = 2;
					        $updateInvoiceParam['partial_payment'] = $invoice_details->cost+$total_tax_amount;
					    }

					    if($data->program_price != 3) {
					        //handle quickbooks
						    if ($invoice_details && $invoice_details->quickbook_invoice_id!=0) {
                                // Assign value of invoice_details object to new variable
                                $QBO_param = $invoice_details;

                                // Declare array to be passed to coupon calculatiuon function
                                $coup_inv_param = array(
                                    'cost' => $QBO_param->cost,
                                    'invoice_id' => $invoice_id
                                );

                                // Assign value of calculation function to new variable
                                $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                // Assign value of variable as new cost to pass to QBO
                                $QBO_param->cost = $cost_with_inv_coupon;

                                // die(print($QBO_param->cost));

                                // Update QBO Invoice with any new info
							    // Update invoice in quickbook.
							    $res = $this->QuickBookInvUpdate($QBO_param);
						    } else {
							    if($data->program_price == 2) {



                                    // Create invoice in quickbook.
                                    $QBO_fallback = false;
                                    if (isset($data->job_id) && !empty($data->job_id)) {
                                        $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));
                                        if (isset($QBO_job_details) && !empty($QBO_job_details)) {
                                            $QBO_param = $invoice_details;

                                            $property_deets = $this->PropertyModel->getOnePropertyDetail($post_data['property_id']);
                                            $property_street = explode(',', $property_deets->property_address)[0];
                                            $QBO_param->property_street = $property_street;
                                            $single_job_desc = $QBO_job_details->job_description;
                                            $single_job_name = $QBO_job_details->job_name;
                                            // Assign value of invoice_details object to new variable


                                            // Declare array to be passed to coupon calculatiuon function
                                            $coup_inv_param = array(
                                                'cost' => $QBO_param->cost,
                                                'invoice_id' => $invoice_details->invoice_id
                                            );

                                            // Assign value of calculation function to new variable
                                            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                            // Assign value of variable as new cost to pass to QBO
                                            $QBO_param->cost = $cost_with_inv_coupon;

                                            // die(print($QBO_param->cost));

                                            // Update QBO Invoice with any new info
                                            $quickbook_invoice_id = $this->QuickBookInv($QBO_param, $single_job_desc, $single_job_name);
                                        } else {
                                            $QBO_fallback = true;
                                        }
                                    } else {
                                        $QBO_fallback = true;
                                    }
                                } else {
                                    $QBO_fallback = true;
                                }
                                if ($QBO_fallback == true) {
                                    // Assign value of invoice_details object to new variable
                                    $QBO_param = $invoice_details;

                                    // Declare array to be passed to coupon calculatiuon function
                                    $coup_inv_param = array(
                                        'cost' => $QBO_param->cost,
                                        'invoice_id' => $invoice_details->invoice_id
                                    );

                                    // Assign value of calculation function to new variable
                                    $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                    // Assign value of variable as new cost to pass to QBO
                                    $QBO_param->cost = $cost_with_inv_coupon;

                                    // die(print($QBO_param->cost));

                                    // Update QBO Invoice with any new info
                                    $quickbook_invoice_id = $this->QuickBookInv($QBO_param, "", "");
                                }

                                // $dataToLog = array( (string)$single_job_name, (string)$single_job_desc);
                                // $data = implode(" - ", $dataToLog);
                                // $data .= PHP_EOL;
                                // file_put_contents('nr_log.txt', $data, FILE_APPEND);

                                if ($quickbook_invoice_id) {
                                    $this->INV->updateInvoive(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));
                                }
                            }
                        }

                        $return_data[$technician_job_assign_id]['status'] = 200;

                        // Only update arrays with value if not One-time Invoice
                        if($data->program_price != 1){
                            $print_invoices[] = $invoice_id;
                            $customers[] = $data->customer_id;
                        }

					    #check for group billing
					    if(isset($data->billing_type) && $data->billing_type == 1){
						    $gb_invoices[] = $invoice_id;
					    }
                        // Check if One-time Invoice
                        if ($data->program_price == 1){
                            $where_inv_arr = array(
                                'invoice_id' => $data->invoice_id
                            );

                            // Get current invoice id of One -time Invoice
                            $get_inv = $this->INV->getOneInvoice($where_inv_arr);
                            //var_dump($report_id);
                            if ($get_inv){
                                $where = array(
                                    'property_id' => $data->property_id,
                                    'job_id' => $data->job_id,
                                    'program_id'=> $data->program_id,
                                    'customer_id' => $data->customer_id,
                                );

                                // Calculate Cost for Individual Job
                                //check for estimate price override
                                $estimate_price_override =   GetOneEstimateJobPriceOverride($where);
                                if ($estimate_price_override && $estimate_price_override->is_price_override_set == 1 ) {
                                    $cost =  $estimate_price_override->price_override;

                                } else {
                                    $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $data->property_id,'program_id' => $data->program_id));
                                    if ($priceOverrideData &&  $priceOverrideData->is_price_override_set == 1 ) {
                                        $cost =  $priceOverrideData->price_override;
                                    } else {
                                        //else no price overrides, then calculate job cost
                                        $lawn_sqf = $data->yard_square_feet;
                                        $job_price = $data->job_price;

                                        //get property difficulty level
                                        if(isset($data->difficulty_level) && $data->difficulty_level == 2){
                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                        }elseif(isset($data->difficulty_level) && $data->difficulty_level == 3){
                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                        }else{
                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                        }
                                        //get base fee
                                        if(isset($data->base_fee_override)){
                                            $base_fee = $data->base_fee_override;
                                        }else{
                                            $base_fee = $setting_details->base_service_fee;
                                        }

                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

                                        //get min. service fee
                                        if(isset($data->min_fee_override)){
                                            $min_fee = $data->min_fee_override;
                                        }else{
                                            $min_fee = $setting_details->base_service_fee;
                                        }

                                        // Compare cost per sf with min service fee
                                        if($cost_per_sqf > $min_fee){
                                            $cost = $cost_per_sqf;
                                        }else{
                                            $cost = $min_fee;
                                        }

                                    }
                                }

                                //update report cost
                                $this->RP->updateReport( array('report_id'=>$report_id), array('cost'=>($cost)));

                                // create the statement and get the id
                                $param = array(
                                    'user_id' => $this->session->userdata['spraye_technician_login']->user_id,
                                    'company_id' => $data->company_id,
                                    'customer_id' => $data->customer_id,
                                    'property_id' => $data->property_id,
                                    'program_id' => $data->program_id,
                                    'statement_date' => date("Y-m-d"),
                                    'invoice_id' => $get_inv->invoice_id,
                                    'statement_created' => date("Y-m-d H:i:s"),
                                    'description' => $data->job_name,
                                    'work_cost' => ($cost),
                                    'report_id' => $report_id,
                                    'payment_status' => $get_inv->payment_status,
                                    'job_id' => $data->job_id
                                );
                                // create the work statement and get the id
                                $work_statement_id = $this->STATE->createOneStatement($param);

                                if ($work_statement_id) {

                                    $return_data[$technician_job_assign_id]['status'] = 200;
                                    $print_statements[] = $work_statement_id;
                                    $state_customers[] = $data->customer_id;

                                } else {
                                    //HANDLE NO INVOICE CREATED ERROR
                                    $return_data[$technician_job_assign_id]['status'] = 400;
                                    $return_data[$technician_job_assign_id]['message'] = "Work Statement not created for technician job assign id ".$technician_job_assign_id;
                                }
                            }
                        }
                    } else {
                        // if invoice id not store in job assign tech table (aka old way of creating an invoice) then we check the invoice table for the property, program, job, customer combo

                        $where = array(
                            'property_id' => $data->property_id,
                            'job_id' => $data->job_id,
                            'program_id'=> $data->program_id,
                            'customer_id' => $data->customer_id,
                        );

                        $check_inv  = $this->INV->getOneInvoice($where);

                        if($check_inv){
                            // Invoice already exists but was created before code changes that implement db table property_program_job_invoice aka invoice for one 1 job at a time
                            $invoice_id = $check_inv->invoice_id;

                            //update tech_job_assign
                            $updateTechAssign  = $this->Tech->updateJobAssign(array('technician_job_assign_id' => $technician_job_assign_id), array('invoice_id'=>$invoice_id));

                            $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));

						    // Create invoice in quickbook.
                            $QBO_fallback = false;
                            if (isset($data->job_id) && !empty($data->job_id)) {
                                $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));
                                if (isset($QBO_job_details) && !empty($QBO_job_details)) {
                                    $QBO_param = $invoice_details;

                                    $property_deets = $this->PropertyModel->getOnePropertyDetail($post_data['property_id']);
                                    $property_street = explode(',', $property_deets->property_address)[0];
                                    $QBO_param->property_street = $property_street;
                                    $single_job_desc = $QBO_job_details->job_description;
                                    $single_job_name = $QBO_job_details->job_name;

                                    // Assign value of invoice_details object to new variable


                                    // Declare array to be passed to coupon calculatiuon function
                                    $coup_inv_param = array(
                                        'cost' => $QBO_param->cost,
                                        'invoice_id' => $invoice_details->invoice_id
                                    );

                                    // Assign value of calculation function to new variable
                                    $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                    // Assign value of variable as new cost to pass to QBO
                                    $QBO_param->cost = $cost_with_inv_coupon;

                                    // die(print($QBO_param->cost));

                                    // Update QBO Invoice with any new info
                                    $quickbook_invoice_id = $this->QuickBookInv($QBO_param, $single_job_desc, $single_job_name);
                                } else {
                                    $QBO_fallback = true;
                                }
                            } else {
                                $QBO_fallback = true;
                            }
                            if ($QBO_fallback == true) {
                                // Assign value of invoice_details object to new variable
                                $QBO_param = $invoice_details;

                               // Declare array to be passed to coupon calculatiuon function
                                $coup_inv_param = array(
                                    'cost' => $QBO_param->cost,
                                    'invoice_id' => $invoice_details->invoice_id
                                );

                                // Assign value of calculation function to new variable
                                $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                // Assign value of variable as new cost to pass to QBO
                                $QBO_param->cost = $cost_with_inv_coupon;

                                // die(print($QBO_param->cost));

                                // Update QBO Invoice with any new info
                                $quickbook_invoice_id = $this->QuickBookInv($QBO_param, "", "");
                            }

                            if ($quickbook_invoice_id) {
                                $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));
                            }

                            //update invoice
                            $updateInvoiceParam =  array(
                                'invoice_created' => date("Y-m-d H:i:s"),
                                'report_id' => $report_id,
                            );

                            if($estimate_paid) {
                                $total_tax_amount = getAllSalesTaxSumByInvoice($invoice_id)->total_tax_amount;
                                $updateInvoiceParam['payment_status'] = 2;
                                $updateInvoiceParam['partial_payment'] = $check_inv->cost+$total_tax_amount;
                            }

                            $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),$updateInvoiceParam);

                            //update report cost
                            $this->RP->updateReport( array('report_id'=>$report_id), array('cost'=>($check_inv->cost)));

                            //if program_price = at completion (2) && invoice is not paid && estimate not paid
                            if($data->program_price == 2 && $invoice_details->payment_status != 2 && !$estimate_paid) {
                                //check customer setting for Basys autocharge
                                $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
                                if($customer_details['clover_autocharge'] == 1){
                                    $clover_response = $this->cloverAutocharge($invoice_id);
                                } else if($customer_details['basys_autocharge'] == 1){
                                    //process payment via basys
                                    $basys_response = $this->basysAutocharge($invoice_id);
                                }
                            }

                            $return_data[$technician_job_assign_id]['status'] = 200;
                            $print_invoices[] = $invoice_id;
                            $customers[] = $data->customer_id;
                            #check for group billing
                            if(isset($data->billing_type) && $data->billing_type == 1){
                                $gb_invoices[] = $invoice_id;
                            }
                        } else {
                            //Invoice not exist case.
                            $QBO_cost = 0;
                            $est_cost = 0;
                            //check for estimate price override
                            $estimate_price_override =   GetOneEstimateJobPriceOverride($where);
                            if ($estimate_price_override && $estimate_price_override->is_price_override_set == 1 ) {
                                $cost =  $estimate_price_override->price_override;

                                $coup_est_param = array(
                                    'cost' => $cost,
                                    'estimate_id' => $estimate_price_override->estimate_id
                                );

                                $est_cost = $this->calculateEstimateCouponCost($coup_est_param);

                            } else {
                                $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $property_id,'program_id' => $program_id));
                                if ($priceOverrideData &&  $priceOverrideData->is_price_override_set == 1 ) {
                                    $cost =  $priceOverrideData->price_override;
                                } else {
                                    //else no price overrides, then calculate job cost
                                    $lawn_sqf = $data->yard_square_feet;
                                    $job_price = $data->job_price;

                                    //get property difficulty level
                                    if(isset($data->difficulty_level) && $data->difficulty_level == 2){
                                        $difficulty_multiplier = $setting_details->dlmult_2;
                                    } elseif(isset($data->difficulty_level) && $data->difficulty_level == 3){
                                        $difficulty_multiplier = $setting_details->dlmult_3;
                                    } else {
                                        $difficulty_multiplier = $setting_details->dlmult_1;
                                    }
                                    //get base fee
                                    if(isset($data->base_fee_override)){
                                        $base_fee = $data->base_fee_override;
                                    } else {
                                        $base_fee = $setting_details->base_service_fee;
                                    }

                                    $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

                                    //get min. service fee
                                    if(isset($data->min_fee_override)){
                                        $min_fee = $data->min_fee_override;
                                    } else {
                                        $min_fee = $setting_details->base_service_fee;
                                    }

                                    // Compare cost per sf with min service fee
                                    if($cost_per_sqf > $min_fee){
                                        $cost = $cost_per_sqf;
                                    } else {
                                        $cost = $min_fee;
                                    }

                                }
                            }

                            if($est_cost){
                                $QBO_cost = $est_cost;
                            } else {
                                $QBO_cost = $cost;
                            }

                            //update report cost
                            $this->RP->updateReport( array('report_id'=>$report_id), array('cost'=>($cost)));

                            // When program type is not Manual Billing.
                            if($data->program_price != 3 && $data->program_price != 1) {
                                // create the invoice and get the id
                                $param = array(
                                    'user_id' => $this->session->userdata['spraye_technician_login']->user_id,
                                    'company_id' => $data->company_id,
                                    'customer_id' => $data->customer_id,
                                    'property_id' => $data->property_id,
                                    'program_id' => $data->program_id,
                                    'job_id' => $data->job_id,
                                    'invoice_date' => date("Y-m-d"),
                                    'invoice_created' => date("Y-m-d H:i:s"),
                                    'report_id' => $report_id,
                                    'description' => $data->job_name,
                                    'cost' => ($cost)
                                );
                                // create the invoice and get the id
                                $invoice_id = $this->INV->createOneInvoice($param);

                                if ($invoice_id) {
                                    //update tech_job_assign
                                    $updateTechAssign  = $this->Tech->updateJobAssign(array('technician_job_assign_id' => $technician_job_assign_id), array('invoice_id'=>$invoice_id));
                                    $param['invoice_id'] = $invoice_id;

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
                                                    'tax_amount' => $cost*$tax_details['tax_value']/100
                                                );
                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                                $total_tax_amount +=  $invoice_tax_details['tax_amount'];
                                            }
                                        }
                                    }
                                    //if estimate paid
                                    if ($estimate_paid) {
                                        $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('payment_status'=>2,'partial_payment'=>$cost+$total_tax_amount));
                                    }
                                    $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));

                                    // die(print_r($invoice_details));

                                    //$data->invoice_details = $invoice_details;
                                    //$data['invoice_details'][] = $invoice_details;
                                    if($data->program_price == 2) {

                                        //create invoice in quickbooks
                                        $QBO_fallback = false;
                                        if (isset($data->job_id) && !empty($data->job_id)) {
                                            $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));
                                            if (isset($QBO_job_details) && !empty($QBO_job_details)) {
                                                $single_job_desc = $QBO_job_details->job_description;
                                                $single_job_name = $QBO_job_details->job_name;
                                                // Assign value of invoice_details object to new variable
                                                $QBO_param = $invoice_details;

                                                // Declare array to be passed to coupon calculatiuon function
                                                $coup_inv_param = array(
                                                    'cost' => $QBO_param->cost,
                                                    'invoice_id' => $invoice_id
                                                );

                                                // Assign value of calculation function to new variable
                                                $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                                // Assign value of variable as new cost to pass to QBO
                                                $QBO_param->cost = $cost_with_inv_coupon;

                                                // die(print($QBO_param->cost));

                                                // Update QBO Invoice with any new info
                                                $quickbook_invoice_id = $this->QuickBookInv($QBO_param, $single_job_desc, $single_job_name);
                                            } else {

                                                $QBO_fallback = true;
                                            }
                                        } else {
                                            $QBO_fallback = true;
                                        }
                                        if ($QBO_fallback == true) {
                                            // Assign value of invoice_details object to new variable
                                            $QBO_param = $invoice_details;

                                            // Declare array to be passed to coupon calculatiuon function
                                            $coup_inv_param = array(
                                                'cost' => $QBO_param->cost,
                                                'invoice_id' => $invoice_details->invoice_id
                                            );

                                            // Assign value of calculation function to new variable
                                            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                            // Assign value of variable as new cost to pass to QBO
                                            $QBO_param->cost = $cost_with_inv_coupon;

                                            // die(print($QBO_param->cost));

                                            // Update QBO Invoice with any new info
                                            $quickbook_invoice_id = $this->QuickBookInv($QBO_param, "", "");
                                        }

                                        if ($quickbook_invoice_id) {
                                            $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));
                                        }
                                    }
                                    //if program_price = at completion (2)  && estimate not paid
                                    if($data->program_price == 2 && !$estimate_paid) {
                                        //check customer setting for Basys autocharge
                                        $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
                                        if($customer_details['clover_autocharge'] == 1){
                                            $clover_response = $this->cloverAutocharge($invoice_id);
                                        } else if($customer_details['basys_autocharge'] == 1){
                                            //process payment via basys
                                            $basys_response = $this->basysAutocharge($invoice_id);


                                        }
								    }
								    $return_data[$technician_job_assign_id]['status'] = 200;
								    $print_invoices[] = $invoice_id;
								    $customers[] = $data->customer_id;
								    #check for group billing
								    if(isset($data->billing_type) && $data->billing_type == 1){
									    $gb_invoices[] = $invoice_id;
								    }

							    } else {
								     //HANDLE NO INVOICE CREATED ERROR
								    $return_data[$technician_job_assign_id]['status'] = 400;
								    $return_data[$technician_job_assign_id]['message'] = "Invoice not created for technician job assign id ".$technician_job_assign_id;
							    }
						    }
					    }
			        }

                    //HANDLE TEXT MESSAGE HERE
                    if ($data->is_mobile_text==1) {
                        $data->technician_message = $message;
                        $textSend = $this->jobCompletionText($data);
                    }
                    //HANDLE EMAIL HERE
                    if(isset($post_data['is_tech_customer_note_required']) && $post_data['is_tech_customer_note_required'] == 1){
                        $noteId = $post_data['requiredNoteId'];
                        $where = array(
                            'note_id' => $noteId
                        );
                        $data->tech_customer_note = $this->CompanyModel->getOneNote($where);
                        $data->tech_customer_note_files = $this->CompanyModel->getNoteFiles($noteId);
                    }

                    if($data->is_email==1  ) {
                        $emailSend = $this->jobCompletionEmail($data,$report_data);

                    }

			    } else {
				     //no result...throw error
				    $return_data[$technician_job_assign_id]['status'] = 400;

			    }//end if result

		    }//endforeach tech assign id

		    #close out service-specific, next service only, notes
            if(isset($post_data['next_service_only_note_ids']) && $post_data['next_service_only_note_ids'] != ""){
                $next_service_only_note_ids = explode(',',$post_data['next_service_only_note_ids']);
                foreach($next_service_only_note_ids as $note){
                    $handleUpdateNote = $this->CompanyModel->updateNoteData(array('note_status'=>2), array('note_id'=>$note));
                }
            }



            //Handle Return Response

            if(is_array($print_invoices) && !empty($print_invoices)){
                $print_invoices_unique = array_unique($print_invoices);
                $invString = implode(',', $print_invoices);
                $invStringUnique = implode(',', $print_invoices_unique);
                $custString = implode(',', $customers);
                //$return_data['inv_url'] = base_url()."technician/invoicePrint/".$invString;
                $return_data['inv_url'] = base_url()."technician/invoicePrint/".$invStringUnique;
                $return_data['email_url'] = base_url()."technician/sendInvoice/".$invString.":".$custString;
                $return_data['invoice_id_nums'] = $invStringUnique;

            }
            if(isset($gb_invoices) && is_array($gb_invoices) && !empty($gb_invoices)){
                $gb_invoices_unique = array_unique($gb_invoices);
                $gbInvString = implode(',', $gb_invoices);
                #send work order to group billing contact
                $autoSendInvoice = $this->sendInvoiceGroupBilling($gbInvString);
            }
            if(is_array($print_statements) && !empty($print_statements)){
                $print_statements_unique = array_unique($print_statements);
                $stateString = implode(',', $print_statements);
                $stateStringUnique = implode(',', $print_statements_unique);
                $stateCustString = implode(',', $state_customers);

                $return_data['inv_url'] = base_url()."technician/statementPrint/".$stateStringUnique;
                $return_data['statement_url'] = base_url()."technician/sendStatement/".$stateString.":".$stateCustString;
                $return_data['statement_id_nums'] = $stateStringUnique;

            }

            // die(print_r($return_data));
            foreach($return_data as $id=>$return){

                if(isset($return['status']) && $return['status'] !== 200){
                    $return_data['error'] = true;
                }
            }
            //clear json cache
            ob_end_clean();

      if($return_data['error'] == false){

//        //webhook_trigger
//        $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
//        if($user_info->webhook_service_completed){
//            $this->load->model('api/Webhook');
//            $webhook_data = ['service_name' => $report_data['program_name'], 'customer_email' =>  $customer_details->email];
//            $response = $this->Webhook->callTrigger($user_info->webhook_service_completed, $webhook_data);
//        }
//        //end of webhook

        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> completed successfully</div>');
        echo json_encode($return_data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>One or more service(s) </strong> could not completed please try again</div>');
        echo json_encode(array('status'=>400,'url'=>base_url()."technician/dashboard/"));
      }
    } else {
      $return_data['status'] = 400;
      $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> not completed please try again</div>');
      echo json_encode(array('status'=>400,'url'=>base_url()."technician/dashboard/"));
    }
  }

	///below was configured before multiple services could be handled at one stop

    public function completeJob($technician_job_assign_id) {

        $message =  $this->input->post('message');
	    $post_data = $this->input->post();

	    //gets one job from table technician_job_assign

        $where = array(
            'technician_job_assign_id' => $technician_job_assign_id
        );

        $data  = $this->Tech->getOneJobAssign($where);

		if($data->invoice_id){
			$invoice_id = $data->invoice_id;
		}else{
			$invoice_id = 0;
		}


        $data->technician_message = $message;


        // Company TimeZone
        $companyCurrentTime = getCompanyTimeNow($this->getCompanyTimeZoneString());
        $companyCurrentDate = getCompanyDateNow($this->getCompanyTimeZoneString());

        
        $param = array (

            'is_job_mode' => 1,

            'is_complete' => 1,

            'job_completed_date'=>$companyCurrentDate,

            'job_completed_time'=>$companyCurrentTime

        );

	    // mark job complete

        $result = $this->Tech->updateJobAssign($where,$param);

	    // if complete...

        if ($result) {

	        // get details, create report, send completion email

            $oneJobDetails = $this->Tech->getOneJobAllDetails($where);

            if ($oneJobDetails) {

                $report_data =   array(

                    'technician_job_assign_id' => $oneJobDetails->technician_job_assign_id,

                    'company_id' => $oneJobDetails->company_id,

                    'user_first_name' => $oneJobDetails->user_first_name,

                    'user_last_name' => $oneJobDetails->user_last_name,

                    'applicator_number' => $oneJobDetails->applicator_number,

                    'applicator_phone_number'=>$oneJobDetails->phone,

                    'first_name' => $oneJobDetails->first_name,

                    'last_name' => $oneJobDetails->last_name,

                    'property_title' => $oneJobDetails->property_title,

                    'property_city' => $oneJobDetails->property_city,

                    'yard_square_feet' => $oneJobDetails->yard_square_feet,

                    'property_state' => $oneJobDetails->property_state,

                    'property_zip' => $oneJobDetails->property_zip,

                    'property_address' => $oneJobDetails->property_address,

                    'wind_speed' => $oneJobDetails->wind_speed,

                    'direction' => $oneJobDetails->direction,

                    'temp' => $oneJobDetails->temp,

                    'job_completed_date' => $oneJobDetails->job_completed_date,

                    'job_completed_time' => $oneJobDetails->job_completed_time,

                    'report_created_date' => date("Y-m-d H:i:s")

                );

                if ($data->is_email==1 && $data->is_auto_email == 1) {

                    $emailSend = $this->jobCompletionEmail($data,$report_data);

                }

		        //create report

                $report_id =  $this->RP->createOneReport($report_data);

		        // get products for this one service

                $product_details = $this->Tech->getAllProductDetails(array('job_id' =>$oneJobDetails->job_id));

                if ($product_details) {

                    foreach ($product_details as $key => $value) {

                        $param = array (

                            'report_id' => $report_id,

                            'product_id' => $value->product_id,

                            'product_name' => $value->product_name,

                            'epa_reg_nunber' => $value->epa_reg_nunber,

                            'weed_pest_prevented' => $value->weed_pest_prevented,

                            're_entry_time' => $value->re_entry_time,

                            'restricted_product' => $value->restricted_product

                        );

                        if ($value->application_type==1) {

                            $param['application_type'] = 'Broadcast';

                        } else if($value->application_type==2) {

                            $param['application_type'] = 'Spot Spray';

                        } elseif ($value->application_type==3) {

                            $param['application_type'] = 'Granular';

                        }

                        if($value->chemical_type==1) {

                            $param['chemical_type'] = 'Herbicide';

                        }

                        else if($value->chemical_type==2) {

                            $param['chemical_type'] = 'Fungicide';

                        }

                        else if($value->chemical_type==3) {

                            $param['chemical_type'] = 'Insecticide';

                        }

                        else if($value->chemical_type==4) {

                            $param['chemical_type'] = 'Fertilizer';

                        }

                        else if($value->chemical_type==5) {

                            $param['chemical_type'] = 'Wetting Agent';

                        }

                        else if($value->chemical_type==6) {

                            $param['chemical_type'] = 'Surfactant/Tank Additive';

                        }

                        else if($value->chemical_type==7) {

                            $param['chemical_type'] = 'Aquatics';

                        }

                        else if($value->chemical_type==8) {

                            $param['chemical_type'] = 'Growth Regulator';

                        }

                        else if($value->chemical_type==9) {

                            $param['chemical_type'] = 'Biostimulants';

                        }

                        $active_ingredients = array();

                        $ingredientDatails = getActiveIngredient(array('product_id'=>$value->product_id));

                            if ($ingredientDatails) {

                                foreach ($ingredientDatails as $key2 => $value2) {

                                    $active_ingredients[] =   $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' %';

                                }

                            }

                        $param['active_ingredients'] =  implode(', ', $active_ingredients);

                        if (!empty($value->application_rate) && $value->application_rate != 0) {

                        $param['application_rate'] = $value->application_rate . ' ' . $value->application_unit . ' / ' . $value->application_per;

                        }

                        $param['estimate_of_pesticide_used'] = amountOfChemicalUsed($value, $post_data[$technician_job_assign_id], $oneJobDetails->yard_square_feet);

                        $param['amount_of_mixture_applied'] =  $post_data[$technician_job_assign_id][$value->product_id] . ' ' . $value->mixture_application_unit;



                        $this->db->insert("report_product",$param);

                        //$this->modify_product_inventory($param['product_id'], $param['estimate_of_pesticide_used']);
                    }

                }

            }

            if($invoice_id == 0){

                // if invoice id not store in job assign tech table (aka old way of creating an invoice) then we check the invoice table for the property, program, job, customer combo
                $where = array(
                    'property_id' => $data->property_id,
                    'job_id' => $data->job_id,
                    'program_id'=> $data->program_id,
                    'customer_id' => $data->customer_id,
                );



                $whereArrPaidEstimate = array(

                    'property_id' => $data->property_id,

                    'program_id'=> $data->program_id,

                    'customer_id' => $data->customer_id,

                    'status' => 3,

                );

                $estimate_paid =   GetOneEstimateDetails($whereArrPaidEstimate);

                $chek_inv  = $this->INV->getOneInvoice($where);



	            // Invoice not exist case.

                if (!$chek_inv) {



                    $where = array(

                    'property_id' => $data->property_id,

                    'job_id' => $data->job_id,

                    'program_id'=> $data->program_id,

                    'customer_id' => $data->customer_id,

                    );

                    $estimate_price_override =   GetOneEstimateJobPriceOverride($where);

                    if ($estimate_price_override && $estimate_price_override->is_price_override_set == 1 ) {

                    $cost =  $estimate_price_override->price_override;

                    } else {

                    $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $data->property_id,'program_id' => $data->program_id));

                    if ($priceOverrideData &&  $priceOverrideData->is_price_override_set == 1 ) {

                        $cost =  $priceOverrideData->price_override;

                    } else {

                        $price = $data->job_price;

                        $cost = ($data->yard_square_feet * $price)/1000;

                    }

                    }

                    $where = array('company_id' =>$data->company_id);

                    $setting_details = $this->CompanyModel->getOneCompany($where);

                    $updatearr = array(

                    'cost' => ($cost),

                    );

                    $this->RP->updateReport( array('report_id'=>$report_id), $updatearr);

                    if($data->program_price != 3) {

                        // When program type is not Manual Billing.

                        $param = array(

                            'user_id' => $this->session->userdata['spraye_technician_login']->user_id,

                            'company_id' => $data->company_id,

                            'customer_id' => $data->customer_id,

                            'property_id' => $data->property_id,

                            'program_id' => $data->program_id,

                            'job_id' => $data->job_id,

                            'invoice_date' => date("Y-m-d"),

                            'invoice_created' => date("Y-m-d H:i:s"),

                            'report_id' => $report_id,

                            'description' => $data->job_name,

                            'cost' => ($cost)

                        );

                        // create the invoice and get the id

                        $invoice_id = $this->INV->createOneInvoice($param);

                        if ($invoice_id) {

                            //update tech_job_assign

                            $updateTechAssign  = $this->Tech->updateJobAssign(array('technician_job_assign_id' => $technician_job_assign_id), array('invoice_id'=>$invoice_id));



                            $param['invoice_id'] = $invoice_id;

                            $total_tax_amount = 0;

                            if ($setting_details->is_sales_tax==1) {

                            $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id'=>$data->property_id));

                            if ($property_assign_tax) {

                                foreach ($property_assign_tax as  $tax_details) {

                                $invoice_tax_details =  array(

                                    'invoice_id' => $invoice_id,

                                    'tax_name' => $tax_details['tax_name'],

                                    'tax_value' => $tax_details['tax_value'],

                                    'tax_amount' => $cost*$tax_details['tax_value']/100

                                );

                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);

                                $total_tax_amount +=  $invoice_tax_details['tax_amount'];

                                }

                            }

                            }

                            if ($estimate_paid) {

                            $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('payment_status'=>2,'partial_payment'=>$cost+$total_tax_amount));

                            }

                            $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));

                            if($data->program_price == 2) {
                                // Create invoice in quickbook.

                                $QBO_fallback = false;

                                if (isset($data->job_id) && !empty($data->job_id)) {

                                    $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));

                                    if (isset($QBO_job_details) && !empty($QBO_job_details)) {

                                        $single_job_desc = $QBO_job_details->job_description;

                                        $single_job_name = $QBO_job_details->job_name;

                                        // Assign value of invoice_details object to new variable
                                        $QBO_param = $invoice_details;

                                            // Declare array to be passed to coupon calculatiuon function
                                            $coup_inv_param = array(
                                                'cost' => $QBO_param->cost,
                                                'invoice_id' => $data->invoice_details->invoice_id
                                            );

                                            // Assign value of calculation function to new variable
                                            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                            // Assign value of variable as new cost to pass to QBO
                                            $QBO_param->cost = $cost_with_inv_coupon;
                                            // die(print($QBO_param->cost));

                                            // Update QBO Invoice with any new info


                                            $quickbook_invoice_id = $this->QuickBookInv($QBO_param, $single_job_desc, $single_job_name);

                                    } else {

                                        $QBO_fallback = true;

                                    }

                                } else {

                                    $QBO_fallback = true;

                                }

                                if ($QBO_fallback == true) {
                                    // Assign value of invoice_details object to new variable
                                    $QBO_param = $invoice_details;

                                    // Declare array to be passed to coupon calculatiuon function
                                    $coup_inv_param = array(
                                        'cost' => $QBO_param->cost,
                                        'invoice_id' => $data->invoice_details->invoice_id
                                    );

                                    // Assign value of calculation function to new variable
                                    $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                                    // Assign value of variable as new cost to pass to QBO
                                    $QBO_param->cost = $cost_with_inv_coupon;

                                    // die(print($QBO_param->cost));

                                    // Update QBO Invoice with any new info


                                    $quickbook_invoice_id = $this->QuickBookInv($QBO_param, "", "");

                                }



                                if ($quickbook_invoice_id) {

                                    $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));

                                }

			                }

                        } else {

                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong> not created</div>');

                            echo json_encode(array('status'=>400,'url'=>base_url()."technician/dashboard/"));

                        }

                    }

                } else {

                    // Invoice already exists but was created before code changes that implement db table property_program_job_invoice aka invoice for one 1 job at a time

                    $invoice_id = $chek_inv->invoice_id;



                    $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));



                    // Create invoice in quickbook.

                    $QBO_fallback = false;

                    if (isset($data->job_id) && !empty($data->job_id)) {

                        $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));

                        if (isset($QBO_job_details) && !empty($QBO_job_details)) {

                            $single_job_desc = $QBO_job_details->job_description;

                            $single_job_name = $QBO_job_details->job_name;

                            // Assign value of invoice_details object to new variable
                            $QBO_param = $invoice_details;

                            // Declare array to be passed to coupon calculatiuon function
                            $coup_inv_param = array(
                                'cost' => $QBO_param->cost,
                                'invoice_id' => $data->invoice_details->invoice_id
                            );

                            // Assign value of calculation function to new variable
                            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                            // Assign value of variable as new cost to pass to QBO
                            $QBO_param->cost = $cost_with_inv_coupon;

                            // die(print($QBO_param->cost));

                            // Update QBO Invoice with any new info

                            $quickbook_invoice_id = $this->QuickBookInv($QBO_param, $single_job_desc, $single_job_name);

                        } else {

                            $QBO_fallback = true;

                        }

                    } else {

                        $QBO_fallback = true;

                    }

                    if ($QBO_fallback == true) {
                        // Assign value of invoice_details object to new variable
                        $QBO_param = $invoice_details;

                        // Declare array to be passed to coupon calculatiuon function
                        $coup_cust_param = array(
                            'cost' => $QBO_param->cost,
                            'customer_id' => $data->customer_id
                        );

                        // Assign value of calculation function to new variable
                        $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);

                        // Assign value of variable as new cost to pass to QBO
                        $QBO_param->cost = $cost_with_cust_coupon;

                        // die(print($QBO_param->cost));

                        // Update QBO Invoice with any new info

                        $quickbook_invoice_id = $this->QuickBookInv($invoice_details, "", "");

                    }



                    if ($quickbook_invoice_id) {

                    $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));

                    }



                    $cost = $chek_inv->cost;

                    //update tech_job_assign

                    $updateTechAssign  = $this->Tech->updateJobAssign(array('technician_job_assign_id' => $technician_job_assign_id), array('invoice_id'=>$invoice_id));



                    //update invoice

                    $updateInvoiceParam =  array(

                    'invoice_created' => date("Y-m-d H:i:s"),

                    'report_id' => $report_id,

                    );

                    if($estimate_paid) {

                    $total_tax_amount = getAllSalesTaxSumByInvoice($chek_inv->invoice_id)->total_tax_amount;

                    $updateInvoiceParam['payment_status'] = 2;

                    $updateInvoiceParam['partial_payment'] = $chek_inv->cost+$total_tax_amount;

                    }

                    $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),$updateInvoiceParam);



                    //update report cost

                    $updatearr = array(

                    'cost' => ($cost),

                    );

                    $this->RP->updateReport( array('report_id'=>$report_id), $updatearr);

                    // need to handle invoice report relationship

                }



            }else{

                //invoice already exists case

                //get job cost for report

                $get_ppjobinv  = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails(array('property_id'=>$data->property_id, 'program_id'=>$data->program_id, 'job_id'=>$data->job_id,'invoice_id'=>$invoice_id));

                if($get_ppjobinv){

                    //update report cost

                    $updatearr = array(

                        'cost' => ($get_ppjobinv->job_cost),

                    );

                    $this->RP->updateReport( array('report_id'=>$report_id), $updatearr);



                    //update PPJOBINV table



                    $update_PPJOBINV = $this->PropertyProgramJobInvoiceModel->updatePropertyProgramJobInvoice(array('property_program_job_invoice_id'=>$get_ppjobinv->property_program_job_invoice_id), array('report_id'=>$report_id, 'updated_at'=>date("Y-m-d H:i:s")));

                }

                $whereArrPaidEstimate = array(

                    'property_id' => $data->property_id,

                    'program_id'=> $data->program_id,

                    'customer_id' => $data->customer_id,

                    'status' => 3,

                );

                $estimate_paid =   GetOneEstimateDetails($whereArrPaidEstimate);

                if($estimate_paid) {

                $total_tax_amount = getAllSalesTaxSumByInvoice($chek_inv->invoice_id)->total_tax_amount;

                $updateInvoiceParam['payment_status'] = 2;

                $updateInvoiceParam['partial_payment'] = $chek_inv->cost+$total_tax_amount;

                }

            }

            $return_data = array();

            $return_data['status'] = 200;

            $return_data['url'] = base_url()."technician/dashboard/";

            if($data->program_price != 3) {

                $return_data['invoice_id'] = $invoice_id;

                $return_data['inv_url'] = base_url()."technician/invoicePrint/".$invoice_id;

                $invoice_details = $this->INV->getOneInvoive(array('invoice_id'=>$invoice_id));

                if ($invoice_details->quickbook_invoice_id!=0) {

                    // Assign value of invoice_details object to new variable
                    $QBO_param = $invoice_details;

                    // Declare array to be passed to coupon calculatiuon function
                    $coup_cust_param = array(
                        'cost' => $QBO_param->cost,
                        'customer_id' => $data->customer_id
                    );

                    // Assign value of calculation function to new variable
                    $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);

                    // Assign value of variable as new cost to pass to QBO
                    $QBO_param->cost = $cost_with_cust_coupon;

                    // die(print($QBO_param->cost));

                    // Update QBO Invoice with any new info

                    $res = $this->QuickBookInvUpdate($invoice_details);

                }else{

                    if($data->program_price == 2) {

                        // Create invoice in quickbook.

                        $QBO_fallback = false;

                        if (isset($data->job_id) && !empty($data->job_id)) {

                            $QBO_job_details = $this->JobModel->getOneJob(array('job_id' => $data->job_id));

                            if (isset($QBO_job_details) && !empty($QBO_job_details)) {

                                $single_job_desc = $QBO_job_details->job_description;

                                $single_job_name = $QBO_job_details->job_name;
                                // Assign value of invoice_details object to new variable
                                $QBO_param = $invoice_details;

                                // Declare array to be passed to coupon calculatiuon function
                                $coup_cust_param = array(
                                    'cost' => $QBO_param->cost,
                                    'customer_id' => $data->customer_id
                                );

                                // Assign value of calculation function to new variable
                                $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);

                                // Assign value of variable as new cost to pass to QBO
                                $QBO_param->cost = $cost_with_cust_coupon;

                                // die(print($QBO_param->cost));

                                // Update QBO Invoice with any new info

                                $quickbook_invoice_id = $this->QuickBookInv($invoice_details, $single_job_desc, $single_job_name);

                            } else {

                            $QBO_fallback = true;

                            }

                        } else {

                            $QBO_fallback = true;

                        }

                        if ($QBO_fallback == true) {
                            // Assign value of invoice_details object to new variable
                            $QBO_param = $invoice_details;

                            // Declare array to be passed to coupon calculatiuon function
                            $coup_cust_param = array(
                                'cost' => $QBO_param->cost,
                                'customer_id' => $data->customer_id
                            );

                            // Assign value of calculation function to new variable
                            $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);

                            // Assign value of variable as new cost to pass to QBO
                            $QBO_param->cost = $cost_with_cust_coupon;

                            // die(print($QBO_param->cost));

                            // Update QBO Invoice with any new info


                            $quickbook_invoice_id = $this->QuickBookInv($invoice_details, "", "");

                        }

                        if ($quickbook_invoice_id) {

                        $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),array('quickbook_invoice_id'=>$quickbook_invoice_id));

                        }

                    }

                }

            }

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> completed successfully</div>');

            echo json_encode($return_data);

        } else {

        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> not completed please try again</div>');

        echo json_encode(array('status'=>400,'url'=>base_url()."technician/dashboard/"));

        }

    }

    public function jobRescheduleText($technician_job_assign_id) {
	  $sendText = [];
	  $tech_job_assign_details = $this->Tech->getOneJobAssign(array('technician_job_assign_id'=>$technician_job_assign_id));
	  if(!empty($tech_job_assign_details)){
		  $textdata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' =>$tech_job_assign_details->company_id));
		  if(isset($textdata['company_details']) && $textdata['company_details']->is_text_message == 1){
			  $textdata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' =>$tech_job_assign_details->company_id));
			  if(isset($textdata['company_email_details']->job_sheduled_skipped_status) && $textdata['company_email_details']->job_sheduled_skipped_status == 1){
				  #check if customer billing type is Group Billing
				  $checkGroupBilling = $this->CustomerModel->checkGroupBilling($tech_job_assign_details->customer_id);
				  if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
					  $groupBillingDetails = $this->PropertyModel->getGroupBillingByProperty($tech_job_assign_details->property_id);
					  $textdata['contactData'] = array(
						  'phone_opt_in'=>$groupBillingDetails['phone_opt_in'],
						  'phone'=>$groupBillingDetails['phone'],
					  );
				  }else{
					  $textdata['contactData'] = array(
						  'phone_opt_in'=>$tech_job_assign_details->is_mobile_text,
						  'phone'=>$tech_job_assign_details->phone,
					  );
				  }

				  if($textdata['contactData']['phone_opt_in'] == 1){
					  $sendText = Send_Text_dynamic($textdata['contactData']['phone'],$textdata['company_email_details']->job_sheduled_skipped_text,'Service Rescheduled');
				  }
			  }
		  }
	  }
	  return $sendText;
    }

	public function jobResceduleEmail($technician_job_assign_id,$data = array()) {
		$tech_job_assign_details = $this->Tech->getOneJobAssign(array('technician_job_assign_id'=>$technician_job_assign_id));
        //die(print_r($tech_job_assign_details->pre_service_notification));
        $pre_service_notification_email = 0;
        $pre_service_notification_text = 0;
        if (strpos( $tech_job_assign_details->pre_service_notification,'"2"') !=0  ){
            //die("Yes, it has a preservice notification to send an email");
            $pre_service_notification_email = 1;
        }
        if (strpos( $tech_job_assign_details->pre_service_notification,'"3"') !=0  ){
            //die("Yes, it has a preservice notification to send an email");
            $pre_service_notification_text = 1;
        }
		if(!empty($tech_job_assign_details)){
			$emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' =>$tech_job_assign_details->company_id));
			$emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' =>$tech_job_assign_details->company_id));
			$emaildata['property_address'] = $tech_job_assign_details->property_address;
			$emaildata['job_name'] = $tech_job_assign_details->job_name;
			$emaildata['reschedule_reason'] = $tech_job_assign_details->reschedule_message;



			#check if customer billing type is Group Billing
			$checkGroupBilling = $this->CustomerModel->checkGroupBilling($tech_job_assign_details->customer_id);
			if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
				$groupBillingDetails = $this->PropertyModel->getGroupBillingByProperty($tech_job_assign_details->property_id);
				$emaildata['contactData'] = array(
					'first_name' => $groupBillingDetails['first_name'],
					'last_name' => $groupBillingDetails['last_name'],
					'contact_id'=> $groupBillingDetails['group_billing_id'],
					'email_opt_in'=>$groupBillingDetails['email_opt_in'],
					'email'=>$groupBillingDetails['email'],
                    'group_billing'=>1,
				);
			}else{
				$emaildata['contactData'] = array(
					'first_name' => $tech_job_assign_details->first_name,
					'last_name' => $tech_job_assign_details->last_name,
					'contact_id'=> $tech_job_assign_details->customer_id,
					'email_opt_in'=>$tech_job_assign_details->is_email,
					'email'=>$tech_job_assign_details->email,
                    'group_billing'=>0,
				);
			}


            $body  = $this->load->view('email/job_skipped_email', $emaildata, true);

            //$body  = $this->load->view('email/reschedule_email', $emaildata, true);
			$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' =>$tech_job_assign_details->company_id,'is_smtp'=>1));
            $company_email_details = false;
			if (!$company_email_details) {
				$company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
			}
			if($emaildata['company_email_details']->job_sheduled_skipped_status == 1 && $emaildata['contactData']['email_opt_in'] == 1 && $pre_service_notification_email) {
			#send email to customer
				$sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $emaildata['company_details']->company_name, "email" =>$emaildata['company_details']->company_email),  $body, 'Service Rescheduled');
			#send email to admin
				$email_details['reschedule_message'] = $tech_job_assign_details->reschedule_message;
				$body = $this->load->view('email/reschedule_email',$email_details,true);
				$sendEmail = Send_Mail_dynamic($company_email_details,$emaildata['company_details']->company_email,array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email),  $body, 'Service Rescheduled');

			}else{
				$sendEmail = [];
			}

			return $sendEmail;

		}

    }



    public function rescheduleJobMultiple($technician_job_assign_ids) {
		if(isset($technician_job_assign_ids)){
			$technician_job_assign_array = explode(',', trim($technician_job_assign_ids));

			$data = $this->input->post();
           // echo 'hola';
           // die("hola");

            $reason_array = explode('/',$data['reason_id'] );
            $reason_message = ($reason_array[0] == '-1')?'Other: '.$data['reason_other']:$reason_array[1];
			$results = array();
			foreach($technician_job_assign_array as $technician_job_assign_id){

				$where_ar = array(
				 'technician_job_assign_id' => $technician_job_assign_id
			   	);
				$updatearr = array(
					'is_job_mode' =>2,
					'reschedule_reason_id' =>$reason_array[0],
                    'reschedule_message' => $reason_message
				 );

					//check for existing invoice
			  $details = $this->Tech->GetOneRow($where_ar);
			  if ($details) {
				if($details->invoice_id){
				  $invoice_id = $details->invoice_id;
				  $deleted_job = $details->job_id;
				  //check for invoice method
				  $invDetails = $this->INV->getOneInvoive(array('invoice_tbl.invoice_id'=>$invoice_id));
				  if($invDetails){
					  //if invoice method = invoice at completion
					  if($invDetails->is_created == 0){
						  //delete record from PropertyProgramJobInvoice table
						  $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id'=>$deleted_job, 'invoice_id'=>$invoice_id));

						  //get all jobs with same invoice id
						  $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id'=>$invoice_id));

						  //if jobs then update invoice
						  if($PPJOBINV){
							//calculate new invoice total
							$total_cost = 0;
							$description = "";
							$json = array();
							$json['jobs'] = array();
							foreach($PPJOBINV as $invoicedJob){
							  $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
							  $total_cost += $invoicedJob['job_cost'];
							  $description .= $job_details->job_name ." ";
							  $json['jobs'][]=array(
								'job_id'=>$invoicedJob['job_id'],
								'job_cost'=>$invoicedJob['job_cost'],
							  );
							}
							//update invoice
							$updateInvArr = array(
							  'cost'=>$total_cost,
							  'description'=>$description,
							  'invoice_updated'=> date("Y-m-d H:i:s"),
							  'json'=>json_encode($json),
							);
							$updateInv = $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),$updateInvArr);
							//update quickbooks

							//update sales tax
							  $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
							  //die(print_r($get_invoice_tax));
							  if (!empty($get_invoice_tax)) {
								  foreach ($get_invoice_tax as $g_i_t){
									  $invoice_tax_details =  array(
										  'invoice_id' => $invoice_id,
										  'tax_name' => $g_i_t['tax_name'],
										  'tax_value' => $g_i_t['tax_value'],
										  'tax_value' => $g_i_t['tax_value'],
										  'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
									  );
									  //delete old sales tax record
									  $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
									  //create new sales tax record
									  $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
								  }
							  }
							  //remove invoice id from tech assign job
							  $updatearr['invoice_id'] = 0;
							//echo $total_cost;
						  }else{
							//if no other jobs with invoice id then delete(archive) invoice
							$archiveInv = $this->INV->deleteInvoice(array('invoice_id'=>$invoice_id));

							//remove invoice id from tech assign job
							$updatearr['invoice_id'] = 0;
						  }
					  }
					  //die(print_r($invDetails));
				  }

				}
			  }
				//die(print_r($updatearr));
			   $result =  $this->Tech->updateJobAssign($where_ar,$updatearr);

				if ($result) {
					#send email
					$sendEmail = $this->jobResceduleEmail($technician_job_assign_id,$data);
					#send text
					$sendText = $this->jobRescheduleText($technician_job_assign_id);

					$results[$technician_job_assign_id] = "success";
				} else {
					$results[$technician_job_assign_id] = "error";

				}

			}//endforeach tech assign id

			if(is_array($results)){
				 if(in_array('error',$results)){
				 	$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>One or more Service(s) </strong> not rescheduled please try again</div>');
				 }else{
					 $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service(s) </strong> rescheduled successfully</div>');
				 }
			}else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>One or more Service(s) </strong> not rescheduled please try again</div>');
			}
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;

		}
	}
    public function rescheduleJob($technician_job_assign_id) {

      $data = $this->input->post();


     //email data

      $email_details['setting_details'] = $this->session->userdata('compny_details');

      $where_ar = array(

         'technician_job_assign_id' => $technician_job_assign_id

       );

      $email_details['job_details']  = $this->Tech->getOneJobAssign($where_ar);

      $email_details['reschedule_message'] = $data['reschedule_message'];

      $body = $this->load->view('email/reschedule_email',$email_details,true);

      $where = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);

      $where['is_smtp'] = 1;

      $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

	  if (!$company_email_details) {

			   $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

	  }

       // $res =   Send_Mail_dynamic($company_email_details,$email_details['setting_details']->company_email,$this->session->userdata['compny_details']->company_name,  $body, 'Service Reschedule');

	  $res = $this->jobResceduleEmail($technician_job_assign_id,$data);



       $where = array(

            'technician_job_assign_id' => $technician_job_assign_id

       );

	  $updatearr = array(

        'is_job_mode' =>2,

        'reschedule_message' =>$data['reschedule_message'],

      );

		//check for existing invoice

      $details = $this->Tech->GetOneRow($where);

  	  if ($details) {

		if($details->invoice_id){

		  $invoice_id = $details->invoice_id;

		  $deleted_job = $details->job_id;

		  //check for invoice method

		  $invDetails = $this->INV->getOneInvoive(array('invoice_tbl.invoice_id'=>$invoice_id));

		  if($invDetails){

			  //if invoice method = invoice at completion

			  if($invDetails->is_created == 0){

				  //delete record from PropertyProgramJobInvoice table

				  $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id'=>$deleted_job, 'invoice_id'=>$invoice_id));



				  //get all jobs with same invoice id

				  $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id'=>$invoice_id));



				  //if jobs then update invoice

				  if($PPJOBINV){

					//calculate new invoice total

					$total_cost = 0;

					$description = "";

					$json = array();

					$json['jobs'] = array();

					foreach($PPJOBINV as $invoicedJob){

					  $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));

					  $total_cost += $invoicedJob['job_cost'];

					  $description .= $job_details->job_name ." ";

					  $json['jobs'][]=array(

					  	'job_id'=>$invoicedJob['job_id'],

					    'job_cost'=>$invoicedJob['job_cost'],

					  );

					}

					//update invoice

					$updateInvArr = array(

					  'cost'=>$total_cost,

					  'description'=>$description,

					  'invoice_updated'=> date("Y-m-d H:i:s"),

					  'json'=>json_encode($json),

					);

					$updateInv = $this->INV->updateInvovice(array('invoice_id'=>$invoice_id),$updateInvArr);

					//update quickbooks



					//update sales tax

					$get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));

					//die(print_r($get_invoice_tax));

					if (!empty($get_invoice_tax)) {

						foreach ($get_invoice_tax as $g_i_t){
							$invoice_tax_details =  array(
								'invoice_id' => $invoice_id,
								'tax_name' => $g_i_t['tax_name'],
								'tax_value' => $g_i_t['tax_value'],
								'tax_value' => $g_i_t['tax_value'],
								'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
							);
							//delete old sales tax record
							$this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
							//create new sales tax record
							$this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
						}

					}

					  //remove invoice id from tech assign job

					  $updatearr['invoice_id'] = 0;

					//echo $total_cost;

				  }else{

					//if no other jobs with invoice id then delete(archive) invoice

					$archiveInv = $this->INV->deleteInvoice(array('invoice_id'=>$invoice_id));



					//remove invoice id from tech assign job

					$updatearr['invoice_id'] = 0;

				  }

			  }

			  //die(print_r($invDetails));

		  }



		}

	  }

		//die(print_r($updatearr));

       $result =  $this->Tech->updateJobAssign($where,$updatearr);



        if ($result) {

          $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> rescheduled successfully</div>');

        } else {

          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> not rescheduled please try again</div>');

        }

        echo "1";



    }

    public function finishDay() {



        $where = array(

            'technician_id' =>$this->session->userdata['spraye_technician_login']->user_id,

            'job_assign_date' => date("Y-m-d"),

            'is_job_mode' => 0,

        );



        $where2 = array(

            'technician_id' =>$this->session->userdata['spraye_technician_login']->user_id,

            'job_assign_date' => date("Y-m-d"),

        );





        $result =  $this->Tech->GetOneRow($where);

        $result2 =  $this->Tech->GetOneRow($where2);

        if ($result) {

            echo 1;

        } else if($result2) {

            echo 2;

        } else {

            echo 3;

        }



    }

    public function logout() {

        $this->session->sess_destroy();

        return redirect('technician/auth');

    }

    public function updateProfile() {



      $where = array('user_id' =>$this->session->userdata['spraye_technician_login']->user_id);



        $data['user_details'] =  $this->Administrator->getOneAdmin($where);

        $data['user_details']->user_pic = ($data['user_details']->user_pic_resized != '') ? $data['user_details']->user_pic_resized : $data['user_details']->user_pic;

        $page["active_sidebar"] = "mangeUserNav";

        $page["page_content"] = $this->load->view("technician/update_profile", $data, TRUE);

        $this->layout->technicianTemplateTableDash($page);



    }

    public function updateProfileData() {

        $where = array('user_id' =>$this->session->userdata['spraye_technician_login']->user_id);

        $data =  $this->input->post();

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('user_first_name', 'first_name', 'trim|required');

        $this->form_validation->set_rules('user_last_name', 'last_name', 'trim|required');

        $this->form_validation->set_rules('email', 'email', 'trim|required');

        $this->form_validation->set_rules('phone', 'phone', 'trim|required');

        $this->form_validation->set_rules('password', 'password', 'trim|required');

        $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE){

        $this->updateProfile();

        }else{

        $checkArray = array(

            'email' => $data['email'],

            'user_id !=' => $this->session->userdata['spraye_technician_login']->user_id,

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

            $file_name  = $this->session->userdata['spraye_technician_login']->user_id.'_'.date("ymdhis").'.'.$fileext ;

            $resized_file_name  = $this->session->userdata['spraye_technician_login']->user_id.'_'.date("ymdhis").'_resized.'.$fileext ;

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

            $this->session->set_flashdata('message',"<div class='alert alert-success alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'></a>

                <strong>Profile </strong>updated successfully</div>");

                    redirect("technician/updateProfile");

            } else {

            $this->session->set_flashdata('message',"<div class='alert alert-danger alert-dismissible fade in'aria-hidden='true' ><a href='#' class='close' data-dismiss='alert' aria-label='close'></a>

                <strong>Profile not updated </strong> Please try again</div>");

                    redirect("technician/updateProfile");

            }

        }

        }

    }

    public function basysAutocharge($invoice_id){

	 	$where = array('invoice_id' => $invoice_id);

        $invoice_details = $this->INV->getOneInvoive($where);

	    $company_id = $this->session->userdata['spraye_technician_login']->company_id;

	   //need to get Basys Customer ID and api key
	    $basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));

	    $customer_details = $this->CustomerModel->getCustomerDetail($invoice_details->customer_id);

        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        $tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax($where);

		////////////////////////////////////

		// START INVOICE CALCULATION COST //

        $tmp_invoice_id = $invoice_id;

		// invoice cost
		// $invoice_total_cost = $invoice->cost;
		// cost of all services (with price overrides) - service coupons
		$job_cost_total = 0;
		$where = array(
			'property_program_job_invoice.invoice_id' => $tmp_invoice_id
		);

		$proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);


		if (!empty($proprojobinv)) {

			foreach($proprojobinv as $job) {

				$job_cost = $job['job_cost'];
                $job_where = array(

                    'job_id' => $job['job_id'],
                    'customer_id' =>$job['customer_id'],
                    'property_id' =>$job['property_id'],
                    'program_id' =>$job['program_id']

                );

                $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);



                if (!empty($coupon_job_details)) {

                    foreach($coupon_job_details as $coupon) {

                        // $nestedData['email'] = json_encode($coupon->coupon_amount);

                        $coupon_job_amm_total = 0;

                        $coupon_job_amm = $coupon->coupon_amount;

                        $coupon_job_calc = $coupon->coupon_amount_calculation;



                        if ($coupon_job_calc == 0) { // flat amm

                            $coupon_job_amm_total = (float) $coupon_job_amm;

                        } else { // percentage

                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;

                        }



                        $job_cost = $job_cost - $coupon_job_amm_total;



                        if ($job_cost < 0) {

                            $job_cost = 0;

                        }

                    }

                }



				$job_cost_total += $job_cost;

			}

            $invoice_total_cost = $job_cost_total;

        } else {

            $invoice_total_cost = $invoice_details->cost;

        }

		// - invoice coupons

		$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id ));

		foreach ($coupon_invoice_details as $coupon_invoice) {

			if (!empty($coupon_invoice)) {

				$coupon_invoice_amm = $coupon_invoice->coupon_amount;

				$coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;



				if ($coupon_invoice_amm_calc == 0) { // flat amm

					$invoice_total_cost -= (float) $coupon_invoice_amm;

				} else { // percentage

					$coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;

					$invoice_total_cost -= $coupon_invoice_amm;

				}

				if ($invoice_total_cost < 0) {

					$invoice_total_cost = 0;

				}

			}

		}

		// + tax cost

		$invoice_total_tax = 0;

		$invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id ));

		if (!empty($invoice_sales_tax_details)) {

			foreach($invoice_sales_tax_details as $tax) {

				if (array_key_exists("tax_value", $tax)) {

					$tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;

					$invoice_total_tax += $tax_amm_to_add;

				}

			}

		}

		$invoice_total_cost += $invoice_total_tax;

		$total_tax_amount = $invoice_total_tax;





        // WITH COUPONS CALC

        $convenience_fee = number_format(($setting_details->convenience_fee * ($invoice_total_cost - $invoice_details->partial_payment) / 100),2);

        $total_amount = $invoice_total_cost + $convenience_fee - $invoice_details->partial_payment;


        $order_id = (string)strtotime("now");



        $post = array(

            "type" => "sale",

            "amount" => round($total_amount * 100),

            "tax_exempt" => false,

            "tax_amount" => round($total_tax_amount * 100),

            "currency" => "USD",

            "description" => "Invoice for CustomerId: ".$invoice_details->customer_id,

            "order_id" => $order_id,

            "processor_id" => "",

            //"ip_address" => $this->getClientIp(),

            "email_receipt" => true,

            "email_address" => $invoice_details->email,

            "create_vault_record" => true,

            "payment_method" => array(

                "customer" => array(

                    "id" => $customer_details['basys_customer_id'],

                    "payment_method_type" => "card"

                ),

            ),

            "billing_address" => array(

                "first_name" => $invoice_details->first_name,

                "last_name" => $invoice_details->last_name,

                "company" => $invoice_details->customer_company_name,

                "address_line_1" => $invoice_details->billing_street,

                "address_line_2" => isset($invoice_details->billing_street_2) ? $invoice_details->billing_street_2 : "",

                "city" => isset($invoice_details->billing_city) ? $invoice_details->billing_city : "",

                "state" => isset($invoice_details->billing_state) ? $invoice_details->billing_state : "",

                "postal_code" => isset($invoice_details->billing_zipcode) ? $invoice_details->billing_zipcode : "",

                "email" => $invoice_details->email,

                "phone" => $invoice_details->phone,

            ),

        );



        if ($convenience_fee != 0) {

            $post['payment_adjustment'] = array(

                "type" => "flat",

                "value" => $convenience_fee * 100,

            );

        }

        $curl = curl_init();

        $payload = json_encode($post);



        curl_setopt_array($curl, array(

            CURLOPT_URL => BASYS_URL."api/transaction",

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_ENCODING => "",

            CURLOPT_MAXREDIRS => 10,

            CURLOPT_TIMEOUT => 0,

            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => "POST",

            CURLOPT_POSTFIELDS =>$payload,

            CURLOPT_HTTPHEADER => array(

                "Authorization: ".$basys_details->api_key,

                "Content-Type: application/json",

            )
        ));

        $response = curl_exec($curl);

        $result = json_decode($response, true);

		curl_close($curl);



		if(isset($result['status']) & $result['status'] == 'success'){

			if ($result['data']['response_code'] == 100) {

				//if payment is successful and approved



				$updatearr = array(

					'payment_status' => 2,

					'status' => 1,

					'basys_transaction_id' => $result['data']['id'],

					'payment_created' => date("Y-m-d H:i:s"),

					'partial_payment' => $invoice_details->cost + $total_tax_amount + $invoice_details->partial_payment,

					'basys_order_id' => $order_id

				);

				$this->INV->updateInvovice(array('invoice_id' => $invoice_id), $updatearr);

				//send email to company admin

				$data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));



                $data['setting_details'] = $setting_details;

                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));

				$invoice_details->convenience_fee = $convenience_fee;

                $invoice_details->tax_amount = $total_tax_amount;

                $data['invoice_details'] = $invoice_details;



				$body = $this->load->view('invoice_paid_mail', $data, true);

				$where = array('company_id' => $company_id, 'is_smtp'=> 1);



                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);



				if (!$company_email_details) {

					$company_email_details = $this->CompanyModel->getOneDefaultEmailArray();

				}



				$res = Send_Mail_dynamic($company_email_details, $data['user_details']->email,  array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');



				//email invoice to customer

				$data['customer_details'] = $customer_details;

                $hashstring = md5($customer_details['email']."-".$invoice_details->customer_id."-".date("Y-m-d H:i:s"));



                $hash_tbl_arr = array(

                    'invoice_id' => $invoice_id,

                    'company_id' => $company_id,

                    'hashstring' => $hashstring,

                    'created_at' => date("Y-m-d H:i:s"),

                );



                $this->db->insert('invoice_hash_tbl',$hash_tbl_arr);

				$return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $result['data']);

                    // die(print_r($return_arr));
			}

			$return_arr = array('status' => 400, 'msg' => $result['data']['response'], 'result' => $result);



		}else{

			$return_arr = array('status' => 400, 'msg' => 'error');

		}



	   	return json_encode($return_arr);



    }

	public function bulkChangeStatus($data){

		// $data =  $this->input->post();

		// die(print_r($data));

		if (!empty($data['bulk_invoice_id'])) {

            // die(print_r($data['bulk_invoice_id']));

			foreach ($data['bulk_invoice_id'] as $key => $value) {

				$where = array(

				'invoice_id' =>$value

				);

				$param = array(

				'status' =>$data['status'],

				'last_modify' => date("Y-m-d H:i:s")

				);

				$invoice_details = $this->INV->getOneInvoive($where);

                // die(print_r($invoice_details));



                // Grab current status of invoice, if 0 change to sent and add any customer credit balance 
                if($invoice_details->status == 0 ){

                    $unpaid = $this->INV->getUnpaidInvoiceById($invoice_details->invoice_id);

                    // die(print_r($unpaid));

                    $customer_id = $invoice_details->customer_id;
                    $customer_info = $this->CustomerModel->getCustomerDetail($customer_id);
                    $credit_amount = $customer_info['credit_amount'];
                    $paid_already = $invoice_details->partial_payment;
                    
                    if(!empty($unpaid)){
                        
                            $invoice_amount  = $unpaid->unpaid_amount;
                            //   die(print_r($invoice_amount));
                            if($credit_amount >= $invoice_amount){
                            // die(print_r($credit_amount));
                                $result = $this->INV->createOnePartialPayment(array(
                                    'invoice_id' => $unpaid->unpaid_invoice,
                                    'payment_amount' => $invoice_amount,
                                    'payment_applied' => $invoice_amount,
                                    'payment_datetime' => date("Y-m-d H:i:s"),
                                    'payment_method' => 5,
                                    'check_number' => null,
                                    'cc_number' => null,
                                    'payment_note' => "Payment made from credit amount {$credit_amount}",
                                    'customer_id' => $customer_id,
                                ));
                        
                                // die(print_r($result));
                                $credit_amount -= $invoice_amount;

                                $param['status'] = 2;
                        
                                //mark this invoice as paid
                                $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'partial_payment' => $invoice_amount + $paid_already, 'payment_created' => date('Y-m-d H:i:s'), 'opened_date' => date('Y-m-d H:i:s'), 'sent_date' => date('Y-m-d H:i:s')] );
                            } else if($credit_amount > 0 && $invoice_amount > 0){
                                $result = $this->INV->createOnePartialPayment(array(
                                    'invoice_id' => $unpaid->unpaid_invoice,
                                    'payment_amount' => $credit_amount,
                                    'payment_applied' => $credit_amount,
                                    'payment_datetime' => date("Y-m-d H:i:s"),
                                    'payment_method' => 5,
                                    'check_number' => null,
                                    'cc_number' => null,
                                    'payment_note' => "Payment made from credit amount {$credit_amount}",
                                    'customer_id' => $customer_id,
                                ));                                         
                        
                                //mark this invoice as paid
                                $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 1, 'payment_status' => 1, 'partial_payment' => $credit_amount + $paid_already, 'payment_created' => date('Y-m-d H:i:s'), 'sent_date' => date('Y-m-d H:i:s')]);  
                                            
                                $credit_amount = 0;
                            }
                        
                        //update customers.credit_amount adjusted credit_amount balance
                        $this->INV->adjustCreditPayment($customer_id, $credit_amount);
                                        
                    }
                } 

				//  If invoice is already paid or partial payment and change status then to skip.

				if($data['status']==2) {

					$param['opened_date'] = date("Y-m-d H:i:s");

				}elseif($data['status']==1) {

                    if($invoice_details->status == 1){
                        $param['status'] = 1;
                    } else if($invoice_details->status == 2){
                        $param['status'] = 2;
                    }

					$param['sent_date'] = date("Y-m-d H:i:s");

					if(empty($invoice_details->first_sent_date)){

						$param['first_sent_date'] = date("Y-m-d H:i:s");

					}

				}

				$result = $this->INV->updateInvovice($where,$param);

				$invoice_details = $this->INV->getOneInvoive($where);

				if ($invoice_details->quickbook_invoice_id!=0) {

                    // Assign value of invoice_details object to new variable
                    $QBO_param = $invoice_details;

                    // Declare array to be passed to coupon calculatiuon function
                    $coup_inv_param = array(
                        'cost' => $QBO_param->cost,
                        'invoice_id' => $value
                    );

                    // Assign value of calculation function to new variable
                    $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                    // Assign value of variable as new cost to pass to QBO
                    $QBO_param->cost = $cost_with_inv_coupon;

                    // die(print($QBO_param->cost));

                    // Update QBO Invoice with any new info
					$res = $this->QuickBookInvUpdate($invoice_details);

				//var_dump($res);

				}

			}

		//  echo 1;

		} else {

		//  echo  0;

		}

	}

	public function sendInvoice($ids){
        //die($ids);
        $ids = explode(":", $ids);

        $customer_ids = explode(",", $ids[1]);

        $invoice_ids = explode(",", $ids[0]);


        function array_combine_($keys, $values){

            $result = array();

            foreach ($keys as $i => $k) {

                $result[$k][] = $values[$i];

            }

            array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));

            return    $result;

        }

        $merged = array_combine_($customer_ids,$invoice_ids);

        $merge2 = array_merge($invoice_ids,$customer_ids);

        // die(print_r($merged));

        $customer_id = array();


		$where = array('invoice_id' => $invoice_ids[0]);

        $invoice_details = $this->INV->getOneInvoice($where);



        $company_id = $invoice_details->company_id;



        $customer_wise_data = [];



        if (!empty($merged)) {

            foreach ($merged as $key => $value) {

                if(is_array($value)){

                    $value = array_unique($value);



                    foreach($value as $subkey => $subvalue){



                        $invoice_id =  $subvalue;

                        $customer_id =  $key;



                        $where = array('invoice_id' =>$invoice_id);

                        $invoice_details = $this->INV->getOneInvoive($where);



                        $detail = array(

                            'invoice_id' => $invoice_id,

                            'invoice_status' => $invoice_details->status

                        );



                        if(array_key_exists($customer_id,$customer_wise_data)) {

                            array_push($customer_wise_data[$customer_id],$detail);

                        } else {

                            $customer_wise_data[$customer_id][] = $detail;

                        }



                    }

                } else {

                    $invoice_id =  $value;

                    $customer_id =  $key;



                    $where = array('invoice_id' =>$invoice_id);

                    $invoice_details = $this->INV->getOneInvoive($where);



                    $detail = array(

                        'invoice_id' => $invoice_id,

                        'invoice_status' => $invoice_details->status

                    );



                    if(array_key_exists($customer_id,$customer_wise_data)) {

                        array_push($customer_wise_data[$customer_id],$detail);

                    } else {

                        $customer_wise_data[$customer_id][] = $detail;

                    }



                }

            }



            if(!empty($customer_wise_data)) {

                $where_company = array('company_id' =>$company_id);

                $data['setting_details'] =

                    $this->CompanyModel->getOneCompany($where_company);

                $data['setting_details']->company_logo =

                    ($data['setting_details']->company_resized_logo != '') ?

                        $data['setting_details']->company_resized_logo :

                            $data['setting_details']->company_logo;

                // die(print_r($customer_wise_data));

                foreach($customer_wise_data as $customer_id => $customer_data) {



                    $data['customer_details'] =

                        $this->CustomerModel->getOneCustomerDetail($customer_id);

                    // die(print_r($data));

                    $email = $data['customer_details']->email;

                    $invoice_id_list = array_column($customer_data,'invoice_id');



                    $data['bulk_invoice_id'] = implode(',',$invoice_id_list);

                    $hashstring =

                        md5($email."-".$customer_id."-".date("Y-m-d H:i:s"));



                    $data['link'] =  base_url('welcome/pdfDailyInvoice/').$hashstring;

                    $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;

                    $body = $this->load->view('admin/invoice/email_pdf',$data,true);
                    //die(print_r($body));
                    $where_company['is_smtp'] = 1;

                    $company_email_details =

                        $this->CompanyEmail->getOneCompanyEmailArray($where_company);



                    if (!$company_email_details) {

                        $company_email_details =

                            $this->Administratorsuper->getOneDefaultEmailArray();

                    }

                    $secondary = isset($data['customer_details']->secondary_email) ? $data['customer_details']->secondary_email : '';

                    $batch_insert_arr = array();



                    foreach($customer_data as $value) {

                        $hash_tbl_arr = array();

                        $update_arr = array('last_modify' => date("Y-m-d H:i:s"));

                        // die(print_r($value));

                        if($value['invoice_status'] == 0 || $invoice_details->status == 1) {

                            $update_arr['bulk_invoice_id'] = $invoice_id_list;

                            $update_arr['status'] = 1;

                            $update_arr['sent_date'] = date("Y-m-d H:i:s");



                            if(empty($invoice_details->first_sent_date)){

                                $update_arr['first_sent_date'] =

                                    date("Y-m-d H:i:s");

                            }


                            // die(print_r($update_arr));
                            $this->bulkChangeStatus($update_arr);

                        }



                        $where_arr = array("invoice_id" => $value['invoice_id']);

                        $hash_tbl_arr['invoice_id'] = $value['invoice_id'];

                        $hash_tbl_arr['company_id'] = $company_id;

                        $hash_tbl_arr['hashstring'] = $hashstring;

                        $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");



                        array_push($batch_insert_arr,$hash_tbl_arr);

                    }

                    // Added hash table concept to get rid of expired link issue.

                    $this->db->insert_batch('invoice_hash_tbl',$batch_insert_arr);


                    $res = Send_Mail_dynamic($company_email_details,

                                $email,

                                array("name" => $this->session->userdata['compny_details']->company_name,

                                "email" => $this->session->userdata['compny_details']->company_email),

                                $body,

                                'Invoice Details - '.date('Y-m-d'),

                                $secondary);
                }

            }

        }

        redirect('technician/dashboard');

    }

	#above function replaces this one

	public function sendInvoiceOld($invoice_id){

		$data = array();

		$where = array('invoice_id' => $invoice_id);

        $invoice_details = $this->INV->getOneInvoice($where);

 		$setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $invoice_details->company_id));

	    $customer_details = $this->CustomerModel->getCustomerDetail($invoice_details->customer_id);



	    $email = $customer_details['email'];

		$hashstring = md5($email."-".$invoice_details->customer_id."-".date("Y-m-d H:i:s"));

		 //hash table concept to get rid of expired link issue.

		$hash_tbl_arr = array(

			'invoice_id' => $invoice_id,

			'company_id' => $invoice_details->company_id,

			'hashstring' => $hashstring,

			'created_at' => date("Y-m-d H:i:s"),

		);



        $this->db->insert('invoice_hash_tbl',$hash_tbl_arr);



		$data['link'] =  base_url('welcome/pdfDailyInvoice/').$hashstring;
        $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;

		$data['invoice_details'] = $invoice_details;

		$data['setting_details'] = $setting_details;

		$data['customer_details'] = $customer_details;

		$body = $this->load->view('tech_invoice_email', $data, true);



		$where = array('company_id' => $invoice_details->company_id, 'is_smtp'=> 1);

		$company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

		if (!$company_email_details) {

			$company_email_details = $this->CompanyModel->getOneDefaultEmailArray();

		}

		$res = Send_Mail_dynamic($company_email_details, $email,  array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Completed Service Invoice Details');



	}



	    /**

     * Returns client ip address

     */

    public function getClientIp()

    {

      $ipaddress = '';

      if (getenv('HTTP_CLIENT_IP')) {

          $ipaddress = getenv('HTTP_CLIENT_IP');

      } else if (getenv('HTTP_X_FORWARDED_FOR')) {

          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

      } else if (getenv('HTTP_X_FORWARDED')) {

          $ipaddress = getenv('HTTP_X_FORWARDED');

      } else if (getenv('HTTP_FORWARDED_FOR')) {

          $ipaddress = getenv('HTTP_FORWARDED_FOR');

      } else if (getenv('HTTP_FORWARDED')) {

          $ipaddress = getenv('HTTP_FORWARDED');

      } else if (getenv('REMOTE_ADDR')) {

          $ipaddress = getenv('REMOTE_ADDR');

      } else {

          $ipaddress = 'UNKNOWN';

      }

      return $ipaddress;

    }



    public function invoicePrint($invoice_ids){

        $where_company = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

          $where_arr = array(
            'company_id' => $this->session->userdata['spraye_technician_login']->company_id,
            'status' => 1
          );

        $data['basys_details'] =  $this->BasysRequest->getOneBasysRequest($where_arr);

        $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect($where_arr);

        $invoice_ids = explode(",", $invoice_ids);


        foreach ($invoice_ids as $key => $value) {

            $where = array(
               "invoice_tbl.company_id" => $this->session->userdata['spraye_technician_login']->company_id,
                'invoice_id' =>$value
            );
             $invoice_details =  $this->INV->getOneInvoive($where);


           if(empty($invoice_details->job_id)){
                //get job data
              $jobs = array();

              $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' =>$value));

              if($job_details){

                  //print_r($job_details);

                  foreach($job_details as $detail){

                      $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id'=>$detail['job_id'],'invoice_id'=>$value));

                      if(isset($detail['report_id'])){

                          $report = $this->RP->getOneRepots(array('report_id'=>$detail['report_id']));

                      } else {

                          $report = '';

                      }

                      // SERVICE WIDE COUPONS
                      $arry = array(

                          'customer_id' => $invoice_details->customer_id,
                          'program_id' => $invoice_details->program_id,
                          'property_id' => $invoice_details->property_id,
                          'job_id' => $detail['job_id']
                      );


                      $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                      $coupon_job_amm = 0;
                      $coupon_job_amm_calc = 5;
                      $coupon_job_code = '';

                      if (!empty($coupon_job)) {
                          $coupon_job_amm = $coupon_job->coupon_amount;
                          $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                          $coupon_job_code = $coupon_job->coupon_code;

                      }


                      $jobs[]=array(
                          'job_id'=>$detail['job_id'],
                          'job_name'=>$detail['job_name'],
                          'job_description'=>$detail['job_description'],
                          'job_cost'=>$detail['job_cost'],
                          'job_assign_date'=>isset($get_assign_date) ? $get_assign_date->job_assign_date : '',
                          'program_name'=>isset($detail['program_name']) ? $detail['program_name'] : '',
                          'job_report'=>isset($report) ? $report : '',
                          'coupon_job_amm' => $coupon_job_amm,
                          'coupon_job_amm_calc' => $coupon_job_amm_calc,
                          'coupon_job_code' => $coupon_job_code,
                      );
                  }
              }

              $invoice_details->jobs = $jobs;

           }

           $invoice_details->all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$value));

           if(!empty($invoice_details->report_id)){
               $invoice_details->report_details =  $this->RP->getOneRepots(array('report_id'=>$invoice_details->report_id));
           }

           $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $value));

           $data['invoice_details'][] = $invoice_details;

           if($invoice_details->status == 0 || $invoice_details->status == 1) {

              $update_arr = array('last_modify' => date("Y-m-d H:i:s"));

              $update_arr['bulk_invoice_id'] = $invoice_ids;

              $update_arr['status'] = 1;

              $update_arr['sent_date'] = date("Y-m-d H:i:s");

              if(empty($invoice_details->first_sent_date)){

                  $update_arr['first_sent_date'] =
                  date("Y-m-d H:i:s");

              }
           }
        }

        //die(print_r($data['invoice_details']));

        $this->bulkChangeStatus($update_arr);

        $this->load->view('admin/invoice/multiple_pdf_invoice_print',$data);
        $html = $this->output->get_output();
//        die(print_r($html));
        // Load pdf library
        $this->load->library('pdf');
        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        // Render the HTML as PDF
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));

    }

    public function getWindSpeed($lat,$long) {



        $geocode =  file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$long.'&appid='.openweathermap);



        $output= json_decode($geocode);

        $returnarray = array(

            'speed' =>round(2.23694*($output->wind->speed),2),

            'deg' =>isset($output->wind->deg) ? $output->wind->deg : 0,

            'temp' =>($output->main->temp - 273.15) * 9/5 + 32

        );

         return  $returnarray ;
    }

    public function QuickBookInv($param, $single_job_desc, $single_job_name){

        $customer_details = $this->CustomerModel->getCustomerDetail($param->customer_id);

        if ($customer_details['quickbook_customer_id']!=0) {

            $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);



            if ($quickBookCustomerDetails) {

                $param->quickbook_customer_id = $customer_details['quickbook_customer_id'];



                $result = $this->createInvoiceInQuickBook($param, $single_job_desc, $single_job_name);



                if ($result['status']==201) {

                   return $result['result'];

                } else {

                  return false;

                }



            } else {

              return false;

            }

        } else {

           return false;

        }

    }

    public function QuickBookInvUpdate($param){





            $customer_details = $this->CustomerModel->getCustomerDetail($param->customer_id);





            if ($customer_details['quickbook_customer_id']!=0) {

                $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);



                // var_dump($quickBookCustomerDetails);



                if ($quickBookCustomerDetails) {



                    $param->quickbook_customer_id = $customer_details['quickbook_customer_id'];



                    $result = $this->updateInvoiceInQuickBook($param);



                // var_dump($result);

                // die();



                    if ($result['status']==200) {

                    return $result['result'];

                    } else {

                    return false;

                    }



                } else {

                return false;

                }

            } else {

            return false;

            }

    }

    public function getOneQuickBookCustomer($quickbook_customer_id){



    $company_details = $this->checkQuickbook();

    if ($company_details) {





        try{





            $dataService = DataService::Configure(array(

            'auth_mode' => 'oauth2',

            'ClientID' => $company_details->quickbook_client_id,

            'ClientSecret' => $company_details->quickbook_client_secret,

            'accessTokenKey' => $company_details->access_token_key,

            'refreshTokenKey' =>$company_details->refresh_token_key,

            'QBORealmID' => $company_details->qbo_realm_id,

            'baseUrl' => "Production"

            ));



            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");



            $entities = $dataService->Query("SELECT * FROM Customer where Id='".$quickbook_customer_id."'");

            $error = $dataService->getLastError();

            if ($error) {

            $return_error = '';

            $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";

                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";

                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";



            return   false;



            } else {



                if(!empty($entities)) {



                $theCustomer = reset($entities);

                return $theCustomer;



                } else {

                return false;

                }



            }

        } catch (Exception $ex) {

        return false;

        }



    } else {



        return false;



    }



    }

    public function checkQuickbook() {

    $where = array(

        'company_id'=>$this->session->userdata['spraye_technician_login']->company_id,

        'is_quickbook'=>1,

        'quickbook_status'=>1

    );



    $company_details = $this->CompanyModel->getOneCompany($where);



    if ($company_details) {





        try {

            $oauth2LoginHelper = new OAuth2LoginHelper($company_details->quickbook_client_id,$company_details->quickbook_client_secret);  // clint id , clint sceter

            $accessTokenObj = $oauth2LoginHelper->

                                refreshAccessTokenWithRefreshToken($company_details->refresh_token_key);

            $accessTokenValue = $accessTokenObj->getAccessToken();

            $refreshTokenValue = $accessTokenObj->getRefreshToken();



            $post_data = array(

            'access_token_key' => $accessTokenValue,

            'refresh_token_key' => $refreshTokenValue,





            );



            $this->CompanyModel->updateCompany($where,$post_data);



            $company_details->access_token_key = $accessTokenValue;



            $company_details->refresh_token_key = $refreshTokenValue;



            return $company_details;



        }



        catch (Exception $ex) {



            return  false;



        }





    } else {

        return false;

    }



    }

    public function createInvoiceInQuickBook($param, $single_job_desc, $single_job_name){



        $company_details = $this->checkQuickbook();


        if ($company_details) {

            try{

                $dataService = DataService::Configure(array(

                    'auth_mode' => 'oauth2',

                    'ClientID' => $company_details->quickbook_client_id,

                    'ClientSecret' => $company_details->quickbook_client_secret,

                    'accessTokenKey' => $company_details->access_token_key,

                    'refreshTokenKey' =>$company_details->refresh_token_key,

                    'QBORealmID' => $company_details->qbo_realm_id,

                    'baseUrl' => "Production"

                ));

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $dataService->throwExceptionOnError(true);

                $details = getVisIpAddr();

                $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$param->invoice_id));

                if ( $single_job_name != "") {

                    $description = 'Service Name: ' . (string) $single_job_name . '.';

                } else {

                    $description = 'Service Name: ' . (string) $param->job_name . '.';

                }

                if ( $single_job_desc != "" ) {

                    $description .= ' Service Description: '. (string) $single_job_desc . '. ';

                } else {
                    $description .= ' Service Description: ' . (string) $param->actual_description_for_QBO . '. ';
                }

                $description .= 'Service Address: ' . (string) $param->property_street . '.';
                $line_ar[] = array(

                    "Description" => $description,

                    "Amount" => $param->cost,

                    "DetailType" => "SalesItemLineDetail",

                    "SalesItemLineDetail" => array(

                        "TaxCodeRef" => array(

                            "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'

                        )

                    )

                );



                if ($all_sales_tax) {

                    foreach ($all_sales_tax as $key => $value) {

                            $line_ar[] = array(

                            "Description" =>  'Sales Tax: '.$value['tax_name'].' ('.floatval($value['tax_value']).'%) ',

                            "Amount" => $value['tax_amount'],

                            "DetailType" => "SalesItemLineDetail",

                            "SalesItemLineDetail" => array(

                                "TaxCodeRef" => array(

                                "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'

                                    // "value" =>  'TAX'

                                )

                            )

                            );

                    }

                }


                $invoice_arr = array(

                    "AllowOnlineCreditCardPayment" => true,

                    "DocNumber" => $param->invoice_id,

                    "TxnDate" => $param->invoice_date,

                    "Line" =>$line_ar ,

                    "CustomerRef" => array(

                        "value" => $param->quickbook_customer_id,

                    )

                );

                if ($param->email!='') {



                    $invoice_arr['BillEmail'] = array(

                        "Address" => $param->email

                    );

                    $invoice_arr['EmailStatus'] = "NeedToSend";

                }

                $theResourceObj = Invoice::create($invoice_arr);

                $resultingObj = $dataService->Add($theResourceObj);

                $this->invoicePaymentManage($dataService,$param);

                $error = $dataService->getLastError();

                if ($error) {

                    $return_error ='';

                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";

                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";

                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                    return array('status'=>400,'msg'=>'Invoice not added successfully','result'=>$return_error);

                }

                else {



                    return array('status'=>201,'msg'=>'Invoice added successfully','result'=>$resultingObj->Id);

                }

            } catch (Exception $ex) {

                return array('status'=>400,'msg'=>$ex->getMessage(),'result'=>'');

            }

       } else {

            return array('status'=>400,'msg'=>'Please integrate a quickbook account','result'=>'');

       }

    }

    public function updateInvoiceInQuickBook($param){

        $company_details = $this->checkQuickbook();

        if ($company_details) {





            try{



                $dataService = DataService::Configure(array(

                    'auth_mode' => 'oauth2',

                    'ClientID' => $company_details->quickbook_client_id,

                    'ClientSecret' => $company_details->quickbook_client_secret,

                    'accessTokenKey' => $company_details->access_token_key,

                    'refreshTokenKey' =>$company_details->refresh_token_key,

                    'QBORealmID' => $company_details->qbo_realm_id,

                    'baseUrl' => "Production"

                ));



                $entities = $dataService->Query("SELECT * FROM Invoice where Id='".$param->quickbook_invoice_id."'");



                // $entities = $dataService->Query("SELECT * FROM Invoice where Id='".$invoiceId."'");

                $error = $dataService->getLastError();

                if ($error) {

                    $return_error = '';



                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";

                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";

                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";



                    return array('status'=>400,'msg'=>'Invoice not added successfully','result'=>$return_error);



                } else {

                    if(!empty($entities)) {

                        $theInvoice = reset($entities);
                        $details = getVisIpAddr();
                        $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$param->invoice_id));

                        $line_ar[] = array(
                            "Description" => $param->job_name.' '.date("m/d/Y", strtotime($param->invoice_created)),

                            "Amount" => $param->cost,

                            "DetailType" => "SalesItemLineDetail",

                            "SalesItemLineDetail" => array(

                                "TaxCodeRef" => array(

                                    "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'

                                )

                            )

                        );



                        if ($all_sales_tax) {



                            foreach ($all_sales_tax as $key => $value) {

                                $line_ar[] = array(

                                    "Description" =>  'Sales Tax: '.$value['tax_name'].' ('.floatval($value['tax_value']).'%) ',

                                    "Amount" => $value['tax_amount'],

                                    "DetailType" => "SalesItemLineDetail",

                                    "SalesItemLineDetail" => array(

                                        "TaxCodeRef" => array(

                                            "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'

                                        )

                                    )

                                );

                            }



                        }





                        $invoice_arr = array(

                            "AllowOnlineCreditCardPayment" => true,

                            "DocNumber" => $param->invoice_id,

                            "TxnDate" => $param->invoice_date,

                            "Line" =>$line_ar ,

                            "CustomerRef" => array(

                                "value" => $param->quickbook_customer_id,

                            )

                        );



                        if ($param->email!='') {



                            $invoice_arr['BillEmail'] = array(

                            "Address" => $param->email

                            );

                            $invoice_arr['EmailStatus'] = "NeedToSend";

                        }

                        $updateInvoice = Invoice::update($theInvoice, $invoice_arr);

                        $resultingCustomerUpdatedObj = $dataService->Update($updateInvoice);

                        $this->invoicePaymentManage($dataService,$param);

                        if ($error) {

                            $return_error = '';

                            $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";

                            $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";

                            $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";





                            return array('status'=>400,'msg'=>'Invoice not update successfully','result'=>$return_error);



                        } else {



                            // var_dump($resultingCustomerUpdatedObj);



                            // print_r($resultingCustomerUpdatedObj);

                            // die();



                            if ($resultingCustomerUpdatedObj) {



                                return array('status'=>200,'msg'=>'invoice update successfully','result'=>$resultingCustomerUpdatedObj->Id);



                            } else {



                                return array('status'=>400,'msg'=>'invoice not  update successfully','result'=>'');

                            }



                        }



                    } else {

                        return array('status'=>404,'msg'=>'invoice not found','result'=>'');

                    }





                }

            } catch (Exception $ex) {

                return array('status'=>400,'msg'=>$ex->getMessage(),'result'=>'');



                // echo 'Message: ' .$ex->getMessage();

            }

        } else {



            return array('status'=>400,'msg'=>'please  quickbook intigrate','result'=>'');



        }





    }

    public function invoicePaymentManage($dataService,$param){





      try {



        if ($param->quickbook_partial_payment_id!=0) {







          $entities = $dataService->Query("SELECT * FROM Payment where Id='".$param->quickbook_partial_payment_id."' ");



          $thePayment = reset($entities);





          $updatePayment = Payment::update($thePayment, [



               "CustomerRef" => ["value" => $param->quickbook_customer_id],

               "TotalAmt" => $param->partial_payment,

               "Line" => [

                  "Amount" => $param->partial_payment,

                  "LinkedTxn" => ["TxnId" => $param->quickbook_invoice_id,"TxnType" => "Invoice"]

               ]

            ]);



          $resultingPaymentObj =  $dataService->Update($updatePayment);



        } else {

            $updatePayment = Payment::create( [



               "CustomerRef" => ["value" => $param->quickbook_customer_id],

               "TotalAmt" => $param->partial_payment,

               "Line" => [

                  "Amount" => $param->partial_payment,

                  "LinkedTxn" => ["TxnId" => $param->quickbook_invoice_id,"TxnType" => "Invoice"]

               ]

            ]);



            $resultingPaymentObj =  $dataService->Add($updatePayment);



        }

          if (!empty($resultingPaymentObj)) {



                    $this->INV->updateInvovice(array('invoice_id'=>$param->invoice_id),array('quickbook_partial_payment_id'=>$resultingPaymentObj->Id));

          }



          // return true;

      } catch(Exception $ex) {

           // echo 'Message: ' .$e->getMessage();



        return false;

      }

    }

    public function chargecardInvoices($invoice_id=0, $no_json=0){

        $invoices = $this->input->post('invoices') ? $this->input->post('invoices') : [$invoice_id];
      
        if (!empty($invoices)) {
          $charge = 0;
          foreach ($invoices as $key => $value) {
            $where = array('invoice_id' => $value);
            $invoice = $this->INV->getOneInvoiceComplete($where);
            if($invoice->basys_autocharge || $invoice->clover_autocharge){
            //   //check if this invoice is on an installment payment
            //   if(isset($invoice->payment_plan) && isset($invoice->installment_amount)){
            //     $installment = $this->INV->getInstallment($invoice->invoice_id);
            //     $installment_amount = $installment ? $installment->installment_amount : 0;
            //   }else{
            //     $installment_amount = 0;
            //   }
              // if($installment_amount)
              // $this->INV->updateInstallmentPaid($installment->id);
              // print "HERE";
              // die(print_r($installment));
              if($invoice->basys_autocharge){
                //if it's a payment plan, then make sure it's ready to make a payment
                // if(isset($invoice->payment_plan) && isset($installment_amount)){
                //   $this->basysAutocharge($invoice->invoice_id, $installment_amount,$installment->id);
                //   $charge++;
                // }elseif(!isset($invoice->payment_plan)){
                  $this->basysAutocharge($invoice_id);
                  $charge++;
                // }
              }elseif($invoice->clover_autocharge){
                // //if it's a payment plan, then make sure it's ready to make a payment
                // if(isset($invoice->payment_plan) && isset($installment_amount)){
                //   $this->cloverAutocharge($invoice->invoice_id, $installment_amount,$installment->id);
                //   $charge++;
                // }elseif(!isset($invoice->payment_plan)){
                  $this->cloverAutocharge($invoice_id);
                  $charge++;
                // }
              }
              // if($invoice->payment_plan && $installment_amount){
              //   $this->INV->updateInstallmentPaid($installment->id);
              // }
            }
          }
            if($charge && $no_json){
              return ["success" => 1, "cards" => $charge];
            }elseif($charge){
              print json_encode(["success" => 1, "cards" => $charge]);
            }elseif(!$charge && $no_json){
              return ["success" => 0, "cards" => null];
            }else{
              print json_encode(["success" => 0, "cards" => null]);
            }
        } else {
            echo 0;
        }
      }

    public function cloverAutocharge($invoice_id){

        $where = array('invoice_id' => $invoice_id);

        $invoice_details = $this->INV->getOneInvoive($where);

        // die(print_r($invoice_details));

        $company_id = $this->session->userdata['spraye_technician_login']->company_id;

        $cardconnect_details = $this->CardConnect->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));

        $customer_details = $this->CustomerModel->getCustomerDetail($invoice_details->customer_id);

        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        $tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax($where);
        ////////////////////////////////////

        // START INVOICE CALCULATION COST //

        $tmp_invoice_id = $invoice_id;


        $job_cost_total = 0;

        $where = array(

            'property_program_job_invoice.invoice_id' => $tmp_invoice_id

        );

        $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);

        if (!empty($proprojobinv)) {

            foreach($proprojobinv as $job) {

                $job_cost = $job['job_cost'];

                $job_where = array(

                    'job_id' => $job['job_id'],

                    'customer_id' =>$job['customer_id'],

                    'property_id' =>$job['property_id'],

                    'program_id' =>$job['program_id']

                );

                $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                if (!empty($coupon_job_details)) {

                    foreach($coupon_job_details as $coupon) {

                        // $nestedData['email'] = json_encode($coupon->coupon_amount);

                        $coupon_job_amm_total = 0;

                        $coupon_job_amm = $coupon->coupon_amount;

                        $coupon_job_calc = $coupon->coupon_amount_calculation;



                        if ($coupon_job_calc == 0) { // flat amm

                            $coupon_job_amm_total = (float) $coupon_job_amm;

                        } else { // percentage

                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;

                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;

                        if ($job_cost < 0) {

                            $job_cost = 0;

                        }
                    }
                }

                $job_cost_total += $job_cost;

            }

            $invoice_total_cost = $job_cost_total;

        } else {

            $invoice_total_cost = $invoice_details->cost;

        }



        // check price override -- any that are not stored in just that ^^.



        // - invoice coupons

        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id ));

        foreach ($coupon_invoice_details as $coupon_invoice) {

            if (!empty($coupon_invoice)) {

                $coupon_invoice_amm = $coupon_invoice->coupon_amount;

                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;



                if ($coupon_invoice_amm_calc == 0) { // flat amm

                    $invoice_total_cost -= (float) $coupon_invoice_amm;

                } else { // percentage

                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;

                    $invoice_total_cost -= $coupon_invoice_amm;

                }

                if ($invoice_total_cost < 0) {

                    $invoice_total_cost = 0;

                }

            }

        }


        //   //Resetting invoice_total_cost with installment_amount
        //   if($installment_amount != 0){
        //     $invoice_total_cost = $installment_amount;
        //   }   
        // + tax cost

        $invoice_total_tax = 0;

        $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id ));

        if (!empty($invoice_sales_tax_details)) {

            foreach($invoice_sales_tax_details as $tax) {

                if (array_key_exists("tax_value", $tax)) {

                    $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;

                    $invoice_total_tax += $tax_amm_to_add;

                }

            }

        }

        $invoice_total_cost += $invoice_total_tax;

        $total_tax_amount = $invoice_total_tax;



        // END TOTAL INVOICE CALCULATION COST //

        ////////////////////////////////////////

        // WITH COUPONS CALC

        $convenience_fee = number_format(($setting_details->convenience_fee * ($invoice_total_cost - $invoice_details->partial_payment) / 100),2);

        $total_amount = $invoice_total_cost + $convenience_fee - $invoice_details->partial_payment;

        $order_id = (string)strtotime("now");

        $post = array(

            'username' => $cardconnect_details->username,

            'password' => decryptPassword($cardconnect_details->password),

            'merchid' => $cardconnect_details->merchant_id,

            'requestData' => array(

                'merchid' => $cardconnect_details->merchant_id,

                'profile' => $customer_details['customer_clover_token'],

                'ecomind' => 'R',

                'cof' => 'M',

                'cofpermission' => 'Y',

                'cofscheduled' => 'Y',

                'capture' => 'Y',

                'amount' => number_format($total_amount, 2, '.', ''),

                "tax_amount" => number_format($total_tax_amount, 2, '.', ''),

                "currency" => "USD",

                "order_id" => $order_id

            )

        );

        $cc_authorize = cardConnectAuthorize($post);

        // die(print_r($cc_authorize));

        if($cc_authorize['status'] == 200){

            if ($cc_authorize['result']->respstat == 'A') {

                //if payment is successful and approved



                $updatearr = array(

                    'payment_status' => 2,

                    'status' => 1,

                    'clover_transaction_id' => $cc_authorize['result']->retref,

                    'payment_created' => date("Y-m-d H:i:s"),

                    'partial_payment' => $invoice_details->cost + $total_tax_amount + $invoice_details->partial_payment,

                    'clover_order_id' => $order_id

                );

                $this->INV->updateInvovice(array('invoice_id' => $invoice_id), $updatearr);


                //for installment payment
                if($installment_amount != 0 && $installment_id != ''){
                    $this->INV->updateInstallmentPaid($installment_id);
                }


                //send email to company admin

                $data['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));



                $data['setting_details'] = $setting_details;



                $invoice_details->convenience_fee = $convenience_fee;

                $invoice_details->tax_amount = $total_tax_amount;

                $data['invoice_details'] = $invoice_details;



                $body = $this->load->view('invoice_paid_mail', $data, true);

                $where = array('company_id' => $company_id, 'is_smtp'=> 1);



                $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);



                if (!$company_email_details) {

                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();

                }



                $res = Send_Mail_dynamic($company_email_details, $data['user_details']->email,  array("name" => $data['setting_details']->company_name, "email" => $data['setting_details']->company_email), $body, 'Transaction Information');



                //email invoice to customer

                //$res2 = $this->sendInvoice($invoice_id);

                $data['customer_details'] = $customer_details;

                $hashstring = md5($customer_details['email']."-".$invoice_details->customer_id."-".date("Y-m-d H:i:s"));



                $hash_tbl_arr = array(

                    'invoice_id' => $invoice_id,

                    'company_id' => $company_id,

                    'hashstring' => $hashstring,

                    'created_at' => date("Y-m-d H:i:s"),

                );



                $this->db->insert('invoice_hash_tbl',$hash_tbl_arr);


                $return_arr = array('status' => 200, 'msg' => 'Payment successfully received.', 'result' => $cc_authorize['result']);



            }

            $return_arr = array('status' => 400, 'msg' => $cc_authorize['result']->resptext, 'result' => $cc_authorize['result']);



        }else{

            $return_arr = array('status' => 400, 'msg' => 'error');

        }



        return json_encode($return_arr);



    }

    public function notesViewAll()

    {

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        $data['company_id'] = $currentUser->company_id;

        $where = array('company_id' => $data['company_id']);

        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $data['note_types'] = $this->CompanyModel->getNoteTypes($data['company_id']);

        $notes_all = $this->CompanyModel->getCompanyNotes($data['company_id']);

        if(!empty($notes_all)) {

        foreach($notes_all as $note)

        {

            $note->comments = $this->CompanyModel->getNoteComments($note->note_id);

            $note->files = $this->CompanyModel->getNoteFiles($note->note_id);

        }

        }

        $data['combined_notes'] = $notes_all;

        usort($data['combined_notes'], function($a, $b) {

        if($a->note_created_at > $b->note_created_at)

        {

            return -1;

        } else

        {

            return 1;

        }

        });

        $page["active_sidebar"] = "all_notes";

        $page["page_name"] = "Notes";

        $page["page_content"] = $this->load->view("admin/notes_view", $data, TRUE);

        $this->layout->superAdminTemplateTable($page);

    }

    public function createNote($data = NULL)

    {

        $data = (empty($data)) ? $this->input->post() : $data;

        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        if($data['note_property_id'] == 0)

        {

            $data['note_property_id'] = NULL;

        }

        if(!empty($data['note_contents']) && $data['note_contents'] != '')

        {

            $params = array(

                'note_user_id' => $currentUser->id,

                'note_company_id' => $currentUser->company_id,

                'note_category' => (isset($data['note_property_id'])) ? 0 : 1,

                'note_property_id' => $data['note_property_id'] ?? NULL,

                'note_customer_id' => $data['note_customer_id'] ?? NULL,

                'note_contents' => trim($data['note_contents']),

                'note_due_date' => $data['note_due_date'] ?? NULL,

                'note_assigned_user' => $data['note_assigned_user'],

                'note_type' => $data['note_type'],
                'note_assigned_services' => $data['note_assigned_services'] ?? NULL,
                'assigned_service_note_duration' => $data['assigned_service_note_duration'] ?? NULL,

                'include_in_tech_view' => (isset($data['include_in_tech_view'])) ? 1 : 0,

            );

            if(isset($data['note_category']) && $data['note_category'] == 2)

            {

            $params['note_category'] = 2;

            }

            $noteId = $this->CompanyModel->addNote($params);

            if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) {

                $fileStatusMsg = $this->addNoteFiles($noteId);

            }

            if($noteId && isset($fileStatusMsg) && $fileStatusMsg)

            {

                $returnMessage = '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>';

                $this->session->set_flashdata('message', $returnMessage);

                redirect($referer_path);

            } elseif($noteId)

            {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>');

                redirect($referer_path);

            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> not added.</div>');

                redirect($referer_path);

            }

        } else

        {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went really <strong>WRONG!</strong></div>');

            redirect($referer_path);

        }

    }

   public function getNote($noteId)

   {

      $note = $this->CompanyModel->getNote($noteId);

      return $note;

   }

   public function getUserNotes($userId)

   {

      $notes = $this->CompanyModel->getUserNotes($userId);

      return $notes;

   }

   public function getCustomerNotes($customerId)

   {

      $notes = $this->CompanyModel->getNotes($customerId);

      return $notes;

   }

   public function getPropertyNotes($propertyId)

   {

      $notes = $this->CompanyModel->getPropertyNotes($propertyId);

      return $notes;

   }

   public function getCompanyNotes($companyId)

   {

      $notes = $this->CompanyModel->getCompanyNotes($companyId);

      return $notes;

   }

   public function getNotesWhere($where)

   {

      if(is_array($where))

      {

         $notes = $this->CompanyModel->getNotesWhere($where);

         return $notes;

      } else

      {

         return array(

            'message' => 'Warning: You must provide an array of column "where" arguments.'

         );

      }

   }

   public function markNoteComplete()

   {

      $id = $this->uri->segment('3');

      $result = $this->CompanyModel->closeNoteStatus($id);

      $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';

      if ($result) {

         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note status <strong>UPDATED</strong> successfully</div>');

         redirect($referer_path);

      } else {

         $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note status did <strong>NOT</strong> update correctly.</div>');

         redirect($referer_path);

      }

   }

   public function deleteNote()

   {

      $id = $this->uri->segment('3');

      $result = $this->CompanyModel->deleteNote($id);

      $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';

      if ($result) {

         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note <strong>DELETED</strong> successfully</div>');

         redirect($referer_path);

      } else {

         $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note did <strong>NOT</strong> delete correctly.</div>');

         redirect($referer_path);

      }

   }

   public function updateAssignUser()

   {

      $data = $this->input->post();

      $where = array(

         'note_id' => $data['noteId']

      );

      $updateData = array(

         'note_assigned_user' => ($data['userId'] == '') ? NULL : $data['userId']

      );

      $status = $this->CompanyModel->updateNoteData($updateData, $where);

      $note = $this->CompanyModel->getOneNote($where);

      if(!empty($updateData['note_assigned_user']))

      {

      $note_creator = $this->Administrator->getOneAdmin(array('id' => $note->note_user_id));

      $note_type = ($note->note_type == 0 || !empty($note->note_type)) ? $this->CompanyModel->getOneNoteTypeName($note->note_type) : 'None';

      $email_array = array(

        'note_creator' => $note_creator->user_first_name.' '.$note_creator->user_last_name,

        'note_type' => $note_type,

        'note_due_date' => $note->note_due_date ?? 'None',

        'note_contents' => $note->note_contents

      );

      $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

      $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

      $where = array('company_id' => $currentUser->company_id);

      $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

      $subject =  'New Note Assignment';

      $where['is_smtp'] = 1;

      $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

      $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $note->note_assigned_user));

      $email_array['name'] = $note_assigned_user->user_first_name.' '.$note_assigned_user->user_last_name;

      // die(print_r(json_encode($email_array)));

      $body  = $this->load->view('email/note_email',$email_array,TRUE);

      $res =   Send_Mail_dynamic( $company_email_details, $note_assigned_user->email, array("name" => $email_array['setting_details']->company_name, "email" => $email_array['setting_details']->company_email),  $body, $subject);

      }

      $return_data = array(

        'status' => $status,

        'note' => $note

      );

      print_r(json_encode($return_data));

   }

   public function updateNoteDueDate()

   {

      $data = $this->input->post();

      $where = array(

         'note_id' => $data['noteId']

      );

      $updateData = array(

         'note_due_date' => $data['dueDate']

      );

      $this->CompanyModel->updateNoteData($updateData, $where);

   }

   // Note Types

   public function getNoteTypes($companyId)

   {

      $note_types = $this->CompanyModel->getNoteTypes($companyId);

      return $note_types;

   }

   public function createNoteType()

   {

      $data = $this->input->post();

      $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/setting';

      if(!empty($data['type_name']) && $data['type_name'] !== 'Task')

      {

        $params = array(

          'type_name' => $data['type_name'],

          'type_company_id' => $data['type_company_id']

        );

        $note_type_id = $this->CompanyModel->createNoteType($params);



        if($note_type_id && $note_type_id > 0)

        {

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Task Type <strong>ADDED</strong> successfully!</div>');

            redirect($referer_path);

        } else

        {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Task Type <strong>NOT</strong> added!</div>');

            redirect($referer_path);

        }

      } else

      {

        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Task Type <strong>CANNOT</strong> be empty!</div>');

        redirect($referer_path);

      }

   }

   // Note Comments

   public function addNoteComment()

   {

      $data = $this->input->post();

      $commentData = array(

         'note_id' => $data['comment-noteid'],

         'comment_user_id' => $data['comment-userid'],

         'comment_body' => $data['add-comment-input'],

      );

      $comment_id = $this->CompanyModel->addNoteComment($commentData);

      $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

      if($comment_id && $comment_id > 0)

      {

         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Comment <strong>ADDED</strong> successfully!</div>');

         redirect($referer_path);

      } else

      {

         $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Comment <strong>NOT</strong> added!</div>');

         redirect($referer_path);

      }

   }

   public function addNoteCommentAjax()

   {

      $data = $this->input->post();

      $commentData = array(

      'note_id' => $data['comment-noteid'],

      'comment_user_id' => $data['comment-userid'],

      'comment_body' => $data['add-comment-input'],

      );

      $comment_id = $this->CompanyModel->addNoteComment($commentData);

      $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

      $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

      if($comment_id && $comment_id > 0)

      {

      $r_comment = $this->CompanyModel->getSingleNoteComment($comment_id);

      $commentData['timestamp'] = $r_comment->comment_created_at;

      $commentData['status'] = 'success';

      $commentData['user_first_name'] = $currentUser->user_first_name;

      $commentData['user_last_name'] = $currentUser->user_last_name;

      $commentData['comment_count'] = $this->CompanyModel->getNoteCommentCount($data['comment-noteid']);

      print_r(json_encode($commentData));

      } else {

      $commentData['status'] = 'failed';

      print_r(json_encode($commentData));

      }

   }

   // Note Files

    public function addNoteFiles($noteId)

    {

        if (!empty($_FILES['files']['name']))

        {

        $fileData = (array)[];

        $filesCount = count($_FILES['files']['name']);

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        for($i = 0; $i < $filesCount; $i++)

        {

            $fileData[$i] = (array)[];

            $file_name  = $_FILES['files']['name'][$i];

            $tmp_name   = $_FILES['files']['tmp_name'][$i];

            $key = 'uploads/note_files/'.$file_name;

            $res=$this->aws_sdk->saveObject($key,$tmp_name);

            $fileData[$i]['file_key'] = $key;

            $fileData[$i]['file_name'] = $file_name;

            $fileData[$i]['note_id'] = $noteId;

            $fileData[$i]['file_user_id'] = $currentUser->id;

        }

        if(!empty($fileData))

        {

            $fileResult = $this->CompanyModel->noteAddFiles($fileData);

            $fileStatusMsg = $fileResult;

        } else

        {

            $fileStatusMsg = "Sorry, there was an error uploading your file.";

        }



        } else

        {

        $fileStatusMsg = false;

        }

        return $fileStatusMsg;

    }

    public function updateAssignType()

    {

        $data = $this->input->post();

        $where = array(

            'note_id' => $data['noteId']

        );

        $updateData = array(

            'note_type' => ($data['typeId'] == '') ? NULL : $data['typeId']

        );

        $this->CompanyModel->updateNoteData($updateData, $where);

    }

  /* Add File to Existing Note */

    public function addToNoteFiles()

    {

        $data = $this->input->post();

        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'technician/';

        if (!empty($_FILES['files']['name']))

        {

        $fileData = (array)[];

        $filesCount = count($_FILES['files']['name']);

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        for($i = 0; $i < $filesCount; $i++)

        {

            $fileData[$i] = (array)[];

            $file_name  = $_FILES['files']['name'][$i];

            $tmp_name   = $_FILES['files']['tmp_name'][$i];

            $key = 'uploads/note_files/'.$file_name;

            $res=$this->aws_sdk->saveObject($key,$tmp_name);

            $fileData[$i]['file_key'] = $key;

            $fileData[$i]['file_name'] = $file_name;

            $fileData[$i]['note_id'] = $data['note_id'];

            $fileData[$i]['file_user_id'] = $currentUser->id;

        }



            if(!empty($fileData))

            {

                $fileResult = $this->CompanyModel->noteAddFiles($fileData);

                $fileStatusMsg = 'Files uploaded successfully!';

            } else

            {

                $fileStatusMsg = "Sorry, there was an error uploading your file.";

            }



        } else

        {

        $fileStatusMsg = "Sorry, there was an error uploading your file.";

        }

        $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible" role="alert" data-auto-dismiss="4000">'.$fileStatusMsg.'</div>');

        redirect($referer_path);

    }

    public function addTechNoteAjax($data = NULL)

    {

        $data = (empty($data)) ? $this->input->post() : $data;

        $post_data = $this->input->post();

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        $note_user_id = $currentUser->id;

        $note_property_id = $post_data['note_property_id'];

        $note_customer_id = $post_data['note_customer_id'] ?? NULL;

        $note_company_id = $currentUser->company_id;

        $saw = $post_data['customer_note_saw'];

        $did = $post_data['customer_note_did'];

        $exp = $post_data['customer_note_expect'];

        $note_contents = 'Saw: '.$saw.';  Did: '.$did.';  Expect: '.$exp;

        $noteData = array(

            'note_user_id' => $note_user_id,

            'note_property_id' => $note_property_id,

            'note_customer_id' => $note_customer_id,

            'note_company_id' => $note_company_id,

            'note_contents' => $note_contents,

            'note_category' => 2,

            'include_in_tech_view' => 1

        );

        $noteId = $this->CompanyModel->addTechNote($noteData);

        if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0]))

        {

            $fileResult = $this->addNoteFilesAjax($noteId);

            if($fileResult == false)

            {

            $status = 'error';

            } else

            {

            $status = 'success';

            }

        } elseif(!empty($noteId))

        {

            $status = 'success';

        } else

        {

            $status = 'error';

        }

        $response = array(

            'status' => $status,

            'note_id' => $noteId

        );

        if(isset($fileResult))

        {

            $response['file_upload'] = $fileResult;

        }

        print_r(json_encode($response));

    }

    public function addNoteFilesAjax($noteId)

    {

        if (!empty($_FILES['files']['name']))

        {

        $fileData = (array)[];

        $filesCount = count($_FILES['files']['name']);

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        for($i = 0; $i < $filesCount; $i++)

        {

            $fileData[$i] = (array)[];

            $file_name  = $_FILES['files']['name'][$i];

            $tmp_name   = $_FILES['files']['tmp_name'][$i];

            $key = 'uploads/note_files/'.$file_name;

            $res=$this->aws_sdk->saveObject($key,$tmp_name);

            $fileData[$i]['file_key'] = $key;

            $fileData[$i]['file_name'] = $file_name;

            $fileData[$i]['note_id'] = $noteId;

            $fileData[$i]['file_user_id'] = $currentUser->id;

        }

        if(!empty($fileData))

        {

            $fileResult = $this->CompanyModel->noteAddFiles($fileData);

            return array(

            'file_result' => $fileResult,

            'aws_resp' => $res

            );

        } else

        {

            return false;

        }

        } else

        {

        return false;

        }

    }

 // Note End

    public function sendStatement($ids){

        // die("I am inside the sent Statement function!");

        $ids = explode(":", $ids);

        $customer_ids = explode(",", $ids[1]);

        $work_statement_ids = explode(",", $ids[0]);



        function array_combine_($keys, $values){

            $result = array();

            foreach ($keys as $i => $k) {

                $result[$k][] = $values[$i];

            }

            array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));

            return    $result;

        }



        $merged = array_combine_($customer_ids,$work_statement_ids);

        $merge2 = array_merge($work_statement_ids,$customer_ids);



        $customer_id = array();



		$where = array('work_statement_id' => $work_statement_ids[0]);

        $statement_details = $this->STATE->getOneStatement($where);



        $company_id = $statement_details->company_id;



        $customer_wise_data = [];



        if (!empty($merged)) {

            foreach ($merged as $key => $value) {

                if(is_array($value)){

                    $value = array_unique($value);



                    foreach($value as $subkey => $subvalue){



                        $work_statement_id =  $subvalue;

                        $customer_id =  $key;



                        $where = array('work_statement_id' =>$work_statement_id);

                        $statement_details = $this->STATE->getOneStatement($where);



                        $detail = array(

                            'work_statement_id' => $work_statement_id,

                            'status' => $statement_details->status

                        );



                        if(array_key_exists($customer_id,$customer_wise_data)) {

                            array_push($customer_wise_data[$customer_id],$detail);

                        } else {

                            $customer_wise_data[$customer_id][] = $detail;

                        }



                    }

                } else {

                    $work_statement_id =  $value;

                    $customer_id =  $key;



                    $where = array('work_statement_id' =>$work_statement_id);

                    $statement_details = $this->STATE->getOneStatement($where);



                    $detail = array(

                        'work_statement_id' => $work_statement_id,

                        'status' => $statement_details->status

                    );



                    if(array_key_exists($customer_id,$customer_wise_data)) {

                        array_push($customer_wise_data[$customer_id],$detail);

                    } else {

                        $customer_wise_data[$customer_id][] = $detail;

                    }



                }

            }



            if(!empty($customer_wise_data)) {

                $where_company = array('company_id' =>$company_id);

                $data['setting_details'] =

                    $this->CompanyModel->getOneCompany($where_company);

                $data['setting_details']->company_logo =

                    ($data['setting_details']->company_resized_logo != '') ?

                        $data['setting_details']->company_resized_logo :

                            $data['setting_details']->company_logo;



                foreach($customer_wise_data as $customer_id => $customer_data) {



                    $data['customer_details'] =

                        $this->CustomerModel->getOneCustomerDetail($customer_id);



                    $email = $data['customer_details']->email;

                    $work_statement_id_list = array_column($customer_data,'work_statement_id');



                    $data['bulk_work_statement_id'] = implode(',',$work_statement_id_list);

                    $hashstring =

                        md5($email."-".$customer_id."-".date("Y-m-d H:i:s"));



                    $data['link'] =  base_url('welcome/pdfWorkStatement/').$hashstring;

                    $body = $this->load->view('admin/invoice/email_statement_pdf',$data,true);

                    $where_company['is_smtp'] = 1;

                    $company_email_details =

                        $this->CompanyEmail->getOneCompanyEmailArray($where_company);



                    if (!$company_email_details) {

                        $company_email_details =

                            $this->Administratorsuper->getOneDefaultEmailArray();

                    }



                    $res = Send_Mail_dynamic($company_email_details,

                                $email,

                                array("name" => $this->session->userdata['compny_details']->company_name,

                                "email" => $this->session->userdata['compny_details']->company_email),

                                $body,

                                'Work Statement Details - '.date('Y-m-d'),

                                $data['customer_details']->secondary_email);



                    if($res['status'] == 1) {

                        $batch_insert_arr = array();



                        foreach($customer_data as $value) {

                            $hash_tbl_arr = array();

                            $update_arr = array('last_modify' => date("Y-m-d H:i:s"));



                            if($value['status'] == 0 || $statement_details->status == 1) {

								$update_arr['bulk_work_statement_id'] = $work_statement_id_list;

                                $update_arr['status'] = 1;

                                $update_arr['sent_date'] = date("Y-m-d H:i:s");



                                if(empty($statement_details->first_sent_date)){

                                    $update_arr['first_sent_date'] =

                                        date("Y-m-d H:i:s");

                                }

								$this->bulkChangeStatementStatus($update_arr);

                            }



                            $where_arr = array("work_statement_id" => $value['work_statement_id']);

                            $hash_tbl_arr['work_statement_id'] = $value['work_statement_id'];

                            $hash_tbl_arr['company_id'] = $company_id;

                            $hash_tbl_arr['hashstring'] = $hashstring;

                            $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");



                            array_push($batch_insert_arr,$hash_tbl_arr);

                        }

                        // Added hash table concept to get rid of expired link issue.

                        $this->db->insert_batch('statement_hash_tbl',$batch_insert_arr);

                    }

                }

            }

        }



        redirect('technician/dashboard/');

    }

    public function bulkChangeStatementStatus($data, $value=''){

		// $data =  $this->input->post();

		// print_r($data);

		if (!empty($data['bulk_work_statement_id'])) {

			foreach ($data['bulk_work_statement_id'] as $key => $value) {

				$where = array(

				'work_statement_id' =>$value

				);

				$param = array(

				'status' =>$data['status'],

				'last_modify' => date("Y-m-d H:i:s")

				);

				$statement_details = $this->STATE->getOneStatement($where);

				//  If invoice is already paid or partial payment and change status then to skip.

				if($data['status']==2) {

					$param['opened_date'] = date("Y-m-d H:i:s");

				}elseif($data['status']==1) {

					$param['sent_date'] = date("Y-m-d H:i:s");

					if(empty($statement_details->first_sent_date)){

						$param['first_sent_date'] = date("Y-m-d H:i:s");

					}

				}

				$result = $this->STATE->updateStatement($where,$param);

				$statement_details = $this->STATE->getOneStatement($where);

			}

		//  echo 1;

		} else {

		//  echo  0;

		}

	}

    public function statementPrint($statement_ids){

        $where_company = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

          $where_arr = array(
            'company_id' => $this->session->userdata['spraye_technician_login']->company_id,
            'status' => 1
          );


        $statement_ids = explode(",", $statement_ids);

        foreach ($statement_ids as $key => $value) {

            $where = array(
               "completed_work_statements.company_id" => $this->session->userdata['spraye_technician_login']->company_id,
                'work_statement_id' =>$value
            );
             $statement_details =  $this->STATE->getOneWorkStatement($where);

           if(empty($statement_details->job_id)){
                //get job data
              $jobs = array();

              $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' =>$statement_details->invoice_id));

              if($job_details){
                  //print_r($job_details);
                  foreach($job_details as $detail){
                      $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id'=>$detail['job_id'],'invoice_id'=>$statement_details->invoice_id));
                              if(isset($detail['report_id'])){
                                  $report = $this->RP->getOneRepots(array('report_id'=>$detail['report_id']));
                              } else {
                                  $report = '';
                              }


                              // SERVICE WIDE COUPONS
                              $arry = array(
                                  'customer_id' => $statement_details->customer_id,
                                  'program_id' => $statement_details->program_id,
                                  'property_id' => $statement_details->property_id,
                                  'job_id' => $detail['job_id']
                              );

                              $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                              $coupon_job_amm = 0;
                              $coupon_job_amm_calc = 5;
                              $coupon_job_code = '';
                              if (!empty($coupon_job)) {
                                  $coupon_job_amm = $coupon_job->coupon_amount;
                                  $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                                  $coupon_job_code = $coupon_job->coupon_code;
                              }



                              $jobs[]=array(
                                  'job_id'=>$detail['job_id'],
                                  'job_name'=>$detail['job_name'],
                                  'job_description'=>$detail['job_description'],
                                  'job_cost'=>$detail['job_cost'],
                                  'job_assign_date'=>isset($get_assign_date) ? $get_assign_date->job_assign_date : '',
                                  'program_name'=>isset($detail['program_name']) ? $detail['program_name'] : '',
                                  'job_report'=>isset($report) ? $report : '',
                                  'coupon_job_amm' => $coupon_job_amm,
                                  'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                  'coupon_job_code' => $coupon_job_code,

                              );
                  }
              }
              $statement_details->jobs = $jobs;
           }
                if(!empty($statement_details->report_id)){
                   $statement_details->report_details =  $this->RP->getOneRepots(array('report_id'=>$statement_details->report_id));
              }

            $data['statement_details'][] = $statement_details;
            if($statement_details->status == 0 || $statement_details->status == 1) {
              $update_arr = array('last_modify' => date("Y-m-d H:i:s"));
              $update_arr['bulk_work_statement_id'] = $statement_ids;
              $update_arr['status'] = 1;
              $update_arr['sent_date'] = date("Y-m-d H:i:s");
              if(empty($statement_details->first_sent_date)){
                  $update_arr['first_sent_date'] =
                  date("Y-m-d H:i:s");
              }
          }
        }

          $this->bulkChangeStatementStatus($update_arr);
        $this->load->view('admin/invoice/multiple_pdf_statement_print',$data);
        $html = $this->output->get_output();
//        die(print_r($html));
        // Load pdf library
        $this->load->library('pdf');
        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        // Render the HTML as PDF
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_statement_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
    }

	public function sendInvoiceGroupBilling($invoice_ids){

        $invoice_ids = explode(",", $invoice_ids);
		$property_ids = array();
		foreach($invoice_ids as $invoice){
			$invoice_details = $this->INV->getOneInvoice(array('invoice_id'=>$invoice));
			$property_ids[] = $invoice_details->property_id;
		}

        function array_combine_($keys, $values){
            $result = array();
            foreach ($keys as $i => $k) {
                $result[$k][] = $values[$i];
            }
            array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
            return    $result;
        }

        $merged = array_combine_($property_ids,$invoice_ids);
        $merge2 = array_merge($invoice_ids,$property_ids);

        $property_id = array();

		$where = array('invoice_id' => $invoice_ids[0]);
        $invoice_details = $this->INV->getOneInvoice($where);

        $company_id = $invoice_details->company_id;

        $customer_wise_data = [];
        $res = [];
        if (!empty($merged)) {
            foreach ($merged as $key => $value) {
                if(is_array($value)){
                    $value = array_unique($value);

                    foreach($value as $subkey => $subvalue){

                        $invoice_id =  $subvalue;
                        $property_id =  $key;

                        $where = array('invoice_id' =>$invoice_id);
                        $invoice_details = $this->INV->getOneInvoive($where);

                        $detail = array(
                            'invoice_id' => $invoice_id,
                            'invoice_status' => $invoice_details->status
                        );

                        if(array_key_exists($property_id,$customer_wise_data)) {
                            array_push($customer_wise_data[$property_id],$detail);
                        } else {
                            $customer_wise_data[$property_id][] = $detail;
                        }

                    }
                } else {
                    $invoice_id =  $value;
                    $property_id =  $key;

                    $where = array('invoice_id' =>$invoice_id);
                    $invoice_details = $this->INV->getOneInvoive($where);

                    $detail = array(
                        'invoice_id' => $invoice_id,
                        'invoice_status' => $invoice_details->status
                    );

                    if(array_key_exists($property_id,$customer_wise_data)) {
                        array_push($customer_wise_data[$property_id],$detail);
                    } else {
                        $customer_wise_data[$property_id][] = $detail;
                    }

                }
            }

            if(!empty($customer_wise_data)) {
                $where_company = array('company_id' =>$company_id);
                $data['setting_details'] =
                    $this->CompanyModel->getOneCompany($where_company);
                $data['setting_details']->company_logo =
                    ($data['setting_details']->company_resized_logo != '') ?
                        $data['setting_details']->company_resized_logo :
                            $data['setting_details']->company_logo;

                foreach($customer_wise_data as $property_id => $property_data) {

                    $data['group_billing_details'] = $this->PropertyModel->getGroupBillingByProperty($property_id);

                    $email = $data['group_billing_details']['email'];
                    $invoice_id_list = array_column($property_data,'invoice_id');

                    $data['bulk_invoice_id'] = implode(',',$invoice_id_list);
                    $hashstring =
                        md5($email."-".$property_id."-".date("Y-m-d H:i:s"));

                    $data['link'] =  base_url('welcome/groupBillingPdf/').$hashstring;
                    $data['linkView'] =  base_url('welcome/groupBillingView/').$hashstring;
                    $body = $this->load->view('admin/invoice/email_pdf',$data,true);
                    $where_company['is_smtp'] = 1;
                    $company_email_details =
                        $this->CompanyEmail->getOneCompanyEmailArray($where_company);

                    if (!$company_email_details) {
                        $company_email_details =
                            $this->Administratorsuper->getOneDefaultEmailArray();
                    }

                    $batch_insert_arr = array();

                    foreach($property_data as $value) {
                        $hash_tbl_arr = array();
                        $update_arr = array('last_modify' => date("Y-m-d H:i:s"));

                        if($value['invoice_status'] == 0 || $invoice_details->status == 1) {
                            $update_arr['bulk_invoice_id'] = $invoice_id_list;
                            $update_arr['status'] = 1;
                            $update_arr['sent_date'] = date("Y-m-d H:i:s");

                            if(empty($invoice_details->first_sent_date)){
                                $update_arr['first_sent_date'] =
                                    date("Y-m-d H:i:s");
                            }
                            $this->bulkChangeStatus($update_arr);
                        }

                        $where_arr = array("invoice_id" => $value['invoice_id']);
                        $hash_tbl_arr['invoice_id'] = $value['invoice_id'];
                        $hash_tbl_arr['company_id'] = $company_id;
                        $hash_tbl_arr['hashstring'] = $hashstring;
                        $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");

                        array_push($batch_insert_arr,$hash_tbl_arr);
                    }
                    // Added hash table concept to get rid of expired link issue.
                    $this->db->insert_batch('invoice_hash_tbl',$batch_insert_arr);

                    $res = Send_Mail_dynamic($company_email_details,$email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Service Details - '.date('Y-m-d'), '');

                }
            }
        }

        return $res; //redirect('technician/dashboard/');
    }
    public function invoicePrintGroupBilling($invoice_ids){

        $where_company = array('company_id' =>$this->session->userdata['spraye_technician_login']->company_id);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where_arr = array(
            'company_id' => $this->session->userdata['spraye_technician_login']->company_id,
            'status' => 1
        );

        $invoice_ids = explode(",", $invoice_ids);

        foreach ($invoice_ids as $key => $value) {

            $where = array(
               "invoice_tbl.company_id" => $this->session->userdata['spraye_technician_login']->company_id,
                'invoice_id' =>$value
            );
            $invoice_details =  $this->INV->getOneInvoive($where);
  		    if(!empty($invoice_details->property_id)){
			    $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
		    }
            if(empty($invoice_details->job_id)){
                //get job data
                $jobs = array();

                $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' =>$value));

                if($job_details){
                    //print_r($job_details);
                    foreach($job_details as $detail){
                        $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id'=>$detail['job_id'],'invoice_id'=>$value));
                        if(isset($detail['report_id'])){
                            $report = $this->RP->getOneRepots(array('report_id'=>$detail['report_id']));
                        } else {
                            $report = '';
                        }


                         // SERVICE WIDE COUPONS
                        $arry = array(
                            'customer_id' => $invoice_details->customer_id,
                            'program_id' => $invoice_details->program_id,
                            'property_id' => $invoice_details->property_id,
                            'job_id' => $detail['job_id']
                        );

                        $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                        $coupon_job_amm = 0;
                        $coupon_job_amm_calc = 5;
                        $coupon_job_code = '';
                        if (!empty($coupon_job)) {
                            $coupon_job_amm = $coupon_job->coupon_amount;
                            $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                            $coupon_job_code = $coupon_job->coupon_code;
                        }



                        $jobs[]=array(
                            'job_id'=>$detail['job_id'],
                            'job_name'=>$detail['job_name'],
                            'job_description'=>$detail['job_description'],
                            'job_cost'=>$detail['job_cost'],
                            'job_assign_date'=>isset($get_assigned_date->job_assign_date) ? $get_assigned_date->job_assign_date : '',
                            'program_name'=>isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report'=>isset($report) ? $report : '',
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,

                        );
                    }
                }
                $invoice_details->jobs = $jobs;
            }
            $invoice_details->all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$value));

            if(!empty($invoice_details->report_id)){
                $invoice_details->report_details =  $this->RP->getOneRepots(array('report_id'=>$invoice_details->report_id));
            }

            $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $value));

            $data['invoice_details'][] = $invoice_details;
            if($invoice_details->status == 0 || $invoice_details->status == 1) {
                $update_arr = array('last_modify' => date("Y-m-d H:i:s"));
                $update_arr['bulk_invoice_id'] = $invoice_ids;
                $update_arr['status'] = 1;
                $update_arr['sent_date'] = date("Y-m-d H:i:s");
                if(empty($invoice_details->first_sent_date)){
                    $update_arr['first_sent_date'] = date("Y-m-d H:i:s");
                }
            }
        }

        $this->bulkChangeStatus($update_arr);
        $this->load->view('admin/invoice/multiple_pdf_invoice_print_group_billing',$data);

        // $this->load->view('pdf_invoice_print',$data);

    }
	public function deleteAssignedPropertyCondition(){
		$data = $this->input->post();
		$response = [];
		if(isset($data['property_condition_assign_id']) && $data['property_condition_assign_id'] != ""){
			$handleUnassign = $this->PropertyModel->deleteAssignedPropertyCondition(array('property_condition_assign_id'=>$data['property_condition_assign_id']));
			if($handleUnassign == 'true'){
				$repsonse['status'] = "success";
				echo json_encode($repsonse);
			}
		}

	}

    /////////// Vehicle Inspections /////////////
    public function submitVehicleInspection()
    {
        $data = $this->input->post();

        $insp_report = array();

        foreach($data as $key => $value)
        {
        if($value == 'on')
        {
            $insp_report[$key] = 1;
        } elseif($value == 'off')
        {
            $insp_report[$key] = 0;
        } elseif($value == 'Y')
        {
            $insp_report[$key] = 1;

        } elseif($value == 'N')
        {
            $insp_report[$key] = 0;
        } else
        {
            $insp_report[$key] = $value;
        }
        }

        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

        $currentAssignedVehicle = $this->CompanyModel->getOneFleetVehicleAssigned( $currentUser->id );
        if( is_null($currentAssignedVehicle) || $currentAssignedVehicle->fleet_id !== $insp_report['truck_number'] )
        {
        if( !is_null( $currentAssignedVehicle ))
        {
            $currentAssignedVehicle->v_assigned_user = null;
            $count = $this->CompanyModel->updateFleetVehicle( $currentAssignedVehicle->fleet_id, $currentAssignedVehicle );
        }

        $currentVehicle = $this->CompanyModel->getOneFleetVehicle( $insp_report['truck_number'] );
        $currentVehicle->v_assigned_user = $currentUser->id;
        $count = $this->CompanyModel->updateFleetVehicle( $currentVehicle->fleet_id, $currentVehicle );
        }

        $fails = $this->vehicleInspectionChk($insp_report);

        $fail_str = '';
        if(is_array($fails))
        {
        foreach($fails as $key => $val)
        {
            $fail_str .= $key.':'.$val.', ';
        }
        $fail_str = substr($fail_str, 0, -2);
        }



        $note = array(
            'note_user_id' => $insp_report['driver_id'],
            'note_company_id' => $currentUser->company_id,
            'note_category' => 3,
            'note_truck_id' => $insp_report['truck_number'],
            'note_contents' => ($fail_str == '') ? nl2br($insp_report['vehicle_inspection_form_notes']) : $fail_str.' - '. nl2br($insp_report['vehicle_inspection_form_notes']),
            'note_due_date' => $data['note_due_date'] ?? NULL,
            'note_assigned_user' => $data['note_assigned_user'] ?? NULL,
            'note_type' => ($fail_str == '') ? 2 : 3
        );

        $noteId = $this->CompanyModel->addNote($note);
        $insp_report['vehicle_note_id'] = $noteId;
        $inspection_id = $this->RP->addInspectionReport($insp_report);

        if($noteId && $note['note_type'] == '3')
        {
            $mnt_arr = array(
                'mnt_truck_id' => $note['note_truck_id'],
                'mnt_note_id' => $noteId,
                'mnt_company_id' =>  $currentUser->company_id
            );
            $mnt_id = $this->CompanyModel->addMaintenanceEntry($mnt_arr);
            $where_array = array(
                'company_id' => $mnt_arr['mnt_company_id'],
                'role_id' => 3
            );
            $manegers = $this->CompanyModel->getMaintenanceManegers($where_array);
            if(empty($manegers))
            {
                $where_array = array(
                    'company_id' => $mnt_arr['mnt_company_id'],
                    'role_id' => 2
                );
                $manegers = $this->CompanyModel->getMaintenanceManegers($where_array);
            }
            if(!empty($manegers))
            {
                $note_creator = $this->Administrator->getOneAdmin(array('id' => $note['note_user_id']));
                foreach($manegers as $maneger)
                {

                    $note_type = $this->CompanyModel->getOneNoteTypeName($note['note_type']);
                    $email_array = array(
                        'note_creator' => $note_creator->user_first_name.' '.$note_creator->user_last_name,
                        'note_type' => $note_type,
                        'note_due_date' => $note['note_due_date'] ?? 'None',
                        'note_contents' => $note['note_contents']
                    );
                    $where = array('company_id' => $currentUser->company_id);
                    $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);
                    $email_array['vehicle_details'] = $this->CompanyModel->getOneFleetVehicle($mnt_arr['mnt_truck_id']);

                    $subject =  'New Vehicle Maintenance';
                    $where['is_smtp'] = 1;
                    $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                    if(isset($note['note_assigned_user']))
                    {
                        $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $note['note_assigned_user']));
                    } else {
                        $note_assigned_user = NULL;
                    }
                    $email_array['name'] = $maneger->user_first_name.' '.$maneger->user_last_name;
                    $body  = $this->load->view('email/maint_email',$email_array,TRUE);
                    $res =   Send_Mail_dynamic( $company_email_details, $maneger->email, array("name" =>  $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, $subject);

                }
            }
        }

        $insp_report['v_insp_id'] = $inspection_id;

        $data['insp_rpt'] = $insp_report;

        echo $inspection_id;

        /*
            $this->load->view('admin/views/fleet/pdf_inspection',$data);
            $html = $this->output->get_output();
            // Load pdf library
            $this->load->library('pdf');
            // Load HTML content
            $this->dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $this->dompdf->setPaper('A4', 'portrate');
            // Render the HTML as PDF
            $this->dompdf->render();
            // Output the generated PDF (1 = download and 0 = preview)
            $this->dompdf->stream($fileName.".pdf", array("Attachment"=>0));
        */
    }

    public function vehicleInspectionChk($insp_arr)
    {
        $keys = array(
        "chk_ac_heater",
        "chk_battery",
        "chk_vhcl_body",
        "chk_brakes",
        "chk_brakes_parking",
        "chk_horn",
        "chk_lights",
        "chk_mirrors",
        "chk_oil",
        "chk_coolant",
        "chk_trans_fld",
        "chk_windows",
        "chk_backpack",
        "chk_blower",
        "chk_tool_kit",
        "chk_trailer_brakes",
        "chk_trailer_coupling",
        "chk_trailer_hitch",
        "chk_trailer_lights",
        "chk_trailer_tires",
        "chk_pump_hoses",
        "chk_pump_spray",
        "chk_pump_oil",
        "chk_pump_tank",
        "chk_pump_lid",
        "chk_safety_gloves",
        "chk_safety_boots",
        "chk_safety_spill_kit",
        "chk_safety_eye_wash",
        "chk_safety_firstaid_kit",
        "chk_safety_glasses",
        "chk_spreader_hopper",
        "chk_spreader_grease",
        "chk_spreader_impellor",
        "chk_spreader_cotterpins",
        "chk_spreader_wheels",
        "chk_spreader_cover",
        "chk_spreader_secured",
        "chk_tire_left_front",
        "chk_tire_right_front",
        "chk_tire_left_rear",
        "chk_tire_right_rear",
        "vehicle_registration_current",
        "tda_supervisor_affidavit_current",
        "vehicle_condition_satisfactory"
        );
        $flags = array();
        foreach($keys as $key => $value)
        {
        if(!isset($insp_arr[$value]) || $insp_arr[$value] == 0)
        {
            $flags[$value] = 'fail';
        }
        }
        return (count($flags) > 0) ? $flags : 0;
    }


    // Vehicle Issue Note Submit

    public function addTechVehicleIssueAjax($data = NULL)
    {
        $data = (empty($data)) ? $this->input->post() : $data;
        $post_data = $this->input->post();
        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
        // $note_user_id = $currentUser->id;
        $note_truck_id = $post_data['truck_number'];
        // $note_customer_id = $post_data['note_customer_id'] ?? NULL;
        $note_company_id = $currentUser->company_id;
        // $saw = $post_data['customer_note_saw'];
        // $did = $post_data['customer_note_did'];
        // $exp = $post_data['customer_note_expect'];
        // $note_contents = 'Saw: '.$saw.';  Did: '.$did.';  Expect: '.$exp;
        $noteData = array(
        'note_user_id' => $data['note_user_id'],
        'note_truck_id' => $note_truck_id,
        'note_company_id' => $note_company_id,
        'note_contents' => $data['note_contents'],
        'note_assigned_user' => $data['note_assigned_user'] ?? NULL,
        'note_due_date' => $data['note_due_date'] ?? NULL,
        'note_category' => $data['note_category'],
        );
        $noteId = $this->CompanyModel->addTechNote($noteData);
        if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0]))
        {
        $fileResult = $this->addNoteFilesAjax($noteId);
        if($fileResult == false)
        {
            $status = 'error';
        } else
        {
            $status = 'success';
        }
        } elseif(!empty($noteId))
        {
        $status = 'success';
        } else
        {
        $status = 'error';
        }
        $response = array(
        'status' => $status,
        'note_id' => $noteId
        );
        if(isset($fileResult))
        {
        $response['file_upload'] = $fileResult;
        }
        print_r(json_encode($response));
    }
	##### ADDED 2/16/22 (RG) #####
	public function editProperty($propertyID = NULL){

	  if (!empty($propertyID)) {
		  $propertyID = $propertyID;
	  } else {
		  $propertyID = $this->uri->segment(4);
	  }
	  // die(print_r($_SESSION));
	  $where =  array('company_id' => $this->session->userdata['spraye_technician_login']->company_id);
	  $data['servicelist'] = $this->JobModel->getJobList($where);
	  $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
	  $data['programlist'] = $this->PropertyModel->getProgramList($where);
	  $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
	  $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
	  $data['propertyData'] = $this->PropertyModel->getPropertyDetail($propertyID);

	  $data['selectedprogramlist'] = $this->PropertyModel->getSelectedProgram($propertyID);
	  $data['source_list'] = $this->SourceModel->getAllSource($where);
		  $data['users'] = $this->Administrator->getAllAdmin($where);
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


	  $selecteddata1 = $this->PropertyModel->getSelectedCustomer($propertyID);
	  $data['selectedcustomerlist']  = array();

	  if (!empty($selecteddata1)) {
		  foreach ($selecteddata1 as $value) {
			  $data['selectedcustomerlist'][] = $value->customer_id;
		  }
	  }

	  $data['selected_program_ids'] = array();
		if (!empty($data['selectedprogramlist'])) {
			foreach ($data['selectedprogramlist'] as $key => $value) {
				$data['selected_program_ids'][]  = $value->program_id;
			}
		}

		// print_r($data['selected_program_ids']); die();
		$data['setting_details'] = $this->CompanyModel->getOneCompany($where);

		$data['assign_sales_tax'] = array();

		$assingtax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $propertyID));

		if ($assingtax) {
			$data['assign_sales_tax'] =  array_column($assingtax, 'sale_tax_area_id');
		}


		$page["active_sidebar"] = "users";
		$page["page_name"] = "Update Property";
		$page["page_content"] = $this->load->view("technician/edit_property", $data, TRUE);
		$this->layout->technicianTemplateTableDash($page);
	  }

	  ##### ADDED 2/25/22 (RG) #####
	   function getLatLongByAddress($address){
			// $address = str_replace(", ",",+",$address);
			// $address = str_replace(" ","%",$address);


			$address = urlencode($address);
			// 1017%Davis%Boulevard+Sikeston+MO+USA
			// die();

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

	  public function updateProperty(){

		$post_data = $this->input->post();
		// die(print_r($post_data));
		// die(print_r($_SESSION));
		$property_id = $this->input->post('property_id');

		$company_id = $this->session->spraye_technician_login->company_id;
		$user_id = $this->session->spraye_technician_login->user_id;
		$setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

		//print_r($property_id); die();

		$this->form_validation->set_rules('property_title', 'Property Title', 'required');
		$this->form_validation->set_rules('property_address', 'Address', 'required');
		$this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
		$this->form_validation->set_rules('property_city', 'City', 'required');
		$this->form_validation->set_rules('property_state', 'State', 'required');
		$this->form_validation->set_rules('property_zip', 'Zipcode', 'required');
		$this->form_validation->set_rules('property_area', 'Area', 'trim');
		$this->form_validation->set_rules('property_type', 'Property Type', 'required');
		$this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
		$this->form_validation->set_rules('property_notes', 'Notes', 'trim');
		$this->form_validation->set_rules('assign_program[]', 'Assign Program', 'trim');
		$this->form_validation->set_rules('assign_customer[]', 'Assign Customer', 'trim');
		// $this->form_validation->set_rules('difficulty_level', 'Difficulty Level', 'required');

		$this->form_validation->set_rules('total_yard_grass', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('front_yard_square_feet', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('back_yard_square_feet', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('front_yard_grass', 'Assign Customer', 'trim');
		$this->form_validation->set_rules('back_yard_grass', 'Assign Customer', 'trim');
		$this->form_validation->set_rules('measure_map_project_id', 'Measure Map ID', 'trim');

		if ($this->form_validation->run() == FALSE) {

		  $this->addProperty();
		} else {

		  $post_data = $this->input->post();

		  $param = array(
			'property_title' => $post_data['property_title'],
			'property_address' => $post_data['property_address'],

			'property_address_2' => $post_data['property_address_2'],
			'property_city' => $post_data['property_city'],
			'property_state' => $post_data['property_state'],
			'property_zip' => $post_data['property_zip'],
			'property_area' => $post_data['property_area'],
			'property_type' => $post_data['property_type'],
			'yard_square_feet' => $post_data['yard_square_feet'],
			'property_notes' => $post_data['property_notes'],
			'property_status' => $post_data['property_status'],
			'source' => $post_data['source'],
			'front_yard_square_feet' => $post_data['front_yard_square_feet'],
			'back_yard_square_feet' => $post_data['back_yard_square_feet'],
			'measure_map_project_id' => $post_data['measure_map_project_id']

			// 'property_price' => $post_data['property_price']
		  );
		  if (!empty($post_data['difficulty_level'])) {
			$param['difficulty_level'] = $post_data['difficulty_level'];
		  } else {
			$param['difficulty_level'] = 1;
		  }
		  if (!empty($post_data['total_yard_grass'])) {
			$param['total_yard_grass'] = $post_data['total_yard_grass'];
		  }
		  if (!empty($post_data['front_yard_grass'])) {
			$param['front_yard_grass'] = $post_data['front_yard_grass'];
		  }
		  if (!empty($post_data['back_yard_grass'])) {
			$param['back_yard_grass'] = $post_data['back_yard_grass'];
		  }
		  if (!empty($post_data['measure_map_project_id'])) {
			$param['measure_map_project_id'] = $post_data['measure_map_project_id'];
		  }

		  $geo = $this->getLatLongByAddress($post_data['property_address']);

		  if ($geo) {

			$param['property_latitude'] = $geo['lat'];
			$param['property_longitude'] = $geo['long'];

			$check = $this->PropertyModel->checkProperty($param, $property_id);

			if ($check == "true") {

			  $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong>  Already exists.</div>');

			  redirect("technician/dashboard");
			} else {

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



			  if (isset($post_data['assign_program']) && !empty($post_data['assign_program'])) {

				foreach (json_decode($post_data['assign_program']) as $value) {

				  $programs = array();
				  $programs['properties'] = array();

				  $param3 = array(
					'property_id' => $property_id,
					'program_id' => $value->program_id,
					'price_override' => $value->price_override,
					'is_price_override_set' => $value->is_price_override_set,
				  );

				  $assignprogram = $this->PropertyModel->assignProgram($param3);

				  $program['properties'][$property_id] = array(
					'program_property_id' => $assignprogram,
				  );



				  // Create Invoice if One-Time Invoicing Program Selected
				  $prog_details = $this->ProgramModel->getProgramDetail($value->program_id);
				  $jobs = $this->ProgramModel->getSelectedJobs($value->program_id);

				  if ($prog_details['program_price'] == 1) {

					//create jobs array
					$ppjobinv = array();

					//get customer property details
					$customer_property_details = $this->CustomerModel->getAllProperty(array('customer_property_assign.property_id' => $property_id));

					if ($customer_property_details) {
					  $QBO_description = array();
					  $actual_description_for_QBO = array();
					  foreach ($customer_property_details as $key2 => $value2) {

						//get customer info
						$cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));

						$total_cost = 0;
						$description = "";

						// foreach program property job... calculate job cost
						foreach ($jobs as $key3 => $value3) {
						  $job_id = $value3->job_id;

						  $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

						  $description = $job_details->job_name . " ";

						  $QBO_description[] = $job_details->job_name;
						  $actual_description_for_QBO[] = $job_details->job_description;

						  $where2 = array(
							'property_id' => $property_id,
							'job_id' => $job_id,
							'program_id' => $value->program_id,
							'customer_id' => $value2->customer_id
						  );

						  //CALCULATE JOB COST

						  //check for price overrides
						  $estimate_price_override =   GetOneEstimateJobPriceOverride($where2);
						  if ($estimate_price_override) {
							$cost =  $estimate_price_override->price_override;
						  } else {
							$priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $value->program_id));

							if ($priceOverrideData && $priceOverrideData->is_price_override_set == 1) {
							  $cost = $priceOverrideData->price_override;
							} else {
							  //else no price overrides, then calculate job cost
							  $lawn_sqf = $value2->yard_square_feet;
							  $job_price = $job_details->job_price;

							  //get property difficulty level
							  if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
								$difficulty_multiplier = $setting_details->dlmult_2;
							  } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
								$difficulty_multiplier = $setting_details->dlmult_3;
							  } else {
								$difficulty_multiplier = $setting_details->dlmult_1;
							  }

							  //get base fee
							  if (isset($job_details->base_fee_override)) {
								$base_fee = $job_details->base_fee_override;
							  } else {
								$base_fee = $setting_details->base_service_fee;
							  }

							  $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

							  //get min. service fee
							  if (isset($job_details->min_fee_override)) {
								$min_fee = $job_details->min_fee_override;
							  } else {
								$min_fee = $setting_details->minimum_service_fee;
							  }

							  // Compare cost per sf with min service fee
							  if ($cost_per_sqf > $min_fee) {
								$cost = $cost_per_sqf;
							  } else {
								$cost = $min_fee;
							  }
							}
						  }
						  $total_cost += $cost;
						  $ppjobinv[] = array(
							  'customer_id' => $value2->customer_id,
							  'property_id' => $property_id,
							  'program_id' => $value->program_id,
							  'job_id' => $job_id,
							  'cost' => $cost,
						  );
						}

						//format invoice data
						$param =  array(
						  'customer_id' => $value2->customer_id,
						  'property_id' => $property_id,
						  'program_id' => $value->program_id,
						  'user_id' => $user_id,
						  'company_id' => $company_id,
						  'invoice_date' => date("Y-m-d"),
						  'description' => $prog_details['program_notes'],
						  'cost' => ($total_cost),
						  'is_created' => 2,
						  'invoice_created' => date("Y-m-d H:i:s"),
						);
						//create invoice
						$invoice_id = $this->INV->createOneInvoice($param);

						//if invoice id
						if ($invoice_id) {
						  $param['invoice_id'] = $invoice_id;
						  //figure tax
						  if ($setting_details->is_sales_tax == 1) {
							$property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $property_id));
							if ($property_assign_tax) {
							  foreach ($property_assign_tax as  $tax_details) {
								$invoice_tax_details =  array(
								  'invoice_id' => $invoice_id,
								  'tax_name' => $tax_details['tax_name'],
								  'tax_value' => $tax_details['tax_value'],
								  'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
								);

								$this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
							  }
							}
						  }

						  //Quickbooks Invoice **

                          $property_deets = $this->PropertyModel->getOnePropertyDetail($param['property_id']);
                          $property_street = explode(',', $property_deets->property_address)[0];

						  $param['customer_email'] = $cust_details['email'];
						  $param['job_name'] = $description;

						  $QBO_description = implode(', ', $QBO_description);
						  $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
						  $QBO_param = (object)$param;
                          $QBO_param->property_street = $property_street;
						  $QBO_param->actual_description_for_QBO = $actual_description_for_QBO;
						  $QBO_param->job_name = $QBO_description;
						  // die(print_r($QBO_param));

						  $quickbook_invoice_id = $this->QuickBookInv($QBO_param,$actual_description_for_QBO,$QBO_description);
						  //if quickbooks invoice then update invoice table with id
						  if ($quickbook_invoice_id) {
							$invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
						  }

						  foreach ($program['properties'] as $propID => $prop) {
							if ($propID == $property_id) {
							  foreach ($ppjobinv as $i => $job) {
								//		echo "Property Program ID: ".$prop['program_property_id']."</br>";
								//		echo "Job ID: ".$job['job_id']."</br>";
								//		echo "Invoice ID: ".$invoice_id."</br>";
								//	echo "---------<br>";
								//store property program job invoice data
								$newPPJOBINV = array(
								  'customer_id' => $job['customer_id'],
								  'property_id' => $job['property_id'],
								  'program_id' => $job['program_id'],
								  'property_program_id' => $prop['program_property_id'],
								  'job_id' => $job['job_id'],
								  'invoice_id' => $invoice_id,
								  'job_cost' => $job['cost'],
								  'created_at' => date("Y-m-d"),
								  'updated_at' => date("Y-m-d"),
								);

								$PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
							  }
							}
						  }

						  // assign coupon if global customer coupon exists
						  $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $value2->customer_id));
						  if (!empty($coupon_customers)) {
							foreach ($coupon_customers as $coupon_customer) {

							  $coupon_id = $coupon_customer->coupon_id;
							  $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

							  // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
							  $expiration_pass = true;
							  if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
								$coupon_expiration_date = strtotime($coupon_details->expiration_date);

								$now = time();
								if ($coupon_expiration_date < $now) {
								  $expiration_pass = false;
								}
							  }

							  if ($expiration_pass == true) {
								$params = array(
								  'coupon_id' => $coupon_id,
								  'invoice_id' => $invoice_id,
								  'coupon_code' => $coupon_details->code,
								  'coupon_amount' => $coupon_details->amount,
								  'coupon_amount_calculation' => $coupon_details->amount_calculation,
								  'coupon_type' => $coupon_details->type
								);
								$resp = $this->CouponModel->CreateOneCouponInvoice($params);
							  }
							}
						  }
						} //end if invoice
					  } //end foreach customer property
					}
				  }
				  // End Create Invoice
				}
			  }



			  $where1 = array('property_id' => $property_id);
			  $delete1 = $this->PropertySalesTax->deletePropertySalesTax($where1);

			  if (isset($post_data['sale_tax_area_id']) && !empty($post_data['sale_tax_area_id'])) {

				foreach ($post_data['sale_tax_area_id'] as $value4) {

				  $param3 = array(
					'property_id' => $property_id,
					'sale_tax_area_id' => $value4
				  );

				  $this->PropertySalesTax->CreateOnePropertySalesTax($param3);
				}
			  }


			  if (!$result) {

				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

				redirect("technician/editProperty/$property_id");
			  } else {
				$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> updated successfully</div>');
				redirect("technician/jobDetails/$property_id");
			  }
			  redirect("technician/dashboard");
			}
		  } else {

			$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid </strong> Property address</div>');
			redirect("technician/editProperty/$property_id");
		  }

		  redirect("technician/jobDetails/$property_id");
		}
	  }

	  public function getcutomerEmail(){
		$customer_id = $this->input->post('customer_id');
		$data = $this->CustomerModel->getCustomerDetail($customer_id);
		echo trim($data['email']);
	  }

    public function addCreditPayment($customer_id)
        {
            $data = $this->input->post();

            $InvPayMethod = "1";
            if($data["payment_type"] == "cash"){
                $InvPayMethod = "0";
            }
            if($data["payment_type"] == "other"){
                $InvPayMethod = "3";
            }

            $invoice_data['customer_id'] = $customer_id;
            $invoice_data['cost'] = 0;//$data['credit_amount'];
            $invoice_data['user_id'] = $this->session->userdata['user_id'];
            $invoice_data['company_id'] = $this->session->userdata['company_id'];
            $invoice_data['program_id'] = -5;
            $invoice_data['property_id'] = $data['property_id'];
            $invoice_data['job_id'] = -5;
            $invoice_data['status'] = 0;
            $invoice_data['is_credit'] = 1;
            $invoice_data['payment_method'] = $InvPayMethod;
            $invoice_data['is_archived'] = 1;
            $invoice_data['invoice_date'] = $invoice_data['invoice_created'] =  date("Y-m-d H:i:s");
            $invoice_data['is_created'] =  1;

            $invoice_data['responsible_party'] =  implode(",", $data["responsible_party"]);
            $invoice_data['credit_notes'] = $data["credit_notes"];
            $invoice_data['credit_amount'] = $data['credit_amount'];
            $invoice_data['is_created'] =  1;

            $invoice_data['notes'] = $invoice_data['description'] = "Adding {$data['credit_amount']} Credit to customer's account";

            //create invoice

            $invoice_id = $this->INV->createOneInvoice($invoice_data);

            // die(print($invoice_id));

            // die(print("Invoice ID is " . $invoice_id));

            // //Adding Version history
            // $versioning = new Versioning($this->session);
            // if($invoice_id){
            //     $versioning->setEvent("Create")
            //         ->setTable("invoice_tbl")
            //         ->setData($invoice_data)
            //         ->setModel($this->INV)
            //         ->setLink("admin/Invoices/editInvoice/{$invoice_id}")
            //         ->setEntityId($invoice_id)
            //         ->setCustomerId($invoice_data['customer_id'])
            //         ->setNotes("Adding {$data['credit_amount']} Credit to customer's account")
            //         ->setDescription("New Credit Amount Created")
            //         ->setVersion();
            // }
            $credit_amount  = $data['credit_amount'];
            $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice_id));

            //get existing credit_amount from db
                
            //charge card on file
            // if($data['payment_type'] == "card"){
            //     $this->chargecardInvoices($invoice_id,1);
            // }

            //get unpaid invoices for customer
            $unpaid = $this->INV->getUnpaidInvoices($customer_id);

            // die(print_r($unpaid));            

            // SAM
            if(!empty($unpaid)){
                foreach ($unpaid as $invoice){
                    // die(print_r($unpaid));
                    $invoice_amount  = $invoice->unpaid_amount;
                    if($credit_amount >= $invoice_amount && $invoice_amount > 0){
                        $inv_details = $this->INV->getOneInvoice(['invoice_id' => $invoice->unpaid_invoice]);
                        $partial_already_paid = $inv_details->partial_payment;
                        $result = $this->INV->createOnePartialPayment(array(
                            'invoice_id' => $invoice->unpaid_invoice,
                            'payment_amount' => $invoice_amount,
                            'payment_applied' => $invoice_amount,
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => 5,
                            'check_number' => null,
                            'cc_number' => null,
                            'payment_note' => "Payment made from credit amount {$data['credit_amount']}",
                            'customer_id' => $customer_id,
                        ));
                        

                        //mark this invoice as paid
                        $this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'last_modify' => date("Y-m-d H:i:s"), 'payment_created' => date("Y-m-d H:i:s"), 'partial_payment' => $partial_already_paid + $invoice_amount, 'opened_date' => date("Y-m-d H:i:s")]);

                        $credit_amount -= $invoice_amount;

                        $invoice_details = $this->INV->getOneInvoice(['invoice_id' => $invoice->unpaid_invoice]);

                        if(!isset($invoice_details->sent_date)){
                            $this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['sent_date' => date("Y-m-d H:i:s")]);
                        }

                    } else if ($credit_amount > 0 && $invoice_amount > 0) {
                        $inv_details = $this->INV->getOneInvoice(['invoice_id' => $invoice->unpaid_invoice]);
                        $partial_already_paid = $inv_details->partial_payment;
                        $result = $this->INV->createOnePartialPayment(array(
                            'invoice_id' => $invoice->unpaid_invoice,
                            'payment_amount' => $credit_amount,
                            'payment_applied' => $credit_amount,
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => 5,
                            'check_number' => null,
                            'cc_number' => null,
                            'payment_note' => "Payment made from credit amount {$data['credit_amount']}",
                            'customer_id' => $customer_id,
                        ));

                        //mark this invoice as paid
                        $this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['payment_status' => 1, 'last_modify' => date("Y-m-d H:i:s"), 'payment_created' => date("Y-m-d H:i:s"), 'partial_payment' => $partial_already_paid + $credit_amount, 'sent_date' => date("Y-m-d H:i:s")]);
                        $credit_amount = 0;
                    }
                }

                //update customers.credit_amount adjusted credit_amount balance
                $this->INV->addCreditPayment($customer_id, $credit_amount, $data['payment_type']);
                //update partial payment
                $result = $this->INV->createOnePartialPayment(array(
                    'invoice_id' => $invoice_id,
                    'payment_amount' => $data['credit_amount'],
                    'payment_applied' => $data['credit_amount'],
                    'payment_datetime' => date("Y-m-d H:i:s"),
                    'payment_method' => 1,
                    'check_number' => null,
                    'cc_number' => null,
                    'payment_note' => "Adding Credit to customer's account",
                    'customer_id' => $customer_id,
                    'is_credit_balance' => 1
                ));
            }else{
                $this->INV->addCreditPayment($customer_id, $credit_amount, $data['payment_type']);
                //update partial payment
                $result = $this->INV->createOnePartialPayment(array(
                    'invoice_id' => $invoice_id,
                    'payment_amount' => $data['credit_amount'],
                    'payment_applied' => $data['credit_amount'],
                    'payment_datetime' => date("Y-m-d H:i:s"),
                    'payment_method' => 1,
                    'check_number' => null,
                    'cc_number' => null,
                    'payment_note' => "Adding Credit to customer's account",
                    'customer_id' => $customer_id,
                    'is_credit_balance' => 1
                ));
            }
            // END SAM


                /*        
                if($this->chargecardInvoices($invoice_id,1) && $data['payment_type'] == "card"){
                //update credit_amount for this customer
                $this->INV->addCreditPayment($customer_id, $data['credit_amount'], $data['payment_type']);
                }else{
                //for non-credit card customer, just add the amount to credit amount
                $this->INV->addCreditPayment($customer_id, $data['credit_amount'], $data['payment_type']);

                //update partial payment
                $result = $this->INV->createOnePartialPayment(array(
                    'invoice_id' => $invoice_id,
                    'payment_amount' => $data['credit_amount'],
                    'payment_applied' => $data['credit_amount'],
                    'payment_datetime' => date("Y-m-d H:i:s"),
                    'payment_method' => 1,
                    'check_number' => null,
                    'cc_number' => null,
                    'payment_note' => "Adding Credit to customer's account",
                    'customer_id' => $customer_id,
                ));

                }*/

                

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Credit added to customer</div>');
                    redirect("admin/editCustomer/{$customer_id}");
        }

      public function calculateInvoiceCouponValue($param = array()){

        $total_cost = $param['cost'];
        $coupon_invoices = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $param['invoice_id']));

        if (!empty($coupon_invoices)) {

            // die(print_r($coupon_invoices));
            foreach ($coupon_invoices as $coupon_invoice) {

                $coupon_id = $coupon_invoice->coupon_id;
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                $expiration_pass = true;
                if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                    $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                    $now = time();
                    if ($coupon_expiration_date < $now) {
                        $expiration_pass = false;
                    }
                }

                if ($expiration_pass == true) {
                    if ($coupon_details->amount_calculation == 0) {
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                            // die(print_r("Coupon is Flat Rate: " . $total_cost));
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                            // die(print_r("Coupon is Percentage: " . $total_cost));
                        }

                    }
                }
            }
        }
        // die(print_r($total_cost));
        // die(print_r(number_format($total_cost, 2, '.', ',')));
        return number_format($total_cost, 2, '.', ',');
    }

    public function calculateCustomerCouponCost($param = array()){
      $total_cost = $param['cost'];
      $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $param['customer_id']));

      if (!empty($coupon_customers)) {
          foreach ($coupon_customers as $coupon_customer) {

              $coupon_id = $coupon_customer->coupon_id;
              $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

              // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
              $expiration_pass = true;
              if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                  $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                  $now = time();
                  if ($coupon_expiration_date < $now) {
                      $expiration_pass = false;
                  }
              }

              if ($expiration_pass == true) {
                  if ($coupon_details->coupon_amount_calculation == 0) {
                      $discount_amm = (float) $coupon_details->coupon_amount;

                      if (($total_cost - $discount_amm) < 0 ) {
                          $total_cost = 0;
                      } else {
                          $total_cost -= $discount_amm;
                      }

                  } else {
                      $percentage = (float) $coupon_details->coupon_amount;
                      $discount_amm = (float) $total_cost * ($percentage / 100);

                      if (($total_cost - $discount_amm) < 0 ) {
                          $total_cost = 0;
                      } else {
                          $total_cost -= $discount_amm;
                      }

                  }
              }
          }
      }

      return number_format($total_cost, 2, '.', ',');
    }

    public function calculateEstimateCouponCost($param = array()){
        $total_cost = $param['cost'];
        $coupon_estimate = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $param['estimate_id']));

        if (!empty($coupon_estimate)) {
            foreach ($coupon_estimate as $coupon_est) {

                $coupon_id = $coupon_est->coupon_id;
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                $expiration_pass = true;
                if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                    $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                    $now = time();
                    if ($coupon_expiration_date < $now) {
                        $expiration_pass = false;
                    }
                }

                if ($expiration_pass == true) {
                    if ($coupon_details->amount_calculation == 0) {
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    }
                }
            }
        }

        return number_format($total_cost, 2, '.', ',');
    }

    public function calculateServiceCouponCost($param = array()){
    $total_cost = $param['cost'];
    $coupon_jobs = $this->CouponModel->getAllCouponJob(array(
        'job_id' => $param['job_id'],
        'program_id' => $param['program_id'],
        'property_id' => $param['property_id'],
        'customer_id' => $param['customer_id']
    ));

    if (!empty($coupon_jobs)) {
        foreach ($coupon_jobs as $coupon_job) {

            $coupon_id = $coupon_job->coupon_id;
            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

            // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
            $expiration_pass = true;
            if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                $now = time();
                if ($coupon_expiration_date < $now) {
                    $expiration_pass = false;
                }
            }

            if ($expiration_pass == true) {
                if ($coupon_details->amount_calculation == 0) {
                    $discount_amm = (float) $coupon_details->amount;

                    if (($total_cost - $discount_amm) < 0 ) {
                        $total_cost = 0;
                    } else {
                        $total_cost -= $discount_amm;
                    }

                } else {
                    $percentage = (float) $coupon_details->amount;
                    $discount_amm = (float) $total_cost * ($percentage / 100);

                    if (($total_cost - $discount_amm) < 0 ) {
                        $total_cost = 0;
                    } else {
                        $total_cost -= $discount_amm;
                    }

                }
            }
        }
    }

    return number_format($total_cost, 2, '.', ',');
    }

    public function addProperty($customer_id=0, $opt = 0)
    {


        $where =  array('company_id' => $this->session->userdata['company_id']);
        $data['customer_id'] = $customer_id;
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0 ));
        foreach($data['programlist'] as $key => $val){
            if(strstr($val->program_name, '-Standalone Service')){
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- One Time Project Invoicing') && strstr($val->program_name, '+')){
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- Invoiced at Job Completion') && strstr($val->program_name, '+')){
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- Manual Billing') && strstr($val->program_name, '+')){
                unset($data['programlist'][$key]);
            }
        }
        $data['taglist'] = $this->PropertyModel->getTagsList($where);
        $data['source_list'] = $this->SourceModel->getAllSource(array('company_id' => $this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
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
        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
        $data['customerData'] = $this->CustomerModel->getCustomerDetail($customer_id);
        $data['customer_name'] = $data['customerData']['first_name']." ".$data['customerData']['last_name'];
//		die(print_r($data['customerlist']));
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));
        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Add Property";
        $page["page_content"] = $this->load->view("admin/add_property", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    /**
     * 
     * Company TimeZone
     *  This function gets the current technician ID from session,
     *  gets the company ID associated with the technician,
     *  queries the company details using the comapany ID,
     *  and then gets the time zone string from company details (setting_details)
     *  The time zone string is then used to get the date/time in company timezone. 
     * 
     *  The comapny date and time relative to the given time zone string
     *  can be gotten using the helper functions
     *      
     *  Include helper
     *   $this->load->helper('time_zone_date_time_helper');
     * 
     *  Access functions
     *   $companyCurrentTime = getCompanyTimeNow($this->getCompanyTimeZoneString());
     *   $companyCurrentDate = getCompanyDateNow($this->getCompanyTimeZoneString());
     *   $companyCurrentDateTime = getCompanyDateTimeNow($this->getCompanyTimeZoneString());
     * 
     */
    private function getCompanyTimeZoneString()
    {
        $tech_user = $this->session->userdata['spraye_technician_login'];
        $tech_user->company_id;
        $setting_details = $this->CompanyModel->getOneCompany(array('company_id'=>$tech_user->company_id));
        $companyTimeZoneString = $setting_details->time_zone;
        return $companyTimeZoneString;        
    }

}

