<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/sms/Send_Text.php';
require_once APPPATH . '/third_party/stripe-php/init.php';
require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;
 

class Customers extends MY_Controller {   

  public function __construct() {
      parent::__construct();
      if (!$this->session->userdata('email')) {
          return redirect('customers/auth');
      }
      $this->load->library('parser');
      $this->load->helper('text');
      $this->loadModel();
      $this->load->helper(array('form', 'url'));
      $this->load->helper('job_helper');
      $this->load->helper('invoice_helper');
      $this->load->library('form_validation');
      $this->load->helper('estimate_helper');
      $this->load->helper('cardconnect_helper');

      if ($this->session->userdata('spraye_technician_login')) {
		return redirect("admin");
	  }
  }
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *http://example.com/index.php/welcome
     * - or -
     *http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *Filename: /opt/lampp/htdocs/spraye_new_design/system/libraries/Form_validation.php


     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
  private function loadModel() {

      $this->load->model('AdminTbl_property_model', 'PropertyModel');
      $this->load->model('AdminTbl_program_model', 'ProgramModel');
      $this->load->model('AdminTbl_customer_model', 'CustomerModel');
      $this->load->model('AdminTbl_product_model', 'ProductModel');
      $this->load->model('Dashboard_model', 'DashboardModel');
      $this->load->model("Administrator");
      $this->load->model('Job_model', 'JobModel');
      $this->load->model('Technician_model', 'Tech');
      $this->load->model('AdminTbl_company_model', 'CompanyModel');   
      $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
      $this->load->model('Company_email_model', 'CompanyEmail');  
      $this->load->model('Administratorsuper');
      $this->load->model('Invoice_model','INV'); 
      $this->load->model('Unassign_job_delete_model','UnassignJobDeleteModal');
      $this->load->model('Sales_tax_model', 'SalesTax');          
      $this->load->model('Help_message', 'HelpMessage');                
      $this->load->model('Property_sales_tax_model', 'PropertySalesTax');                
      $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');                
      $this->load->model('Invoice_job_model', 'invoiceJob');                
      $this->load->model('Data_table_manage_model', 'DataTableModel');
      $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
      $this->load->model('Cardconnect_model', 'CardConnect');
      $this->load->model('Basys_request_modal', 'BasysRequest');
      $this->load->model('AdminTbl_coupon_model', 'CouponModel');
      $this->load->model('Estimate_model', 'EstimateModel');
      $this->load->model('Payment_invoice_logs_model', 'PartialPaymentModel');
      $this->load->model('Refund_invoice_logs_model', 'RefundPaymentModel');
      $this->load->model('Cardconnect_model', 'CardConnectModel');

  }

	public function basysAddCustomer(){
		$data = $this->input->post();
		//print_r($data);
		//get api key
		$company_id = $this->session->userdata('company_id');
		$basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
		$apiKey = $basys_details->api_key;
		
		$customer_details = $this->CustomerModel->getCustomerDetail($data['customer_id']);
		
		if($customer_details){
			$customer = array(
				"id_format" => "xid_type_last4",
				"default_payment" => array(
					"card" => array(
						"number" => $data['card_number'],
						"expiration_date" => $data['card_exp']
					)
    			),
				"default_billing_address" => array(
					"first_name" => $customer_details['first_name'],
					"last_name" => $customer_details['last_name'],
				
				),
			);
			
			//$url = "https://sandbox.basysiqpro.com/api";   //test
			$url = BASYS_URL."api";
			
			$curl = curl_init();
			$payload = json_encode($customer);
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url."/vault/customer",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>$payload,
				CURLOPT_HTTPHEADER => array(
					"Authorization: ".$apiKey,
					"Content-Type: application/json"
				),
			));
			$response = curl_exec($curl);
			$result = json_decode($response, true);
			curl_close($curl);
			
			if(isset($result['data']['id'])){
				//print_r($result);
				$basys_customer_id = $result['data']['id'];
				
				$update = $this->CustomerModel->updateCustomerData(array('basys_customer_id'=>$basys_customer_id),array('customer_id'=>$data['customer_id']));
			}

			echo $response;
	
		}
			
	}
	public function basysGetCustomerRecord($basys_customer_id){
		
		//get api key
		$company_id = $this->session->userdata('company_id');
		$basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
		$apiKey = $basys_details->api_key;
		
		//$url = "https://sandbox.basysiqpro.com/api";   //test
		$url = BASYS_URL."api";
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url."/vault/".$basys_customer_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: ".$apiKey
			),
		));

		$response = curl_exec($curl);
		$result = json_decode($response, true);
		curl_close($curl);

		return $response;
	}

	public function basysUpdateCustomerPayment(){
		$data = $this->input->post();
		//print_r($data);
		//get basys customer data
		$get_basys_customer = $this->basysGetCustomerRecord($data['basys_customer_id']);
		$basys_customer_details = json_decode($get_basys_customer,true);
		
		if($basys_customer_details['status'] == 'success'){
			
			$payment_method_id = $basys_customer_details['data']['data']['customer']['payments']['cards'][0]['id'];

			//get api key
			$company_id = $this->session->userdata('company_id');
			$basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
			$apiKey = $basys_details->api_key;

			if($payment_method_id){
				
				$card = array(
					"number" => $data['card_number'],
					"expiration_date" => $data['card_exp']
				);

				//$url = "https://sandbox.basysiqpro.com/api";   //test
				$url = BASYS_URL."api";

				$curl = curl_init();
				$payload = json_encode($card);
				curl_setopt_array($curl, array(
					CURLOPT_URL => $url."/vault/customer/".$data['basys_customer_id']."/card/".$payment_method_id,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$payload,
					CURLOPT_HTTPHEADER => array(
						"Authorization: ".$apiKey,
						"Content-Type: application/json"
					),
				));
				$response = curl_exec($curl);
				$result = json_decode($response, true);
				curl_close($curl);

				echo $response;

			}
		}else{
			echo $data['status'] = 'error';
		}
			
	}

##### RESTRUCTURED DASHBOARD RG ####
public function dashboard($customerID = NULL,$active=0) {
    $company_id =  $this->session->userdata['company_id'];

    if (!empty($customerID)) {
        $customerID= $customerID;
    } else{
        $customerID=$this->uri->segment(4);
    }
    
    $company_id = $this->session->userdata['company_id'];
    $where = array('company_id' =>$this->session->userdata['company_id']);
    
    $data['customerData'] = $this->CustomerModel->getCustomerDetail($customerID);
    $data['propertylist'] = $this->CustomerModel->getPropertyList($where);
    // die(print_r($data['customerData']));
  
    /// GET ASSIGNED PROGRAMS
    $customerProperties = $this->PropertyModel->getAllCustomerProperties($customerID);
    $data['customerProperties'] = $customerProperties;
    // die(print_r($data['customerProperties']));
    
    $prop_programs = array();
    foreach($customerProperties as $k=>$prop){
        //get programs 
        $programs = $this->PropertyModel->getAllprogram(array('property_id'=>$prop->property_id));
     
        foreach($programs as $program){
            $prop_programs[]=array(
                'property_id'=>$prop->property_id,
                'property_title'=>$prop->property_title,
                'program_name'=>$program->program_name,
                'property_address' => $prop->property_address,
            );
        }
    }
    
    $data['prop_programs'] = $prop_programs;
    // die(print_r($data['prop_programs']));
    ////////////////////////
  
    $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
    $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
    $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
	$data['company_notifications'] = $this->CompanyModel->getOneCompanyEmailArray($where);
    $data['cardconnect_details'] = $this->CardConnect->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));
    $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
	
    $selecteddata = $this->CustomerModel->getSelectedProperty($customerID);

    $data['selectedpropertylist']  = array();
    if (!empty($selecteddata)) {
        foreach ($selecteddata as $value) {
            $data['selectedpropertylist'][] = $value->property_id;
        }
        
    }
    
    $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id'=>$this->session->userdata['company_id']));
  
    $where = array(
        'technician_job_assign.company_id' => $this->session->userdata['company_id'],
        'technician_job_assign.customer_id'=>$customerID,
    );

    $data['customer_all_jobs'] = $this->DashboardModel->getAssignTechnician($where);

    $coupon_where = array(
        'company_id' => $this->session->userdata['company_id'],
        'type' => 0
    );
    $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
    
    $coupon_where = array(
        'company_id' => $this->session->userdata['company_id'],
        'type' => 1
    );
    $data['customer_perm_coupons'] = $this->CouponModel->getAllCoupon($coupon_where);
    
    $coupon_where = array(
        'customer_id' => $customerID
    );
    $data_temp_coupon = $this->CouponModel->getCouponCustomers($coupon_where);
    $data['customer_existing_perm_coupons']  = array();
    if (!empty($data_temp_coupon)) {
        foreach ($data_temp_coupon as $value) {
            $data['customer_existing_perm_coupons'][] = $value->coupon_id;
        } 
    }  
    $whereActive = array('invoice_tbl.company_id' =>$this->session->userdata['company_id'],'invoice_tbl.customer_id'=>$customerID, 'is_archived' => 0, 'status !=' => 0 );
    $whereArrExclude = array(
        "programs.program_price" => 2,
        // "technician_job_assign.is_complete" => 0,
        "technician_job_assign.is_complete !=" => 1,
        "technician_job_assign.is_complete IS NOT NULL" => null,
    );
    $whereArrExclude2 = array(
        "programs.program_price" => 2,
        "technician_job_assign.invoice_id IS NULL" => null,
        "invoice_tbl.report_id" => 0,
        "property_program_job_invoice2.report_id IS NULL" => null,
    );
    $data['invoice_details'] = $this->INV->getActiveInvoices($whereActive, $whereArrExclude,  $whereArrExclude2);  

    $payment_terms_id = $this->CompanyModel->getPaymentTerms(array('company_id'=>$company_id));
    switch ($payment_terms_id->payment_terms) {
        case 1: // 1 = Due on Receipt
            $payment_terms = 0;
            break;
        case 2: // 2 = Net 7
            $payment_terms = 7;
            break;
        case 3: // 3 = Net 10
            $payment_terms = 10;
            break;
        case 4: // 4 = Net 14
            $payment_terms = 14;
            break;
        case 5: // 5 = Net 15
            $payment_terms = 15;
            break;
        case 6: // 6 = Net 20
            $payment_terms = 20;
            break;
        case 7: // 7 = Net 30
            $payment_terms = 30;
            break;
        case 8: // 8 = Net 45
            $payment_terms = 45;
            break;
        case 9: // 9 = Net 60
            $payment_terms = 60;
            break;
        case 10: // 10 = Net 90
            $payment_terms = 90;
            break;
        default:
            break;
    }	
    
    $outstanding = array();
    foreach ($data['invoice_details'] as $k => $i) {

      $refund_date = $this->INV->getRefundDate($i->invoice_id);

            if(isset($refund_date)){
                $data['invoice_details'][$k]->refund_datetime = $refund_date->refund_datetime;
            }
        //filter out incomplete services with program priee == 1
        $assigned = $this->DashboardModel->getAssignTechnician(array('invoice_id' => $i->invoice_id, 'program_price' => 2));
        if (isset($assigned)) {
            foreach ($assigned as $key => $row) {
                if ($row->is_complete != 1) {
                    unset($data['invoice_details'][$k]);
                }
            }
        }
    }
    
    $all_invoice_partials = [];
    $due_invoices = [];
    $past_due_invoices = [];
    $partial_amount_paid = 0;
    $total_due = 0;
    $total_past_due = 0;
    foreach($data['invoice_details'] as $k => $i){

        //late fee
        $late_fee = $this->INV->getLateFee($i->invoice_id);
        // die(print_r($i2));
        if($i->is_archived != 1 && $i->status != 0){
            if(isset($i->first_sent_date)){
                $invoiceDate = $i->first_sent_date;
            } else{
                $invoiceDate = $i->invoice_date;
            }
     
            ////////////////////////////////////
            // START INVOICE CALCULATION COST //
      
            // invoice cost
            // $invoice_total_cost = $invoice->cost;
      
            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $i->invoice_id
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
                    //var_dump($job_cost);
                    $job_cost_total += $job_cost;
                }
            } else {
      
                // IF none from that table, is old invoice, calculate old way
                $job_cost_total = $i->cost;
      
            }
            $invoice_total_cost = $job_cost_total;
            //var_dump($invoice_total_cost);
            // check price override -- any that are not stored in just that ^^.
      
            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $i->invoice_id));
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
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $i->invoice_id));
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
            //var_dump($invoice_total_cost);

    
            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////
      
            $cost = '$ '.number_format($invoice_total_cost,2);
            // $due = $invoice_total_cost-$i2->partial_payment;
            if($i->refund_amount_total == 0){

                $due = ($i->cost-$i->partial_payment <= 0) ? 0 : $invoice_total_cost-$i->partial_payment;
            } else {
                $due = 0;
            }
  
            $data['due'] = $i->cost-$i->partial_payment;
            //var_dump($data['due']);
            if ($due < 0) {
                $due = 0;
            }
            $balance_due = 0 ? '$ 0.00' : '$ '.number_format($due,2);
      
            if($i->payment_status != 2){
                $outstanding[]=array(
                    'invoice_id'=>$i->invoice_id,
                    'service_name' => $i->description,
                    'amount_due'=>$due + $late_fee,
                    'due_date'=>date('Y-m-d',strtotime($invoiceDate. '+ '.$payment_terms.' day')),
                    'status' => $i->payment_status
                );
                array_push($due_invoices, $i->invoice_id);
            }
            if($i->payment_status == 3){
                $past_due[]=array(
                    'invoice_id'=>$i->invoice_id,
                    'service_name' => $i->description,
                    'amount_due'=>$due,
                    'due_date'=>date('Y-m-d',strtotime($invoiceDate. '+ '.$payment_terms.' day')),
                    'status' => $i->payment_status
                );
                array_push($past_due_invoices, $i->invoice_id);
            }
            if (isset($data['invoice_details'][$k]) && !empty($data['invoice_details'][$k])) {
                $data['invoice_details'][$k]->total_cost_actual = $invoice_total_cost + $late_fee;
            }
            if (isset($data['invoice_details'][$k]) && !empty($data['invoice_details'][$k])) {
                $data['invoice_details'][$k]->amount_due = $due;
            }
        
        } else {
          $invoice_total_cost = $i->cost;

          // + tax cost
          $invoice_total_tax = 0;
          $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $i->invoice_id));
          // die(print_r($invoice_sales_tax_details));
          if (!empty($invoice_sales_tax_details)) {
              foreach ($invoice_sales_tax_details as $tax) {
                  if (array_key_exists("tax_value", $tax)) {
                      $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                      $invoice_total_tax += $tax_amm_to_add;
                  }
              }
          }
          $invoice_total_cost += $invoice_total_tax;

          $cost = '$ ' . number_format($invoice_total_cost, 2);


          if (isset($data['invoice_details'][$k]) && !empty($data['invoice_details'][$k])) {
              $data['invoice_details'][$k]->total_cost_actual = $cost;
          }
        }

        ##### PARTIAL PAYMENTS PAID #####
        $invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id'=>$i->invoice_id));
        array_push($all_invoice_partials, $invoice_partials);
    
        // if (count($all_invoice_partials) > 0){
        foreach($invoice_partials as $paid_amount){
            $partial_amount_paid += number_format($paid_amount->payment_amount,2, '.', '');
        }
        // }
        
        // die(print_r($this->db->last_query()));
        ####
        //var_dump($due);
        $total_due += $due;
    }
    if(isset($past_due)){
        foreach($past_due as $amount){
            $total_past_due += $amount['amount_due'];
        }
    } else {
        $past_due = 0;

    }

    $data['all_invoice_partials'] = $all_invoice_partials;
    $data['total_due'] = $total_due;
    $data['past_due'] = $past_due;
    $data['total_past_due'] = $total_past_due;
    $data['past_due_invoices'] = $past_due_invoices;
    $data['due_invoices'] = $due_invoices;
    $data['outstanding'] = $outstanding;
    $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' =>$this->session->userdata['company_id']));
      
    $data['active_nav_link'] = $active;
  
    ////// GET UNSCHEDULED SERVICES
    $where = array(
        'jobs.company_id' => $company_id,
        'property_tbl.company_id' => $company_id,
        'customer_id' => $customerID,
        'property_status' => 1
    ); 
    $unassignedServices = $this->DashboardModel->getCustomerUnschedServ($where);
  
    $where = array(
        'jobs.company_id' => $company_id,
        'property_tbl.company_id' => $company_id,
        'customers.customer_id' => $customerID
    ); 
    $all_services = $this->DashboardModel->getCustomerAllServices($where);
    
    
    $data['all_services'] = $all_services;

    if(!empty($data['all_services'])){
        foreach($data['all_services'] as $key => $val){
            $canc_arr = array(
                'job_id' => $val->job_id,
                'customer_id' => $val->customer_id,
                'program_id' => $val->program_id,
                'property_id' => $val->property_id
            );
            $data['all_services'][$key]->cancelled = $this->DashboardModel->getIsCancelledService($canc_arr);
        }
    }

    foreach($data['all_services'] as $all_services) {
        $cost = 0;
        if($all_services->job_cost == NULL) {
            // got this math from updateProgram - used to calculate price of job when not pulling it from an invoice
            $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $all_services->property_id, 'program_id' => $all_services->program_id));

            if ($priceOverrideData->is_price_override_set == 1) {
                // $price = $priceOverrideData->price_override;
                $cost =  $priceOverrideData->price_override;
            } else {
                //else no price overrides, then calculate job cost
                $lawn_sqf = $all_services->yard_square_feet;
                $job_price = $all_services->job_price;

                //get property difficulty level
                if (isset($all_services->difficulty_level) && $all_services->difficulty_level == 2) {
                    $difficulty_multiplier = $data['setting_details']->dlmult_2;
                } elseif (isset($all_services->difficulty_level) && $all_services->difficulty_level == 3) {
                    $difficulty_multiplier = $data['setting_details']->dlmult_3;
                } else {
                    $difficulty_multiplier = $data['setting_details']->dlmult_1;
                }

                //get base fee 
                if (isset($all_services->base_fee_override)) {
                    $base_fee = $all_services->base_fee_override;
                } else {
                    $base_fee = $data['setting_details']->base_service_fee;
                }

                $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                //get min. service fee
                if (isset($all_services->min_fee_override)) {
                    $min_fee = $all_services->min_fee_override;
                } else {
                    $min_fee = $data['setting_details']->minimum_service_fee;
                }

                // Compare cost per sf with min service fee
                if ($cost_per_sqf > $min_fee) {
                    $cost = $cost_per_sqf;
                } else {
                    $cost = $min_fee;
                }
            }
            $all_services->job_cost = $cost;
        }
    }

    if (!empty($unassignedServices)) {
        foreach ($unassignedServices as $key => $value) {
            $arrayName = array(
                'customer_id' => $value->customer_id,
                'job_id' => $value->job_id,
                'program_id' => $value->program_id,
                'property_id' => $value->property_id,
            );
            $assign_table_data = $this->Tech->GetOneRow($arrayName);
            if ($assign_table_data) {
                if ($assign_table_data->is_job_mode != 2) {
                    unset($unassignedServices[$key]);
                } 
            }
            $archivedArray = array(
                'customer_id' => $value->customer_id,
                'job_id' => $value->job_id,
                'program_id' => $value->program_id,
                'property_id' => $value->property_id,
            );
            $archived_table_data = $this->UnassignJobDeleteModal->getOneDeletedRow($archivedArray);
            if ($archived_table_data) {
                // if ($archived_table_data->is_job_mode != 2) {
                unset($unassignedServices[$key]);
                // } 
            }
        }             
    }
    $data['unscheduled'] = $unassignedServices;

    $data['all_customers'] = $this->CustomerModel->get_all_customer(array('email' => $data['customerData']['email']));

    /////////////////////////////////////
  
    ###### GET ALL CUSTOMER PROPERTIES ########
    $all_properties = $this->CustomerModel->getSelectedProperty($customerID);
    $data['property_list']  = array();
    if (!empty($all_properties)) {
        foreach ($all_properties as $value) {
            $data['property_list'][] = $value->property_id;
        }
    }
    ####
  
    /// GET SCHEDULED SERVICES 
    $data['scheduled'] = $this->DashboardModel->getAssignTechnician(array('technician_job_assign.company_id' => $company_id,'is_job_mode'=>0, 'technician_job_assign.customer_id'=>$customerID));
    $page["page_name"] = "My Account";
    $page["page_content"] = $this->load->view("customers/dashboard", $data, TRUE);
    
    $this->layout->customersTemplateTable($page);
}
  ####

  ##### CREATE LOGIN FOR CREATING A NEW USER PASSWORD #####
  // public function addUser(){
  //   // $page["active_sidebar"] = "mangeUserNav";
  //    $page["page_name"] = "Create Login";
  //    $page["page_content"] = $this->load->view("customers/add_user", '', TRUE);
  //    $this->layout->customersTemplateTable($page);
  // }
  ####

  ##### CREATE LOGIN FOR SAVING NEW USER DATA #####
  public function addUserData(){
    $data = $this->input->post();
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
      redirect("customers/addUser");
    } else {

    //   echo "<pre>";
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
                  redirect("customers/dashbo");
              }
        
      //  } else {

        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Please contact support to upgrade your plan.</div>');
      redirect("customers/addUser");
        
      //  }
    }
  }
  ####

  ##### CREATE LOGIN FOR CREATING A NEW USER PASSWORD OR UPDATE PASSWORD #####
  public function updateAccount(){
    
    $customerID = $_SESSION['customer_id'];
    $companyID = $_SESSION['compny_details']->company_id;
  
    $data['settings'] = $this->CompanyModel->getOneCompany(array('company_id' =>$_SESSION['compny_details']->company_id));

    $data['customerData'] = $this->CustomerModel->getCustomerDetail($customerID);
    $customerData = $data['customerData'];

    // $page["active_sidebar"] = "mangeUserNav";
    $page["page_name"] = "Update Password";
    $page["page_content"] = $this->load->view("customers/update_customer", "", TRUE);
    $this->layout->customersTemplateTable($page);
  }
  ####

  ##### CREATE LOGIN FOR SAVING NEW USER DATA #####
  public function updateAccountData(){
    $data = $this->input->post();
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
    
    if ($this->form_validation->run() == FALSE) {
      // echo validation_errors();
      $this->updateAccount();
   
    } else {
 
      $company_id =  $this->session->userdata['company_id'];
      $customer_id =  $this->session->userdata['customer_id'];
      $customerID = $_SESSION['customer_id'];
      
      $user_id = md5(json_encode($data).date("Ymdhis"));
      $param = array(
          // 'user_id' => $user_id,
          'company_id' => $company_id,
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email'],
          'phone' => $data['phone'],
          'password' => md5($data['password']),
          'role_id' => (isset($data['role_id']) ? $data['role_id'] : $data['role_id'] = 2),                             
          'created_at' => Date("Y-m-d H:i:s")
      );

      $result = $this->CustomerModel->updateCustomer($customer_id, $param);
        
      if ($result) {
                      
        switch ($data['role_id']) {
          case 2:
            $role = "Account Owner";
            break;
          case 3:
            $role = "Account Admin";
            break;
          default:
            $role = "No Role";
            break;
        }

        $email_array = array(
          'name' => $data['first_name'].' '.$data['last_name'],
          'email' => $data['email'],
          'password' => $data['password'],
          'role' => $role,                
        );

        $where = array('company_id' => $this->session->userdata['company_id']);

        $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $body  = $this->load->view('email/user_email',$email_array,TRUE);
        $subject =  'New Password';

          $where['is_smtp'] = 1;
        
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
        
          if (!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

          } 
            
        $res =   Send_Mail_dynamic($company_email_details,$data['email'],array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, $subject);

        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Account </strong>updated successfully</div>');
        redirect("customers/dashboard/$customerID");
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>User</strong>not updated.</div>');
        redirect("customers/dashboard/$customerID");
      }

      $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Please contact support to upgrade your plan.</div>');
      redirect("customers/dashboard/$customerID");
        
    }
  }
  ####

  public function getCalederbyYnassignJobs(){

    $company_id = $this->session->userdata['company_id'];
    $param =  $this->input->post();
    $param['tecnician_id_array'] =  json_decode($param['tecnician_id_array']);    
  
    $currentdate = date('Y-m-d',strtotime($param['datesting']));
    
    $oneMonthdate = date('Y-m-d', strtotime("+1 month", strtotime($currentdate)));


    $where_unassign = array('technician_job_assign.company_id' => $company_id,'is_job_mode'=>0,'job_assign_date >='=>$currentdate,'job_assign_date <='=>$oneMonthdate);

    $data['assign_data'] = $this->DashboardModel->getUnAssignJobsGroup($where_unassign,$param['tecnician_id_array']);


    if ($data['assign_data']) {

        foreach ($data['assign_data'] as $key => $value) {
          $where_unassign['job_assign_date'] = $value->job_assign_date;
          $data['assign_data'][$key]->assign_data_result = $this->DashboardModel->getAssignTechnician($where_unassign,$param['tecnician_id_array']);                
        }
    }

    $data['currentdate'] = $currentdate;


    $html = $this->load->view('caledr_data_ajax',$data,true);
    echo $html;
  }

  

   

  

  public function scheduledJobsData(){
    $company_id = $this->session->userdata['company_id'];
    $data = $this->DashboardModel->getAssignTechnicianJson(array('technician_job_assign.company_id' => $company_id,'is_job_mode'=>0));
    // echo $this->db->last_query();
    echo json_encode($data);
  }
  
  public function logout() {
    $redirect_slug = $this->session->userdata('slug');
    $this->session->sess_destroy();
    
    return redirect('welcome/'.$redirect_slug);
    // return redirect('customers/auth');
  }
 
    /**
     * Returns comma-seperated email address
     * @params post
     * @return string json_encoded string
     */
  public function addSecondaryEmailDataJson() {
    $data = $this->input->post();      
    $this->form_validation->set_rules('secondary_email', 'Email', 'required|valid_email');
    if ($this->form_validation->run() == FALSE) {
      $return_array =  array('status' => 400,'msg'=>validation_errors());          
    } else {
      $data = $this->input->post();
      $emails_list = [];
      if($data['already_added_emails'] != '') {
        // Converts string into array.
        $emails_list = explode(',',$data['already_added_emails']);
        // Checks to avoid duplicate email entry for customer. 
        if(!in_array($data['secondary_email'],$emails_list)) {
          array_push($emails_list,$data['secondary_email']);
        }
      } else {
        array_push($emails_list,$data['secondary_email']);
      }
      $result = implode(',',$emails_list);
      $return_array =  array('status' => 200,'msg'=>'Property  added successfully.','result'=>$result);
    }
    echo json_encode($return_array);
  }


/*///////////////////////   Ajax Code           ///////////////////    */

  public function propertyListAjax(){
    
    $selected_ids = array();
    $selectedPropertiesids = array();
    $current_added_id = '';
    
    
    if ($this->input->post()) {

        $proertyPriceOverRide = json_decode($this->input->post('proertyPriceOverRide'));

        
        if(!empty($proertyPriceOverRide)) {

            $selected_ids = array_map(function($e) {
              return is_object($e) ? $e->property_id : $e['property_id'];
          }, $proertyPriceOverRide);              
        }

        if (!empty($this->input->post('selectedProperties'))) {
          $selectedPropertiesids = $this->input->post('selectedProperties');
        }

        $current_added_id = $this->input->post('current_added_id');


    }
    //  print_r($selectedPropertiesids);
  

    $where = array('property_tbl.company_id'=>$this->session->userdata['company_id']);
      $propertyData = $this->PropertyModel->get_all_property($where);
          if(!empty($propertyData)){

              foreach ($propertyData as $value) {

                if(in_array($value->property_id,$selected_ids)) {
                  $select1 =  'selected';
                } else {
                  $select1 = '';
                }

                if(in_array($value->property_id,$selectedPropertiesids)) {
                  $select2 =  'selected';
            //      echo "he".$value->property_id;
                } else {
                  $select2 = '';
              //    echo "no".$value->property_id;

                }

                if($value->property_id==$current_added_id) {
                  $select3 =  'selected';
                } else {
                  $select3 = '';
                }

                if ($select1 == 'selected' || $select2 == 'selected' || $select3 == 'selected' ){
                  $select = 'selected'; 
                } else {
                  $select = '';
                }




              echo '<option value="'.$value->property_id.'" '.$select.' >'.$value->property_title.'</option>';
              }
          }
  }

  public function propertyListAjaxSelctedByCustomer($customer_id){
            $where = array('property_tbl.company_id'=>$this->session->userdata['company_id']);

            $propertyData = $this->PropertyModel->get_all_property($where);

             $selecteddata = $this->CustomerModel->getSelectedProperty($customer_id);
       
        $selectedpropertylist  = array();
        if (!empty($selecteddata)) {
                foreach ($selecteddata as $value) {
                    $selectedpropertylist[] = $value->property_id;
                }
            
        }

                if(!empty($propertyData)){

                    foreach ($propertyData as $value) { ?>

    <option value="<?php echo $value->property_id; ?>" <?php if(in_array($value->property_id, $selectedpropertylist )) { ?>
    selected <?php } ?>> <?php echo $value->property_title;  ?> </option>

    <?php } 
                }                
  }

    public function customerListAjax(){
        $where = array('company_id'=>$this->session->userdata['company_id']);
        $customerData = $this->CustomerModel->get_all_customer($where);
        if(!empty($customerData)){
      
            foreach ($customerData as $value) {
                echo '<option value="'.$value->customer_id.'" title="'.$value->billing_street.'"  >'.$value->first_name.' '.$value->last_name.'</option>';
            }
        }
    }

  public function programListAjax(){

         $selected_ids = array();
          
          if ($this->input->post()) {

              $programPriceOverRide = json_decode($this->input->post('programPriceOverRide'));

             
              if(!empty($programPriceOverRide)) {

                  $selected_ids = array_map(function($e) {
                    return is_object($e) ? $e->program_id : $e['program_id'];
                }, $programPriceOverRide);              
              }
          }


        $where = array('company_id'=>$this->session->userdata['company_id']);
 
        $programData = $this->ProgramModel->get_all_program($where);
            if(!empty($programData)){

            foreach ($programData as $value) {

                if(in_array($value->program_id,$selected_ids)) {
                        $select =  'selected';
                } else {
                        $select = '';
               }

                echo '<option value="'.$value->program_id.'" '.$select.' >'.$value->program_name.'</option>';
            }
        }
  }

  public function HelpMessagesend($value=''){
    $data =  $this->input->post();

    if ( trim($data['message']) != '' ) {
      
        $where = array(
            'company_id' =>$this->session->userdata['company_id'],
            'is_smtp' =>1
       );

       $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
       if (!$company_email_details) {
     
          $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        } 
    

        $body = $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'].' sent you help message :<br>'.trim($data['message']);

        $res =   Send_Mail_dynamic($company_email_details,helpEmailTo,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Get Help From Spraye Web Admin Pannel');

        if ($res['status']) {          

            $param = array(
                 'company_id' => $this->session->userdata['company_id'],
                 'user_id' => $this->session->userdata['user_id'],
                  'message'=>trim($data['message']),
                  'create_at '=>date("Y-m-d H:i:s"),
            );
           
           $result =  $this->HelpMessage->CreateOneHelpMessage($param);

           if ($result) {             
             $return_array =  array('status' => 200,'msg'=>'Help message sent successfully.','result'=>$result);
           } else {
             $return_array =  array('status' => 400,'msg'=>'Something went wrong','result'=>array());
           }       
        } else {
           $return_array =  array('status' => 400,'msg'=>$res['message'],'result'=>array());               

        }
    } else {

      $return_array =  array('status' => 400,'msg'=>'We are unable to send empty message','result'=>array());

    }
        echo json_encode($return_array);    
      
      
  }


  public function dataTableManage(){
   $data =  $this->input->post();
  
   
   $where = array(
    'company_id' =>$this->session->userdata['company_id'],
    'table_name' =>$data['table_name']
   );

    $updatearr = array(
     'company_id' =>$this->session->userdata['company_id'],
     'table_name' =>$data['table_name']
    );

     if (array_key_exists('colmn_id', $data)) {
     $updatearr['colmn_id'] = $data['colmn_id']; 
    }
    
    if (array_key_exists('colmn_order', $data)) {
     $updatearr['colmn_order'] = $data['colmn_order']; 
    }
    
    if (array_key_exists('page_lenght', $data)) {
     $updatearr['page_lenght'] = $data['page_lenght']; 
    }


   $chek =  $this->DataTableModel->getOneOneDataTable($where);
   
    if ($chek) {

     $res =  $this->DataTableModel->updateOneDataTable($where, $updatearr);     
   
    } else {
    
     $res =  $this->DataTableModel->CreateOneDataTable($updatearr);     
      
    }

    if ($res) {
       $return_array =  array('status' => 200,'msg'=>'successfully','result'=>array());
      
    } else {

       $return_array =  array('status' => 400,'msg'=>'somthing went wrong','result'=>array());

    }

    echo json_encode($return_array);    

  }

  public function getLatLongByAddress($address) {
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


  public function createCustomerInQuickbook($param){

          $company_details = $this->checkQuickbook();

          if ($company_details) {


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


                  // Add a customer


              $cust_email =  isset($param['email']) ? trim($param['email']) : '';
              
              $quickbook_customer_id_check = $this->custCheckInQuickBook($dataService,$cust_email);


                if ($quickbook_customer_id_check) {

                  return array('status'=>201,'msg'=>'customer added successfully','result'=>$quickbook_customer_id_check);
                  
                } else {

                        // Add a customer
                        $customerObj = Customer::create([
                        
                        "BillAddr" => [
                          "Line1"=>  trim($param['billing_street']),
                          "City"=>  trim($param['billing_city']),
                          "Country"=>  "",
                          "CountrySubDivisionCode"=>  "",
                          "PostalCode"=>  trim($param['billing_zipcode'])
                          ],
                        "Notes" =>  "",
                        "Title"=>  "",
                        "GivenName"=>  trim($param['first_name']),
                        "MiddleName"=>  "",
                        "FamilyName"=>  trim($param['last_name']),
                        "Suffix"=>  "",
                        "FullyQualifiedName"=>trim($param['first_name']).' '.trim($param['last_name']),
                        "CompanyName"=> isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                        "DisplayName"=> trim($param['first_name']).' '.trim($param['last_name']),
                        "PrimaryPhone"=>  [
                            "FreeFormNumber"=> isset($param['phone']) ? trim($param['phone']) : '' 
                        ],
                        "PrimaryEmailAddr"=>  [
                            "Address" =>  isset($param['email']) ? trim($param['email']) : ''
                        ]



                        ]);

                        $resultingCustomerObj = $dataService->Add($customerObj);
                        $error = $dataService->getLastError();
                        if ($error) {
                            $return_error = '';
                            $return_error = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                            $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                            $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                            return array('status'=>400,'msg'=>'customer not added','result'=>$return_error);
                        } else {                
                            
                            return array('status'=>201,'msg'=>'customer added successfully','result'=>$resultingCustomerObj->Id);
                        }  

                } 

                                    

            
          } else {

            return array('status'=>400,'msg'=>'please intigrate quickbook account','result'=>'');

          }    
  }


  
  public function custCheckInQuickBook($dataService,$email=''){

    if ($email!='') {
    
        try{

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $entities = $dataService->Query("SELECT * FROM Customer where PrimaryEmailAddr ='".$email."'");
            $error = $dataService->getLastError();

            if ($error) {

                  $return_error = '';
                  $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                  $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                  $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                  return false;   

            } else {

                if(!empty($entities)) {
                    return $entities[0]->Id;                        
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




  public function updatCustomerInQickbook($quickbook_customer_id,$param){

    $company_details = $this->checkQuickbook();
    if ($company_details) {

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

        return   array('status'=>400,'msg'=>'auth failed','result'=>$return_error);
            
        } else {

            if(!empty($entities)) {

                $theCustomer = reset($entities);

                // var_dump($theCustomer);

                $updateCustomer = Customer::update($theCustomer, [
                    //If you are going to do a full Update, set sparse to false
                  
                
                    "BillAddr" => [
                      "Line1"=>  trim($param['billing_street']),
                      "City"=>  trim($param['billing_city']),
                      "Country"=>  "",
                      "CountrySubDivisionCode"=>  "",
                      "PostalCode"=>  trim($param['billing_zipcode'])
                      ],
                    "Notes" =>  "",
                    "Title"=>  "",
                    "GivenName"=>  trim($param['first_name']),
                    "MiddleName"=>  "",
                    "FamilyName"=>  trim($param['last_name']),
                    "Suffix"=>  "",
                    "FullyQualifiedName"=>trim($param['first_name']).' '.trim($param['last_name']),
                    "CompanyName"=> isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                    "DisplayName"=> trim($param['first_name']).' '.trim($param['last_name']),
                    "PrimaryPhone"=>  [
                        "FreeFormNumber"=> isset($param['phone']) ? trim($param['phone']) : '' 
                    ],
                    "PrimaryEmailAddr"=>  [
                        "Address" =>  isset($param['email']) ? trim($param['email']) : ''
                    ]

                ]);


                $resultingCustomerUpdatedObj = $dataService->Update($updateCustomer);

                if ($error) {
                  $return_error = '';
                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                    return array('status'=>400,'msg'=>'customer not added','result'=>$return_error);
                    
                } else {

                  $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingCustomerUpdatedObj, $urlResource);

                  return  array('status'=>200,'msg'=>'customer update successfully','result'=>'');
                }

            } else {

              return   array('status'=>404,'msg'=>'customer not found','result'=>'');


            } 

          }
      
    } else {
      
        return array('status'=>400,'msg'=>'please intigrate quickbook account','result'=>'');

    }
  
  }



  public function getOneQuickBookCustomer($quickbook_customer_id){

    $company_details = $this->checkQuickbook();
    if ($company_details) {

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
      
    } else {
      
        return false;

    }
  
  }


  public function QuickBookInv($param=array()){


          $customer_details = $this->CustomerModel->getCustomerDetail($param['customer_id']);

          if ($customer_details['quickbook_customer_id']!=0) {
              $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);
      
              if ($quickBookCustomerDetails) {
                  $param['quickbook_customer_id'] = $customer_details['quickbook_customer_id'];
                  
                  $result = $this->createInvoiceInQuickBook($param);
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


  public function createInvoiceInQuickBook($param){


    $company_details = $this->checkQuickbook();

        if ($company_details) {

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
            //Add a new Invoice

            // var_dump($param);
            // die();

            $details = getVisIpAddr();

            $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$param['invoice_id']));

            $description = 'Service Name: ' . $param['job_name'] . '. Service Description: ' . $param['actual_description_for_QBO'];

                $line_ar[] = array(
                "Description" => $description,
                "Amount" => $param['cost'],
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => array(
                  "TaxCodeRef" => array(
                    "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'
                      // "value" =>  'TAX'
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
                  "DocNumber" => $param['invoice_id'],
                  "TxnDate" => $param['invoice_date'],
                  "Line" =>$line_ar ,
                  "CustomerRef" => array(
                      "value" => $param['quickbook_customer_id'],
                  )
              );

              if ($param['email']!='') {

                  $invoice_arr['BillEmail'] = array(
                    "Address" => $param['email']
                  );
                  $invoice_arr['EmailStatus'] = "NeedToSend";
              }


            $theResourceObj = Invoice::create($invoice_arr);

            $resultingObj = $dataService->Add($theResourceObj);


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


        } else {

        return array('status'=>400,'msg'=>'please intigrate quickbook account','result'=>'');


        }
  }


  public function checkQuickbook() {
    $where = array(
      'company_id'=>$this->session->userdata['company_id'],
      'is_quickbook'=>1,
      'quickbook_status'=>1
    );

    $company_details = $this->CompanyModel->getOneCompany($where);

    if ($company_details) {


      try{


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

      } catch (Exception $ex) {
              return false;
      }      
    } else {
      return false;
    }

  }

  public function customerPortalPayment() {
    $data =  $this->input->post();
    // die(print_r($data));
    if($data['payment_status'] == 3 && isset($data['past_invoice_ids'])){
      ##### GETS ALL PAST DUE INVOICES #####
      $past_due_invoice =[];
      $past_due_cost =[];
      $invoice_sales_tax =[];
      $past_due_tax = [];
      foreach($data['past_invoice_ids'] as $pd => $invoice){
        array_push($past_due_invoice, $this->INV->getOneRow(array('invoice_id' => $invoice)));
        array_push($invoice_sales_tax, $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice)));
        // $invoice_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice));
      }
      ##### GETS ALL COST OF PAST DUE INVOICES #####
      foreach($past_due_invoice as $d => $value){

        $past_due_cost[$d]['invoice_id'] =  $value->invoice_id;
        $past_due_cost[$d]['customer_id'] =  $value->customer_id;
        $past_due_cost[$d]['cost'] = $value->cost;
      }
      // die(print_r($past_due_cost));

      ##### GETS ALL SALES TAX ON PAST DUE INVOICES #####
      foreach($invoice_sales_tax as $t => $tax){
        // foreach($tax as $t => $tax_amount){
          // array_push($due_tax, $tax_amount['tax_amount'])
          $past_due_tax[$t]['invoice_id'] = $tax[0]['invoice_id']; 
          $past_due_tax[$t]['tax_amount'] = $tax[0]['tax_amount']; 
        // }
      }

      ##### ADDS THE COST AND SALES TAX ON INVOICE #####
      $total_past_due = [];
      foreach($past_due_cost as $c){
        foreach($past_due_tax as $t){
          if($c['invoice_id'] == $t['invoice_id']){
            
            $total_past_due[] = array(
              'invoice_id' => $c['invoice_id'],
              'customer_id' => $c['customer_id'],
              'total_due' => $c['cost'] + $t['tax_amount'],
            );
          }
        }
      }
      ##### GET ALL PAYMENTS MADE #####
      $all_payments_details = [];
      array_push($all_payments_details, $this->PartialPaymentModel->getAllPartialPaymentWhereIn('invoice_id', $data['past_invoice_ids']));
      ##### SUBTRACT ANY PAYMENT MADE ON INVOICE #####
      $actual_due = [];
      foreach($total_past_due as $past_due){
        foreach($all_payments_details as $k => $payment_details){
          foreach($payment_details as $details){

          
            if(isset($payment_details) && $details->invoice_id == $past_due['invoice_id']){
              $actual_due[] = array(
                'invoice_id' => $past_due['invoice_id'],
                'customer_id' => $past_due['customer_id'],
                'payment_due' =>  $past_due['total_due'] - $details->payment_applied
              );
            } else if(empty($payment_details)) {
              $actual_due[] = array(
                'invoice_id' => $past_due['invoice_id'],
                'customer_id' => $past_due['customer_id'],
                'payment_due' =>  $past_due['total_due']
              );
            }
          }
        }
      }

      // $total_cost_all_partial_payment_logs += $data['partial_payment'];
      $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
        $param['partial_payment'] = $new_total_partial;
        $param['payment_status'] = 2;
        $param['payment_created'] = date("Y-m-d H:i:s");

        if($data['payment_method'] == 1) {
          $check_number = $data['payment_info'];
        } else if($data['payment_method'] == 2) {
          $cc_number = $data['payment_info'];
        } else if($data['payment_method'] == 3) {
          $other = $data['payment_info'];
        } 

        $result = $this->PartialPaymentModel->createOnePartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
          'payment_amount' =>$due_balance,
          'payment_applied' => $due_balance,
          'payment_datetime' => date("Y-m-d H:i:s"),
          'payment_method' => $data['payment_method'],
          'check_number' => (isset($check_number) ? $check_number : ''),
          'cc_number' => (isset($cc_number) ? $cc_number : ''),
          'payment_note' => (isset($other) ? $other : ''),
          'customer_id' => $invoice_details->customer_id
        ));
        
        $result = $this->INV->updateInvoive($where,$param);
        $invoice_details = $this->INV->getOneInvoive($where);
        
        if ($invoice_details->quickbook_invoice_id!=0) {
        $res = $this->QuickBookInvUpdate($invoice_details);
        }

        if ($result) {
        if (isset($err_msg)) {
          echo $err_msg;
          return;
        }
          echo "true";
        } else {
          echo "false";
        }
        return;

    } else {

      if (isset($data['total_due'])){
        $due_balance = (float)str_replace(' ', '', $data['total_due']);
      }
      else {
        $due_balance = 0;
      }

      $where = array(
      'invoice_id' => $data['invoice_id']
      );
      // die(print_r($where));
      $param = array(
      'payment_status' =>$data['payment_status'],
      'last_modify' => date("Y-m-d H:i:s")
      );

      $invoice_details = $this->INV->getOneInvoive($where);

      $total_tax_amount = getAllSalesTaxSumByInvoice($data['invoice_id'])->total_tax_amount;

      ////////////////////////////////////
      // START INVOICE CALCULATION COST //

      // vars 
      $tmp_invoice_id = $data['invoice_id'];

      // cost of all services (with price overrides) - service coupons
      $job_cost_total = 0;
      $where_alt = array(
        'property_program_job_invoice.invoice_id' => $tmp_invoice_id
      );
      $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where_alt);

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


      $over_all_due = $invoice_details->cost+$total_tax_amount;
      $over_all_due = $invoice_total_cost;

      if ($data['payment_status']==1) {
        $new_total_partial = $invoice_details->partial_payment + $data['partial_payment'];
        $total_cost_all_partial_payment_logs = 0;
        $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
        ));
        foreach($all_partial_payments as $partial_payment) {
          $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
        }

        // $total_cost_all_partial_payment_logs += $data['partial_payment'];
        $new_total_partial = $total_cost_all_partial_payment_logs + $data['partial_payment'];

        // if greater or equal, set partial to total and set to paid status
        if ($new_total_partial >= $over_all_due ) {

          $param['partial_payment'] = $over_all_due;
          $param['payment_status'] = 2;
          $param['payment_created'] = date("Y-m-d H:i:s");
          
          if($data['payment_method'] == 1) {
            $check_number = $data['payment_info'];
          } else if($data['payment_method'] == 2) {
            $cc_number = $data['payment_info'];
          } else if($data['payment_method'] == 3) {
            $other = $data['payment_info'];
          } 

          $result = $this->PartialPaymentModel->createOnePartialPayment(array(
            'invoice_id' => $tmp_invoice_id,
            'payment_amount' => $over_all_due - $total_cost_all_partial_payment_logs,
            'payment_applied' => $over_all_due - $total_cost_all_partial_payment_logs,
            'payment_datetime' => date("Y-m-d H:i:s"),
            'payment_method' => $data['payment_method'],
            'check_number' => (isset($check_number) ? $check_number : ''),
            'cc_number' => (isset($cc_number) ? $cc_number : ''),
            'payment_note' => (isset($other) ? $other : ''),
            'customer_id' => $invoice_details->customer_id
          ));

          $err_msg = "set to paid";

        } else {

          // $param['partial_payment'] = $new_total_partial;
          $param['partial_payment'] = $new_total_partial;
          $param['payment_created'] = date("Y-m-d H:i:s");

          if ($total_cost_all_partial_payment_logs > $over_all_due) {
            $param['partial_payment'] = $over_all_due;
            $param['payment_status'] = 2;
            $err_msg = "set to paid";
          } else {
            if($data['payment_method'] == 1) {
              $check_number = $data['payment_info'];
            } else if($data['payment_method'] == 2) {
              $cc_number = $data['payment_info'];
            } else if($data['payment_method'] == 3) {
              $other = $data['payment_info'];
            }  
            $result = $this->PartialPaymentModel->createOnePartialPayment(array(
              'invoice_id' => $tmp_invoice_id,
              'payment_amount' => $data['partial_payment'],
              'payment_applied' => $data['partial_payment'],
              'payment_datetime' => date("Y-m-d H:i:s"),
              'payment_method' => $data['payment_method'],
              'check_number' => (isset($check_number) ? $check_number : ''),
              'cc_number' => (isset($cc_number) ? $cc_number : ''),
              'payment_note' => (isset($other) ? $other : ''),
              'customer_id' => $invoice_details->customer_id
            ));
          }

        }
          
        $result = $this->INV->updateInvoive($where,$param);
        $invoice_details = $this->INV->getOneInvoive($where);
        if ($invoice_details->quickbook_invoice_id!=0) {
          $res = $this->QuickBookInvUpdate($invoice_details);
        }

        if ($result) {
          if (isset($err_msg)) {
            echo $err_msg;
            return;
          }
          echo "true";
        } else {
          echo "false";
        }
        return;

      } else if ($data['payment_status']==2  && $due_balance > 0) {

        $new_total_partial = $invoice_details->partial_payment + $due_balance;

        $total_cost_all_partial_payment_logs = 0;
        $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
        ));
        foreach($all_partial_payments as $partial_payment) {
          $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
        }

        // $total_cost_all_partial_payment_logs += $data['partial_payment'];
        $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
        // die(print_r(floatval($over_all_due)));
          $param['partial_payment'] = $new_total_partial;
          $param['payment_status'] = 2;

          $result = $this->PartialPaymentModel->createOnePartialPayment(array(
            'invoice_id' => $tmp_invoice_id,
            'payment_amount' =>$due_balance,
            'payment_applied' => $due_balance,
            'payment_datetime' => date("Y-m-d H:i:s"),
            'payment_method' => $data['payment_method'],
            'customer_id' => $invoice_details->customer_id
          ));
          
          $result = $this->INV->updateInvoive($where,$param);
          $invoice_details = $this->INV->getOneInvoive($where);
            
          if ($invoice_details->quickbook_invoice_id!=0) {
          $res = $this->QuickBookInvUpdate($invoice_details);
          }

          if ($result) {
          if (isset($err_msg)) {
            echo $err_msg;
            return;
          }
            echo "true";
          } else {
            echo "false";
          }
          return;
      } else if ($data['payment_status']== 3  && $due_balance > 0) {


        // 	$result = $this->PartialPaymentModel->createOnePartialPayment(array(
        // 		'invoice_id' => $tmp_invoice_id,
        // 		'payment_amount' =>$due_balance,
        // 		'payment_applied' => $due_balance,
        // 		'payment_datetime' => date("Y-m-d H:i:s"),
        // 		'payment_method' => $data['payment_method'],
        // 		'check_number' => (isset($check_number) ? $check_number : ''),
        // 		'cc_number' => (isset($cc_number) ? $cc_number : ''),
        // 		'payment_note' => (isset($other) ? $other : ''),
        // 		'customer_id' => $invoice_details->customer_id
        // 	));
          
        // 	$result = $this->INV->updateInvoive($where,$param);
        // 	$invoice_details = $this->INV->getOneInvoive($where);
          
        // 	if ($invoice_details->quickbook_invoice_id!=0) {
        // 	$res = $this->QuickBookInvUpdate($invoice_details);
        // 	}

        // 	if ($result) {
        // 	if (isset($err_msg)) {
        // 		echo $err_msg;
        // 		return;
        // 	}
        // 		echo "true";
        // 	} else {
        // 		echo "false";
        // 	}
        // 	return;

      } else if ($data['payment_status']==4  && $due_balance > 0) {
        // die(print_r($data));
        $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
        $data['refund_total'] = $invoice_nums->refund_amount_total;
        $data['partial_payment'] = $invoice_nums->partial_payment;
        $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
        ));
        $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
        foreach($all_partial_payments as $k => $payment){
          $all_payments[$k] = $payment->payment_amount;

          $payments_total = array_sum($all_payments);
        }
        #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
        $where = array(
          'invoice_id' =>$data['invoice_id']
        );

        ##### CREATE A NEW REFUND PAYMENT LOG #####
        
        if($data['payment_method'] == 1) {

          $check_number = $data['refund_note'];

        } else if($data['payment_method'] == 2) {

          $cc_number = $data['refund_note'];

        } else if($data['payment_method'] == 3) {

          $other = $data['refund_note'];

        }  else {
          $refund_note = $data['refund_note'];
        } 
        
        $param = array(
          // 'payment_invoice_logs_id' => $payment_log_id,
          'invoice_id' => $tmp_invoice_id,     
          'refund_amount' => $data['refund_payment'],
          // 'refund_amount' => $refund,
          'refund_datetime' => date("Y-m-d H:i:s"),
          'refund_method' => $data['payment_method'],
          'check_number' => (isset($check_number) ? $check_number : ''),
          'cc_number' => (isset($cc_number) ? $cc_number : ''),
          'refund_note' => (isset($other) ? $other : ''),
          'customer_id' => $invoice_details->customer_id
        );

        $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
        ####
        // $refund = $data['partial_payment'] - $data['refund_total'];
        $param = array(     
          // 'partial_payment' =>($payment_total - $payment_total),
          // 'refund_amount_total' => $data['refund_total'] + $refund
          'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
          'payment_status' => $data['payment_status']
        );

        $result = $this->INV->updateInvoive($where,$param);

        ##### UPDATE PAYMENT INVOICE LOG #####
        $where1 = array(
          'invoice_id' =>$data['invoice_id']
          );

        $param1= array(     
          'payment_applied' => 0
        );

        $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1,$param1);

          if ($result) {
            if (isset($err_msg)) {
              echo $err_msg;
              return;
            }
              echo "true";
            } else {
              echo "false";
            }
            return;
      } else if ($data['payment_status']==4  && $due_balance == 0) {
        $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
        $data['refund_total'] = $invoice_nums->refund_amount_total;
        $data['partial_payment'] = $invoice_nums->partial_payment;
        $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
        ));
        // $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
        foreach($all_partial_payments as $k => $payment){
          $all_payments[$k] = $payment->payment_amount;

          $payments_total = array_sum($all_payments);
        }
        #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
        $where = array(
          'invoice_id' =>$data['invoice_id']
        );
        
        // $refund = $data['partial_payment'] - $data['refund_total'];

        ##### CREATE A NEW REFUND PAYMENT LOG #####
        
        if($data['payment_method'] == 1) {

          $check_number = $data['refund_note'];

        } else if($data['payment_method'] == 2) {

          $cc_number = $data['refund_note'];

        } else if($data['payment_method'] == 3) {

          $other = $data['refund_note'];

        }  else {
          $refund_note = $data['refund_note'];
        } 
        
        $param = array(
          // 'payment_invoice_logs_id' => $payment_log_id,
          'invoice_id' => $tmp_invoice_id,     
          'refund_amount' => $data['refund_payment'],
          // 'refund_amount' => $refund,
          'refund_datetime' => date("Y-m-d H:i:s"),
          'refund_method' => $data['payment_method'],
          'check_number' => (isset($check_number) ? $check_number : ''),
          'cc_number' => (isset($cc_number) ? $cc_number : ''),
          'refund_note' => (isset($other) ? $other : ''),
          'customer_id' => $invoice_details->customer_id
        );

        $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
        ####
        // $refund = $data['partial_payment'] - $data['refund_total'];
        $param = array(     
          // 'partial_payment' =>($payment_total - $payment_total),
          // 'refund_amount_total' => $data['refund_total'] + $refund
          'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
          'payment_status' => $data['payment_status']
        );

        $result = $this->INV->updateInvoive($where,$param);

        ##### UPDATE PAYMENT INVOICE LOG #####
        $where1 = array(
          'invoice_id' =>$data['invoice_id']
          );

        $param1= array(     
          'payment_applied' => 0
        );

        $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1,$param1);
        if ($result) {
          if (isset($err_msg)) {
            echo $err_msg;
            return;
          }
            echo "true";
          } else {
            echo "false";
          }
          return;
      } else {
        $total_cost_all_partial_payment_logs = 0;
        $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
          'invoice_id' => $tmp_invoice_id,
        ));
        foreach($all_partial_payments as $partial_payment) {
          $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
        }
        $param['partial_payment'] = $total_cost_all_partial_payment_logs;
      }

      $result = $this->INV->updateInvoive($where,$param);
      $invoice_details = $this->INV->getOneInvoive($where);

      if ($invoice_details->quickbook_invoice_id!=0) {

        $res = $this->QuickBookInvUpdate($invoice_details);

          //var_dump($res);
      }

      if ($result) {
        echo "true";
      } else {
        echo "false";
      }
    }
  }

  public function updateCustomer(){

    $user_id = $this->session->userdata['user_id'];
    $post_data = $this->input->post();
    // die(print_r($post_data));
    $customerid = $this->input->post('customer_id');
   
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim');
    $this->form_validation->set_rules('customer_company_name', 'customer_company_name', 'trim');
    $this->form_validation->set_rules('email', 'Email', 'trim');
    $this->form_validation->set_rules('phone', 'Phone', 'trim');
    $this->form_validation->set_rules('billing_street', 'Billing Street', 'required');
    $this->form_validation->set_rules('billing_street_2', 'Billing Street 2', 'trim');
    $this->form_validation->set_rules('billing_city', 'City', 'required');
    $this->form_validation->set_rules('billing_state', 'State', 'required');
    $this->form_validation->set_rules('billing_zipcode', 'ZipCode', 'required');

    if ($this->form_validation->run() == FALSE){                   
      $this->editCustomer($customerid);       
    }else {      
      $post_data = $this->input->post();

      $param = array(
        'user_id' => $user_id,
        'first_name' => $post_data['first_name'],
        'last_name' => $post_data['last_name'],
        'customer_company_name' => $post_data['customer_company_name'],
        'email' => $post_data['email'],
        'phone' => $post_data['phone'],
        'home_phone' => $post_data['home_phone'],
        'work_phone' => $post_data['work_phone'],
        'billing_street' => $post_data['billing_street'],             
        'billing_street_2' => $post_data['billing_street_2'],
        'billing_city' => $post_data['billing_city'],
        'billing_state' => $post_data['billing_state'],
        'billing_zipcode' => $post_data['billing_zipcode'],
        // 'customer_status' => $post_data['customer_status']

      );

      if (isset($post_data['clover_autocharge'])) {
        $param['clover_autocharge'] = 1;
    } else {
        $param['clover_autocharge'] = 0;
    }

      if (isset($post_data['basys_autocharge'])) {
        $param['basys_autocharge'] = 1;
      } else {
        $param['basys_autocharge'] = 0;
      }

      if (isset($post_data['is_email'])) {
        $param['is_email'] = 1;
      } else {
        $param['is_email'] = 0;
      }
    
      if (isset($post_data['is_mobile_text'])) {
        $param['is_mobile_text'] = 1;
      } else {
        $param['is_mobile_text'] = 0;
      }

      $customer_details = $this->CustomerModel->getCustomerDetail($customerid);
      // die($this->db->last_query());
      if ($customer_details['quickbook_customer_id']!=0) {
        $res = $this->updatCustomerInQickbook($customer_details['quickbook_customer_id'],$param);
        if ($res['status']==404) {
              $param['quickbook_customer_id'] = 0;
        }
      }  

      $param['secondary_email'] = $post_data['secondary_email_list_hid'];

      $result = $this->CustomerModel->updateAdminTbl($customerid, $param);
            
      if (!$result) {

          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

          redirect("customers/dashboard/$customerid");
      } else {

          
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> updated successfully</div>');
          
        redirect("customers/dashboard/$customerid");
      }
    }
  }

   

/*//////////////////////// Ajax Code End Here  ///////////// */

  public function updateTextAlert(){
    $post_data = $this->input->post();
      // die(print_r($post_data));
    $customerid = $this->input->post('customer_id');
    // die(print_r($customerid));
    $company_id =  $this->session->userdata['company_id'];
    $data['customerData'] = $this->CustomerModel->getCustomerDetail($customerid);
    // die(print_r($data['customerData']));
    $where = array('company_id' =>$this->session->userdata['company_id']);
    $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

    $param['is_mobile_text'] = $post_data['accept'];

      $result = $this->CustomerModel->updateAdminTbl($customerid, $param);

      ##### TEXT MESSAGE #####
      $emaildata['company_text_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $company_id));
      //  die($this->db->last_query());
      // die(print_r($emaildata['company_email_details']));

      if ($this->session->userdata['is_text_message'] &&  $data['customerData']['is_mobile_text'] ==1) {

        //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->program_assigned_text);
        $company_name = $data['setting_details']->company_name;
        $company_phone = $data['setting_details']->company_phone_number;
      
        $data['company_text_details'] ="{$company_name} Updates! Msg&Data rates may apply. 3 msg/month. Reply 'STOP' to cancel.  Call/text {$company_phone} for help";
      
        $text_res = Send_Text_dynamic($data['customerData']['phone'],$data['company_text_details'],'Program Assigned');

      #### END TEXT MESSAGE ####
       
      $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> updated successfully</div>');
        
      // redirect("customers/dashboard/$customerid");
      echo json_encode(array('status'=>'success'));
    }

    
  }

    public function cloverUpdateCustomerPayment()
    {
        $data = $this->input->post();
        //die(print_r($data));
        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'company_id' => $company_id,
            'status' => 1
        );

        $clover_details = $this->CardConnectModel->getOneCardConnect($where);

        if ($clover_details){

            // die(print_r($data));

            $tokenAcct = array(
                'tokenData' => $data['tokenData']
            );

            $tokenize = cardConnectTokenizeAccount($tokenAcct);

            // die(print_r($tokenize['result']->token));

            // die(print_r($tokenize));

            if ($tokenize){
                $param = array(
                    'username' => $clover_details->username,
                    'password' => decryptPassword($clover_details->password),
                    'proData' => array(
                        'merchid' => $clover_details->merchant_id,
                        'profile' => $data['clover_token'] . '/' . $data['clover_acct'],
                        'account' => $tokenize['result']->token,
                        'cof' => 'M',
                        'auoptout' => 'Y',
                        'cofpermission' => 'Y',
                        'profileupdate' => 'Y',
                        'expiry' => $data['tokenData']['expiry'],
                    )
                );



                $updated = updateCloverProfile($param);

                // die(print_r($updated));

                if ($updated['status'] == 200){

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');

                    $return_arr = array('status' => 200, 'msg' => 'Payment Credentials Updated Successfully.', 'result' => $updated['result']);
                } else {
                    $return_arr = array('status' => 400, 'msg' => $updated['result']->resptext, 'result' => $updated['result']);
                }
            } else {
                $return_arr = array('status' => 400, 'msg' => $tokenize['result']->message);
            }

        } else {
            $return_arr = array('status' => 400, 'msg' => 'Customer Not Found');
        }
        echo json_encode($return_arr);
    }

    public function cloverAddCustomer()
    {
        $data = $this->input->post();
        //die(print_r($data));

        $company_id = $this->session->userdata('company_id');
        $cardconnect_details = $this->CardConnectModel->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));

        $customer_details = $this->CustomerModel->getCustomerDetail($data['customer_id']);



        if ($customer_details) {

            $tokenAcct = array(
                'tokenData' => $data['tokenData']
            );

            // die(print_r($tokenAcct));

            $token = cardConnectTokenizeAccount($tokenAcct);

            // die(print_r($token));

            if($token){

                $cc_auth = array(
                    'username' => $cardconnect_details->username,
                    'password' => decryptPassword($cardconnect_details->password),
                    'merchid' => $cardconnect_details->merchant_id,
                    'requestData' => array(
                        'merchid' => $cardconnect_details->merchant_id,
                        'account' => $token['result']->token,
                        'email' => $customer_details['email'],
                        'ecomind' => 'R',
                        'cof' => 'M',
                        'cofpermission' => 'Y',
                        'cofscheduled' => 'N',
                        'name' => $customer_details['first_name'] . ' ' . $customer_details['last_name'],
                        'address' => $customer_details['billing_street'],
                        'city' => $customer_details['billing_city'],
                        'region' => $customer_details['billing_state'],
                        'postal' => $customer_details['billing_zipcode'],
                        'profile' => 'Y',
                        'amount' => number_format(0.00, 2)
                    )
                );

                $cc_authorize = cardConnectAuthorize($cc_auth);

                // die(print_r($cc_authorize));

                if ($cc_authorize['status'] == 200){

                    if ($cc_authorize['result']->respstat == 'A'){
                        $where = array('customer_id' => $data['customer_id']);
                        $param = array(
                            'customer_clover_token' => $cc_authorize['result']->profileid,
                            'clover_acct_id' => $cc_authorize['result']->acctid,
                            'clover_autocharge' => 1,
                            'basys_customer_id' => '',
                            'basys_autocharge' => 0
                        );
                        $this->CustomerModel->updateCustomerData($param, $where);

                        // die(print_r($cc_authorize));

                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');

                        $return_arr = array('status' => 200, 'msg' => 'Payment Credentials Added Successfully.', 'result' => $cc_authorize['result']);
                    } else {
                        $return_arr = array('status' => 400, 'msg' => $cc_authorize['result']->resptext, 'result' => $cc_authorize['result']);
                    }
                } else {
                    $return_arr = array('status' => 400, 'msg' => $cc_authorize['message']);
                }

            } else {
                $return_arr = array('status' => 400, 'msg' => $token['result']->message);
            }


        } else {
            $return_arr = array('status' => 400, 'msg' => 'Customer Not Found');
        }
        echo json_encode($return_arr);
    }
}