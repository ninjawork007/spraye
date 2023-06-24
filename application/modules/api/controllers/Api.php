<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;


class Api extends REST_Controller
{
      public $key;
      public $user;
      public $token;

      public function __construct() {
        parent::__construct();        
        
             $this->load->model('admin/Administrator');
             $this->load->model("Customer_model");
             $this->load->model("AdminTbl_property_model");
             $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
             $this->load->model("Estimate_model");

             
            $this->load->helper('invoice_helper');
            $this->load->helper('estimate_helper');


            $this->load->model("../modules/admin/models/AdminTbl_property_model", 'AdminTbl_property_model2');     
            $this->load->model('../modules/admin/models/Invoice_model', 'INV');
            $this->load->model('../modules/admin/models/Property_sales_tax_model', 'PropertySalesTax2');
            $this->load->model('../modules/admin/models/AdminTbl_customer_model', 'CustomerModel');
            $this->load->model('../modules/admin/models/AdminTbl_program_model', 'ProgramModel');
            $this->load->model('../modules/admin/models/Job_model', 'JobModel');            
            $this->load->model('../modules/admin/models/AdminTbl_company_model', 'CompanyModel');
            $this->load->model('../modules/admin/models/AdminTbl_coupon_model', 'CouponModel');
            $this->load->model('../modules/admin/models/Technician_model', 'Tech');
            $this->load->model('../modules/admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax');
            $this->load->model('../modules/admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
            $this->load->model("../modules/admin/models/Estimate_model", 'Estimate_model2');
            $this->load->model('../modules/admin/models/Company_email_model', 'CompanyEmail');
            $this->load->model('../modules/admin/models/Administratorsuper', 'Administratorsuper');
            $this->load->model("../modules/admin/models/Administrator", 'Administrator2');

            



             $this->key =  !empty($this->_head_args['Apikey']) ? $this->_head_args['Apikey'] : null; //'9643f202589a07f918063e44ce257151';
             $this->user =  $this->Administrator->getOneAdmin(array("user_id" => $this->key));
             
      }    

      public function login_post() {
        
          if(!empty($this->key)){
              if($this->user){
                  $this->response([
                      'status' => TRUE,
                      'message' => 'User login successful.',
                  ], REST_Controller::HTTP_OK);
              }else{
                  // Set the response and exit
                  //BAD_REQUEST (400) being the HTTP response code
                  $this->response("Invalid API key", REST_Controller::HTTP_BAD_REQUEST);
              }
          }else{
              // Set the response and exit
              $this->response("Provide an API key.", REST_Controller::HTTP_BAD_REQUEST);
          }
      }
      

      
      public function customer_get($id = 0) {
          $customer_id = $id ? $id :0;
          
          $customers = $this->Customer_model->getCustomerDetail($customer_id);
          
          // Check if the user data exists
          if(!empty($customers && $this->user)){
              // Set the response and exit
              //OK (200) being the HTTP response code
              $this->response($customers, REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'No user was found.'
              ], REST_Controller::HTTP_NOT_FOUND);
          }
      }
      
      public function customer_put($id = 0) {
          $first_name = !empty($this->put('first_name',true)) ? $this->put('first_name',true) : "";
          $last_name = !empty($this->put('last_name',true)) ? $this->put('last_name',true) : "";
          $email = !empty($this->put('email',true)) ? $this->put('email',true) : "";
          $customer_company_name = !empty($this->put('customer_company_name',true)) ? $this->put('customer_company_name',true) : "";
          $customer_status = !empty($this->put('customer_status',true)) ? $this->put('customer_status',true) : 1;
          $billing_street = !empty($this->put('billing_street',true)) ? $this->put('billing_street',true) : "";
          $billing_street_2 = !empty($this->put('billing_street_2',true))? $this->put('billing_street_2',true) : "";
          $billing_city = !empty($this->put('billing_city',true))? $this->put('billing_city',true) : "";
          $billing_state = !empty($this->put('billing_state',true))? $this->put('billing_state',true) : "";
          $billing_zipcode = !empty($this->put('billing_zipcode',true))? $this->put('billing_zipcode',true) : "";
          $phone = !empty($this->put('phone'))? $this->put('phone') : "";
          $property_id = $this->put('property_id');
          $clover_acct_id = !empty($this->put('clover_acct_id',true))? $this->put('clover_acct_id',true) : "";
          $customer_clover_token = !empty($this->put('customer_clover_token',true))? $this->put('customer_clover_token',true) : "";
          // Validate the post data
          if(!empty($first_name) || !empty($last_name) || !empty($email) || !empty($phone) || !empty($this->user)){
              // Update user's account data
                    
                    $customer = $this->Customer_model->insert_customer(['first_name' => $first_name,
                                                                                  'last_name' => $last_name,
                                                                                  'customer_company_name' => $customer_company_name,
                                                                                  'email' => $email,
                                                                                  'phone' => $phone,
                                                                                  'billing_street' => $billing_street,
                                                                                  'billing_street_2' => $billing_street_2,
                                                                                  'billing_city' => $billing_city,
                                                                                  'billing_state' => $billing_state,
                                                                                  'billing_zipcode' => $billing_zipcode,
                                                                                  'company_id' => $this->user->company_id,
                                                                                  'user_id' => $this->user->user_id,
                                                                                  'customer_status' => $customer_status,
                                                                                  'clover_acct_id' => $clover_acct_id,
                                                                                  'customer_clover_token' => $customer_clover_token
                                                                                  ]);
              //assign property_id to customer
              if($property_id && $customer){
                $this->Customer_model->assignProperty(['customer_id' => $customer, 'property_id' => $property_id]);
              }
          
              // Set the response and exit
              //OK (200) being the HTTP response code
              $this->response([
                  'status' => true,
                  'message' => 'Customer successfully created.',
                  'result' => $customer,
              ], REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'Customer could not be added.'
              ], REST_Controller::HTTP_NOT_FOUND);
          }
      }
      
      public function property_put($id = 0) {
        $property_title = !empty($this->put('property_title',true)) ? $this->put('property_title',true) : "";
        $property_area = !empty($this->put('property_area',true)) ? $this->put('property_area',true) : "";
        $property_status = !empty($this->put('property_status',true)) ? $this->put('property_status',true) : 1;
        $yard_square_feet = !empty($this->put('yard_square_feet',true)) ? $this->put('yard_square_feet',true) : "";
        $property_address = !empty($this->put('property_address',true)) ? $this->put('property_address',true) : "";
        $property_address_2 = !empty($this->put('property_address_2',true))? $this->put('property_address_2',true) : "";
        $property_city = !empty($this->put('property_city',true))? $this->put('property_city',true) : "";
        $property_state = !empty($this->put('property_state',true))? $this->put('property_state',true) : "";
        $property_zip = !empty($this->put('property_zip',true))? $this->put('property_zip',true) : "";
        $front_yard_grass = !empty($this->put('front_yard_grass'))? $this->put('front_yard_grass') : "";
        $back_yard_grass = !empty($this->put('back_yard_grass'))? $this->put('back_yard_grass') : "";
        $property_notes = !empty($this->put('property_notes'))? $this->put('property_notes') : "";
        $total_yard_grass = !empty($this->put('total_yard_grass'))? $this->put('total_yard_grass') : "";
        $source = !empty($this->put('source'))? $this->put('source') : "";
        $sales_tax_area = !empty($this->put('sales_tax_area'))? $this->put('sales_tax_area') : "";
        $property_longitude = !empty($this->put('property_longitude'))? $this->put('property_longitude') : "";
        $property_latitude = !empty($this->put('property_latitude'))? $this->put('property_latitude') : "";
        $customer_id = !empty($this->put('customer_id'))? $this->put('customer_id') : "";


        // Validate the post data
        if(!empty($property_title) && !empty($customer_id) && !empty($property_address) && !empty($yard_square_feet) && !empty($this->user)){
            // Update user's account data
            
            $property = $this->AdminTbl_property_model->insert_property(['property_title' => $property_title,
                                                                 'property_area' => $property_area,
                                                                 'yard_square_feet' => $yard_square_feet,
                                                                 'property_address' => $property_address,
                                                                 'property_address_2' => $property_address_2,
                                                                 'property_city' => $property_city,
                                                                 'property_state' => $property_state,
                                                                 'property_zip' => $property_zip,
                                                                 'front_yard_grass' => $front_yard_grass,
                                                                 'back_yard_grass' => $back_yard_grass,
                                                                 'total_yard_grass' => $total_yard_grass,
                                                                 'property_notes' => $property_notes,
                                                                 'property_status' => $property_status,
                                                                 'user_id' => $this->user->user_id,
                                                                 'company_id' => $this->user->company_id,
                                                                 'source' => $source,
                                                                 'property_longitude' => $property_longitude,
                                                                 'property_latitude' => $property_latitude,
                                                                 'property_type' => 'Residential',
                                                                 'available_days' => '{"fri": true, "mon": true, "sat": true, "sun": true, "thu": true, "tue": true, "wed": true}'
                                                                ]);





            $propertySalesTax = $this->PropertySalesTax->CreateOnePropertySalesTax(['property_id' => $property,
                                                                            'sale_tax_area_id' => $sales_tax_area
                                                                        ]);


                                                                       // assign property to customer
                                                                         $assign_cust_params = array(
                                                                            'property_id' => $property,
                                                                            'customer_id' => $customer_id
                                            
                                                                        );
            $assignCustomerResult = $this->AdminTbl_property_model->assignCustomer($assign_cust_params);



            
            
            $param = ['tags' => '1'];
    
            
            $this->AdminTbl_property_model->updateAdminTblZap($property, $param);



        
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response([
                'status' => true,
                'message' => 'Property successfully created.',
                'result' => $property,
            ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'Property could not be added.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

      public function property_post(){

          $property_title = !empty($this->post('property_title',true)) ? $this->post('property_title',true) : "";
          $address = !empty($this->post('address',true)) ? $this->post('address',true) : "";

          $params = ['property_tbl.user_id' => $this->user->user_id];
          if($property_title){
            $params['property_tbl.property_title'] = $property_title;
          }
          if($address){
            $params['property_tbl.property_address'] = $address;
          }

          
          $property = $this->AdminTbl_property_model->get_all_property($params);
          
          // Check if the user data exists
          if(!empty($property && $this->user)){
              // Set the response and exit
              //OK (200) being the HTTP response code
              $this->response($property, REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'No property was found.'
              ], REST_Controller::HTTP_NOT_FOUND);
          }
      }
      public function estimate_post(){

          $estimate_id = !empty($this->post('estimate_id',true)) ? $this->post('estimate_id',true) : "";

          $params = ['customers.user_id' => $this->user->user_id];

          if($estimate_id){
            $params['t_estimate.estimate_id'] = $estimate_id;
          }
          $estimate = $this->Estimate_model->getAllEstimate($params);
          
          // Check if the user data exists
          if(!empty($estimate) && !empty($this->user)){
              // Set the response and exit
              //OK (200) being the HTTP response code
              $this->response($estimate, REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'No estimate was found.'
              ], REST_Controller::HTTP_NOT_FOUND);
          }
      }




      public function estimate_put(){

          //$estimate_date = !empty($this->post('estimate_date',true)) ? $this->post('estimate_date',true) : "";
          // $params = ['property_tbl.user_id' => $this->user->user_id];
          // if($property_title){
          //   $params['property_tbl.property_title'] = $property_title;
          // }
          // 
          // $property = $this->AdminTbl_property_model->get_all_property($params);



          /*
          //for postman:
          $data = file_get_contents('php://input');

          $jsonData = json_decode($data, true); // Convert JSON to associative array


          $property_id = !empty($jsonData['property_id']) ? $jsonData['property_id'] : "";
          $estimate_date = !empty($jsonData['estimate_date']) ? $jsonData['estimate_date'] : ""; 
          
          $sales_rep = !empty($jsonData['sales_rep']) ? $jsonData['sales_rep'] : "";
                  
          $customer_id = !empty($jsonData['customer_id']) ? $jsonData['customer_id'] : "";

          $notes = !empty($jsonData['notes']) ? $jsonData['notes'] : "";          
          $signwell_status = !empty($jsonData['signwell_status']) ? $jsonData['signwell_status'] : ""; 
          $status = !empty($jsonData['status']) ? $jsonData['status'] : "";  

          $property_status = !empty($jsonData['property_status']) ? $jsonData['property_status'] : ""; 

          $program_pricing = !empty($jsonData['program_pricing']) ? $jsonData['program_pricing'] : "";
     

          $source = !empty($jsonData['source']) ? $jsonData['source'] : "";
       

          $service_id = !empty($jsonData['service_id']) ? $jsonData['service_id'] : [];
        

          $email_notes = !empty($jsonData['email_notes']) ? $jsonData['email_notes'] : "";
       

          $coupon_id = !empty($jsonData['coupon_id']) ? $jsonData['coupon_id'] : "";
     

          $program_price = !empty($jsonData['program_price']) ? $jsonData['program_price'] : "";
     

          $jobIds = !empty($jsonData['job_id']) ? $jsonData['job_id'] : [];
         

          $program_id = !empty($jsonData['program_id']) ? $jsonData['program_id'] : [];
      

          $price_override = !empty($jsonData['price_override']) ? $jsonData['price_override'] : "";
       

          */


          //for zapier:
          
          $property_id = !empty($this->put('property_id',true)) ? $this->put('property_id',true) : "";
          $estimate_date = !empty($this->put('estimate_date',true)) ? $this->put('estimate_date',true) : "";
          //$pricing = !empty($this->put('pricing',true)) ? $this->put('pricing',true) : 1;
          //$customer_message = !empty($this->put('customer_message',true)) ? $this->put('customer_message',true) : "";
          //$coupon = !empty($this->put('coupon',true)) ? $this->put('coupon',true) : "";          
          $sales_rep = !empty($this->put('sales_rep',true))? $this->put('sales_rep',true) : "";
          //$service = !empty($this->put('service',true))? $this->put('service',true) : "";
          //$company_id = !empty($this->put('company_id',true))? $this->put('company_id',true) : "";
          $customer_id = !empty($this->put('customer_id',true))? $this->put('customer_id',true) : "";
          $notes = !empty($this->put('notes',true))? $this->put('notes',true) : "";
         // $basys_transaction_id = !empty($this->put('basys_transaction_id',true))? $this->put('basys_transaction_id',true) : "";
          //$signwell_id = !empty($this->put('signwell_id',true))? $this->put('signwell_id',true) : "";
          $signwell_status = !empty($this->put('signwell_status',true))? $this->put('signwell_status',true) : "";
          $status = !empty($this->put('status',true))? $this->put('status',true) : "";
          $property_status = !empty($this->put('property_status',true))? $this->put('property_status',true) : "";
          $program_pricing = !empty($this->put('program_pricing',true))? $this->put('program_pricing',true) : "";
          $source = !empty($this->put('source',true))? $this->put('source',true) : "";
          $service_id = !empty($this->put('service_id',true))? $this->put('service_id',true) : [];
          $email_notes = !empty($this->put('email_notes',true))? $this->put('email_notes',true) : "";
          $coupon_id = !empty($this->put('coupon_id',true))? $this->put('coupon_id',true) : [];
          $program_price = !empty($this->put('program_price',true))? $this->put('program_price',true) : "";
          $jobIds = !empty($this->put('job_id',true))? $this->put('job_id',true) : [];
          $program_id = !empty($this->put('program_id',true))? $this->put('program_id',true) : [];
          $price_override = !empty($this->put('price_override',true))? $this->put('price_override',true) : [];


          

          if( !is_array($coupon_id) ) {           
            $temp5 = array();
            $temp5[] = $coupon_id;
            $coupon_id = $temp5;
        }

        


        
         // only need if setting up zapier for user to enter comma seperated values. curreently builds array.  - also needed for postman
         /*
        if( strpos($service_id, ',') !== false ) {
            $service_id = explode(',',$service_id);
        }else{
            $temp = array();
            $temp[] = $service_id;
            $service_id = $temp;
        }

        if( strpos($program_id, ',') !== false ) {
            $program_id = explode(',',$program_id);
        }else{
            $temp2 = array();
            $temp2[] = $program_id;
            $program_id = $temp2;
        }




        if( strpos($jobIds, ',') !== false ) {
            $jobIds = explode(',',$jobIds);
        }else{
            $temp3 = array();
            $temp3[] = $jobIds;
            $jobIds = $temp3;
        }


        if( strpos($price_override, ',') !== false ) {
            $price_override = explode(',',$price_override);
        }else{
            $temp4 = array();
            $temp4[] = $price_override;
            $price_override = $temp4;
        }

        

          //remove above for zapier
        */



         
          

          $property_status = strtolower($property_status);

          switch ($property_status) {
            case "non-active":
                $property_status = '0';
              break;

            case "active":
                $property_status = '1';
              break;
            
            case "prospect":
                $property_status = '2';
              break; 

            default:
              $property_status = $property_status;
          }


          
          $status = strtolower($status);
          switch ($status) {
            case "draft":
                $status = '0';
              break;

            case "sent":
                $status = '1';
              break;
            
            case "accepted":
                $status = '2';
              break;
              
              case "paid":
                $status = '3';
              break;

            case "declined":
                $status = '5';
              break;

            default:
              $status = $status;
          }
         

          $company_id = $this->CustomerModel->getCompanyForCustomer($customer_id);
          //$company_id = $company_id[0]->company_id;
          $custData = $this->Customer_model->getOneCustomer(['customer_id' => $customer_id]);
          
            $propArray = array();
            $prop = new stdClass;     
            $prop->property_id = $property_id;
            $prop->customer_id =  $customer_id;
            $prop->customer_email = $custData->email;

            $propArray[] = $prop;



            $priceoverridearray = array();           
            $priceoverrideObj = new stdClass;     
            $priceoverrideObj->propertyId = $property_id;
            $priceoverrideObj->price_override =  $price_override; //needs to be an array
            $priceoverrideObj->job_id =  $jobIds;

            
            $priceoverridearray[] = $priceoverrideObj;


           


            //$listarray = array();
            $listarray = new stdClass;  
            
            $tempItem = array();

            $length = count($program_id);
//double check code from estiates. this seems to be missing something
          for($i = 0; $i < $length; $i++){
              $programsListItem = new stdClass;

              $programsListItem->program_id = $program_id[$i];
              if(array_key_exists($i, $jobIds)){
                $programsListItem->program_jobs = $jobIds[$i];
              }else{
                $programsListItem->program_jobs = "";
              }
              
              $tempItem[] = $programsListItem;
          }
                    

          $listarray->programs = $tempItem;
          //$listItem->programs->programsListItem = $tempItem;




          $listarray->services = array();
          $length2 = count($jobIds);

          for($i=0; $i < $length2; $i++){

            $job_id_obj = new stdClass;
            $job_id_obj->job_id = $jobIds[$i];

            $listarray->services[] = $job_id_obj;
          }
                                

          //$listarray[] = $listItem;




           
          // Validate the post data
          if(!empty($property_id) && !empty($program_id) && !empty($customer_id) && !empty($this->user)){
                // Update user's account data
                if($estimate_date){
                    $param = array(
                        'company_id' => $company_id,
                        'customer_id' => $customer_id,
                        'property_id' => $property_id,
                        'estimate_date' => $estimate_date,
                        'program_id' => $program_id,
                        'status' => $status,
                        'property_status' => $property_status,
                        'sales_rep' => $sales_rep,
                        'estimate_created_date' => date("Y-m-d H:i:s"),
                        'estimate_update' => date("Y-m-d H:i:s"),
                        'notes' => $notes,
                        'source' => $source,
                        'program_pricing' => $program_pricing,
                        'signwell_status' => $signwell_status,
                        'service_id' => $service_id,
                        'email_notes'=> $email_notes,
                        'property_data_array'=> $propArray,
                        'assign_onetime_coupons'=> $coupon_id,
                        'estimate_date_submit'=> date("Y-m-d"),
                        'program_price'=> $program_price,
                        'priceoverridearray'=> $priceoverridearray,
                        'listarray'=> $listarray

                    );
                }else{
                    $param = array(
                        'company_id' => $company_id,
                        'customer_id' => $customer_id,
                        'property_id' => $property_id,
                        'estimate_date' => date("Y-m-d"),
                        'program_id' => $program_id,
                        'status' => $status,
                        'property_status' => $property_status,
                        'sales_rep' => $sales_rep,
                        'estimate_created_date' => date("Y-m-d H:i:s"),
                        'estimate_update' => date("Y-m-d H:i:s"),
                        'notes' => $notes,
                        'source' => $source,
                        'program_pricing' => $program_pricing,
                        'signwell_status' => $signwell_status,
                        'service_id' => $service_id,
                        'email_notes'=> $email_notes,
                        'property_data_array'=> $propArray, 
                        'assign_onetime_coupons'=> $coupon_id,
                        'estimate_date_submit'=> date("Y-m-d"),
                        'program_price'=> $program_price,
                        'priceoverridearray'=> $priceoverridearray,
                        'listarray'=> $listarray                                  
                        
                    );
                }
                
           

                 //line 945 add estimate -  $listarray                        
                       

                 

                $result = $this->addBulkEstimateData($param);


              //$result = $this->Estimate_model->CreateOneEstimate($param);


             // $estimate_response = $this->addEstimateData($param, true, $job_id_to_program,$program_ids_for_join_table); 
             // $this->Estimate_model2->CreateEstimatePrograms($program_ids_for_join_table,$estimate_response['estimate_id'], $job_ids_for_program_join); 
              



            }

        
          
        
            
          
          // Check if the user data exists
          if(!empty($this->user) && !empty($result)){
                $this->response([
                  'status' => true,
                  'message' => 'Estimate successfully created.',
                  'result' => ['Estimate ID' => $result]
              ], REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'Estimate could not be created.'
              ], REST_Controller::HTTP_NOT_FOUND);
          }
      }


























      public function addBulkEstimateData($data = null){

        $backendCall = (isset($data)) ? true : false;
        $tmpData = (isset($data)) ? $data : null; //$this->input->post();
        
        // die(print_r($tmpData));
        $company_id = $this->user->company_id;
        $user_id = $this->user->user_id;
        
        $tmpData['company_id'] = $company_id;
        $tmpData['user_id'] = $user_id;
        // $test = $this->ProgramModel->getSelectedJobsAnother('1038');
        // Parse the JSON strings

        

        $property_array = $tmpData['property_data_array'];
        $listarray = $tmpData['listarray'];

        

        $programs = $listarray->programs;
        // going to need all the program_ids from these programs as well as the new ones we are going to make below - so start that array here
        $program_ids_for_join_table = $job_ids_for_program_join = array();
         
        foreach($programs as $pro) {
          $program_ids_for_join_table[$pro->program_id] = 0;
        }
        $services = $listarray->services; 
        $priceoverridearray = (is_array($tmpData['priceoverridearray'])) ? $tmpData['priceoverridearray'] : json_decode($tmpData['priceoverridearray']);
        
        
        //return print_r($priceoverridearray,true); 
        


        $unmodifiedProgram = false;
        
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
      
          $program_price = $tmpData['program_price'];
       
        $or = (array)[];
        $price_overrides = (array)[];
        foreach ($priceoverridearray as $ovr){
          $tmp = (object)[];
          $tmp->propertyId = $ovr->propertyId;
          $tmp->price_override = $ovr->price_override;
          $tmp->program_jobs = $ovr->job_id;
          array_push($or,$tmp);
        }

          
        
//return print_r($or[0]->price_override,true);
 
        //die(print_r(json_encode($or)));
        foreach($or as $o){
           
          $tmp = (object)[];
          $tmp->propertyId = $o->propertyId;
 
          //error in the loop below

          for($i=0; $i<count($o->price_override); $i++){
           
            $tmp = (object)[];
            $tmp->propertyId = $o->propertyId;            
            if(is_array($o->program_jobs)){
                if(array_key_exists($i, $o->program_jobs)){
                    $tmp->job_id = $o->program_jobs[$i];
                }else{
                    $tmp->job_id = "";
                }
                
            }else{
                $tmp->job_id = "";
            }
 
            
            
            if(is_array($o->price_override)){
                 
                if(array_key_exists($i, $o->price_override)){
                    $tmp->price_override = ($o->price_override[$i] != '') ? $o->price_override[$i] : null;
                }else{
                    $tmp->price_override = "";
                }
                
            }else{
                $tmp->price_override = "";
            }
                     
            //$tmp->price_override = ($o->price_override[$i] != '') ? $o->price_override[$i] : null;
            $tmp->is_price_override_set = ($tmp->price_override != '') ? 1 : null;
            array_push($price_overrides, $tmp);
            
            
          }
           

        }

       
        // die(print_r(json_encode($price_overrides)));
        $jobsAll = $job_id_to_program = array();
        //var_dump($programs);

       
        

        foreach($programs as $program){

            if(is_array($program->program_jobs)){
                $jobsAll = array_unique(array_merge($jobsAll,$program->program_jobs));
                         
           
                foreach($program->program_jobs as $p) {
                     $job_id_to_program[$p] = $program->program_id;
                }
            }
        }


        


        foreach($services as $service){
          $this_service_info = $this->JobModel->getOneJob(array('job_id' => $service->job_id));
          $programData = array();
          $programData['company_id'] = $company_id;
          $programData['user_id'] = $user_id;
          $programData['program_name'] = $this_service_info->job_name;
          $programData['jobs_all'] = array($this_service_info->job_id);
          $programData['program_price'] = $program_price;  
          $programResults = $this->createModifiedBundledProgram($programData);
          $job_id_to_program[$this_service_info->job_id] = $programResults["program_id"];
          $program_ids_for_join_table[$programResults["program_id"]] = 1;
          $job_ids_for_program_join[$programResults["program_id"]] = $this_service_info->job_id;
          $jobsAll = array_unique(array_merge($jobsAll,array($service->job_id)));
        }
        $data = (array)[];
       
        foreach($property_array as $property){
          $tmp = json_decode(json_encode(clone $property), true);
          $tmp['estimate_date'] = $tmpData['estimate_date'];
          $tmp['estimate_date_submit'] = $tmpData['estimate_date_submit'];
          $tmp['status'] = $tmpData['status'];
          $tmp["signwell_status"] = $tmpData["signwell_status"];
          $tmp['notes'] = $tmpData['notes'];
          $tmp['email_notes'] = $tmpData['email_notes'];
          $tmp["source"] = $tmpData["source"];
          $tmp["program_pricing"] = $tmpData["program_pricing"];
          // $tmp['property_status'] = $tmpData['property_status'];
          $tmp['sales_rep'] = $tmpData['sales_rep'];
          //$tmp['program_id'] = $programResults['program_id'];
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
                if($tmp['property_id'] == $price_override->propertyId && $tmpJob->job_id == $price_override->job_id)
                {
                  $tmpJob->price_override = $price_override->price_override;
                  $tmpJob->is_price_override_set = 1;
                }
              }
            }
            $tmpJob->is_price_override_set = (isset($tmpJob->price_override)) ? $tmpJob->is_price_override_set : null;
            $tmpJob->price_override = (isset($tmpJob->price_override)) ? $tmpJob->price_override : "";
      
            array_push($tmp['joblistarray'], $tmpJob);
          }

          
          // --> Insert Property Program Job Price Overrides Function HERE <-- 
          $priceOverrideResults = (array)[];
          foreach($tmp['joblistarray'] as $jobOverride){
            $arr = array(
              'program_id' => $job_id_to_program[$jobOverride->job_id],
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
        $company_id = $this->user->company_id;
          $user_id = $this->user->user_id;
          $where = array('company_id' =>$this->user->company_id);
         
          $userCompany = $this->CompanyModel->getOneCompany($where);

    
        $estimate_response = 'true';
        
        //return print_r($data,true);


        // die(print_r($data));
        // die(print_r(json_encode($data)));
       // $return_messages = (array)[];

       

        foreach($data as $submission){
            
              $estimate_response = $this->addEstimateData($submission, true, $job_id_to_program,$program_ids_for_join_table);
              //$message = $estimate_response['message'];
              //@TODO
              //here's where we need to stop creating new programs, 
              //instead get program ids and jobs ids for this estimate
              //
              
              
             $this->Estimate_model->CreateEstimatePrograms($program_ids_for_join_table,$estimate_response, $job_ids_for_program_join);
             
            
           
        }
        if($backendCall == true){
          return $estimate_response;
        } else {
            return $estimate_response;
        }

      }























      public function createModifiedBundledProgram($data)
{

  //create new ad_hoc program based on selected program id
    $newProgram = array(
        'user_id' => $data['user_id'],
        'company_id' => $data['company_id'],
        'program_name' => $data['program_name'],
        'program_price' => $data['program_price'],
        'ad_hoc' => 1
  );

  $program_id = $this->ProgramModel->insert_program($newProgram);

  $program_jobs = $data['jobs_all'];
  foreach($program_jobs as $pj) {
    //Assign jobs to program
    $programJob = array(
        'program_id' => $program_id,
        'job_id' => $pj,
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






























      public function addEstimateData($data = null, $bulk_call = false, $job_id_to_program = array(), $programs_added_to_estimate = array()) {
        
       

          //$data = (isset($data)) ? $data : $this->input->post();
      
          $company_id = $this->user->company_id;
          $user_id = $this->user->user_id;
          $where = array('company_id' =>$this->user->company_id);
         
          $userCompany = $this->CompanyModel->getOneCompany($where);


      
          // we need to see if there is already an estimate for this person with this program and if there is we do not create a new estimate
          // get all estimates that are tied to this customer
          $estimate_ids_for_this_customer = $this->Estimate_model->getAllNotAcceptedEstimateIdsByCustomer(array("customer_id"=>$data['customer_id']));
          $estimate_ids_to_check = array();
          $all_new_programs = true;
          foreach($estimate_ids_for_this_customer as $eif) {
              $estimate_ids_to_check[] = $eif->estimate_id;
          }
          if(!empty($estimate_ids_to_check)) {
              $programs_on_estimate_for_customer = $this->Estimate_model->getAllJoinedProgramsForAllEstimates($estimate_ids_to_check);
              $programs_already_on_estimates = array();
              foreach($programs_on_estimate_for_customer as $poe) {
                  $programs_already_on_estimates[] = $poe->program_id;
              }
              foreach($programs_already_on_estimates as $paoe) {
                  if(in_array($paoe, array_keys($programs_added_to_estimate))) {
                      $all_new_programs = false;
                      $estimate_id = false;
                  }
              }
          }

          
          if($all_new_programs == true) {
              $param = array(
                  'company_id' => $company_id,
                  'customer_id' => $data['customer_id'],
                  'property_id' => $data['property_id'],
                  'estimate_date' => $data['estimate_date'],
                  //'program_id' => $program_id,
                  'status' => $data['status'],
                  // 'property_status' => $data['property_status'],
                  'sales_rep' => $data['sales_rep'],
                  'estimate_created_date' => date("Y-m-d H:i:s"),
                  'estimate_update' => date("Y-m-d H:i:s"),
                  'notes' => $data['notes'],
                  'source' => $data['source'],
                  'signwell_status' => $data['signwell_status'],
                  'program_pricing' => $data['program_pricing'],
              );
              $estimate_id = $this->Estimate_model->CreateOneEstimate($param);
          }
     
          if($estimate_id){
              if(isset($data['joblistarray']) && !empty($data['joblistarray'])) {
                  foreach (json_decode($data['joblistarray']) as $value) {
                      $param3 = array(
                          'estimate_id' => $estimate_id,
                          'customer_id' => $data['customer_id'],
                          'property_id' => $data['property_id'],
                          'program_id' => $job_id_to_program[$value->job_id],
                          'job_id' => $value->job_id,
                          'price_override' => $value->price_override,
                          'is_price_override_set' => $value->is_price_override_set,
                          'created_at' => date("Y-m-d H:i:s")
                      );
                      $this->Estimate_model->CreateOneEstimatePriceOverRide($param3);
                  }
              }
      
              // apply assigned coupons
              if (array_key_exists("assign_onetime_coupons",$data)) {
                    if(is_array($data['assign_onetime_coupons'])){
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
      
              ##### Creating an internal note #####
              $dataNotes = array(
                  'note_property_id' => $data['property_id'],
                  'note_category' => 0,
                  'note_type' => 4,
                  'note_assigned_user' => $data['sales_rep'],
                  'note_due_date' => date("Y-m-d H:i:s"),
                  'note_due_date_submit' => date("Y-m-d H:i:s"),
                  'include_in_tech_view' => 1,
                  'note_contents' => 'Sales Call has been assigned to you.',
              );
             $this->createNote($dataNotes);

             //return print_r( $data ,true);


              $response_object = json_decode('');
              if ($data['status']==1 && $data['customer_email']!='' ){
                  // if are are in here then we also need to check for signwell being set - if its set we dont want to send the email this way but instead send it through signwell
                
                  $company_id = $this->user->company_id;
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
                                      "decline_redirect_url": "'.base_url('welcome/set_signwell_estimate_rejected/'.$estimate_id).'",
                                      "message": "'.nl2br($data['email_notes']).'"
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
                          $this->Estimate_model->updateEstimateSignWellID($estimate_id, $response_object->id);
                      }
                  } else {
                      if(isset($data['email_notes']) && $data['email_notes'] != ''){
                          $email_data['msgtext'] = $data['email_notes'];
                      }else{
                          $email_data['msgtext'] = '';
                      }
                      // $data['company_details'] = $this->CompanyModel->getOneCompany($where_company);
                      $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
                      $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);
                      $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;
                      $body = $this->load->view('../modules/admin/views/estimate/estimate_email',$email_data,true);
                      $where_company['is_smtp'] = 1;
                      $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                      if (!$company_email_details) {
                          $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                      }
                      $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $userCompany->company_name, "email" => $userCompany->company_email),  $body, 'Estimate Details',$email_data['customer_details']->secondary_email);
      
                      if($data['sales_rep'] != ''){
      
                          $rep_data['sale_rep'] = $this->Administrator2->getOneAdmin(array('id' => $data['sales_rep']));
                          // die(print_r($rep_data));
                          $body = $this->load->view('../modules/admin/views/estimate/assigned_email',$rep_data,true);
                          $rep =   Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email,array("name" => $userCompany->company_name, "email" => $userCompany->company_email),  $body, 'Estimate Assigned',$email_data['customer_details']->secondary_email);
                          // die(print_r($rep));
                      }
                  }
                   
              }    
              
          } 

          return $estimate_id;
        
      }
      


















      





      public function createNote($data = NULL){


        $company_id = $this->user->company_id;
        $user_id = $this->user->user_id;
        $where = array('company_id' =>$this->user->company_id);
       
        $userCompany = $this->CompanyModel->getOneCompany($where);


        //$data = (empty($data)) ? $this->input->post() : $data;
        // die(print_r($data));
       // $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if($data['note_property_id'] == 0){
            $data['note_property_id'] = NULL;
        }

      if(!empty($data)){  //added this check after getting rid of using post as an option
        
        if(!empty($data['note_contents']) && $data['note_contents'] != ''){
            $params = array(
                'note_user_id' => $this->user->user_id,
                'note_company_id' => $this->user->company_id,
                'note_category' => (isset($data['note_property_id'])) ? 0 : 1,
                'note_property_id' => $data['note_property_id'] ?? NULL,
                'note_customer_id' => $data['note_customer_id'] ?? NULL,
                'note_contents' => nl2br($data['note_contents']),
                'note_due_date' => $data['note_due_date'] ?? NULL,
                'note_assigned_user' => $data['note_assigned_user'],
                'note_type' => $data['note_type'] ?? 0,
                'include_in_tech_view' => (isset($data['include_in_tech_view'])) ? 1 : 0,
            );
  
            
  
            if($data['note_category'] == 2)
            {
            $params['note_category'] = 2;
            }
            $noteId = $this->CompanyModel->addNote($params);
            if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) 
            {
                $fileStatusMsg = $this->addNoteFiles($noteId);
            }
            // if($noteId && isset($fileStatusMsg) && $fileStatusMsg){
                
            if($noteId){
  
              if(!empty($params['note_assigned_user'])){
                $note_creator = $this->Administrator->getOneAdmin(array('user_id' => $params['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($params['note_type']);
                

                                

                $email_array = array(
                'note_creator' => $note_creator->user_first_name.' '.$note_creator->user_last_name,
                'note_type' => $note_type,
                'note_due_date' => $params['note_due_date'] ?? 'None',
                'note_contents' => $params['note_contents']
                );
                
                
                $where = array('company_id' => $this->user->company_id);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);
  
                $subject =  'New Note Assignment';
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                $note_assigned_user = $this->Administrator2->getOneAdmin(array('id' => $params['note_assigned_user']));
                $email_array['name'] = $note_assigned_user->user_first_name.' '.$note_assigned_user->user_last_name;
                // die(print_r(json_encode($email_array)));
                $body  = $this->load->view('../modules/admin/views/email/note_email',$email_array,TRUE);
                //left off
                $res =   Send_Mail_dynamic( $company_email_details, $note_assigned_user->email, array("name" => $userCompany->company_name, "email" => $userCompany->company_email),  $body, $subject);
             
            }
  
    
  
            //     $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>');
            //     redirect($referer_path);
            // } else {
            //     $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> not added.</div>');
            //     redirect($referer_path);
            }
            
        } else {
           // $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went really <strong>WRONG!</strong></div>');
           // redirect($referer_path);
        }
        
      }
    }





















      public function calculateCustomerCouponCost($param){
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






    public function createInvoiceInQuickBook($param)
    {


        $company_details = $this->checkQuickbook();

        if ($company_details) {

            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
            ));

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $dataService->throwExceptionOnError(true);
            //Add a new Invoice

            // var_dump($param);
            // die();

            $details = getVisIpAddr();

            $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $param['invoice_id']));

            $description = 'Service Name: ' . $param['job_name'] . '. Service Description: ' . $param['actual_description_for_QBO'];

            $line_ar[] = array(
                "Description" => $description,
                "Amount" => $param['cost'],
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => array(
                    "TaxCodeRef" => array(
                        "value" =>  $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX'
                        // "value" =>  'TAX'
                    )
                )
            );

            if ($all_sales_tax) {

                foreach ($all_sales_tax as $key => $value) {
                    $line_ar[] = array(
                        "Description" =>  'Sales Tax: ' . $value['tax_name'] . ' (' . floatval($value['tax_value']) . '%) ',
                        "Amount" => $value['tax_amount'],
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => array(
                            "TaxCodeRef" => array(
                                "value" =>  $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX'
                                // "value" =>  'TAX'
                            )
                        )
                    );
                }
            }


            $invoice_arr = array(
                "AllowOnlineCreditCardPayment" => true,
                "DocNumber" => $param['invoice_id'],
                "TxnDate" => $param['invoice_date'],
                "Line" => $line_ar,
                "CustomerRef" => array(
                    "value" => $param['quickbook_customer_id'],
                )
            );

            if ($param['email'] != '') {

                $invoice_arr['BillEmail'] = array(
                    "Address" => $param['email']
                );
                $invoice_arr['EmailStatus'] = "NeedToSend";
            }


            $theResourceObj = Invoice::create($invoice_arr);

            $resultingObj = $dataService->Add($theResourceObj);


            $error = $dataService->getLastError();
            if ($error) {
                $return_error = '';
                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                return array('status' => 400, 'msg' => 'Invoice not added successfully', 'result' => $return_error);
            } else {

                return array('status' => 201, 'msg' => 'Invoice added successfully', 'result' => $resultingObj->Id);
            }
        } else {

            return array('status' => 400, 'msg' => 'please intigrate quickbook account', 'result' => '');
        }
    }







    public function QuickBookInv($param = array())
    {


        $customer_details = $this->CustomerModel->getCustomerDetail($param['customer_id']);

        if ($customer_details['quickbook_customer_id'] != 0) {
            $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);

            if ($quickBookCustomerDetails) {
                $param['quickbook_customer_id'] = $customer_details['quickbook_customer_id'];

                $result = $this->createInvoiceInQuickBook($param);
                if ($result['status'] == 201) {
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





    public function getOneQuickBookCustomer($quickbook_customer_id)
    {

        $company_details = $this->checkQuickbook();
        if ($company_details) {

            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
            ));

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $entities = $dataService->Query("SELECT * FROM Customer where Id='" . $quickbook_customer_id . "'");
            $error = $dataService->getLastError();
            if ($error) {
                $return_error = '';
                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                return   false;
            } else {

                if (!empty($entities)) {

                    $theCustomer = reset($entities);
                    return $theCustomer;
                } else {
                    return false;
                }
            }
        } else {

            return false;
        }
    }








 public function checkQuickbook()
    {
        $where = array(
            'company_id' => $this->user->company_id, 
            'is_quickbook' => 1,
            'quickbook_status' => 1
        );

        $company_details = $this->CompanyModel->getOneCompany($where);

        if ($company_details) {


            try {


                $oauth2LoginHelper = new OAuth2LoginHelper($company_details->quickbook_client_id, $company_details->quickbook_client_secret);  // clint id , clint sceter
                $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($company_details->refresh_token_key);
                $accessTokenValue = $accessTokenObj->getAccessToken();
                $refreshTokenValue = $accessTokenObj->getRefreshToken();

                $post_data = array(
                    'access_token_key' => $accessTokenValue,
                    'refresh_token_key' => $refreshTokenValue,


                );

                $this->CompanyModel->updateCompany($where, $post_data);

                $company_details->access_token_key = $accessTokenValue;

                $company_details->refresh_token_key = $refreshTokenValue;

                return $company_details;
            } catch (Exception $ex) {
                return false;
            }
        } else {
            return false;
        }
    }










    public function calculateServiceCouponCost($param){
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







    





public function assignprogramfromzapier_post(){

            $property_id = !empty($this->post('property_id',true)) ? $this->post('property_id',true) : "";         
            $program_id = !empty($this->post('program_id',true))? $this->post('program_id',true) : "";
            $price_override = !empty($this->post('price_override',true))? $this->post('price_override',true) : "";
            $discount_code = !empty($this->post('discount_code',true))? $this->post('discount_code',true) : "";
            
            
          
          // Validate the post data
    if(!empty($property_id) && !empty($program_id) && !empty($this->user)){ 
        if(!empty($discount_code)){
              
                        

                        if($price_override){
                            $param = array(
                                'program_id' => $program_id,
                                'property_id' => $property_id,
                                'price_override' => $price_override,
                                'is_price_override_set' => 1
                                //'discount_code' => $discount_code
                            );                            
                        }else{
                            $param = array(
                                'program_id' => $program_id,
                                'property_id' => $property_id//,
                                //'price_override' => $price_override//,
                                //'discount_code' => $discount_code
                            );
                        }
                        $result = $this->AdminTbl_property_model->assignProgram($param);
                    
                        $this->AdminTbl_property_model2->autoStatusCheck(0,$property_id);

                        $value = $property_id;

                    $program = array();
                    $program['properties'] = array();
                    $program['properties'][$value] = array(
                        'program_property_id' => $result,
                      );

                    
                    //$value2 = "";
                    //foreach ($customer_property_details as $key2 => $value2) {
                   //     $value2 = $this->CustomerModel->getAllProperty(array('customer_property_assign.property_id' => $property_id));
                    //}

                    
                    $prog_details = $this->ProgramModel->getProgramDetail($program_id);


                    
                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->user->company_id)); 




                    $jobs = $this->ProgramModel->getSelectedJobs($program_id);

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
                          $est_cost = 0;
                          $QBO_cost = 0;

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
                                        'program_id' => $program_id,
                                        'customer_id' => $value2->customer_id
                                        );

                                        //CALCULATE JOB COST 
                                        if(false){
                                            $cost = $price_override; 
                                        }else{

                                        //check for price overrides
                                        $estimate_price_override =   GetOneEstimateJobPriceOverride($where2);
                                        if ($estimate_price_override) {
                                        $cost =  $estimate_price_override->price_override;
                                        
                                        $est_coup_param = array(
                                            'cost' => $cost,
                                            'estimate_id' => $estimate_price_override->estimate_id
                                        );

                                        $est_cost =  $this->calculateEstimateCouponCost($est_coup_param);



                                        } else {
                                
                               

                                            $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

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



                                    }






                                                $total_cost += $cost;
                                                $ppjobinv[] = array(
                                                'customer_id' => $value2->customer_id,
                                                'property_id' => $property_id,
                                                'program_id' => $program_id,
                                                'job_id' => $job_id,
                                                'cost' => $cost,
                                                );

                                                if($est_cost != 0){
                                                    $job_coup_param = array(
                                                        'customer_id' => $value2->customer_id,
                                                        'property_id' => $property_id,
                                                        'program_id' => $program_id,
                                                        'cost' => $est_cost,
                                                        'job_id' => $job_id
                                                    );
                                
                                                    $QBO_cost = $this->calculateServiceCouponCost($job_coup_param);
                                                } else {
                                                    $job_coup_param = array(
                                                        'customer_id' => $value2->customer_id,
                                                        'property_id' => $property_id,
                                                        'program_id' => $program_id,
                                                        'cost' => $cost,
                                                        'job_id' => $job_id
                                                    );
                                
                                                    $QBO_cost = $this->calculateServiceCouponCost($job_coup_param);
                                                }
                    } 
                       

                                    //format invoice data
                                    $param =  array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'user_id' => $this->user->user_id,  
                                        'company_id' => $this->user->company_id,  
                                        'invoice_date' => date("Y-m-d"),
                                        'description' => $prog_details['program_notes'],
                                        'cost' => ($total_cost),
                                        'is_created' => 2,
                                        'invoice_created' => date("Y-m-d H:i:s"),
                                    );

                                    $invoice_id = $this->INV->createOneInvoice($param);

									  //if invoice id
									if ($invoice_id) {
                                            $param['invoice_id'] = $invoice_id;
                                            //figure tax	
                                            if ($setting_details->is_sales_tax == 1) {
                                                
                                                $property_assign_tax = $this->PropertySalesTax2->getAllPropertySalesTax(array('property_id' => $property_id));
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

										$param['customer_email'] = $cust_details['email'];
										$param['job_name'] = $description;

										$QBO_description = implode(', ', $QBO_description);
										$actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
										$QBO_param = $param;
										$QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
										$QBO_param['job_name'] = $QBO_description;

                                        $cust_coup_param = array(
                                            'cost' => $QBO_cost,
                                            'customer_id' => $QBO_param['customer_id']
                                        );
                        
                                        $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);
                                          
										$quickbook_invoice_id = $this->QuickBookInv($QBO_param);

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
                            




                   
                            










        }else{
            if($price_override){
                $param = array(
                    'program_id' => $program_id,
                    'property_id' => $property_id,
                    'price_override' => $price_override,
                    'is_price_override_set' => 1
                    //'discount_code' => $discount_code
                );                            
            }else{
                $param = array(
                    'program_id' => $program_id,
                    'property_id' => $property_id//,
                    //'price_override' => $price_override//,
                    //'discount_code' => $discount_code
                );
            }
            $result = $this->AdminTbl_property_model->assignProgram($param);
                    

                    $this->AdminTbl_property_model2->autoStatusCheck(0,$property_id);

                    $value = $property_id;

                    $program = array();
                    $program['properties'] = array();
                    $program['properties'][$value] = array(
                        'program_property_id' => $result,
                      );




                    
                    //$value2 = "";
                    //foreach ($customer_property_details as $key2 => $value2) {
                   //     $value2 = $this->CustomerModel->getAllProperty(array('customer_property_assign.property_id' => $property_id));
                    //}

                    
                    $prog_details = $this->ProgramModel->getProgramDetail($program_id);


                    
                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->user->company_id)); 




                    $jobs = $this->ProgramModel->getSelectedJobs($program_id);

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
                                $est_cost = 0;
                                $QBO_cost = 0;

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
                                    'program_id' => $program_id,
                                    'customer_id' => $value2->customer_id
                                    );

                                      //CALCULATE JOB COST 
                                      if(false){
                                        $cost = $price_override;
                                    }else{

                                    //check for price overrides
                                    $estimate_price_override =   GetOneEstimateJobPriceOverride($where2);
                                    if ($estimate_price_override) {
                                        $cost =  $estimate_price_override->price_override;
                                        
                                        $est_coup_param = array(
                                            'cost' => $cost,
                                            'estimate_id' => $estimate_price_override->estimate_id
                                        );

                                        $est_cost =  $this->calculateEstimateCouponCost($est_coup_param);



                                    } else {
                                    
                                

                                        $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

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


                                }








                                
                                        $total_cost += $cost;
                                        $ppjobinv[] = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'job_id' => $job_id,
                                        'cost' => $cost,
                                        );

                                        if($est_cost != 0){
                                            $job_coup_param = array(
                                                'customer_id' => $value2->customer_id,
                                                'property_id' => $property_id,
                                                'program_id' => $program_id,
                                                'cost' => $est_cost,
                                                'job_id' => $job_id
                                            );
                        
                                            $QBO_cost = $this->calculateServiceCouponCost($job_coup_param);
                                        } else {
                                            $job_coup_param = array(
                                                'customer_id' => $value2->customer_id,
                                                'property_id' => $property_id,
                                                'program_id' => $program_id,
                                                'cost' => $cost,
                                                'job_id' => $job_id
                                            );
                        
                                            $QBO_cost = $this->calculateServiceCouponCost($job_coup_param);
                                        }
                                }
                        

                                        //format invoice data
                                        $param =  array(
                                            'customer_id' => $value2->customer_id,
                                            'property_id' => $property_id,
                                            'program_id' => $program_id,
                                            'user_id' => $this->user->user_id,
                                            'company_id' => $this->user->company_id, 
                                            'invoice_date' => date("Y-m-d"),
                                            'description' => $prog_details['program_notes'],
                                            'cost' => ($total_cost),
                                            'is_created' => 2,
                                            'invoice_created' => date("Y-m-d H:i:s"),
                                        );

                                        $invoice_id = $this->INV->createOneInvoice($param);

                                        //if invoice id
                                        if ($invoice_id) {
                                            $param['invoice_id'] = $invoice_id;
                                            //figure tax	
                                            if ($setting_details->is_sales_tax == 1) {
                                                
                                                $property_assign_tax = $this->PropertySalesTax2->getAllPropertySalesTax(array('property_id' => $property_id));
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

                                            $param['customer_email'] = $cust_details['email'];
                                            $param['job_name'] = $description;

                                            $QBO_description = implode(', ', $QBO_description);
                                            $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                            $QBO_param = $param;
                                            $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                            $QBO_param['job_name'] = $QBO_description;

                                            $cust_coup_param = array(
                                                'cost' => $QBO_cost,
                                                'customer_id' => $QBO_param['customer_id']
                                            );
                            
                                            $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);
                                            
                                            $quickbook_invoice_id = $this->QuickBookInv($QBO_param);

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
                        } //end if cust prop details
					} // end if prog details
        } //end else

    } // end first if



           



          // Check if the user data exists
          if(!empty($this->user) && !empty($result)){  

                $this->response([
                  'status' => true,
                  'message' => 'Program assigned successfully.',
                  'result' => ['ID' => $result]
              ], REST_Controller::HTTP_OK);
          }else{
              // Set the response and exit
              //NOT_FOUND (404) being the HTTP response code
              $this->response([
                  'status' => FALSE,
                  'message' => 'Program could not be assigned.'
              ], REST_Controller::HTTP_NOT_FOUND);
        }
        
} //end function







      public function tag_post(){
        
        $tag = !empty($this->post('tag',true)) ? $this->post('tag',true) : "";
        $property_id = !empty($this->post('property_id',true)) ? $this->post('property_id',true) : "";
        
        $param = ['tags' => $tag];

        
        $result = $this->AdminTbl_property_model->updateAdminTblZap($property_id, $param);


        // Check if the user data exists
        if(!empty($result && $this->user)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response([
                'status' => TRUE,
                'message' => 'Tag added to property.'
            ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No result was found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

      }




      public function tagGet_post(){
       $tag_name = !empty($this->post('tag_name',true)) ? $this->post('tag_name',true) : "";
       
       if($tag_name){
        $where = array(
            'company_id' => $this->user->company_id,
            'tags_title' => $tag_name
        );

       }

       $taglist = $this->AdminTbl_property_model->getTagsListZap($where);

        $tagsObject = new stdClass();
        $tagsObject->tags = $taglist;
        $resultsArray = array($tagsObject);
       
       
       
       // Check if the user data exists
        if(!empty($taglist && $this->user)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response($taglist, REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No taglist was found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
      }


      public function programs_post(){

       $program_name = !empty($this->post('program_name',true)) ? $this->post('program_name',true) : "";

       $where =  ['company_id' => $this->user->company_id];

       if($program_name){
        $where['program_name'] = $program_name;

       }
       $programs = $this->AdminTbl_property_model->getProgramList($where);

       // Check if the user data exists
        if(!empty($programs && $this->user)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response($programs, REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No programs were found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
      }


      public function assignprogram_post(){
       $property_id = !empty($this->post('property_id',true)) ? $this->post('property_id',true) : "";
       $program_id = !empty($this->post('program_id',true)) ? $this->post('program_id',true) : "";
        
       $where =  ['property_id' => $property_id, 'program_id' => $program_id];
       $assign = $this->AdminTbl_property_model->assignProgram($where);

       // Check if the user data exists
        if(!empty($assign && $this->user)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response("Program assigned", REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No program was assigned'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
      

      public function webhook_put(){
        if (!$this->user) {
          $this->response([
                  'status' => FALSE,
                  'message' => 'Webhook could not be added.'
              ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            
            if($this->put('trigger') == "customer_created"){
              $data['webhook_customer_created'] = $this->put('hookurl');
              $response = $this->put('hookurl')." added Customer Created webhook";
            }elseif($this->put('trigger') == "program_assigned"){
              $data['webhook_program_assigned'] = $this->put('hookurl');
              $response = $this->put('hookurl')." added Program Assigned webhook";
            }elseif($this->put('trigger') == "service_completed"){
              $data['webhook_service_completed'] = $this->put('hookurl');
              $response = $this->put('hookurl')." added Service Completed webhook";
            }elseif($this->put('trigger') == "account_cancelled"){
              $data['webhook_account_cancelled'] = $this->put('hookurl');
              $response = $this->put('hookurl')." added Account Cancelled webhook";
            }elseif($this->put('trigger') == "tag_created"){
                $data['webhook_tag_created'] = $this->put('hookurl');
                $response = $this->put('hookurl')." added Tag Created webhook";
              }elseif($this->put('trigger') == "property_created"){
                $data['webhook_property_created'] = $this->put('hookurl');
                $response = $this->put('hookurl')." added Property Created webhook";
              }


            $webhook = $this->Administrator->updateWebhook(['id' => $this->user->id], $data);

            $this->response([
                'status' => true,
                'message' => 'Webhook successfully created.',
                'result' => $webhook,
            ], REST_Controller::HTTP_OK);
              
        }
      }

      public function webhook_delete(){
        if (!$this->user) {
          $this->response([
                  'status' => FALSE,
                  'message' => 'Webhook could not be added.'
              ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            
            if($this->delete('trigger') == "customer_created"){
              $data['webhook_customer_created'] = null;
              $response = "Removed Customer Created webhook";
            }elseif($this->delete('trigger') == "program_assigned"){
              $data['webhook_program_assigned'] = null;
              $response = "Removed Program Assigned webhook";
            }elseif($this->delete('trigger') == "service_completed"){
              $data['webhook_service_completed'] = null;
              $response = "Removed Service Completed webhook";
            }elseif($this->delete('trigger') == "account_cancelled"){
              $data['webhook_account_cancelled'] = null;
              $response = "Removed Account Cancelled webhook";
            }elseif($this->delete('trigger') == "tag_created"){
              $data['webhook_tag_created'] = null;
              $response = "Removed Tag Created webhook";
            }elseif($this->delete('trigger') == "property_created"){
                $data['webhook_property_created'] = null;
                $response = "Removed Property Created webhook";
              }


            $webhook = $this->Administrator->updateWebhook(['id' => $this->user->id], $data);

            $this->response([
                'status' => true,
                'message' => 'Webhook successfully created.',
                'result' => $webhook,
            ], REST_Controller::HTTP_OK);
              
        }
      }

      public function webhook_get(){
        if (!$this->user) {
          $this->response([
                  'status' => FALSE,
                  'message' => 'Webhook could not be retrieved.'
              ], REST_Controller::HTTP_NOT_FOUND);
        } else {

            
            $result = $this->Customer_model->getOneCustomer(['user_id' => $this->user->user_id]);

            $this->response([[
                'status' => true,
                'message' => 'Webhook retrieved.',
                'result' => $result
            ]], REST_Controller::HTTP_OK);
              
        }
      }
      public function webhook_post(){

       

        if (!$this->user) {
          $this->response([
                  'status' => FALSE,
                  'message' => 'Webhook could not be retrieved.'
              ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            

            if($this->post('trigger') == "customer_created"){
              $sample_customer = $this->Customer_model->getLatestCustomer(['user_id' => $this->user->user_id]);
              $result = $this->Customer_model->getCustomerDetail($sample_customer->customer_id);
            }elseif($this->post('trigger') == "program_assigned"){
              $result =  ['program_id' => '1234', 'program_name' => 'Sample Program', 'customer_email' =>  'Sample email'];
            }elseif($this->post('trigger') == "service_completed"){
              $result = ['service_name' => 'sample name', 'customer_email' =>  'sample_email'];
            }elseif($this->post('trigger') == "account_cancelled"){
              //$sample_customer = $this->Customer_model->getOneCustomer(['user_id' => $this->user->user_id]);
              //$result = $this->Customer_model->getCustomerDetail($sample_customer->customer_id);
              $result =  ['Customer Name'=>'Sample_name', 'Customer Email'=>'Sample_email', 'Property Address'=>'Sample_address', 'Service Area'=>'Sample_service_area'];
              
            }elseif($this->post('trigger') == "tag_created"){
                $result = ['property_id' => '1234', 'tags' =>  '1'];
            }elseif($this->post('trigger') == "property_created"){
                $sample_property = $this->AdminTbl_property_model->getLatestProperty(['user_id' => $this->user->user_id]);
                $sampleData = $this->AdminTbl_property_model->getOnePropertyDetail($sample_property->property_id);

                $result = ['Property ID' => $sampleData->property_id, 'Customer Email' => "email", 'Property Name'=>$sampleData->property_title, 'Service Area'=>$sampleData->property_area, 'Property Address'=>$sampleData->property_address, 'Latitude'=>$sampleData->property_latitude, 'Longitude'=>$sampleData->property_longitude, 'Yard Square Feet'=>$sampleData->yard_square_feet, 'Grass Type'=>$sampleData->total_yard_grass ];
                        
            }
            
            $this->response([[
                'status' => true,
                'message' => 'Webhook retrieved.',
                'result' => $result
            ]], REST_Controller::HTTP_OK);
              
        }
      }
}