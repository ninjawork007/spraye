<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require FCPATH . 'vendor/autoload.php';
include APPPATH ."libraries/dompdf/autoload.inc.php";

class Reports extends MY_Controller {


    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }
        $this->load->library('parser');
        $this->load->helper('text');
        $this->load->helper('job_helper');
        $this->loadModel();
        // $this->load->library('Ajax_pagination');
        // $this->perPage = 10;
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
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('Reports_model', 'RP');
        $this->load->helper('report_helper');
        $this->load->model('Sales_tax_model', 'SalesTax');
        $this->load->model('Invoice_model','INV');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('AdminTbl_property_model', 'PropertyModel');
        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
		$this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
		$this->load->model('AdminTbl_coupon_model', 'CouponModel');
		$this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
		$this->load->model('AdminTbl_program_model', 'ProgramModel');
		$this->load->model('Job_model', 'JobModel');
		$this->load->model('Dashboard_model', 'DashboardModel');
		$this->load->helper('estimate_helper');
		$this->load->model('Estimate_model', 'EstimateModal');
		$this->load->model('AdminTbl_company_model', 'CompanyModel');
		$this->load->helper('invoice_helper');
		$this->load->model('AdminTbl_company_model', 'CompanyModel');
		$this->load->model('Commissions_model', 'CommissionModel');
		$this->load->model('Bonuses_model', 'BonusModel');
        $this->load->model('Job_product_model', 'JobAssignProduct');
		$this->load->model('Cancelled_services_model','CancelledModel');
        $this->load->model('Source_model', 'SourceModel');
        $this->load->model('Save_tech_eff_report_model', 'TechEffReportModel');
        $this->load->model('Save_sales_summary_filter_model', 'SalesSummarySaveModel');
        $this->load->model('Save_service_summary_filter_model', 'ServiceSummarySaveModel');
        $this->load->model('Save_sales_pipeline_filter_model', 'SaveSalesPipelineFilterModel');
        $this->load->model('Payment_invoice_logs_model', 'PartialPaymentModel');
        $this->load->model('MassEmailModel', 'MassEmailModel');
    }



    public function testing($value=''){

        $report_details = $this->db->join('technician_job_assign',"technician_job_assign.technician_job_assign_id=report.technician_job_assign_id","inner")->get('report')->result();
        echo "<pre>";
    
        foreach ($report_details as $key => $value) {
            
             $where = array(
             'customer_id' => $value->customer_id,
             'property_id' => $value->property_id,
             'job_id' => $value->job_id,
             'program_id' => $value->program_id

             );

          $invoice_details  =  $this->db->where($where)->get('invoice_tbl')->row();
          
            if ($invoice_details) {

                $param = array (
                     'cost' =>  $invoice_details->cost,  
                     'tax_name' =>  $invoice_details->tax_name,  
                     'tax_value' =>  $invoice_details->tax_value,                    
                     'tax_amount' =>  $invoice_details->tax_amount           
                );

              $this->db->where('report_id',$value->report_id)->update('report',$param);
           }

        }       
    }

## Completed Service Log Report
    public function index()
    {   
        //get the posts data
        $data['report_details'] = $this->RP->getAllRepots();
		//die(print_r($data));
	    $page["active_sidebar"] = "reports";
        $page["page_name"] = 'Completed Service Log';
        $page["page_content"] = $this->load->view("admin/report/view_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
 
   
## ajax data for Completed Service Log Report
     function ajaxPaginationData(){
        $conditions = array();
        
        //set conditions for search
        $customer_name = $this->input->post('customer_name');
        $technician_name = $this->input->post('technician_name');
        $product_name = $this->input->post('product_name');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');

        if(!empty($customer_name)){
            $conditions['search']['customer_name'] = $customer_name;
        }
        if(!empty($technician_name)){
            $conditions['search']['technician_name'] = $technician_name;
        }

        if(!empty($job_completed_date_to)){
            $conditions['search']['job_completed_date_to'] = $job_completed_date_to;
        }
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }

         if(!empty($product_name)){
            $conditions['search']['product_name'] = $product_name;
        }
        
         //get posts data
        $data['report_details'] = $this->RP->getAllRepots($conditions);
           
        $body =  $this->load->view('admin/report/ajax_report', $data, false);

        echo $body;

    }



## Download CSV for Completed Service Log Report
    public function downloadCsv(){

        $status = '';
        $conditions = array();
        $customer_name = $this->input->post('customer_name');
        $technician_name = $this->input->post('technician_name');
        $product_name = $this->input->post('product_name');


        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');
       
        if(!empty($customer_name)){
            $conditions['search']['customer_name'] = $customer_name;
        }
        if(!empty($technician_name)){
            $conditions['search']['technician_name'] = $technician_name;
        }

        if(!empty($job_completed_date_to)){
            $conditions['search']['job_completed_date_to'] = $job_completed_date_to;
        }
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }

          if(!empty($product_name)){
            $conditions['search']['product_name'] = $product_name;
        }
    
        $data = $this->RP->getAllRepots($conditions);

        // echo $this->db->last_query();
        // die();
        if($data){

            
                    $delimiter = ",";
                    $filename = "report_" . date('Y-m-d') . ".csv";
                    
                    //create a file pointer
                    $f = fopen('php://memory', 'w');
                    //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
                    
                    //set column headers
                    $fields = array('User','Applicator Number','Customer','Invoice Amount','Property Name','City','Sq. Ft.','State','Zip','Property Address','Product','Service Name','EPA #','Application Rate','Estimate of Chemical Used', 'Amount of Mixture Applied', 'Weed/Pest Prevented','Active Ingredients','Application Type','Re-Entry Time','Chemical Type', 'Restricted Product','Application Method', 'Area of Property Treated', 'Wind Speed','Wind Direction','Temperature','Date','Time Started', 'Time Completed');
                    fputcsv($f, $fields, $delimiter);
                    
                    //output each row of the data, format line as csv and write to file pointer
                 
                   
                    foreach ($data as $key => $value) {
                        $status = 1;

                        $product_name = '';
                        $service_name = '';
                        $epa_reg_nunber = '';
                        $application_rate = '';
                        $estimate_of_pesticide_used = '';
                        $amount_of_mixture_applied = '';
                        $weed_pest_prevented =  '' ;
                       
                        $active_ingredients =  '' ;
                        $application_type =  '' ;
                        $re_entry_time =  '' ;
                        $chemical_type =  '' ;
						$restricted_product = '';
                        $application_method =  '' ;
                        $area_of_property_treated =  '' ;


                        $product_details = reportProductDetails($value->thereportid);

                        if ($product_details) {

                                  $product_name_ar =   array_column($product_details, 'product_name');
                                  $product_name =  implode(", ", $product_name_ar);
  
                                  $epa_reg_nunber_ar =   array_column($product_details, 'epa_reg_nunber');
                                  $epa_reg_nunber =  implode(", ", $epa_reg_nunber_ar);
                                   
                                  $application_rate_ar =   array_column($product_details, 'application_rate');
                                  $application_rate =  implode(", ", $application_rate_ar);
                                   
                                  $estimate_of_pesticide_used_ar =   array_column($product_details, 'estimate_of_pesticide_used');
                                  $estimate_of_pesticide_used =  implode(", ", $estimate_of_pesticide_used_ar);

                                  $amount_of_mixture_applied_ar =   array_column($product_details, 'amount_of_mixture_applied');
                                  $amount_of_mixture_applied =  implode(", ", $amount_of_mixture_applied_ar);
                                   
                                  $weed_pest_prevented_ar =   array_column($product_details, 'weed_pest_prevented');
                                  $weed_pest_prevented =  implode(", ", $weed_pest_prevented_ar);
                                 
                                  $active_ingredients_ar =   array_column($product_details, 'active_ingredients');
                                  $active_ingredients =  implode(", ", $active_ingredients_ar);

                                  $application_type_ar =   array_column($product_details, 'application_type');
                                  $application_type =  implode(", ", $application_type_ar);

                                  $re_entry_time_ar =   array_column($product_details, 're_entry_time');
                                  $re_entry_time =  implode(", ", $re_entry_time_ar);
                                   
 
                                  $chemical_type_ar =   array_column($product_details, 'chemical_type');
                                  $chemical_type =  implode(", ", $chemical_type_ar);
                                   
								  $restricted_product_ar =   array_column($product_details, 'restricted_product');
                                  $restricted_product =  implode(", ", $restricted_product_ar);

                                  $application_method_ar =   array_column($product_details, 'application_method');
                                  $application_method =  implode(", ", $application_method_ar);

                                  $area_of_property_treated_ar =   array_column($product_details, 'area_of_property_treated');
                                  $area_of_property_treated =  implode(", ", $area_of_property_treated_ar);
                                   
                        }

                        
                        $wind_speed =  round(/*2.23694**/($value->wind_speed),2).' MPH';

                        //die(print_r($value));

                        $lineData = array($value->user_first_name.' '.$value->user_last_name,$value->applicator_number, $value->first_name.' '.$value->last_name , $value->cost,$value->property_title, $value->property_city, $value->yard_square_feet, $value->property_state, $value->property_zip,$value->property_address, $product_name,$value->job_name, $epa_reg_nunber, $application_rate, $estimate_of_pesticide_used, $amount_of_mixture_applied, $weed_pest_prevented, $active_ingredients, $application_type, $re_entry_time, $chemical_type, $restricted_product, $application_method, $area_of_property_treated, $wind_speed, $value->direction, $value->temp.' F', $value->job_completed_date,  $value->job_start_time, $value->job_completed_time);
 
                        fputcsv($f, $lineData, $delimiter);
                       
                        }


                        if ($status==1) {

                                        //move back to beginning of file
                            fseek($f, 0);
                            
                            //set headers to download file rather than displayed
                            header('Content-Type: text/csv');
                              //  $pathName =  "down/".$filename;
                            header('Content-Disposition: attachment; filename="' .$filename. '";');
                            
                            //output all remaining data on a file pointer
                            fpassthru($f);
                            
                        } else {
                                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                                redirect("admin/reports");


                        }                    
                

        } else {


                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
                redirect("admin/reports");




        }    

    }

## Invoice Age Report
	public function invoiceAgeReport(){
		$company_id = $this->session->userdata['company_id'];
		#populate filter dropdowns
		$data['customers'] = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
		$data['service_areas'] = $this->ServiceArea->getAllServiceArea(array('company_id'=>$this->session->userdata['company_id']));
		$data['tax_details'] = $this->SalesTax->getAllSalesTaxArea(array('company_id'=>$this->session->userdata['company_id']));
		
		#get report data
		$report_data = array();
		
		#get customer invoices
		$customer_invoices = array();
		$current = [];
		$aged30 = [];
		$aged60 = [];
		$aged90 = [];
		if(isset($data['customers']) && !empty($data['customers'])){
			foreach($data['customers'] as $customer){
				//echo "CUSTOMER: ".$customer->customer_id."<br>";
				$customer_invoices[$customer->customer_id] = array();

				$whereArr = array(
					'customer_id'=>$customer->customer_id,
					'status !='=>0, //where status != unsent
					'payment_status !='=>2, //where payment_status != paid
					'is_archived'=>0, //where not archived
				); 
				$invoices = $this->INV->getAllInvoicesReport($whereArr);

				$current_amount_due = 0;
				$aged30_amount_due = 0;
				$aged60_amount_due = 0;
				$aged90_amount_due = 0;

				foreach($invoices as $invoice){
				#Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
					#check for coupons at customer, property, job level

					$job_cost_total = 0;
					$invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id'=>$invoice->invoice_id));
					if (!empty($invoice_jobs)) {
						foreach($invoice_jobs as $job) {
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
					}else {
						#account for old invoicing process
						$invoice_total_cost = $invoice->cost;
					}
					#check for coupons at invoice level
					$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id ));
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


					$amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

							/*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
								'cost'=>$invoice->cost,
								'cost_after_coupons'=>$invoice_total_cost,
								'tax_amount'=>$invoice->tax_amount,
								'partial_payment'=>$invoice->partial_payment,
								'total_amount_due'=>$amount_due,
							); */
					#get age of invoice
					$now = new DateTime('now');
					$invoice_date = new DateTime($invoice->first_sent_date);
					$aged = $invoice_date->diff($now);
					$aged = $aged->format('%r%a');

					if($aged <= 30){
						$current[] = $invoice->invoice_id;
						$current_amount_due += $amount_due;
					}elseif($aged > 30 && $aged <= 60){
						$aged30[] = $invoice->invoice_id;
						$aged30_amount_due += $amount_due;
					}elseif($aged > 60 && $aged <= 90){
						$aged60[] = $invoice->invoice_id;
						$aged60_amount_due += $amount_due;
					}elseif($aged > 90){
						$aged90[] = $invoice->invoice_id;
						$aged90_amount_due += $amount_due;
					}

						//echo "Invoice ID: ".$invoice->invoice_id." - Today: ".date('Y-m-d')." - Invoice Date: ".$invoice->invoice_date." - Aged: ".$aged."<br>";
				}

				$customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
				$customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
				$customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
				$customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
				$customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
				$customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
				$customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

				$customer_total_due = $current_amount_due + $aged30_amount_due + $aged60_amount_due + $aged90_amount_due;
				$customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

						//echo "TOTAL 0-30 Days: ".count($current)."<br>";
						//echo "TOTAL 31-60 Days: ".count($aged30)."<br>";
						//echo "TOTAL 61-90 Days: ".count($aged60)."<br>";
						//echo "TOTAL 90+ Days: ".count($aged90)."<br><br>";

				#remove customers with $0 balance
				if($customer_total_due > 0){
					$report_data[] = $customer_invoices[$customer->customer_id];
				}
				if(is_array($current) && count($current) > 0){
					$data['current_invoices'] = implode(',',$current);
				}
				if(is_array($aged30) && count($aged30) > 0){
					$data['aged30_invoices'] = implode(',',$aged30);
				}
				if(is_array($aged60) && count($aged60) > 0){
					$data['aged60_invoices'] = implode(',',$aged60);
				}
				if(is_array($aged90) && count($aged90) > 0){
					$data['aged90_invoices'] = implode(',',$aged90);
				}



			}
		}
		//die(print_r($data));
		
		$data['report_details'] = $report_data;
		$page["active_sidebar"] = "invoiceAgeReport";
        $page["page_name"] = 'Invoice Age Report';
        $page["page_content"] = $this->load->view("admin/report/view_invoice_age_report", $data, TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
	}

    ## Revenue by Service Type Report
    public function revenueServieType(){
        $company_id = $this->session->userdata['company_id'];
        $data['ServiceArea'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);
        
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
            'invoice_created >=' => date("Y-01-01"),
        );
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);
        $data['AllServiceType'] = $this->RP->get_job_company($this->session->userdata['company_id']);

        $ServiceTypeID = array();
        
        foreach($data['invoices'] as $Index => $INVs){
            $param = array('property_program_job_invoice.invoice_id' => $INVs->invoice_id);
            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $INVs->invoice_id));
            $TotalPayment = 0;
            foreach($all_invoice_partials as $PayPart){
                $TotalPayment += $PayPart->payment_amount;
            }
            $data['invoices'][$Index]->payment = $TotalPayment;

            $jobs = array();
            if ($details) {
                foreach ($details as $detail) {
                    $JobDetails = $this->RP->get_job_detail($detail['job_id']);
                    if($TotalPayment > $detail['job_cost']){
                        $PaidAmount = $detail['job_cost'];
                        $TotalPayment -= $detail['job_cost'];
                    }else{
                        $PaidAmount = $TotalPayment;
                    }

                    $jobs[] = array(
                        'job_name' => $detail['job_name'],
                        'job_cost' => $detail['job_cost'],
                        'PaidAmount' => $PaidAmount
                    );
                    $ServiceType = 0;
                    if(isset($JobDetails[0]->service_type_id)){
                        $ServiceType = $JobDetails[0]->service_type_id;
                    }

                    $ServiceTypeID[$ServiceType] += $PaidAmount;
                }
            }
            
            $data['invoices'][$Index]->Jobs = $jobs;
        }

        $data['Services'] = $ServiceTypeID;

        $page["active_sidebar"] = "revenueServieType";
        $page["page_name"] = 'Revenue By Service Type';
        $page["page_content"] = $this->load->view("admin/report/view_revenue_service_type", $data, TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    public function ajaxDataForTevenueServieType(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
        );

        if($start_date != ""){
            $whereArr['invoice_created >='] = $start_date;
        }

        if($end_date != ""){
            $whereArr['invoice_created <='] = $end_date;
        }
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);

        $data['StartDate'] = $data['invoices'][0]->invoice_created;
        $data['EndDate'] = $data['invoices'][count($data['invoices']) -1]->invoice_created;

        $data['AllServiceType'] = $this->RP->get_job_company($this->session->userdata['company_id']);

        $ServiceTypeID = array();
        
        foreach($data['invoices'] as $Index => $INVs){
            $param = array('property_program_job_invoice.invoice_id' => $INVs->invoice_id);
            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $INVs->invoice_id));
            $TotalPayment = 0;
            foreach($all_invoice_partials as $PayPart){
                $TotalPayment += $PayPart->payment_amount;
            }
            $data['invoices'][$Index]->payment = $TotalPayment;

            $jobs = array();
            if ($details) {
                foreach ($details as $detail) {
                    $JobDetails = $this->RP->get_job_detail($detail['job_id']);
                    if($TotalPayment > $detail['job_cost']){
                        $PaidAmount = $detail['job_cost'];
                        $TotalPayment -= $detail['job_cost'];
                    }else{
                        $PaidAmount = $TotalPayment;
                    }

                    $jobs[] = array(
                        'job_name' => $detail['job_name'],
                        'job_cost' => $detail['job_cost'],
                        'PaidAmount' => $PaidAmount
                    );
                    $ServiceType = 0;
                    if(isset($JobDetails[0]->service_type_id)){
                        $ServiceType = $JobDetails[0]->service_type_id;
                    }

                    if($customer != ""){
                        if($customer == $JobDetails[0]->service_type_id){
                            $ServiceTypeID[$ServiceType] += $PaidAmount;
                        }
                    }else{
                        $ServiceTypeID[$ServiceType] += $PaidAmount;
                    }
                }
            }
            $data['invoices'][$Index]->Jobs = $jobs;
        }
        $data['Services'] = $ServiceTypeID;
        $body =  $this->load->view('admin/report/ajax_revenue_service_type', $data, false);
    }

    public function downloadRevueTypeCSV(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
        );

        if($start_date != ""){
            $whereArr['invoice_created >='] = $start_date;
        }

        if($end_date != ""){
            $whereArr['invoice_created <='] = $end_date;
        }
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);

        $StartDate = $data['invoices'][0]->invoice_created;
        $EndDate = $data['invoices'][count($data['invoices']) -1]->invoice_created;

        $data['AllServiceType'] = $this->RP->get_job_company($this->session->userdata['company_id']);

        $ServiceTypeID = array();
        
        foreach($data['invoices'] as $Index => $INVs){
            $param = array('property_program_job_invoice.invoice_id' => $INVs->invoice_id);
            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $INVs->invoice_id));
            $TotalPayment = 0;
            foreach($all_invoice_partials as $PayPart){
                $TotalPayment += $PayPart->payment_amount;
            }
            $data['invoices'][$Index]->payment = $TotalPayment;

            $jobs = array();
            if ($details) {
                foreach ($details as $detail) {
                    $JobDetails = $this->RP->get_job_detail($detail['job_id']);
                    if($TotalPayment > $detail['job_cost']){
                        $PaidAmount = $detail['job_cost'];
                        $TotalPayment -= $detail['job_cost'];
                    }else{
                        $PaidAmount = $TotalPayment;
                    }

                    $jobs[] = array(
                        'job_name' => $detail['job_name'],
                        'job_cost' => $detail['job_cost'],
                        'PaidAmount' => $PaidAmount
                    );
                    $ServiceType = 0;
                    if(isset($JobDetails[0]->service_type_id)){
                        $ServiceType = $JobDetails[0]->service_type_id;
                    }

                    if($customer != ""){
                        if($customer == $JobDetails[0]->service_type_id){
                            $ServiceTypeID[$ServiceType] += $PaidAmount;
                        }
                    }else{
                        $ServiceTypeID[$ServiceType] += $PaidAmount;
                    }
                }
            }
        }

        
        if(is_array($ServiceTypeID) && count($ServiceTypeID) > 0){
             $delimiter = ",";
             $filename = "revenue_service_type" . date('Y-m-d') . ".csv";
            
            #create a file pointer
             $f = fopen('php://memory', 'w');
            
        
            #set column headers
             $fields = array('Date','Service Type','Amount');
             fputcsv($f, $fields, $delimiter);
            
            #output each row of the data, format line as csv and write to file pointer
            $total_current = 0;
            $total_30 = 0;
            $total_60 = 0;
            $total_90 = 0;
            $total_all = 0;
            foreach ($ServiceTypeID as $Index => $ServiceName) {
                $ServiceTypeName = "";
                if($Index == 0 || $Index == ""){
                    $ServiceTypeName = "NONE SELECTED   ";
                }else{
                    $Serv = $this->db->select('*')->from("service_type_tbl")->where(array("service_type_id" => $Index))->get()->row();
                    $ServiceTypeName = $Serv->service_type_name;
                }

                $lineData = array(date("m/d/Y", strtotime($StartDate)) . " TO " . date("m/d/Y", strtotime($EndDate)), $ServiceTypeName, $ServiceName);

                fputcsv($f, $lineData, $delimiter);
            }

            #move back to beginning of file
            fseek($f, 0);

            #set headers to download file rather than displayed
            header('Content-Type: text/csv'); 
            header('Content-Disposition: attachment; filename="' .$filename. '";');

            #output all remaining data on a file pointer
            fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/revenueServieType");
        }    
    }


    ## Customer Credit Report
    public function creditReport(){
        $company_id = $this->session->userdata['company_id'];
        #populate filter dropdowns
        $data['customers'] = $this->CustomerModel->getCustomerList(array('company_id' => $this->session->userdata['company_id']));

        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_credit' => 1
        );
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);

        $page["active_sidebar"] = "creditReport";
        $page["page_name"] = 'Customer Credit Report';
        $page["page_content"] = $this->load->view("admin/report/view_credit_report", $data, TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }


    #List all cancelled serive
    public function cancelService(){
        $company_id = $this->session->userdata['company_id'];
        #populate filter dropdowns
        $data['customers'] = $this->CustomerModel->getCustomerList(array('company_id' => $this->session->userdata['company_id']));
        $data['jobs'] = $this->JobModel->getAllJob(array('jobs.company_id' =>$this->session->userdata['company_id']));

        $data['all_services'] = $this->DashboardModel->getCustomerAllServicesWithSalesRep(array('jobs.company_id' => $company_id, 'property_tbl.company_id' => $company_id));

        $NewServiceArray = array();

        $TotalRevenueLost = 0;
        $TotlaNewRevenueLost = 0;
        $TotalExistingRevenueLost = 0;
        $data['setting_details'] = $this->CompanyModel->getOneCompany( array( 'company_id' => $this->session->userdata['company_id'] ));

        foreach($data['all_services'] as $all_services) {
            $cost = 0;
            $canc_arr = array(
                'job_id' => $all_services->job_id,
                'customer_id' => $all_services->customer_id,
                'property_id' => $all_services->property_id
            );
            $CenInfo = $this->CancelledModel->getCancelledServiceInfo($canc_arr);

            if($all_services->job_cost == NULL && isset($CenInfo[0]->is_cancelled)) {
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
                    $difficulty_multiplier = 0;

                    if (isset($all_services->difficulty_level) && $all_services->difficulty_level == 2) {
                        $difficulty_multiplier = $data['setting_details']->dlmult_2;
                    } elseif (isset($all_services->difficulty_level) && $all_services->difficulty_level == 3) {
                        $difficulty_multiplier = $data['setting_details']->dlmult_3;
                    } else {
                        if(isset($data['setting_details']->dlmult_1)){
                            $difficulty_multiplier = $data['setting_details']->dlmult_1;
                        }
                    }

                    //get base fee 
                    $base_fee = 0;
                    if (isset($all_services->base_fee_override)) {
                        $base_fee = $all_services->base_fee_override;
                    } else {
                        if(isset($data['setting_details']->base_service_fee)){
                            $base_fee = $data['setting_details']->base_service_fee;
                        }
                    }

                    $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                    //get min. service fee
                    $min_fee = 0;
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

                $TotalRevenueLost += $cost;
                if($CenInfo[0]->created_at < date("Y-m-d", strtotime("-30 days"))){
                    $TotalExistingRevenueLost += $cost;
                }else{
                    $TotlaNewRevenueLost += $cost;
                }

                $all_services->job_cost = $cost;
                $all_services->cancel_reason = $CenInfo[0]->cancel_reason;
                $all_services->created_at = $CenInfo[0]->created_at;
                $NewServiceArray[] = $all_services;
            }
        }

        $data['Services'] = $NewServiceArray;

        $data['TotalRevenueLost'] = $TotalRevenueLost;
        $data['TotalExistingRevenueLost'] = $TotalExistingRevenueLost;
        $data['TotlaNewRevenueLost'] = $TotlaNewRevenueLost;
        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasons($this->session->userdata['company_id']);
        
        $page["active_sidebar"] = "cancelService";
        $page["page_name"] = 'Cancel Details Report';
        $page["page_content"] = $this->load->view("admin/report/view_cancel_service_report", $data, TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }


## Ajax Data for Invoice Age Report
	public function ajaxDataForInvoiceAgeReport(){
		$company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        $service_area = $this->input->post('service_area');
		$tax_name = $this->input->post('tax_name');
        $property_type = $this->input->post('property_type');
		$interval = $this->input->post('interval');

		if(!empty($interval)){
            $data['interval'] = $interval;
        }else{
			$data['interval'] = "30";
		}
		
		if(!empty($customer)){
            $customers = $this->CustomerModel->getCustomerList(array('customer_id'=>$customer));
        }else{
			$customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
		}
		
		#get report data
		$report_data = array();
		
		#get customer invoices
		$customer_invoices = array();
		$current = [];
		$aged15 = [];
		$aged30 = [];
		$aged45 = [];
		$aged60 = [];
		$aged75 = [];
		$aged90 = [];
		
		if($data['interval'] == "15"){
			foreach($customers as $customer){
				//echo "CUSTOMER: ".$customer->customer_id."<br>";
				$customer_invoices[$customer->customer_id] = array();

				$whereArr = array(
					'customer_id'=>$customer->customer_id,
					'status !='=>0, //where status != unsent
					'payment_status !='=>2, //where payment_status != paid
					'is_archived'=>0, //where not archived
				);

				if(!empty($tax_name)){
					$whereArr['invoice_sales_tax.tax_name'] = $tax_name;
				}
				if(!empty($service_area)){
					$whereArr['property_area'] = $service_area;
				}
                if(!empty($service_type)){
					$whereArr['property_type'] = $property_type;
				}
				$invoices = $this->INV->getAllInvoicesReport($whereArr);

				
				$current_amount_due = 0;
				$aged15_amount_due = 0;
				$aged30_amount_due = 0;
				$aged45_amount_due = 0;
				$aged60_amount_due = 0;
				$aged75_amount_due = 0;
				$aged90_amount_due = 0;
				
				foreach($invoices as $invoice){
				#Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
					#check for coupons at customer, property, job level
					$job_cost_total = 0;
					$invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id'=>$invoice->invoice_id));
					if (!empty($invoice_jobs)) {
						foreach($invoice_jobs as $job) {
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
					}else {
						#account for old invoicing process
						$invoice_total_cost = $invoice->cost;
					}
					#check for coupons at invoice level
					$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id ));
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


					$amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

							/*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
								'cost'=>$invoice->cost,
								'cost_after_coupons'=>$invoice_total_cost,
								'tax_amount'=>$invoice->tax_amount,
								'partial_payment'=>$invoice->partial_payment,
								'total_amount_due'=>$amount_due,
							); */
					#get age of invoice
					$now = new DateTime('now');
					$invoice_date = new DateTime($invoice->first_sent_date);
					$aged = $invoice_date->diff($now);
					$aged = $aged->format('%r%a');
					
					if($aged <= 15){
						$current[] = $invoice->invoice_id;
						$current_amount_due += $amount_due;
					}elseif($aged > 15 && $aged <= 30){
						$aged15[] = $invoice->invoice_id;
						$aged15_amount_due += $amount_due;
					}elseif($aged > 30 && $aged <= 45){
						$aged30[] = $invoice->invoice_id;
						$aged30_amount_due += $amount_due;
					}elseif($aged > 45 && $aged <= 60){
						$aged45[] = $invoice->invoice_id;
						$aged45_amount_due += $amount_due;
					}elseif($aged > 60 && $aged <= 75){
						$aged60[] = $invoice->invoice_id;
						$aged60_amount_due += $amount_due;
					}elseif($aged > 75 && $aged <= 90){
						$aged75[] = $invoice->invoice_id;
						$aged75_amount_due += $amount_due;
					}elseif($aged > 90){
						$aged90[] = $invoice->invoice_id;
						$aged90_amount_due += $amount_due;
					}

				}

				$customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
				$customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
				$customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
				$customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
				$customer_invoices[$customer->customer_id]['15_total'] = $aged15_amount_due;
				$customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
				$customer_invoices[$customer->customer_id]['45_total'] = $aged45_amount_due;
				$customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
				$customer_invoices[$customer->customer_id]['75_total'] = $aged75_amount_due;
				$customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

				$customer_total_due = $current_amount_due + $aged15_amount_due + $aged30_amount_due + $aged45_amount_due + $aged60_amount_due + $aged75_amount_due + $aged90_amount_due;
				$customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

				#remove customers with $0 balance
				if($customer_total_due > 0){
					$report_data[] = $customer_invoices[$customer->customer_id];
				}
				if(is_array($current) && count($current) > 0){
					$data['current_invoices'] = implode(',',$current);
				}
				if(is_array($aged15) && count($aged15) > 0){
					$data['aged15_invoices'] = implode(',',$aged15);
				}
				if(is_array($aged30) && count($aged30) > 0){
					$data['aged30_invoices'] = implode(',',$aged30);
				}
				if(is_array($aged45) && count($aged45) > 0){
					$data['aged45_invoices'] = implode(',',$aged45);
				}
				if(is_array($aged60) && count($aged60) > 0){
					$data['aged60_invoices'] = implode(',',$aged60);
				}
				if(is_array($aged75) && count($aged75) > 0){
					$data['aged75_invoices'] = implode(',',$aged75);
				}
				if(is_array($aged90) && count($aged90) > 0){
					$data['aged90_invoices'] = implode(',',$aged90);
				}

			}
		}else{
			foreach($customers as $customer){
				//echo "CUSTOMER: ".$customer->customer_id."<br>";
				$customer_invoices[$customer->customer_id] = array();

				$whereArr = array(
					'invoice_tbl.customer_id'=>$customer->customer_id,
					'status !='=>0, //where status != unsent
					'payment_status !='=>2, //where payment_status != paid
					'is_archived'=>0, //where not archived
				);

				if(!empty($tax_name)){
					$whereArr['invoice_sales_tax.tax_name'] = $tax_name;
				}
				if(!empty($service_area)){
					$whereArr['property_area'] = $service_area;
				}
                if(!empty($property_type)){
                    $whereArr['property_type'] = $property_type;
                }
				$invoices = $this->INV->getAllSalesInvoice($whereArr);
				
				$current_amount_due = 0;
				$aged30_amount_due = 0;
				$aged60_amount_due = 0;
				$aged90_amount_due = 0;
				
				foreach($invoices as $invoice){
				#Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
					#check for coupons at customer, property, job level
					$job_cost_total = 0;
					$invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id'=>$invoice->invoice_id));
					if (!empty($invoice_jobs)) {
						foreach($invoice_jobs as $job) {
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
					}else {
						#account for old invoicing process
						$invoice_total_cost = $invoice->cost;
					}
					#check for coupons at invoice level
					$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id ));
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


					$amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

							/*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
								'cost'=>$invoice->cost,
								'cost_after_coupons'=>$invoice_total_cost,
								'tax_amount'=>$invoice->tax_amount,
								'partial_payment'=>$invoice->partial_payment,
								'total_amount_due'=>$amount_due,
							); */
					#get age of invoice
					$now = new DateTime('now');
					$invoice_date = new DateTime($invoice->first_sent_date);
					$aged = $invoice_date->diff($now);
					$aged = $aged->format('%r%a');

					if($aged <= 30){
						$current[] = $invoice->invoice_id;
						$current_amount_due += $amount_due;
					}elseif($aged > 30 && $aged <= 60){
						$aged30[] = $invoice->invoice_id;
						$aged30_amount_due += $amount_due;
					}elseif($aged > 60 && $aged <= 90){
						$aged60[] = $invoice->invoice_id;
						$aged60_amount_due += $amount_due;
					}elseif($aged > 90){
						$aged90[] = $invoice->invoice_id;
						$aged90_amount_due += $amount_due;
					}

						//echo "Invoice ID: ".$invoice->invoice_id." - Today: ".date('Y-m-d')." - Invoice Date: ".$invoice->invoice_date." - Aged: ".$aged."<br>";
				}

				$customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
				$customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
				$customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
				$customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
				$customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
				$customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
				$customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

				$customer_total_due = $current_amount_due + $aged30_amount_due + $aged60_amount_due + $aged90_amount_due;
				$customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

						//echo "TOTAL 0-30 Days: ".count($current)."<br>";
						//echo "TOTAL 31-60 Days: ".count($aged30)."<br>";
						//echo "TOTAL 61-90 Days: ".count($aged60)."<br>";
						//echo "TOTAL 90+ Days: ".count($aged90)."<br><br>";

				#remove customers with $0 balance
				if($customer_total_due > 0){
					$report_data[] = $customer_invoices[$customer->customer_id];
				}
				if(is_array($current) && count($current) > 0){
					$data['current_invoices'] = implode(',',$current);
				}
				if(is_array($aged30) && count($aged30) > 0){
					$data['aged30_invoices'] = implode(',',$aged30);
				}
				if(is_array($aged60) && count($aged60) > 0){
					$data['aged60_invoices'] = implode(',',$aged60);
				}
				if(is_array($aged90) && count($aged90) > 0){
					$data['aged90_invoices'] = implode(',',$aged90);
				}

			}
		}		

		$data['report_details'] = $report_data;
        if($data['interval'] == "15") {
            $body =  $this->load->view('admin/report/ajax_invoice_age_report15', $data, false);
        } else {
            $body =  $this->load->view('admin/report/ajax_invoice_age_report', $data, false);
        }
	}

## Download Invoice Age CSV
	public function downloadInvoiceAgeCsv(){
		$company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        $service_area = $this->input->post('service_area');
		$tax_name = $this->input->post('tax_name');
		$interval = $this->input->post('interval');
		
		if(!empty($interval)){
            $data['interval'] = $interval;
        }else{
			$data['interval'] = "30";
		}
		
		if(!empty($customer)){
            $customers = $this->CustomerModel->getCustomerList(array('customer_id'=>$customer));
        }else{
			$customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
		}
		
		#get report data
		$report_data = array();
		
		#get customer invoices
		$customer_invoices = array();
		$current = [];
		$aged15 = [];
		$aged30 = [];
		$aged45 = [];
		$aged60 = [];
		$aged75 = [];
		$aged90 = [];
		if($data['interval'] == "15"){
			foreach($customers as $customer){
				//echo "CUSTOMER: ".$customer->customer_id."<br>";
				$customer_invoices[$customer->customer_id] = array();

				$whereArr = array(
					'invoice_tbl.customer_id'=>$customer->customer_id,
					'status !='=>0, //where status != unsent
					'payment_status !='=>2, //where payment_status != paid
					'is_archived'=>0, //where not archived
				);

				if(!empty($tax_name)){
					$whereArr['invoice_sales_tax.tax_name'] = $tax_name;
				}
				if(!empty($service_area)){
					$whereArr['property_area'] = $service_area;
				}
				$invoices = $this->INV->getAllSalesInvoice($whereArr);
			
				$current_amount_due = 0;
				$aged15_amount_due = 0;			
				$aged30_amount_due = 0;				
				$aged45_amount_due = 0;				
				$aged60_amount_due = 0;				
				$aged75_amount_due = 0;
				$aged90_amount_due = 0;
				
				foreach($invoices as $invoice){
				#Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
					#check for coupons at customer, property, job level
					$job_cost_total = 0;
					$invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id'=>$invoice->invoice_id));
					if (!empty($invoice_jobs)) {
						foreach($invoice_jobs as $job) {
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
					}else {
						#account for old invoicing process
						$invoice_total_cost = $invoice->cost;
					}
					#check for coupons at invoice level
					$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id ));
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


					$amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

							/*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
								'cost'=>$invoice->cost,
								'cost_after_coupons'=>$invoice_total_cost,
								'tax_amount'=>$invoice->tax_amount,
								'partial_payment'=>$invoice->partial_payment,
								'total_amount_due'=>$amount_due,
							); */
					#get age of invoice
					$now = new DateTime('now');
					$invoice_date = new DateTime($invoice->first_sent_date);
					$aged = $invoice_date->diff($now);
					$aged = $aged->format('%r%a');
					
					if($aged <= 15){
						$current[] = $invoice->invoice_id;
						$current_amount_due += $amount_due;
					}elseif($aged > 15 && $aged <= 30){
						$aged15[] = $invoice->invoice_id;
						$aged15_amount_due += $amount_due;
					}elseif($aged > 30 && $aged <= 45){
						$aged30[] = $invoice->invoice_id;
						$aged30_amount_due += $amount_due;
					}elseif($aged > 45 && $aged <= 60){
						$aged45[] = $invoice->invoice_id;
						$aged45_amount_due += $amount_due;
					}elseif($aged > 60 && $aged <= 75){
						$aged60[] = $invoice->invoice_id;
						$aged60_amount_due += $amount_due;
					}elseif($aged > 75 && $aged <= 90){
						$aged75[] = $invoice->invoice_id;
						$aged75_amount_due += $amount_due;
					}elseif($aged > 90){
						$aged90[] = $invoice->invoice_id;
						$aged90_amount_due += $amount_due;
					}

				}

				$customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
				$customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
				$customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
				$customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
				$customer_invoices[$customer->customer_id]['15_total'] = $aged15_amount_due;
				$customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
				$customer_invoices[$customer->customer_id]['45_total'] = $aged45_amount_due;
				$customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
				$customer_invoices[$customer->customer_id]['75_total'] = $aged75_amount_due;
				$customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

				$customer_total_due = $current_amount_due + $aged15_amount_due + $aged30_amount_due + $aged45_amount_due + $aged60_amount_due + $aged75_amount_due + $aged90_amount_due;
				$customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

				#remove customers with $0 balance
				if($customer_total_due > 0){
					$report_data[] = $customer_invoices[$customer->customer_id];
				}

			}
		}else{
			foreach($customers as $customer){
				//echo "CUSTOMER: ".$customer->customer_id."<br>";
				$customer_invoices[$customer->customer_id] = array();

				$whereArr = array(
					'invoice_tbl.customer_id'=>$customer->customer_id,
					'status !='=>0, //where status != unsent
					'payment_status !='=>2, //where payment_status != paid
					'is_archived'=>0, //where not archived
				);

				if(!empty($tax_name)){
					$whereArr['invoice_sales_tax.tax_name'] = $tax_name;
				}
				if(!empty($service_area)){
					$whereArr['property_area'] = $service_area;
				}
				$invoices = $this->INV->getAllSalesInvoice($whereArr);

				$current_amount_due = 0;
				$aged30_amount_due = 0;
				$aged60_amount_due = 0;
				$aged90_amount_due = 0;
				
				foreach($invoices as $invoice){
				#Calculate Amount Due: Cost - Coupons + Tax - Partial Payments
					#check for coupons at customer, property, job level
					$job_cost_total = 0;
					$invoice_jobs = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon(array('invoice_id'=>$invoice->invoice_id));
					if (!empty($invoice_jobs)) {
						foreach($invoice_jobs as $job) {
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
					}else {
						#account for old invoicing process
						$invoice_total_cost = $invoice->cost;
					}
					#check for coupons at invoice level
					$coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id ));
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


					$amount_due = $invoice_total_cost + $invoice->tax_amount - $invoice->partial_payment;

							/*$customer_invoices[$customer->customer_id][$invoice->invoice_id] = array(
								'cost'=>$invoice->cost,
								'cost_after_coupons'=>$invoice_total_cost,
								'tax_amount'=>$invoice->tax_amount,
								'partial_payment'=>$invoice->partial_payment,
								'total_amount_due'=>$amount_due,
							); */
					#get age of invoice
					$now = new DateTime('now');
					$invoice_date = new DateTime($invoice->first_sent_date);
					$aged = $invoice_date->diff($now);
					$aged = $aged->format('%r%a');

					if($aged <= 30){
						$current[] = $invoice->invoice_id;
						$current_amount_due += $amount_due;
					}elseif($aged > 30 && $aged <= 60){
						$aged30[] = $invoice->invoice_id;
						$aged30_amount_due += $amount_due;
					}elseif($aged > 60 && $aged <= 90){
						$aged60[] = $invoice->invoice_id;
						$aged60_amount_due += $amount_due;
					}elseif($aged > 90){
						$aged90[] = $invoice->invoice_id;
						$aged90_amount_due += $amount_due;
					}

						//echo "Invoice ID: ".$invoice->invoice_id." - Today: ".date('Y-m-d')." - Invoice Date: ".$invoice->invoice_date." - Aged: ".$aged."<br>";
				}

				$customer_invoices[$customer->customer_id]['customer_id'] = $customer->customer_id;
				$customer_invoices[$customer->customer_id]['first_name'] = $customer->first_name;
				$customer_invoices[$customer->customer_id]['last_name'] = $customer->last_name;
				$customer_invoices[$customer->customer_id]['current_total'] = $current_amount_due;
				$customer_invoices[$customer->customer_id]['30_total'] = $aged30_amount_due;
				$customer_invoices[$customer->customer_id]['60_total'] = $aged60_amount_due;
				$customer_invoices[$customer->customer_id]['90_total'] = $aged90_amount_due;

				$customer_total_due = $current_amount_due + $aged30_amount_due + $aged60_amount_due + $aged90_amount_due;
				$customer_invoices[$customer->customer_id]['customer_total_due'] = $customer_total_due;

						//echo "TOTAL 0-30 Days: ".count($current)."<br>";
						//echo "TOTAL 31-60 Days: ".count($aged30)."<br>";
						//echo "TOTAL 61-90 Days: ".count($aged60)."<br>";
						//echo "TOTAL 90+ Days: ".count($aged90)."<br><br>";

				#remove customers with $0 balance
				if($customer_total_due > 0){
					$report_data[] = $customer_invoices[$customer->customer_id];
				}

			}
		}

		$data['report_details'] = $report_data;
		
        if(is_array($data['report_details']) && count($data['report_details']) > 0){
			 $delimiter = ",";
             $filename = "invoice_age_report_" . date('Y-m-d') . ".csv";
			
			#create a file pointer
             $f = fopen('php://memory', 'w');
     		
			if(isset($data['interval']) && $data['interval'] == "15"){
				#set column headers
				 $fields = array('Customer','0-15 Days','16-30 Days','31-45 Days','46-60 Days','61-75 Days','76-90 Days','90+ Days','Total');
				 fputcsv($f, $fields, $delimiter);
				
				#output each row of the data, format line as csv and write to file pointer
				$total_current = 0;
				$total_15 = 0;
				$total_30 = 0;
				$total_45 = 0;
				$total_60 = 0;
				$total_75 = 0;
				$total_90 = 0;
				$total_all = 0;
				foreach($data['report_details'] as $row => $col){ 
                	$lineData = array($col['first_name']." ".$col['last_name'],number_format($col['current_total'],2), number_format($col['15_total'],2), number_format($col['30_total'],2), number_format($col['45_total'],2), number_format($col['60_total'],2), number_format($col['75_total'],2), number_format($col['90_total'],2),number_format($col['customer_total_due'],2) );
 
                    fputcsv($f, $lineData, $delimiter);
					
					$total_current += $col['current_total'];
					$total_15 += $col['15_total'];
					$total_30 += $col['30_total'];
					$total_45 += $col['45_total'];
					$total_60 += $col['60_total'];
					$total_75 += $col['75_total'];
					$total_90 += $col['90_total'];
					$total_all += $col['customer_total_due'];
                }
				#add totals row
				$lineData = array("TOTALS",number_format($total_current,2), number_format($total_15,2), number_format($total_30,2), number_format($total_45,2), number_format($total_60,2), number_format($total_75,2), number_format($total_90,2),number_format($total_all,2) );
				fputcsv($f, $lineData, $delimiter);
			}else{
				#set column headers
				 $fields = array('Customer','0-30 Days','31-60 Days','61-90 Days','90+ Days','Total');
				 fputcsv($f, $fields, $delimiter);
				
				#output each row of the data, format line as csv and write to file pointer
				$total_current = 0;
				$total_30 = 0;
				$total_60 = 0;
				$total_90 = 0;
				$total_all = 0;
				foreach($data['report_details'] as $row => $col){ 
                	$lineData = array($col['first_name']." ".$col['last_name'],number_format($col['current_total'],2), number_format($col['30_total'],2), number_format($col['60_total'],2), number_format($col['90_total'],2),number_format($col['customer_total_due'],2) );
 
                    fputcsv($f, $lineData, $delimiter);
					
					$total_current += $col['current_total'];
					$total_30 += $col['30_total'];
					$total_60 += $col['60_total'];
					$total_90 += $col['90_total'];
					$total_all += $col['customer_total_due'];
                }
				#add totals row
				$lineData = array("TOTALS",number_format($total_current,2), number_format($total_30,2), number_format($total_60,2), number_format($total_90,2),number_format($total_all,2) );
				fputcsv($f, $lineData, $delimiter);
			}
			#move back to beginning of file
			fseek($f, 0);

			#set headers to download file rather than displayed
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' .$filename. '";');

			#output all remaining data on a file pointer
			fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/invoiceAgeReport");
        }    

    }



    ## Ajax Data for Customer Credit Report
    public function ajaxDataForCustomerCreditReport(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');

        $customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
        
        if(empty($customer)){
            $whereArr = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'is_credit' => 1
            );
        }else{
            $whereArr = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'invoice_tbl.customer_id' => $customer,
                'is_credit' => 1
            );
        }
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);
        $body =  $this->load->view('admin/report/ajax_customer_credit_report', $data, false);
    }

    ## Ajax Data for Cancel Service
    public function ajaxDataForCancelService(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');

        $customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
        
        if(empty($customer)){
            $whereArr = array(
                'jobs.company_id' => $company_id,
                'property_tbl.company_id' => $company_id
            );
        }else{
            $whereArr = array(
                'jobs.company_id' => $company_id,
                'property_tbl.company_id' => $company_id,
                'customers.customer_id' => $customer,
            );
        }

        if($this->input->post("serviceArea") != ""){
            $whereArr['jobs.job_id'] = $this->input->post("serviceArea");
        }

        if($this->input->post("newExisting") == "1"){
            $whereArr['customers.created_at >='] = date("Y-m-d 00:00:00", strtotime("-1 year"));
        }

        if($this->input->post("newExisting") == "0"){
            $whereArr['customers.created_at <='] = date("Y-m-d 00:00:00", strtotime("-1 year"));
        }

        if($this->input->post("reason") != ""){
            $whereArr['property_tbl.cancel_reason like '] = "%".$this->input->post("reason")."%";
        }

        if($this->input->post("CancelStatus") != ""){
            $whereArr['property_tbl.property_status'] = $this->input->post("CancelStatus");
        }
        
        $data['all_services'] = $this->DashboardModel->getCustomerAllServicesWithSalesRep($whereArr);
        $data['setting_details'] = $this->CompanyModel->getOneCompany( array( 'company_id' => $this->session->userdata['company_id'] ));
        $NewServiceArray = array();
        foreach($data['all_services'] as $all_services) {
            $cost = 0;
            $canc_arr = array(
                'job_id' => $all_services->job_id,
                'customer_id' => $all_services->customer_id,
                'property_id' => $all_services->property_id
            );

            if($this->input->post("date_from") != ""){
                $canc_arr['created_at >='] = date("Y-m-d 00:00:00", strtotime($this->input->post("date_from")));
            }

            if($this->input->post("date_to") != ""){
                $canc_arr['created_at <='] = date("Y-m-d 23:59:59", strtotime($this->input->post("date_to")));
            }

            $CenInfo = $this->CancelledModel->getCancelledServiceInfo($canc_arr);

            if($all_services->job_cost == NULL && isset($CenInfo[0]->is_cancelled)) {
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
                $all_services->cancel_reason = $CenInfo[0]->cancel_reason;
                $all_services->created_at = $CenInfo[0]->created_at;
                $NewServiceArray[] = $all_services;
            }
        }

        $data['Services'] = $NewServiceArray;
        $body =  $this->load->view('admin/report/ajax_cancel_service_report', $data, false);
    }
    

    ## Download Customer Credit CSV
    public function downloadCreditReportCsv(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        
        if(!empty($interval)){
            $data['interval'] = $interval;
        }else{
            $data['interval'] = "30";
        }
        
        if(!empty($customer)){
            $customers = $this->CustomerModel->getCustomerList(array('customer_id'=> $customer));
        }else{
            $customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
        }

        if($customer == ""){
            $whereArr = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'is_credit' => 1
            );
        }else{
            $whereArr = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'invoice_tbl.customer_id' => $customer,
                'is_credit' => 1
            );
        }
        
        $data['invoices'] = $this->INV->getAllInvoicesReport($whereArr);
        
        if(count($data['invoices']) > 0){
            $delimiter = ",";
            $filename = "customer_credit_report_" . date('Y-m-d') . ".csv";

            #create a file pointer
            $f = fopen('php://memory', 'w');

            #set column headers
            $fields = array('Customer','Date','Amount','Payment Type','Note','Responsible Party');
            fputcsv($f, $fields, $delimiter);

            #output each row of the data, format line as csv and write to file pointer
            foreach($data['invoices'] as $row => $Invs){

                $CustomerData = $this->db->select('*')->from("customers")->where(array("customer_id" => $Invs->customer_id))->get()->row();
                $ResponsibleParty = "";
                $Part = explode(",", $Invs->responsible_party);

                foreach($Part as $PP){
                    $PartData = $this->db->select('*')->from("users")->where(array("id" => $PP))->get()->row();
                    $ResponsibleParty .= $PartData->user_first_name." ".$PartData->user_last_name.", ";
                }

                $paymentMothods = "";

                if($Invs->payment_method == 0){
                    $paymentMothods = "Cash";
                }
                if($Invs->payment_method == 1){
                    $paymentMothods = "Check";
                }
                if($Invs->payment_method == 3){
                    $paymentMothods = "Other";
                }

                $lineData = array(
                                    $CustomerData->first_name. " " . $CustomerData->last_name,
                                    date("d F, Y", strtotime($Invs->invoice_created)),
                                    $Invs->credit_amount,
                                    $paymentMothods,
                                    $Invs->credit_notes,
                                    $ResponsibleParty
                                );
                fputcsv($f, $lineData, $delimiter);
            }

            #move back to beginning of file
            fseek($f, 0);

            #set headers to download file rather than displayed
            header('Content-Type: text/csv'); 
            header('Content-Disposition: attachment; filename="' .$filename. '";');

            #output all remaining data on a file pointer
            fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/creditReport");
        }    

    }



    ## Download Cancelled Service CSV
    public function downloadCancelServiceReportCsv(){
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');
        
        $company_id = $this->session->userdata['company_id'];
        $customer = $this->input->post('customer');

        $customers = $this->CustomerModel->getCustomerList(array('company_id'=>$this->session->userdata['company_id']));
        
        if(empty($customer)){
            $whereArr = array(
                'jobs.company_id' => $company_id,
                'property_tbl.company_id' => $company_id
            );
        }else{
            $whereArr = array(
                'jobs.company_id' => $company_id,
                'property_tbl.company_id' => $company_id,
                'customers.customer_id' => $customer,
            );
        }
        
        $data['all_services'] = $this->DashboardModel->getCustomerAllServicesWithSalesRep($whereArr);
        $NewServiceArray = array();
        foreach($data['all_services'] as $all_services) {
            $cost = 0;

            $canc_arr = array(
                'job_id' => $all_services->job_id,
                'customer_id' => $all_services->customer_id,
                'property_id' => $all_services->property_id
            );
            $CenInfo = $this->CancelledModel->getCancelledServiceInfo($canc_arr);

            if($all_services->job_cost == NULL && isset($CenInfo[0]->is_cancelled)) {
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
                $all_services->cancel_reason = $CenInfo[0]->cancel_reason;
                $all_services->created_at = $CenInfo[0]->created_at;
                $NewServiceArray[] = $all_services;
            }
        }
        
        if(count($NewServiceArray) > 0){
            $delimiter = ",";
            $filename = "cancel_service_report_" . date('Y-m-d') . ".csv";

            #create a file pointer
            $f = fopen('php://memory', 'w');

            #set column headers
            $fields = array('Customer','Service Name','Cost','Reason','Cancel Date');
            fputcsv($f, $fields, $delimiter);

            #output each row of the data, format line as csv and write to file pointer
            foreach($NewServiceArray as $row => $Invs){
                $CustomerData = $this->db->select('*')->from("customers")->where(array("customer_id" => $Invs->customer_id))->get()->row();
                $lineData = array(
                                    $CustomerData->first_name. " " . $CustomerData->last_name,
                                    $Invs->job_name,
                                    $Invs->job_cost,
                                    $Invs->cancel_reason,
                                    date("d F, Y", strtotime($Invs->created_at))
                                );
                fputcsv($f, $lineData, $delimiter);
            }

            #move back to beginning of file
            fseek($f, 0);

            #set headers to download file rather than displayed
            header('Content-Type: text/csv'); 
            header('Content-Disposition: attachment; filename="' .$filename. '";');

            #output all remaining data on a file pointer
            fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/creditReport");
        }    

    }
    


## Sales Tax Report
    public function salesTaxReport(){

        $company_id = $this->session->userdata['company_id'];
        $where = array('company_id'=>$this->session->userdata['company_id']);
        $data['tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);


        // new method
        $total_sales_tax_data = array();

        // then go through each invoice, calculate total, and add to correct sales tax area
          // only if the invoice's jobs are complete.

        //$fixed_date = '2022-07-01 00:00:00';
        $fixed_date = date('Y-m-01 00:00:00');
        //die($fixed_date);
        $where = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0,
            'invoice_tbl.invoice_date >=' => $fixed_date
        );

        // Array to be passed to function to take hotfix issue historical data into account
        $where_hotfix = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0,
            'invoice_tbl.invoice_date >=' => $fixed_date
        );

        // Array to be passed to function to take hotfix issue historical data into account on partial payments
        $where_hot_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0,
            'invoice_tbl.invoice_date >=' => $fixed_date
        );
        
        // Array to be passed to function to take partial payments into account
        $where_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0,
            'invoice_tbl.invoice_date >=' => $fixed_date
        );

        $inv_and_sales_tax = $this->INV->getAllSalesInvoice($where);

        $hotfixed = $this->INV->getAllHotfixSalesInvoice($where_hotfix);

        $hotpartialed = $this->INV->getAllPartialHotfixSalesInvoice($where_hot_partial);

        $partialed = $this->INV->getAllPartialSalesInvoice($where_partial);


        if($hotfixed){
            foreach($hotfixed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
                
            }
        }

        if($hotpartialed){
            foreach($hotpartialed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
            }
        }

        if($partialed){
            foreach($partialed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
            }
        }


        // die(print_r($inv_and_sales_tax));


        // $inv_and_sales_tax += $this->INV->getAllSalesInvoice($where_hotfix);
        // echo "<pre>";
        foreach($inv_and_sales_tax as $invoice_details) {
            $invoice_id = $invoice_details->invoice_id;
            $tax_name = $invoice_details->tax_name;
            $tax_value = $invoice_details->tax_value;
			$refund_amount = $invoice_details->refund_amount_total;

            // check for the invoice's jobs to be complete
            $invoice_jobs_are_complete = 0;
            $where = array(
                'invoice_tbl.invoice_id' => $invoice_id
            );

            $inv_tech = $this->InvoiceSalesTax->matchInvTechByInvId($where);
			// die(print_r($this->db->last_query ()));
            
            if (isset($inv_tech) && !empty($inv_tech)) { // match by invoice_id
                foreach($inv_tech as $inv_tech_detail) {
                    if ($inv_tech_detail->is_complete == 1) {
                        $invoice_jobs_are_complete = 1;
                    }
                }
            } else {

                $where = array(
                    'invoice_tbl.invoice_id' => $invoice_id
                );

                $inv_tech = $this->InvoiceSalesTax->matchInvTechByAllFour($where);
                // die(print_r($inv_tech));
                if (isset($inv_tech) && !empty($inv_tech)) { // match by all 4 ids
                    foreach($inv_tech as $inv_tech_detail) {
                        if ($inv_tech_detail->is_complete == 1) {
                            $invoice_jobs_are_complete = 1;
                        }
                    }
                } else {

                    $where = array(
                        'invoice_tbl.invoice_id' => $invoice_id
                    );

                    $inv_tech = $this->InvoiceSalesTax->matchInvTechByTbl($where);
                    if (isset($inv_tech) && !empty($inv_tech)) { // match by program jobs
                        $invoice_jobs_are_complete = 1;
                        foreach($inv_tech as $inv_tech_detail) {
                            if ($inv_tech_detail->is_complete == 0) {
                                $invoice_jobs_are_complete = 0;
                            }
                        }
                    }
                }
            }

            $program_price = $this->RP->getProgramPriceById($invoice_details->program_id)->program_price;
            // die(print_r($program_price));

            if (($invoice_jobs_are_complete == 1 && ($program_price == 1 || $program_price == 2)) || ($program_price == 3 && ($invoice_details->payment_status == 2 || $invoice_details->payment_status == 1))) { 
                // add sales tax if complete

                ////////////////////////////////////
				// START INVOICE CALCULATION COST //

                // vars
                $tmp_invoice_id = $invoice_id;

				// invoice cost
				// $invoice_total_cost = $invoice->cost;

				// cost of all services (with price overrides) - service coupons
				$job_cost_total = 0;
				$where = array(
					'property_program_job_invoice.invoice_id' => $tmp_invoice_id
				);
				$proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);
				// die(print_r($this->db->last_query ()));
				if (!empty($proprojobinv)) {
                    $got_all_costs = false;
					foreach($proprojobinv as $job) {
						if($invoice_details->partial_payment == 0 && $invoice_details->payment_status == 2){
							$job_cost = $job['job_cost'];
						} else {
							$job_cost = $invoice_details->partial_payment;
                            $got_all_costs = true; // this means we are grabbing all the info from the invoice already, no need to run the foreach over and over and add to this
						}

						$job_cost_total += $job_cost;
                        if($got_all_costs == true) {
                            break;
                        }
					}
                    $invoice_total_cost = $job_cost_total-$refund_amount;
                } else {
                    $invoice_total_cost = $invoice_details->cost-$refund_amount;
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

                if (!isset($total_sales_tax_data[$tax_name])) {
                    $total_sales_tax_data[$tax_name] = array(
                        'total_tax' => 0,
                        'OLD_total_tax' => 0,
                        'gross_revenue' => 0,
                        'total_sales' => 0,
                        'tax_value' => $invoice_details->tax_value,
                        'tax_name' => $invoice_details->tax_name
                    );
                }
                $total_sales_tax_data[$tax_name]['total_tax'] += number_format((float)$total_tax_amount, 2,'.','');
                $total_sales_tax_data[$tax_name]['OLD_total_tax'] += number_format((float)$invoice_details->tax_amount, 2,'.','');
                $total_sales_tax_data[$tax_name]['gross_revenue'] += $invoice_total_cost;
                $total_sales_tax_data[$tax_name]['total_sales'] += $invoice_total_cost-$total_tax_amount;

            } else { // don't add price if not complete
            }
        }

        $data['new_report_details'] = $total_sales_tax_data;
		// die(print_r($data['new_report_details']));
        $page["active_sidebar"] = "salesTaxReport";
        $page["page_name"] = 'Sales Tax Report';
        $page["page_content"] = $this->load->view("admin/report/view_sales_tax_report", $data, TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    function ajaxDataForSalesTaxReport(){
       
        $tax_name = $this->input->post('tax_name');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');
        $data['job_completed_date_to'] =$job_completed_date_to;
        $data['job_completed_date_from'] =$job_completed_date_from;

        $company_id = $this->session->userdata['company_id'];

        // new method
        $total_sales_tax_data = array();
        $additional_where = "";

        $where = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_hotfix = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_hot_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        if(!empty($tax_name)){ 
            // $additional_where .= "AND invoice_sales_tax.tax_name = '$tax_name' ";
            $where['invoice_sales_tax.tax_name'] = $tax_name;
            $where_hotfix['invoice_sales_tax.tax_name'] = $tax_name;
            $where_hot_partial['invoice_sales_tax.tax_name'] = $tax_name;
            $where_partial['invoice_sales_tax.tax_name'] = $tax_name;
        }

        $inv_and_sales_tax = $this->INV->getAllSalesInvoice($where);


        // die(print_r($this->db->last_query()));
        // $inv_and_sales_tax += $this->INV->getAllSalesInvoice($where_hotfix);

        $hotfixed = $this->INV->getAllHotfixSalesInvoice($where_hotfix);

        $hotpartialed = $this->INV->getAllPartialHotfixSalesInvoice($where_hot_partial);

        $partialed = $this->INV->getAllPartialSalesInvoice($where_partial);



        if($hotfixed){
            foreach($hotfixed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
                
            }
        }

        if($hotpartialed){
            foreach($hotpartialed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
            }
        }

        if($partialed){
            foreach($partialed as $fix){
                if(!in_array($fix, $inv_and_sales_tax)){
                    array_push($inv_and_sales_tax, $fix);
                }
            }
        }

        // need to go through and see which we can remove
        if(!empty($job_completed_date_to)){
            $where['latest_date <='] = $job_completed_date_to;
            $where_hotfix['latest_date <='] = $job_completed_date_to;
            $where_hot_partial['latest_date <='] = $job_completed_date_to;
            $where_partial['latest_date <='] = $job_completed_date_to;
        }

         if(!empty($job_completed_date_from)){
            $where['latest_date >='] = $job_completed_date_from;
            $where_hotfix['latest_date >='] = $job_completed_date_from;
            $where_hot_partial['latest_date >='] = $job_completed_date_from;
            $where_partial['latest_date >='] = $job_completed_date_from;
        }
        if(!empty($job_completed_date_to) && !empty($job_completed_date_from)) {
            $date_to = date('Y-m-d',strtotime($job_completed_date_to));
            $date_from = date('Y-m-d',strtotime($job_completed_date_from));
            foreach($inv_and_sales_tax as $key=>$invd) {
                $latest_date = date('Y-m-d',strtotime($invd->latest_date));
                if (($latest_date >= $date_from) && ($latest_date <= $date_to)){
                    // this means the dates are inbetween!   
                } else {
                    //var_dump($key);
                    unset($inv_and_sales_tax[$key]);
                }
            }
        }

        // echo "<pre>";
        $invoices_already_used = array();
        foreach($inv_and_sales_tax as $invoice_details) {
            $invoice_id = $invoice_details->invoice_id;
            if(!in_array($invoice_id, $invoices_already_used)) {
                $tax_name = $invoice_details->tax_name;
                $tax_value = $invoice_details->tax_value;
                $refund_amount = $invoice_details->refund_amount_total;

                // check for the invoice's jobs to be complete
                $invoice_jobs_are_complete = 0;
                $where = array(
                    'invoice_tbl.invoice_id' => $invoice_id
                );

                $inv_tech = $this->InvoiceSalesTax->matchInvTechByInvId($where);
                if (isset($inv_tech) && !empty($inv_tech)) { // match by invoice_id
                    foreach($inv_tech as $inv_tech_detail) {
                        if ($inv_tech_detail->is_complete == 1) {
                            $invoice_jobs_are_complete = 1; 
                        }
                    }
                } else {

                    $where = array(
                        'invoice_tbl.invoice_id' => $invoice_id
                    );

                    $inv_tech = $this->InvoiceSalesTax->matchInvTechByAllFour($where);
                    if (isset($inv_tech) && !empty($inv_tech)) { // match by all 4 ids
                        foreach($inv_tech as $inv_tech_detail) {
                            if ($inv_tech_detail->is_complete == 1) {
                                $invoice_jobs_are_complete = 1;
                            }
                        }
                    } else {

                        $where = array(
                            'invoice_tbl.invoice_id' => $invoice_id
                        );

                        $inv_tech = $this->InvoiceSalesTax->matchInvTechByTbl($where);
                        
                        if (isset($inv_tech) && !empty($inv_tech)) { // match by program jobs
                            $invoice_jobs_are_complete = 1;
                            foreach($inv_tech as $inv_tech_detail) {
                                if ($inv_tech_detail->is_complete == 0) {
                                    $invoice_jobs_are_complete = 0;
                                }
                            }
                        }
                    }
                }
                $program_price = $this->RP->getProgramPriceById($invoice_details->program_id)->program_price;

                // die(print_r($program_price));

                if (($invoice_jobs_are_complete == 1 && ($program_price == 1 || $program_price == 2)) || ($program_price == 3 && ($invoice_details->payment_status == 2 || $invoice_details->payment_status == 1))) {  // add sales tax if complete

                    ////////////////////////////////////
                    // START INVOICE CALCULATION COST //
                    
                    // vars 
                    $tmp_invoice_id = $invoice_id;

                    // invoice cost
                    // $invoice_total_cost = $invoice->cost;

                    // cost of all services (with price overrides) - service coupons
                    $job_cost_total = 0;
                    $invoice_total_tax = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $tmp_invoice_id
                    );
                    $where_in = "property_program_job_invoice.job_id IN (Select job_id from technician_job_assign WHERE invoice_id = '".$invoice_id."' and is_complete = 1)";
                    $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where, $where_in);

                    if (!empty($proprojobinv)) {
                        foreach($proprojobinv as $job) {

                            
                            $invoice_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id ));
                            if (!empty($invoice_sales_tax)) {
                                $tax_added = 0;
                                foreach($invoice_sales_tax as $tax1) {
                                    if (array_key_exists("tax_value", $tax1)) {
                                        $tax_subtracted = ($tax1['tax_value']  / 100) * $job['job_cost'];
                                        //die(print_r($tax_subtracted));
                                            $tax_added += $tax_subtracted;
                                        // $job_cost += $tax_subtracted;
                                        $invoice_total_tax += $tax_added;
                                    }
                                }
                            }
                            $job_cost = $job['job_cost'] + $tax_added;
                            // die(print_r($tax_subtracted));

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
                    
                    
                    //$invoice_total_cost += $invoice_total_tax;
                    $total_tax_amount = $invoice_total_tax;

                    // END TOTAL INVOICE CALCULATION COST //
                    ////////////////////////////////////////

                    if (!isset($total_sales_tax_data[$tax_name])) {
                        $total_sales_tax_data[$tax_name] = array(
                            'total_tax' => 0,
                            'OLD_total_tax' => 0,
                            'gross_revenue' => 0,
                            'total_sales' => 0,
                            'tax_value' => $invoice_details->tax_value,
                            'tax_name' => $invoice_details->tax_name
                        );
                    }
                    $total_sales_tax_data[$tax_name]['total_tax'] += number_format((float)$total_tax_amount, 2,'.','');
                    $total_sales_tax_data[$tax_name]['OLD_total_tax'] += number_format((float)$invoice_details->tax_amount, 2,'.','');
                    $total_sales_tax_data[$tax_name]['gross_revenue'] += $invoice_total_cost;
                    $total_sales_tax_data[$tax_name]['total_sales'] += $invoice_total_cost-$total_tax_amount;

                } 
                $invoices_already_used[] = $invoice_id;
            }
        }

        $data['new_report_details'] = $total_sales_tax_data;
   
        // $data['report_details'] = $this->InvoiceSalesTax->getAllInvoiceSalesTaxDetails($where, $additional_where);

        // print_r($this->db->last_query());
        // die();
        $body =  $this->load->view('admin/report/ajax_sales__report', $data, false);
    }


    public function downloadTaxCsv(){

        $tax_name = $this->input->post('tax_name');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');

        $company_id = $this->session->userdata['company_id'];
        // $where = "WHERE invoice_tbl.payment_status = 2 AND invoice_tbl.company_id = $company_id AND invoice_tbl.payment_created NOT LIKE '0000-00-00 00:00:00' AND invoice_sales_tax.tax_amount != 0";
        // $additional_where = "";

      //   if(!empty($tax_name)){ 
      //     $additional_where .= "AND invoice_sales_tax.tax_name = '$tax_name' ";
      // }

      // if(!empty($job_completed_date_to)){
      //     $additional_where .= "AND CASE WHEN IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)) > payment_created THEN CAST(IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)) AS DATE) ELSE CAST(payment_created AS DATE) END >= '$job_completed_date_to'";
      // }

      //  if(!empty($job_completed_date_from)){
      //     $additional_where .= "AND CASE WHEN IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)) > payment_created THEN CAST(IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)) AS DATE) ELSE CAST(payment_created AS DATE) END <= '$job_completed_date_to'";
      // }

        // new method
        $total_sales_tax_data = array();

        $where = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_hotfix = array(
            'invoice_tbl.payment_status' => 2,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_hot_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created' => '0000-00-00 00:00:00',
            'invoice_tbl.last_modify !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        $where_partial = array(
            'invoice_tbl.payment_status' => 1,
            'invoice_tbl.company_id' => $company_id,
            'invoice_tbl.payment_created !=' => '0000-00-00 00:00:00',
            'invoice_sales_tax.tax_amount !=' => 0
        );

        if(!empty($tax_name)){ 
            // $additional_where .= "AND invoice_sales_tax.tax_name = '$tax_name' ";
            $where['invoice_sales_tax.tax_name'] = $tax_name;
            $where_hotfix['invoice_sales_tax.tax_name'] = $tax_name;
            $where_hot_partial['invoice_sales_tax.tax_name'] = $tax_name;
            $where_partial['invoice_sales_tax.tax_name'] = $tax_name;
        }

        if(!empty($job_completed_date_to) && !empty($job_completed_date_from)) {
            $date_to = date('Y-m-d',strtotime($job_completed_date_to));
            $date_from = date('Y-m-d',strtotime($job_completed_date_from));
            foreach($inv_and_sales_tax as $key=>$invd) {
                $latest_date = date('Y-m-d',strtotime($invd->latest_date));
                if (($latest_date >= $date_from) && ($latest_date <= $date_to)){
                    // this means the dates are inbetween!   
                } else {
                    //var_dump($key);
                    unset($inv_and_sales_tax[$key]);
                }
            }
        }

        // echo "<pre>";
        $invoices_already_used = array();
        foreach($inv_and_sales_tax as $invoice_details) {
            $invoice_id = $invoice_details->invoice_id;
            if(!in_array($invoice_id, $invoices_already_used)) {
                $tax_name = $invoice_details->tax_name;
                $tax_value = $invoice_details->tax_value;
                $refund_amount = $invoice_details->refund_amount_total;

                // check for the invoice's jobs to be complete
                $invoice_jobs_are_complete = 0;
                $where = array(
                    'invoice_tbl.invoice_id' => $invoice_id
                );

                $inv_tech = $this->InvoiceSalesTax->matchInvTechByInvId($where);
                if (isset($inv_tech) && !empty($inv_tech)) { // match by invoice_id
                    foreach($inv_tech as $inv_tech_detail) {
                        if ($inv_tech_detail->is_complete == 1) {
                            $invoice_jobs_are_complete = 1; 
                        }
                    }
                } else {

                    $where = array(
                        'invoice_tbl.invoice_id' => $invoice_id
                    );

                    $inv_tech = $this->InvoiceSalesTax->matchInvTechByAllFour($where);
                    if (isset($inv_tech) && !empty($inv_tech)) { // match by all 4 ids
                        foreach($inv_tech as $inv_tech_detail) {
                            if ($inv_tech_detail->is_complete == 1) {
                                $invoice_jobs_are_complete = 1;
                            }
                        }
                    } else {

                        $where = array(
                            'invoice_tbl.invoice_id' => $invoice_id
                        );

                        $inv_tech = $this->InvoiceSalesTax->matchInvTechByTbl($where);
                        
                        if (isset($inv_tech) && !empty($inv_tech)) { // match by program jobs
                            $invoice_jobs_are_complete = 1;
                            foreach($inv_tech as $inv_tech_detail) {
                                if ($inv_tech_detail->is_complete == 0) {
                                    $invoice_jobs_are_complete = 0;
                                }
                            }
                        }
                    }
                }
                $program_price = $this->RP->getProgramPriceById($invoice_details->program_id)->program_price;

                // die(print_r($program_price));

                if (($invoice_jobs_are_complete == 1 && ($program_price == 1 || $program_price == 2)) || ($program_price == 3 && ($invoice_details->payment_status == 2 || $invoice_details->payment_status == 1))) {  // add sales tax if complete

                    ////////////////////////////////////
                    // START INVOICE CALCULATION COST //
                    
                    // vars 
                    $tmp_invoice_id = $invoice_id;

                    // invoice cost
                    // $invoice_total_cost = $invoice->cost;

                    // cost of all services (with price overrides) - service coupons
                    $job_cost_total = 0;
                    $invoice_total_tax = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $tmp_invoice_id
                    );
                    $where_in = "property_program_job_invoice.job_id IN (Select job_id from technician_job_assign WHERE invoice_id = '".$invoice_id."' and is_complete = 1)";
                    $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where, $where_in);

                    if (!empty($proprojobinv)) {
                        foreach($proprojobinv as $job) {

                            
                            $invoice_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id ));
                            if (!empty($invoice_sales_tax)) {
                                $tax_added = 0;
                                foreach($invoice_sales_tax as $tax1) {
                                    if (array_key_exists("tax_value", $tax1)) {
                                        $tax_subtracted = ($tax1['tax_value']  / 100) * $job['job_cost'];
                                        //die(print_r($tax_subtracted));
                                            $tax_added += $tax_subtracted;
                                        // $job_cost += $tax_subtracted;
                                        $invoice_total_tax += $tax_added;
                                    }
                                }
                            }
                            $job_cost = $job['job_cost'] + $tax_added;
                            // die(print_r($tax_subtracted));

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
                    
                    
                    //$invoice_total_cost += $invoice_total_tax;
                    $total_tax_amount = $invoice_total_tax;

                    // END TOTAL INVOICE CALCULATION COST //
                    ////////////////////////////////////////

                    if (!isset($total_sales_tax_data[$tax_name])) {
                        $total_sales_tax_data[$tax_name] = array(
                            'total_tax' => 0,
                            'OLD_total_tax' => 0,
                            'gross_revenue' => 0,
                            'total_sales' => 0,
                            'tax_value' => $invoice_details->tax_value,
                            'tax_name' => $invoice_details->tax_name
                        );
                    }
                    $total_sales_tax_data[$tax_name]['total_tax'] += number_format((float)$total_tax_amount, 2,'.','');
                    $total_sales_tax_data[$tax_name]['OLD_total_tax'] += number_format((float)$invoice_details->tax_amount, 2,'.','');
                    $total_sales_tax_data[$tax_name]['gross_revenue'] += $invoice_total_cost;
                    $total_sales_tax_data[$tax_name]['total_sales'] += $invoice_total_cost-$total_tax_amount;

                } 
                $invoices_already_used[] = $invoice_id;
            }
        }

        $data = $total_sales_tax_data;
        // $data =$this->InvoiceSalesTax->getAllInvoiceSalesTaxDetails($where, $additional_where);

        if($data){


                    $delimiter = ",";
                    $filename = "sales_tax_report_" . date('Y-m-d') . ".csv";

                    //create a file pointer
                    $f = fopen('php://memory', 'w');
                    //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');

                    //set column headers
                    $fields = array('Dates Collected','Sales tax area','Total Sales Tax Collected','Gross Revenue','Total Sales');
                    fputcsv($f, $fields, $delimiter);

                    //output each row of the data, format line as csv and write to file pointer


                    foreach ($data as $key => $value) {

                        $lineData = array( ((!empty($job_completed_date_to) || !empty($job_completed_date_from))?$job_completed_date_to." - ".$job_completed_date_from:"All Time"), $value['tax_name'].' ('.floatval($value['tax_value']).'%) ', number_format($value['total_tax'],2), number_format($value['gross_revenue'],2), number_format($value['total_sales'],2) );

                        fputcsv($f, $lineData, $delimiter);

                        }

                            //move back to beginning of file
                            fseek($f, 0);

                            //set headers to download file rather than displayed
                            header('Content-Type: text/csv');
                              //  $pathName =  "down/".$filename;
                            header('Content-Disposition: attachment; filename="' .$filename. '";');

                            //output all remaining data on a file pointer
                            fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/salesTaxReport");
        }

    }


    public function customersCsv($value=''){
   
        $where =  array('company_id' => $this->session->userdata['company_id']);        
        $data = $this->CustomerModel->get_all_customer($where);

        if($data){

            $delimiter = ",";
            $filename = "customers_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('First Name','Last Name','Company Name','Email', 'Email Subscribed', 'Secondary Email(s)','Mobile', 'Text Subscribed', 'Home','Work','Billing Address',' Billing Address 2','City','Billing State','Zip Code','Customer Status');
        
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
        
        
            foreach ($data as $key => $value) {

            $lineData = array($value->first_name,$value->last_name,$value->customer_company_name, $value->email, $value->is_email == 1 ? "Subscribed" : "Unsubscribed", $value->secondary_email,$value->phone, $value->is_mobile_text == 1 ? "Subscribed" : "Unsubscribed", $value->home_phone, $value->work_phone, $value->billing_street, $value->billing_street_2,$value->billing_city, $value->billing_state, $value->billing_zipcode,$value->customer_status==1 ? 'Active':'Non-Active');

                fputcsv($f, $lineData, $delimiter);
            
            }

            //move back to beginning of file
            fseek($f, 0);
            
            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            //  $pathName =  "down/".$filename;
            header('Content-Disposition: attachment; filename="' .$filename. '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);

        } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
        redirect("admin/customerList");
        }    

    }

    /**
     * download notes based on filterred to csv file
     *
     * @return void
     */
    public function NotesCSVDownload() {
        $get_data = $this->input->get();
        $notes = $this->CompanyModel->getCompanyNotes($this->session->userdata['company_id'], $get_data);

        if (count($notes) > 0) {
            $delimiter = ",";
            $filename = "notes_" . date('Y-m-d') . ".csv";

            //create a file pointer
            $f = fopen('php://memory', 'w');

            //set column headers
            $fields = array(
                'Note Customer',
                'Note Creator',
                'Note Created Date',
                'Note Contents',
                'Note Type',
                'Note Assigned',
                'Customer Address',
                'Note Status',
                'Note Due Date',
                'Tech Visible',
            );

            fputcsv($f, $fields, $delimiter);

            foreach ($notes as $key => $value) {
                $body = array(
                    $value->customer_full_name,
                    $value->user_first_name ? ($value->user_first_name . ' . ' . $value->user_last_name) : '',
                    $value->note_created_at,
                    $value->note_contents,
                    $value->type_name,
                    $value->user_assigned_full_name,
                    $value->property_address . ', ' . $value->property_city,
                    $value->note_status == 1 ? 'Open' : ($value->note_status == 2 ? 'Closed' : ''),
                    $value->note_due_date,
                    $value->include_in_tech_view == 1 ? 'Yes' : 'No',
                );
                fputcsv($f, $body, $delimiter);

            }

            //move back to beginning of file
            fseek($f, 0);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' .$filename. '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/notesViewAll");
        }
    }
  

    public function propertiesCsv($value=''){

        $company_id = $this->session->userdata['company_id'];
        $where =  array('property_tbl.company_id' =>$company_id);
        $data = $this->PropertyModel->get_all_property($where);
    
        if($data){

            $delimiter = ",";
            $filename = "properties_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Property Name','Address','Address 2','City','State','Zip Code','Service Area','Property Type','Yard Square Feet','Sales Tax Area','Property  Status','Property Notes');

            fputcsv($f, $fields, $delimiter);

            foreach ($data as $key => $value) {
    
            $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id'=>$value->property_id));
    
            $all_tax_name = '';
    
            if ($property_assign_tax) {
            $all_tax =  array_column($property_assign_tax, 'tax_name');
            $all_tax_name =implode(', ', $all_tax);
            }

            $lineData = array($value->property_title,$value->property_address,$value->property_address_2, $value->property_city ,$value->property_state, $value->property_zip, isset($value->property_area) && $value->property_area != 0 ? $value->category_area_name : 'None', $value->property_type, $value->yard_square_feet,$all_tax_name,$value->property_status==1 ? 'Active' : 'Non-Active',$value->property_notes);
            
            fputcsv($f, $lineData, $delimiter);           
            }

            //move back to beginning of file
            fseek($f, 0);
            
            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            //  $pathName =  "down/".$filename;
            header('Content-Disposition: attachment; filename="' .$filename. '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);

        } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
        redirect("admin/propertyList");
        }


    }

  /* Technician Efficiency Report */
  public function techEfficiencyReport() 
  {
	$data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
    $data['SavedFilter'] = $this->TechEffReportModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));
	$data['initial_data'] = $this->initialLoadTechEfficiencyReport();
	// $data['report_details'] = $this->RP->getAllRepots();
	$page["active_sidebar"] = "techEfficiencyReport";
	$page["page_name"] = 'Technician Efficiency Report';
	$page["page_content"] = $this->load->view("admin/report/view_tech_efficiency_report", $data, TRUE);
	$this->layout->superAdminReportTemplateTable($page);
  }

  public function initialLoadTechEfficiencyReport($from_date = null, $to_date = null, $TcechIDs = []) {

    $ConditionArray = array('company_id' => $this->session->userdata['company_id']);

    if(count($TcechIDs) > 0){
        $ConditionArray = array('company_id' => $this->session->userdata['company_id'], "user_id" => $TcechIDs);
    }

    $users = $this->Administrator->getAllAdmin($ConditionArray);

		$results = array();
		foreach($users as $user)
		{
			$technician_id = $user->user_id;
			$job_completed_date_from = $from_date ?? '';
			$job_completed_date_to = $to_date ?? date('Y-m-d');

			$conditions = array(
				'technician_id' => explode(",", $technician_id),
				'is_complete' => 1,
				'date_from' => $job_completed_date_from,
				'date_to' => $job_completed_date_to
			);
			$days_worked = $this->RP->getTecnicianhDaysWorkedBetweenDates($conditions);
			$jobs_completed = $this->RP->getTecnicianhJobsCompletedBetweenDates($conditions);
			// die(print_r(json_encode($jobs_completed),true)); // 0
			// die(print_r(json_encode($invoiceIds),true)); // []
			$jobAssignIds = $this->RP->getTecnicianJobAssignIdsBetweenDates($conditions);
			// die(print_r(json_encode($jobAssignIds),true)); // []
			if($jobs_completed > 0 && count($jobAssignIds) > 0)
			{
				$reports = $this->RP->getTechJobReports($jobAssignIds);
				$completed_sqft = 0;
				$revenue = 0;
				foreach($reports as $report)
				{
                    $rev_total = $report->cost;
                    $tech_info = $this->RP->getReportTech($report->technician_job_assign_id);

                    if(!empty($tech_info)){
                            $job_discounts = $this->RP->getJobDiscounts($tech_info->job_id, $tech_info->customer_id);
                            if(!empty($job_discounts)){
                                 foreach($job_discounts as $j_d){
                                    if ($j_d->amount_calculation == 0){
                                        $rev_total -= $j_d->amount;
                                    } else if ($j_d->amount_calculation == 1){
                                        $rev_total -= ($report->cost * ($j_d->amount / 100));
                                    }
                                }
                            }
                            $invoice_discounts = $this->RP->getInvoiceDiscounts($tech_info->invoice_id);
                            if(!empty($invoice_discounts)){
                                foreach($invoice_discounts as $j_d){
                                    if ($j_d->amount_calculation == 0){
                                        $rev_total -= $j_d->amount;
                                    } else if ($j_d->amount_calculation == 1){
                                        $rev_total -= ($report->cost * ($j_d->amount / 100));
                                    }
                                }
                            }
                    }

					$completed_sqft += $report->yard_square_feet;
					$revenue += floatval($rev_total);
				}
//                if ($revenue < 0){
//                    $revenue = 0;
//                }
				$service_per_day = $jobs_completed / $days_worked;
				$revenue_per_day = $revenue / $days_worked;
				$sqft_per_day = $completed_sqft / $days_worked;
				$job_times = $this->RP->getTecnicianhJobTimesBetweenDates($conditions);
				$total_job_time = 0;
				// $difference = 0;
				foreach($job_times as $job_time)
				{
					$difference = 0;
					$difference = round( abs( strtotime( $job_time->job_completed_time) - strtotime( $job_time->job_start_time )) / 60, 2);
					$total_job_time += $difference;
				}

                

                if ($total_job_time < 60.00){
                    $avg_revenue_per_hour = $revenue;
                    // print_r('<p> Less Than: ' . $total_job_time . '</p><br/>');
                } else {
                    // print_r('<p> More Than: ' . $total_job_time . '</p><br/>');
                    $avg_revenue_per_hour = ( $total_job_time == 0 ) ? 0 : $revenue / $total_job_time * 60;
                }

                // print_r('<p> Rev Per Hour: ' . $avg_revenue_per_hour . '</p><br/>');
				// die( var_dump(  round( $total_job_time ) ));
				
                // die( var_dump(  round( $avg_revenue_per_hour ) ));
				$total_job_time = $this->convertToHoursMins($total_job_time);

				
				if( $job_completed_date_from == '')
				{
					$job_completed_date_from = '∞';
				}
				$resp_data = array(
					'tech_name' => $user->user_first_name.' '.$user->user_last_name,
					'date_range' => $job_completed_date_from.' - '.$job_completed_date_to,
					't_days_worked' => $days_worked,
					't_services' => $jobs_completed,
					't_sqft' => $completed_sqft,
					't_revenue' => $revenue,
					'avg_services' => round($service_per_day, 2),
					'avg_sqft' => round($sqft_per_day, 2),
					'avg_revenue' => round($revenue_per_day, 2),
					't_servce_time' => $total_job_time,
					'avg_revenue_hr' => $avg_revenue_per_hour
				);
				array_push($results, $resp_data);
			} else {
				if( $job_completed_date_from == '')
				{
					$job_completed_date_from = '∞';
				}				
				$resp_data = array(
					'tech_name' => $user->user_first_name.' '.$user->user_last_name,
					'date_range' => $job_completed_date_from.' - '.$job_completed_date_to,
					't_days_worked' => 0,
					't_services' => 0,
					't_sqft' => 0,
					't_revenue' => 0,
					'avg_services' => 0,
					'avg_sqft' => 0,
					'avg_revenue' => 0,
					't_servce_time' => "00:00",
					'avg_revenue_hr' => 0
				);
				array_push($results, $resp_data);
			}
		}
		return $results;
  }

  public function ajaxForTechEfficiencyReport() 
  {
		$technician_id = $this->input->post('technician_id');
		$job_completed_date_from = $this->input->post('job_completed_date_from');
		$job_completed_date_to = $this->input->post('job_completed_date_to');

		$resp_data = $this->initialLoadTechEfficiencyReport($job_completed_date_from, $job_completed_date_to, explode(",", $technician_id));
		echo json_encode($resp_data);
  }

	function convertToHoursMins($time, $format = '%02d:%02d') {
		$hours = $time / 60;
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

	function downloadEfficiencyReportCsv()
	{
		$data = $this->input->post();
		// die(print_r($data['csvData']));
		$json = json_decode($data['csvData']);
		// die(var_dump($json));
		$filename = "efficiency_report_" . date('Y-m-d') . ".csv";
		// $json = json_decode($data);
		foreach($json as $key => $value)
		{
			$json[$key] = '"'.implode('","',$value).'"';
		}
		$json = implode(PHP_EOL,$json);
		
		$f = fopen('php://memory', 'w');
		fputs($f, $json);
		fseek($f, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' .$filename. '";');
		fpassthru($f);
	}

	// Technician Available Work Report
	public function techAvailableWorkReport()
	{
		$where = array('company_id' =>$this->session->userdata['company_id']);
		$data['program_details'] = $this->ProgramModel->get_all_program($where);
		$data['service_details'] = $this->JobModel->getJobList($where);
		$data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
		$company_id = $this->session->userdata['company_id'];

		$page["active_sidebar"] = "techAvailableWorkReport";
		$page["page_name"] = 'Available Work Report';
		$page["page_content"] = $this->load->view("admin/report/view_available_work_report", $data, TRUE);
		$this->layout->superAdminReportTemplateTable($page);
	}

	public function getIncompleteJobCost($where_arr, $where_arr2) 
	{

		$job_price = $where_arr2['job_price'];
		$yard_square_feet = $where_arr2['sqft'];

		$estimate_price_override = GetOneEstimateJobPriceOverride($where_arr);
		if ($estimate_price_override && $estimate_price_override->is_price_override_set == 1 ) {
			$cost =  $estimate_price_override->price_override;
			return $cost;
		} else {
			$priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $where_arr['property_id'],'program_id' => $where_arr['program_id']));
			if ($priceOverrideData &&  $priceOverrideData->is_price_override_set == 1 ) {            
			return $cost =  $priceOverrideData->price_override;
			} else {
			$price = $job_price;
			return $cost = ($yard_square_feet * $price)/1000;
			}
		}

	}

    public function ajaxForAvailableWorkReport() 
    {
	  	$qPrograms = $this->input->post('programs');
		$qProgramsArr = explode(',', $this->input->post('programs'));
	  	$qServices = explode(',', $this->input->post('services'));
	 	$programFlag = ($qPrograms !== 'null') ? true : false;
		$company_id = $this->session->userdata['company_id'] ?? $this->session->userdata['spraye_technician_login']->company_id;
		$where = array(
			'jobs.company_id' => $company_id,
			'property_tbl.company_id' => $company_id,
			'customer_status' => 1,
			'property_status' => 1
		);

		$where_in = array(
			'job_id' => $qServices
		);

		if($programFlag) {
			$where_in['program_id'] = $qProgramsArr;
		}

		$outstandingWork = $this->DashboardModel->getWorkReportOutstanding($where, $where_in);
		// die( print_r( json_encode( $outstandingWork ),true ));
		// $unServiceList = $this->DashboardModel->getUnassignedServiceList($where);
		// die(print_r(json_encode($unServiceList),true));

		$jobs = array();

		if($programFlag)
		{
			$qPrograms = explode(',', $qPrograms);
		}
        
		foreach($qServices as $service)
		{
			$where = array(
				'job_id'	=> $service
			);
			$job = $this->JobModel->getOneJob($where);
			$job->assigned = $this->RP->GetAllRow($where);

			if($programFlag && is_array($qPrograms))
			{
				foreach($job->assigned as $key => $value)
				{
					if(!in_array($value['program_id'], $qPrograms))
					{
						unset($job->assigned[$key]);
					}
				}
			}

			foreach($job->assigned as $key => $value)
			{
				if(is_null($value['invoice_id']) || !isset($value['invoice_id']) || $value['invoice_id'] == '' || $value['invoice_id'] == 0)
				{
					unset($job->assigned[$key]);
				}
			}
			if(!empty($job->assigned))
			{	
				array_push($jobs,$job);
			}
		}

		$areas = array();
		foreach($outstandingWork as $item)
		{
			$category_area_name = (isset($item->category_area_name) ? $item->category_area_name : 'NONE');
			if(!in_array($category_area_name, $areas)) {
                array_push($areas, $category_area_name);
			}
		}
        
        
		foreach($jobs as $job)
		{
			foreach($job->assigned as $item)
			{
                $category_area_name = (isset($item['category_area_name']) ? $item['category_area_name'] : 'NONE');
				if(!in_array($item['category_area_name'], $areas) && !is_null($item['category_area_name']))
				{

					array_push($areas, $item['category_area_name']);
				}				
			}
		}
		$index = 0;
		$services = array();
		foreach($areas as $area)
		{
			$tmp = (object)[];
			$tmp->open = array();
			$tmp->closed = array();
			$tmp->index = $index;
			$tmp->area = $area;
			foreach($outstandingWork as $item)
			{
                // sometimes the $item->category_area_name will come in as NULL - and above sets those to NONE - so we need to check for null and change to NONE so we get the results we expect
                if($item->category_area_name == NULL) {
                    $category_area_name_check = 'NONE';
                } else {
                    $category_area_name_check = $item->category_area_name;
                }
				if(isset($category_area_name_check) && $category_area_name_check == $area)
				{
					//get program invoice method
					$checkInvMethod = $this->ProgramModel->getOneProgramForCheck( array( 'program_id' => $item->program_id ));
					$programPrice = $checkInvMethod->program_price;
					//get job cost depending on invoice method
					if( $programPrice == 1 ) 
					{
						$where_arr = array(
							'customer_id' => $item->customer_id, 
							'property_id' => $item->property_id, 
							'program_id' => $item->program_id, 
							'job_id' => $item->job_id
						);
						$ppjobinv_details = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails( $where_arr );
						if( isset( $ppjobinv_details ) && isset( $ppjobinv_details->job_cost ))
						{
							$job_cost = $ppjobinv_details->job_cost;
						} else {
                            $job_cost = $this->calculateJobCost( $item );
						}
					} elseif( $programPrice == 2 || $programPrice == 3 )
					{
						$job_cost = $this->calculateJobCost( $item );
					}
					$job_cost = ( $job_cost === 0 || $job_cost === NULL ) ? 0 : $job_cost;
					$job = $this->JobModel->getOneJob(array( 'job_id' => $item->job_id));
					if( !is_null( $job ))
					{
						$tmp2 = (object)[];
						$tmp2->job_id = $item->job_id;
						$tmp2->sqft = $item->yard_square_feet;
						$tmp2->job_price_per = 1000;
						$tmp2->job_price = $job->job_price;
						$tmp2->job_name = $job->job_name;
						$tmp2->job_cost = $job_cost;
						array_push($tmp->open, $tmp2);
					}
				}
			}
			// die( print_r( json_encode( $jobs ), true ));
			foreach($jobs as $item)
			{
				foreach($item->assigned as $job)
				{
                    // sometimes the $job['category_area_name'] will come in as NULL - and above sets those to NONE - so we need to check for null and change to NONE so we get the results we expect
					if($job['category_area_name'] == NULL) {
                        $category_area_name_check = 'NONE';
                    } else {
                        $category_area_name_check = $job['category_area_name'];
                    }
                    if( $category_area_name_check == $area )
					{
						$cost = 0;
						if( isset( $job['invoice_id'] ))
						{
							$where = array(
								'job_id' => $job['job_id'],
								'invoice_id' => $job['invoice_id']
							);
							$cost = $this->RP->getJobInvoiceCost( $where );
						}
						if( $cost === 0 || $cost === NULL )
						{
							//get program invoice method
							$checkInvMethod = $this->ProgramModel->getOneProgramForCheck( array( 'program_id' => $job['program_id'] ));
							$programPrice = $checkInvMethod->program_price;
							//get job cost depending on invoice method
							if( $programPrice == 1 ) 
							{
								$where_arr = array(
									'customer_id' => $job['customer_id'], 
									'property_id' => $job['property_id'], 
									'program_id' => $job['program_id'], 
									'job_id' => $job['job_id']
								);
								$ppjobinv_details = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails( $where_arr );
								if( isset( $ppjobinv_details ) && isset( $ppjobinv_details->job_cost ))
								{
									$cost = $ppjobinv_details->job_cost;
								} else {
									// die(print_r( (object)$job,true ));
									$cost = $this->calculateJobCost( (object)$job );
								}
							} elseif( $programPrice == 2 || $programPrice == 3 )
							{
								$cost = $this->calculateJobCost( (object)$job );
							}
							$cost = ( $cost === 0 ) ? null : $cost;
							$cost = ( $cost === 0 || $cost === NULL ) ? 0 : $cost;
						}
						if( !is_null($cost) )
						{
							$tmp2 = (object)[];
							$tmp2->job_id = $job['job_id'];
							$tmp2->sqft = $job['yard_square_feet'];
							$tmp2->job_price_per = 1000;
							$tmp2->job_price = $item->job_price;
							$tmp2->job_name = $item->job_name;
							$tmp2->invoice_id = $job['invoice_id'];
							$tmp2->invoiced_price = $cost;
							array_push( $tmp->closed, $tmp2 );
						}
					}
				}
			}
			array_push($services, $tmp);
			$index++;
		}
		// die( print_r( json_encode( $services ),true ));
		$results = array();
		foreach($services as $service)
		{
			$t_serv_assgn = count( $service->open ) + count( $service->closed );
			$t_serv_comp = count( $service->closed );
			$t_serv_out = count( $service->open );
			$total_sqft = 0;
			$total_sqft_comp = 0;
			$total_sqft_out = 0;
			$total_rev_prod = 0;
			$total_rev_out = 0;
			$total_rev = 0;
			foreach($service->open as $open)
			{
				$total_sqft += floatval( $open->sqft );
				$total_sqft_out += floatval( $open->sqft );
				$total_rev_out +=  $open->job_cost;
				$total_rev += $open->job_cost;
			}
			foreach($service->closed as $closed)
			{
				$total_sqft += floatval( $closed->sqft );
				$total_sqft_comp += floatval( $closed->sqft );
				$total_rev_prod += floatval( $closed->invoiced_price );
				// $total_rev_prod += floatval( $closed->job_price ) * ( floatval( $closed->sqft ) / floatval( $closed->job_price_per ));
				$total_rev += $closed->invoiced_price;
			}
			$tmp = array(
				'service_area' => $service->area,
				't_serv_assgn' => $t_serv_assgn,
				't_serv_comp' => $t_serv_comp,
				't_serv_out' => $t_serv_out,
				'perc_comp' => ( $t_serv_comp == 0 ) ? 0 : round(( $t_serv_comp / $t_serv_assgn ) * 100 ),
				'total_sqft' => $total_sqft,
				'total_sqft_comp' => $total_sqft_comp,
				'total_sqft_out' => $total_sqft_out, 
				'perc_sqft_comp' => ( $total_sqft_comp == 0 ) ? 0 : round(( $total_sqft_comp / $total_sqft ) * 100 ),
				'total_rev_prod' => round( $total_rev_prod, 2 ),
				'total_rev_out' => round( $total_rev_out, 2 ),
				'perc_rev_prod' =>  ( $total_rev == 0 ) ? 0 : round(( $total_rev_prod / $total_rev ) * 100 ),
			);
			array_push($results ,$tmp);
		}

		echo json_encode($results);

	}	

	function downloadWorkReportCsv()
	{
		$data = $this->input->post();
		// die(print_r($data['csvData']));
		$json = json_decode($data['csvData']);
		// die(var_dump($json));
		$filename = "work_report" . date('Y-m-d') . ".csv";
		// $json = json_decode($data);
		foreach($json as $key => $value)
		{
			$json[$key] = '"'.implode('","',$value).'"';
		}
		$json = implode(PHP_EOL,$json);
		
		$f = fopen('php://memory', 'w');
		fputs($f, $json);
		fseek($f, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' .$filename. '";');
		fpassthru($f);
	}

	public function calculateJobCost( $job_details )
	{
		$job_cost = 0;
		// Currently only tested on invoice pricing 2
		$where_arr = array(
			'customer_id' => $job_details->customer_id,
			'property_id' => $job_details->property_id,
			'program_id' => $job_details->program_id,
			'job_id' => $job_details->job_id
		);
		$estimate_price_override = GetOneEstimateJobPriceOverride( $where_arr );
		if( $estimate_price_override && !empty( $estimate_price_override->is_price_override_set ))
		{
			$job_cost = $estimate_price_override->price_override;
		} else {
			$where_arr = array(
				'property_id' => $job_details->property_id,
				'program_id' => $job_details->program_id
			);
			$priceOverrideData = $this->Tech->getOnePriceOverride( $where_arr );

			if( isset($priceOverrideData->is_price_override_set) && $priceOverrideData->is_price_override_set == 1 )
			{
				$job_cost = $priceOverrideData->price_override;
			} else {
				//else no price overrides, then calculate job cost
				$job = $this->JobModel->getOneJob(array( 'job_id' => $job_details->job_id ));
				$property = $this->PropertyModel->getOneProperty( array( 'property_id' => $job_details->property_id ));
				$lawn_sqf = $job_details->yard_square_feet;
				$job_price = $job->job_price;

				//get property difficulty level
				$setting_details = $this->CompanyModel->getOneCompany( array( 'company_id' => $this->session->userdata['company_id'] ));

				if( isset( $property->difficulty_level ) && $property->difficulty_level == 2 )
				{
					$difficulty_multiplier = $setting_details->dlmult_2;
				} elseif( isset( $property->difficulty_level ) && $property->difficulty_level == 3 )
				{
					$difficulty_multiplier = $setting_details->dlmult_3;
				} else {
					$difficulty_multiplier = $setting_details->dlmult_1;
				}

				//get base fee 
				if( isset( $job->base_fee_override ))
				{
					$base_fee = $job->base_fee_override;
				} else 
				{
					$base_fee = $setting_details->base_service_fee;
				}

				$cost_per_sqf = $base_fee + ( $job_price * $lawn_sqf * $difficulty_multiplier ) / 1000;

				//get min. service fee
				if( isset( $job->min_fee_override ))
				{
					$min_fee = $job->min_fee_override;
				} else {
						$min_fee = $setting_details->minimum_service_fee;
				}

				// Compare cost per sf with min service fee
				if ($cost_per_sqf > $min_fee) {
						$job_cost = $cost_per_sqf;
				} else {
						$job_cost = $min_fee;
				}
			}
		}
		
		return $job_cost;

	}
	##### ADDED BY (RG) 2/23/22 #####
	## Sales Pipeline Summary Report
    public function salesPipelineSummary(){   
        //get the posts data
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
        $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);
      
        $data['pipeline_summary'] = $this->EstimateModal->getAllEstimate($where);
        
        $total_summary = [];
        if($data['pipeline_summary']){

            foreach($data['pipeline_summary'] as $summary){
                if(is_array($total_summary) && array_key_exists($summary->sales_rep, $total_summary)){
                    $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                    $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                   
                    $total_summary[$summary->sales_rep]['total_estimates'] += 1;
                    $total_summary[$summary->sales_rep]['total_cost'] += $total_cost;
                    if(isset($summary->property_status) && $summary->property_status == 1){
                        $total_summary[$summary->sales_rep]['customer'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['customer_total'] += $estimate_cost;
                        
                    } else {
                        $total_summary[$summary->sales_rep]['prospect'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['prospect_total'] += $estimate_cost;
                    }
                } else {
                    if(isset($summary->sales_rep) && $summary->sales_rep != '' && $summary->sales_rep !='0'){
                        $total_summary[$summary->sales_rep]['rep_name'] = $summary->user_first_name.' '.$summary->user_last_name;
                        $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                        $total_summary[$summary->sales_rep]['total_estimates'] = 1;
                        $total_summary[$summary->sales_rep]['total_cost'] = $total_cost;
                    
                        if(isset($summary->property_status) && $summary->property_status == 1){
                            $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                            $total_summary[$summary->sales_rep]['customer'] = 1 ;
                            $total_summary[$summary->sales_rep]['prospect'] = 0 ;
                            $total_summary[$summary->sales_rep]['customer_total'] = $estimate_cost ;
                            $total_summary[$summary->sales_rep]['prospect_total'] = 0 ;
                        } else {
                            $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                            $total_summary[$summary->sales_rep]['customer'] = 0 ;
                            $total_summary[$summary->sales_rep]['prospect'] = 1 ;
                            $total_summary[$summary->sales_rep]['prospect_total'] = $estimate_cost ;
                            $total_summary[$summary->sales_rep]['customer_total'] = 0 ;
                        }
                    }
                }
               
            }
        }
        
		// die(print_r( $data['pipeline_summary']));
		// die(print_r($total_summary));
        $data['total_summary'] = $total_summary;
	    $page["active_sidebar"] = "pipelineSummary";
        $page["page_name"] = 'Sales Pipeline Summary Report';
        $page["page_content"] = $this->load->view("admin/report/view_pipeline_summary_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }

    function totalEstimateCost($estimate_id, $property_id, $program_id, $yard_square_feet){
        $line_total = 0; 
        $job_details =  GetOneEstimatAllJobPrice(array('estimate_id'=>$estimate_id));
        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));

        if ($job_details) {

        foreach ($job_details as $key2 => $value2) {
            if ($value2['price_override'] != '' && $value2['price_override']!=0 && $value2['is_price_override_set'] == 1) {
                $cost =  $value2['price_override'];
                
            } else if ($value2['price_override'] != '' && $value2['price_override'] == 0 && $value2['is_price_override_set'] == 1){
                $cost = number_format(0, 2);
                // die(print_r($job_details));
            } else {

            $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id'=>$property_id,'program_id'=>$program_id)); 

            if ($priceOverrideData && $priceOverrideData->price_override!=0 && $priceOverrideData->is_price_override_set == 1) {
                // $price = $priceOverrideData->price_override;
                $cost =  $priceOverrideData->price_override;
                
            } else if ($priceOverrideData && $priceOverrideData->price_override == 0 && $priceOverrideData->is_price_override_set == 1){
                    $cost = number_format(0, 2);
            } else {
                //else no price overrides, then calculate job cost
                $lawn_sqf = $yard_square_feet;
                $job_price = $value2['job_price'];
                                
                //get property difficulty level
                if(isset($value->difficulty_level) && $value->difficulty_level == 2){
                $difficulty_multiplier = $setting_details->dlmult_2;
                }elseif(isset($value->difficulty_level) && $value->difficulty_level == 3){
                $difficulty_multiplier = $setting_details->dlmult_3;
                }else{
                $difficulty_multiplier = $setting_details->dlmult_1;
                }
                            
                //get base fee 
                if(isset($value2['base_fee_override'])){
                $base_fee = $value2['base_fee_override'];
                }else{
                $base_fee = $setting_details->base_service_fee;
                }

                $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

                //get min. service fee
                if(isset($value2['min_fee_override'])){
                $min_fee = $value2['min_fee_override'];
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

            //  $line_total += $cost;
            $line_total += round($cost, 2);
        }
        
        }

        // apply coupons if exists
        $total_cost = $line_total;
        if (isset($value->coupon_details) && !empty($value->coupon_details)){
        foreach($value->coupon_details as $coupon) {
            if ($coupon->coupon_amount_calculation == 0) { // flat
                $coupon_amm = $coupon->coupon_amount;
            } else { // perc
                $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
            }
            $total_cost -= $coupon_amm;
            if ($total_cost < 0) {
                $total_cost = 0;
            }
        }
        }
        $line_total = $total_cost;

        // apply sales tax
        $line_tax_amount = 0;
        if ($setting_details->is_sales_tax==1) {
            $sales_tax_details =  getAllSalesTaxByProperty($property_id);

            if ($sales_tax_details) {
                foreach ($sales_tax_details as  $property_sales_tax) {
                $line_tax_amount += $line_total * $property_sales_tax->tax_value /100;
                }           
            }
            $line_total += $line_tax_amount;
        }

        // echo '$ '.number_format(($line_total) ,2); 
        return $line_total; 
        
    }
 
   
## ajax data for Sales Pipeline Summary Report
     function ajaxPipelineSummaryData(){
        $conditions = array();
        
        //set conditions for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');

        if(!empty($sales_rep_id)){
            $conditions['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
         if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
        
        // die(print_r($conditions));
         //get posts data
        $data['estimate_summary'] = $this->EstimateModal->getAllEstimateSearch($conditions);
        // die(print_r($data['estimate_summary']));
          $total_summary = [];
          if(!empty($data['estimate_summary'])){

              foreach($data['estimate_summary'] as $summary){
                if(is_array($total_summary) && array_key_exists($summary->sales_rep, $total_summary)){
                    $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                    $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                   
                    
                    $total_summary[$summary->sales_rep]['total_estimates'] += 1;
                    $total_summary[$summary->sales_rep]['total_cost'] += $total_cost;
                    if(isset($summary->property_status) && $summary->property_status == 1){
                        $total_summary[$summary->sales_rep]['customer'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['customer_total'] += $estimate_cost;
                        
                    } else {
                        $total_summary[$summary->sales_rep]['prospect'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['prospect_total'] += $estimate_cost;
                    }
                } else {
                    $total_summary[$summary->sales_rep]['rep_name'] = $summary->user_first_name.' '.$summary->user_last_name;
                    $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                    $total_summary[$summary->sales_rep]['total_estimates'] = 1;
                    $total_summary[$summary->sales_rep]['total_cost'] = $total_cost;
                  
                    if(isset($summary->property_status) && $summary->property_status == 1){
                        $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                        $total_summary[$summary->sales_rep]['customer'] = 1 ;
                        $total_summary[$summary->sales_rep]['prospect'] = 0 ;
                        $total_summary[$summary->sales_rep]['customer_total'] = $estimate_cost ;
                        $total_summary[$summary->sales_rep]['prospect_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                        $total_summary[$summary->sales_rep]['customer'] = 0 ;
                        $total_summary[$summary->sales_rep]['prospect'] = 1 ;
                        $total_summary[$summary->sales_rep]['prospect_total'] = $estimate_cost ;
                        $total_summary[$summary->sales_rep]['customer_total'] = 0 ;
                    }
                   
                }
               
            }
          }
       $data['total_summary'] = $total_summary;
        //    die(print_r($data['total_summary']));
        $body =  $this->load->view('admin/report/ajax_pipeline_summary_report', $data, false);

        echo $body;

    }



## Download CSV for Sales Pipeline Summary Report
    public function downloadPipelineSummaryCsv(){

        $status = '';
        $conditions = array();
         //set conditions for search
         $sales_rep_id = $this->input->post('sales_rep_id');
         $estimate_created_date_to = $this->input->post('estimate_created_date_to');
         $estimate_created_date_from = $this->input->post('estimate_created_date_from');
 
         if(!empty($sales_rep_id)){
             $conditions['search']['sales_rep_id'] = $sales_rep_id;
         }
 
         if(!empty($estimate_created_date_to)){
             $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
         }
          if(!empty($estimate_created_date_from)){
             $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
         }
         
         // die(print_r($conditions));
          //get posts data
         $data['estimate_summary'] = $this->EstimateModal->getAllEstimateSearch($conditions);
           $total_summary = [];
        if($data['estimate_summary']){

            foreach($data['estimate_summary'] as $summary){
                if(is_array($total_summary) && array_key_exists($summary->sales_rep, $total_summary)){
                    $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                    $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                   
                    
                    $total_summary[$summary->sales_rep]['total_estimates'] += 1;
                    $total_summary[$summary->sales_rep]['total_cost'] += $total_cost;
                    if(isset($summary->property_status) && $summary->property_status == 1){
                        $total_summary[$summary->sales_rep]['customer'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['customer_total'] += $estimate_cost;
                        
                    } else {
                        $total_summary[$summary->sales_rep]['prospect'] += 1 ;
                        
                    $total_summary[$summary->sales_rep]['prospect_total'] += $estimate_cost;
                    }
                } else {
                    $total_summary[$summary->sales_rep]['rep_name'] = $summary->user_first_name.' '.$summary->user_last_name;
                    $total_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                    $total_summary[$summary->sales_rep]['total_estimates'] = 1;
                    $total_summary[$summary->sales_rep]['total_cost'] = $total_cost;
                  
                    if(isset($summary->property_status) && $summary->property_status == 1){
                        $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                        $total_summary[$summary->sales_rep]['customer'] = 1 ;
                        $total_summary[$summary->sales_rep]['prospect'] = 0 ;
                        $total_summary[$summary->sales_rep]['customer_total'] = $estimate_cost ;
                        $total_summary[$summary->sales_rep]['prospect_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($summary->estimate_id, $summary->property_id, $summary->program_id, $summary->yard_square_feet);
                        $total_summary[$summary->sales_rep]['customer'] = 0 ;
                        $total_summary[$summary->sales_rep]['prospect'] = 1 ;
                        $total_summary[$summary->sales_rep]['prospect_total'] = $estimate_cost ;
                        $total_summary[$summary->sales_rep]['customer_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        $data['total_summary'] = $total_summary;
            // die(print_r($data['total_summary']));
        // echo $this->db->last_query();
        // die();
        if($total_summary){
        $delimiter = ",";
        $filename = "report_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
        
        //set column headers
        $fields = array('User','Total # of Open Estimates','Total $ of Open Estimates','Total # of New Customer Estimates','Total $ of New Customer Estimates','Total # of Existing Customer Estimates','Total $ of Existing Customer Estimates',);
        fputcsv($f, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        
        
        foreach ($total_summary as $key => $value) {
            $status = 1;
            $lineData = array($value['rep_name'],$value['total_estimates'], number_format(($value['total_cost']) ,2), $value['prospect'],number_format(($value['prospect_total']) ,2),$value['customer'], number_format(($value['customer_total']) ,2));

            fputcsv($f, $lineData, $delimiter);
            
            }


            if ($status==1) {

                            //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                    //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/reports/salesPipelineSummary");
            }   

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/reports/salesPipelineSummary");

        }    

    }

	## Sales  Summary Report
    public function salesSummary( $active = 0){   
        //get the posts data
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
        $data['SavedFilter'] = $this->SalesSummarySaveModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));

        $where = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);
      
        $data['sales_summary'] = $this->EstimateModal->getAllEstimate($where);
        
        // die(print_r($data['sales_summary']));

        $report_summary = [];
        if($data['sales_summary']){

            foreach($data['sales_summary'] as $report){
                if(is_array($report_summary) && array_key_exists($report->sales_rep, $report_summary)){
                    $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                   
                    $report_summary[$report->sales_rep]['total_estimates'] += 1;
                    if(isset($report->status) && $report->status == 2){
                        $report_summary[$report->sales_rep]['accepted'] += 1 ;
                        $report_summary[$report->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($report->status) && $report->status == 5){
                        $report_summary[$report->sales_rep]['declined'] += 1 ;
                        $report_summary[$report->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $report_summary[$report->sales_rep]['accepted'] += 0 ;
                        $report_summary[$report->sales_rep]['accepted_total'] += 0;
                        $report_summary[$report->sales_rep]['declined'] += 0 ;
                        $report_summary[$report->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $report_summary[$report->sales_rep]['rep_name'] = $report->user_first_name.' '.$report->user_last_name;

                    $name_from_source = $this->RP->getNameFromSourceNumber($report->source);
                    if($name_from_source == "") {
                        $name_from_source = $this->RP->getUserNameFromSourceNumber($report->source);
                    }


                    $report_summary[$report->sales_rep]['source'] = $name_from_source;
                    $total_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                    $report_summary[$report->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($report->status) && $report->status == 2){
                        $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                        $report_summary[$report->sales_rep]['accepted'] = 1 ;
                        $report_summary[$report->sales_rep]['declined'] = 0 ;
                        $report_summary[$report->sales_rep]['accepted_total'] = $estimate_cost ;
                        $report_summary[$report->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($report->status) && $report->status == 5){
                        $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                        $report_summary[$report->sales_rep]['accepted'] = 0 ;
                        $report_summary[$report->sales_rep]['declined'] = 1 ;
                        $report_summary[$report->sales_rep]['declined_total'] = $estimate_cost ;
                        $report_summary[$report->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                        $report_summary[$report->sales_rep]['accepted'] = 0 ;
                        $report_summary[$report->sales_rep]['declined'] = 0 ;
                        $report_summary[$report->sales_rep]['declined_total'] = 0 ;
                        $report_summary[$report->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        
		// die(print_r( $data['sales_summary']));
		// die(print_r($report_summary));
		// die(print_r($rep_summary));
        $data['report_summary'] = $report_summary;

        $data['active_nav_link'] = $active;


	    $page["active_sidebar"] = "salesSummary";
        $page["page_name"] = 'Sales Summary Report';
        $page["page_content"] = $this->load->view("admin/report/view_sales_summary_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
   
    ## ajax data for Sales Summary Report
     function ajaxSalesSummaryData(){
        $conditions = array();
        
        //set conditions for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($sales_rep_id)){
            $conditions['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
         if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
        if(!empty($date_range_date_to)){
            $conditions['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions['search']['date_range_date_from'] = $date_range_date_from;
        }
        if(!empty($comparision_range_date_to)){
            $conditions['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }

        // die(print_r($conditions));
        
         //get posts data
        $data['sales_summary'] = $this->EstimateModal->getAllEstimateSearch($conditions);

        $report_summary = [];
        foreach($data['sales_summary'] as $report){
            if(is_array($report_summary) && array_key_exists($report->sales_rep, $report_summary)){
                $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
               
                
                $report_summary[$report->sales_rep]['total_estimates'] += 1;
                if(isset($report->status) && $report->status == 2){
                    $report_summary[$report->sales_rep]['accepted'] += 1 ;
                    $report_summary[$report->sales_rep]['accepted_total'] += $estimate_cost;
                    
                } elseif (isset($report->status) && $report->status == 5){
                    $report_summary[$report->sales_rep]['declined'] += 1 ;
                    $report_summary[$report->sales_rep]['declined_total'] += $estimate_cost;
                } else {
                    $report_summary[$report->sales_rep]['accepted'] += 0 ;
                    $report_summary[$report->sales_rep]['accepted_total'] += 0;
                    $report_summary[$report->sales_rep]['declined'] += 0 ;
                    $report_summary[$report->sales_rep]['declined_total'] += 0;

                }
            } else {
                $report_summary[$report->sales_rep]['rep_name'] = $report->user_first_name.' '.$report->user_last_name;
                $total_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                $report_summary[$report->sales_rep]['total_estimates'] = 1;
              
                if(isset($report->status) && $report->status == 2){
                    $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                    $report_summary[$report->sales_rep]['accepted'] = 1 ;
                    $report_summary[$report->sales_rep]['declined'] = 0 ;
                    $report_summary[$report->sales_rep]['accepted_total'] = $estimate_cost ;
                    $report_summary[$report->sales_rep]['declined_total'] = 0 ;
                } elseif (isset($report->status) && $report->status == 5){
                    $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                    $report_summary[$report->sales_rep]['accepted'] = 0 ;
                    $report_summary[$report->sales_rep]['declined'] = 1 ;
                    $report_summary[$report->sales_rep]['declined_total'] = $estimate_cost ;
                    $report_summary[$report->sales_rep]['accepted_total'] = 0 ;
                } else {
                    $estimate_cost = $this->totalEstimateCost($report->estimate_id, $report->property_id, $report->program_id, $report->yard_square_feet);
                    $report_summary[$report->sales_rep]['accepted'] = 0 ;
                    $report_summary[$report->sales_rep]['declined'] = 0 ;
                    $report_summary[$report->sales_rep]['declined_total'] = 0 ;
                    $report_summary[$report->sales_rep]['accepted_total'] = 0 ;
                }
               
            }
           
        }
           $data['report_summary'] = $report_summary;
        $body =  $this->load->view('admin/report/ajax_sales_summary_report', $data, false);

        echo $body;

    }
    #### total estimates
     function ajaxSalesSummaryDataNew(){
        $conditions_1 = array();
        //set conditions for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');
        

        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
       
        $conditions_2 = array();
        //set conditions for search
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');
        
        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }
        
        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }
        // die(print_r($conditions_1));
        // die(print_r($conditions_2));
         //get posts data
        $data['sales_summary_1'] = $this->EstimateModal->getAllEstimateSearch($conditions_1);
        $data['sales_summary_2'] = $this->EstimateModal->getAllEstimateSearch($conditions_2);
		//  die(print_r( $data['sales_summary_1']));
		//  die(print_r( $data['sales_summary_2']));
        #### REPORT SUMMARY CONDITION #1 ####
        $report_summary_1 = [];
        if(!empty($data['sales_summary_1'])){

            foreach($data['sales_summary_1'] as $report_1){
                if(is_array($report_summary_1) && array_key_exists($report_1->sales_rep, $report_summary_1)){
                    $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                   
                    
                    $report_summary_1[$report_1->sales_rep]['total_estimates'] += 1;
                    if(isset($report_1->status) && $report_1->status == 2){
                        $report_summary_1[$report_1->sales_rep]['accepted'] += 1 ;
                        $report_summary_1[$report_1->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($report_1->status) && $report_1->status == 5){
                        $report_summary_1[$report_1->sales_rep]['declined'] += 1 ;
                        $report_summary_1[$report_1->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $report_summary_1[$report_1->sales_rep]['accepted'] += 0 ;
                        $report_summary_1[$report_1->sales_rep]['accepted_total'] += 0;
                        $report_summary_1[$report_1->sales_rep]['declined'] += 0 ;
                        $report_summary_1[$report_1->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $report_summary_1[$report_1->sales_rep]['rep_id'] = $report_1->sales_rep;
                    $report_summary_1[$report_1->sales_rep]['rep_name'] = $report_1->user_first_name.' '.$report_1->user_last_name;
                    $total_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                    $report_summary_1[$report_1->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($report_1->status) && $report_1->status == 2){
                        $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                        $report_summary_1[$report_1->sales_rep]['accepted'] = 1 ;
                        $report_summary_1[$report_1->sales_rep]['declined'] = 0 ;
                        $report_summary_1[$report_1->sales_rep]['accepted_total'] = $estimate_cost ;
                        $report_summary_1[$report_1->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($report_1->status) && $report_1->status == 5){
                        $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                        $report_summary_1[$report_1->sales_rep]['accepted'] = 0 ;
                        $report_summary_1[$report_1->sales_rep]['declined'] = 1 ;
                        $report_summary_1[$report_1->sales_rep]['declined_total'] = $estimate_cost ;
                        $report_summary_1[$report_1->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                        $report_summary_1[$report_1->sales_rep]['accepted'] = 0 ;
                        $report_summary_1[$report_1->sales_rep]['declined'] = 0 ;
                        $report_summary_1[$report_1->sales_rep]['declined_total'] = 0 ;
                        $report_summary_1[$report_1->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        #### END REPORT SUMMARY CONDITION #1 ####
        #### REPORT SUMMARY CONDITION #2 ####
        $report_summary_2 = [];
        if(!empty($data['sales_summary_2'])){

            foreach($data['sales_summary_2'] as $report_2){
                if(is_array($report_summary_2) && array_key_exists($report_2->sales_rep, $report_summary_2)){
                    $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                   
                    
                    $report_summary_2[$report_2->sales_rep]['total_estimates'] += 1;
                    if(isset($report_2->status) && $report_2->status == 2){
                        $report_summary_2[$report_2->sales_rep]['accepted'] += 1 ;
                        $report_summary_2[$report_2->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($report_2->status) && $report_2->status == 5){
                        $report_summary_2[$report_2->sales_rep]['declined'] += 1 ;
                        $report_summary_2[$report_2->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $report_summary_2[$report_2->sales_rep]['accepted'] += 0 ;
                        $report_summary_2[$report_2->sales_rep]['accepted_total'] += 0;
                        $report_summary_2[$report_2->sales_rep]['declined'] += 0 ;
                        $report_summary_2[$report_2->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $report_summary_2[$report_2->sales_rep]['rep_id'] = $report_2->sales_rep;
                    $report_summary_2[$report_2->sales_rep]['rep_name'] = $report_2->user_first_name.' '.$report_2->user_last_name;
                    $total_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                    $report_summary_2[$report_2->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($report_2->status) && $report_2->status == 2){
                        $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                        $report_summary_2[$report_2->sales_rep]['accepted'] = 1 ;
                        $report_summary_2[$report_2->sales_rep]['declined'] = 0 ;
                        $report_summary_2[$report_2->sales_rep]['accepted_total'] = $estimate_cost ;
                        $report_summary_2[$report_2->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($report_2->status) && $report_2->status == 5){
                        $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                        $report_summary_2[$report_2->sales_rep]['accepted'] = 0 ;
                        $report_summary_2[$report_2->sales_rep]['declined'] = 1 ;
                        $report_summary_2[$report_2->sales_rep]['declined_total'] = $estimate_cost ;
                        $report_summary_2[$report_2->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                        $report_summary_2[$report_2->sales_rep]['accepted'] = 0 ;
                        $report_summary_2[$report_2->sales_rep]['declined'] = 0 ;
                        $report_summary_2[$report_2->sales_rep]['declined_total'] = 0 ;
                        $report_summary_2[$report_2->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        #### END REPORT SUMMARY CONDITION #2 ####

           $data['report_summary_1'] = $report_summary_1;
           $data['report_summary_2'] = $report_summary_2;
        //    die(print_r($data['report_summary_1']));
        //    die(print_r($data['report_summary_2']));
        $report_results = [];
        if(isset($data['report_summary_2']) && !empty($data['report_summary_2'])){

            foreach($data['report_summary_1'] as $rSummary1){
                foreach($data['report_summary_2'] as $rSummary2){
                    if(($rSummary1['rep_id'] == $rSummary2['rep_id'] )){
                        $report_result = array(
                            'rep_id' => $rSummary1['rep_id'],
                            'rep_name' => $rSummary1['rep_name'],
                            'total_estimates_1' => $rSummary1['total_estimates'],
                            'accepted_1' => $rSummary1['accepted'],
                            'declined_1' => $rSummary1['declined'],
                            'accepted_total_1' => $rSummary1['accepted_total'],
                            'declined_total_1' => $rSummary1['declined_total'],
                            'total_estimates_2' => $rSummary2['total_estimates'],
                            'accepted_2' => $rSummary2['accepted'],
                            'declined_2' => $rSummary2['declined'],
                            'accepted_total_2' => $rSummary2['accepted_total'],
                            'declined_total_2' => $rSummary2['declined_total'],
    
                        );
                        array_push($report_results, $report_result );
                   
                    }
                }
            }
        } else {
            foreach($data['report_summary_1'] as $rSummary1){
                $report_result = array(
                    'rep_id' => $rSummary1['rep_id'],
                    'rep_name' => $rSummary1['rep_name'],
                    'total_estimates_1' => $rSummary1['total_estimates'],
                    'accepted_1' => $rSummary1['accepted'],
                    'declined_1' => $rSummary1['declined'],
                    'accepted_total_1' => $rSummary1['accepted_total'],
                    'declined_total_1' => $rSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,

                );
                array_push($report_results, $report_result );
            }
            // die(print_r($report_results));
        }
        $data['report_results'] = $report_results;
        // die(print_r($data['report_results']));

        $body =  $this->load->view('admin/report/ajax_sales_summary_report_new', $data, false);

        echo $body;

    }
   
    #### accepted estimates
     function ajaxSalesSummaryDataAccepted(){
        
        $conditions_1 = array();
        //set conditions_1 for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        // die(print_r($sales_rep_id));
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');

        if(!empty($sales_rep_id)){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();
        //set conditions_1 for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        // die(print_r($sales_rep_id));
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($sales_rep_id)){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }

        // die(print_r($conditions_1));
        // die(print_r($conditions_2));
        
         //get posts data
        $data['sales_summary_1'] = $this->EstimateModal->getAllEstimateSearch($conditions_1);
        $data['sales_summary_2'] = $this->EstimateModal->getAllEstimateSearch($conditions_2);

        #### ACCEPTED SUMMARY CONDITION #1 ####
        $accepted_summary_1 = [];
        if(!empty($data['sales_summary_1'])){

            foreach($data['sales_summary_1'] as $accepted_1){
                if(is_array($accepted_summary_1) && array_key_exists($accepted_1->sales_rep, $accepted_summary_1)){
                    $estimate_cost = $this->totalEstimateCost($accepted_1->estimate_id, $accepted_1->property_id, $accepted_1->program_id, $accepted_1->yard_square_feet);
                   
                    
                    $accepted_summary_1[$accepted_1->sales_rep]['total_estimates'] += 1;
                    if(isset($accepted_1->status) && $accepted_1->status == 2){
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted'] += 1 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($accepted_1->status) && $accepted_1->status == 5){
                        $accepted_summary_1[$accepted_1->sales_rep]['declined'] += 1 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted'] += 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted_total'] += 0;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined'] += 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $accepted_summary_1[$accepted_1->sales_rep]['rep_id'] = $accepted_1->sales_rep;
                    $accepted_summary_1[$accepted_1->sales_rep]['rep_name'] = $accepted_1->user_first_name.' '.$accepted_1->user_last_name;
                    $total_cost = $this->totalEstimateCost($accepted_1->estimate_id, $accepted_1->property_id, $accepted_1->program_id, $accepted_1->yard_square_feet);
                    $accepted_summary_1[$accepted_1->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($accepted_1->status) && $accepted_1->status == 2){
                        $estimate_cost = $this->totalEstimateCost($accepted_1->estimate_id, $accepted_1->property_id, $accepted_1->program_id, $accepted_1->yard_square_feet);
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted'] = 1 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined'] = 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted_total'] = $estimate_cost ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($accepted_1->status) && $accepted_1->status == 5){
                        $estimate_cost = $this->totalEstimateCost($accepted_1->estimate_id, $accepted_1->property_id, $accepted_1->program_id, $accepted_1->yard_square_feet);
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted'] = 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined'] = 1 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined_total'] = $estimate_cost ;
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($accepted_1->estimate_id, $accepted_1->property_id, $accepted_1->program_id, $accepted_1->yard_square_feet);
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted'] = 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined'] = 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['declined_total'] = 0 ;
                        $accepted_summary_1[$accepted_1->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        ### ACCEPTED SUMMARY CONDITION #1 ###
        #### ACCEPTED SUMMARY CONDITION #2 ####
        $accepted_summary_2 = [];
        if(!empty($data['sales_summary_2'])){

            foreach($data['sales_summary_2'] as $accepted_2){
                if(is_array($accepted_summary_2) && array_key_exists($accepted_2->sales_rep, $accepted_summary_2)){
                    $estimate_cost = $this->totalEstimateCost($accepted_2->estimate_id, $accepted_2->property_id, $accepted_2->program_id, $accepted_2->yard_square_feet);
                   
                    
                    $accepted_summary_2[$accepted_2->sales_rep]['total_estimates'] += 1;
                    if(isset($accepted_2->status) && $accepted_2->status == 2){
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted'] += 1 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($accepted_2->status) && $accepted_2->status == 5){
                        $accepted_summary_2[$accepted_2->sales_rep]['declined'] += 1 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted'] += 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted_total'] += 0;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined'] += 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $accepted_summary_2[$accepted_2->sales_rep]['rep_id'] = $accepted_2->sales_rep;
                    $accepted_summary_2[$accepted_2->sales_rep]['rep_name'] = $accepted_2->user_first_name.' '.$accepted_2->user_last_name;
                    $total_cost = $this->totalEstimateCost($accepted_2->estimate_id, $accepted_2->property_id, $accepted_2->program_id, $accepted_2->yard_square_feet);
                    $accepted_summary_2[$accepted_2->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($accepted_2->status) && $accepted_2->status == 2){
                        $estimate_cost = $this->totalEstimateCost($accepted_2->estimate_id, $accepted_2->property_id, $accepted_2->program_id, $accepted_2->yard_square_feet);
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted'] = 1 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined'] = 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted_total'] = $estimate_cost ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($accepted_2->status) && $accepted_2->status == 5){
                        $estimate_cost = $this->totalEstimateCost($accepted_2->estimate_id, $accepted_2->property_id, $accepted_2->program_id, $accepted_2->yard_square_feet);
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted'] = 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined'] = 1 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined_total'] = $estimate_cost ;
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($accepted_2->estimate_id, $accepted_2->property_id, $accepted_2->program_id, $accepted_2->yard_square_feet);
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted'] = 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined'] = 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['declined_total'] = 0 ;
                        $accepted_summary_2[$accepted_2->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
        ### ACCEPTED SUMMARY CONDITION #2 ###
           $data['accepted_summary_1'] = $accepted_summary_1;
           $data['accepted_summary_2'] = $accepted_summary_2;
        //    die(print_r($data['accepted_summary_1']));
        //    die(print_r($data['accepted_summary_2']));
       
        $accepted_results = [];
        if(isset($data['accepted_summary_2']) && !empty($data['accepted_summary_2'])){
            foreach($data['accepted_summary_1'] as $aSummary1){
                foreach($data['accepted_summary_2'] as $aSummary2){
                    if($aSummary1['rep_id'] == $aSummary2['rep_id']){
                        $accepted_result = array(
                            'rep_id' => $aSummary1['rep_id'],
                            'rep_name' => $aSummary1['rep_name'],
                            'total_estimates_1' => $aSummary1['total_estimates'],
                            'accepted_1' => $aSummary1['accepted'],
                            'declined_1' => $aSummary1['declined'],
                            'accepted_total_1' => $aSummary1['accepted_total'],
                            'declined_total_1' => $aSummary1['declined_total'],
                            'total_estimates_2' => $aSummary2['total_estimates'],
                            'accepted_2' => $aSummary2['accepted'],
                            'declined_2' => $aSummary2['declined'],
                            'accepted_total_2' => $aSummary2['accepted_total'],
                            'declined_total_2' => $aSummary2['declined_total'],


                        );
                        array_push($accepted_results, $accepted_result );
                    }
                }
            }
        } else {
            foreach($data['accepted_summary_1'] as $aSummary1){
                $accepted_result = array(
                    'rep_id' => $aSummary1['rep_id'],
                    'rep_name' => $aSummary1['rep_name'],
                    'total_estimates_1' => $aSummary1['total_estimates'],
                    'accepted_1' => $aSummary1['accepted'],
                    'declined_1' => $aSummary1['declined'],
                    'accepted_total_1' => $aSummary1['accepted_total'],
                    'declined_total_1' => $aSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,

                );
                array_push($accepted_results, $accepted_result );
            } 
        }
        // die(print_r($accepted_results));
        $data['accepted_results'] = $accepted_results;

        $body =  $this->load->view('admin/report/ajax_sales_summary_report_accepted', $data, false);
        
        echo $body;

    }
   
    #### declined estimates
     function ajaxSalesSummaryDataDeclined(){
        $conditions_1 = array();
        //set conditions_1 for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        // die(print_r($sales_rep_id));
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');

        if(!empty($sales_rep_id)){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();
        //set conditions_1 for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        // die(print_r($sales_rep_id));
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($sales_rep_id)){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }

        // die(print_r($conditions_1));
        // die(print_r($conditions_2));
        
         //get posts data
        $data['sales_summary_1'] = $this->EstimateModal->getAllEstimateSearch($conditions_1);
        $data['sales_summary_2'] = $this->EstimateModal->getAllEstimateSearch($conditions_2);
        // die(print_r($data['sales_summary_1']));
        // die(print_r($data['sales_summary_2']));

        $declined_summary_1 = [];
        if(!empty($data['sales_summary_1'])){
            foreach($data['sales_summary_1'] as $declined_1){
                if(is_array($declined_summary_1) && array_key_exists($declined_1->sales_rep, $declined_summary_1)){
                    $estimate_cost = $this->totalEstimateCost($declined_1->estimate_id, $declined_1->property_id, $declined_1->program_id, $declined_1->yard_square_feet);
                   
                    
                    $declined_summary_1[$declined_1->sales_rep]['total_estimates'] += 1;
                    if(isset($declined_1->status) && $declined_1->status == 2){
                        $declined_summary_1[$declined_1->sales_rep]['accepted'] += 1 ;
                        $declined_summary_1[$declined_1->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($declined_1->status) && $declined_1->status == 5){
                        $declined_summary_1[$declined_1->sales_rep]['declined'] += 1 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $declined_summary_1[$declined_1->sales_rep]['accepted'] += 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['accepted_total'] += 0;
                        $declined_summary_1[$declined_1->sales_rep]['declined'] += 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $declined_summary_1[$declined_1->sales_rep]['rep_id'] = $declined_1->sales_rep;
                    $declined_summary_1[$declined_1->sales_rep]['rep_name'] = $declined_1->user_first_name.' '.$declined_1->user_last_name;
                    $total_cost = $this->totalEstimateCost($declined_1->estimate_id, $declined_1->property_id, $declined_1->program_id, $declined_1->yard_square_feet);
                    $declined_summary_1[$declined_1->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($declined_1->status) && $declined_1->status == 2){
                        $estimate_cost = $this->totalEstimateCost($declined_1->estimate_id, $declined_1->property_id, $declined_1->program_id, $declined_1->yard_square_feet);
                        $declined_summary_1[$declined_1->sales_rep]['accepted'] = 1 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined'] = 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['accepted_total'] = $estimate_cost ;
                        $declined_summary_1[$declined_1->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($declined_1->status) && $declined_1->status == 5){
                        $estimate_cost = $this->totalEstimateCost($declined_1->estimate_id, $declined_1->property_id, $declined_1->program_id, $declined_1->yard_square_feet);
                        $declined_summary_1[$declined_1->sales_rep]['accepted'] = 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined'] = 1 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined_total'] = $estimate_cost ;
                        $declined_summary_1[$declined_1->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($declined_1->estimate_id, $declined_1->property_id, $declined_1->program_id, $declined_1->yard_square_feet);
                        $declined_summary_1[$declined_1->sales_rep]['accepted'] = 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined'] = 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['declined_total'] = 0 ;
                        $declined_summary_1[$declined_1->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }

        $declined_summary_2 = [];
        if(!empty($data['sales_summary_2'])){
            foreach($data['sales_summary_2'] as $declined_2){
                if(is_array($declined_summary_2) && array_key_exists($declined_2->sales_rep, $declined_summary_2)){
                    $estimate_cost = $this->totalEstimateCost($declined_2->estimate_id, $declined_2->property_id, $declined_2->program_id, $declined_2->yard_square_feet);
                   
                    
                    $declined_summary_2[$declined_2->sales_rep]['total_estimates'] += 1;
                    if(isset($declined_2->status) && $declined_2->status == 2){
                        $declined_summary_2[$declined_2->sales_rep]['accepted'] += 1 ;
                        $declined_summary_2[$declined_2->sales_rep]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($declined_2->status) && $declined_2->status == 5){
                        $declined_summary_2[$declined_2->sales_rep]['declined'] += 1 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined_total'] += $estimate_cost;
                    } else {
                        $declined_summary_2[$declined_2->sales_rep]['accepted'] += 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['accepted_total'] += 0;
                        $declined_summary_2[$declined_2->sales_rep]['declined'] += 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined_total'] += 0;
    
                    }
                } else {
                    $declined_summary_2[$declined_2->sales_rep]['rep_id'] = $declined_2->sales_rep;
                    $declined_summary_2[$declined_2->sales_rep]['rep_name'] = $declined_2->user_first_name.' '.$declined_2->user_last_name;
                    $total_cost = $this->totalEstimateCost($declined_2->estimate_id, $declined_2->property_id, $declined_2->program_id, $declined_2->yard_square_feet);
                    $declined_summary_2[$declined_2->sales_rep]['total_estimates'] = 1;
                  
                    if(isset($declined_2->status) && $declined_2->status == 2){
                        $estimate_cost = $this->totalEstimateCost($declined_2->estimate_id, $declined_2->property_id, $declined_2->program_id, $declined_2->yard_square_feet);
                        $declined_summary_2[$declined_2->sales_rep]['accepted'] = 1 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined'] = 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['accepted_total'] = $estimate_cost ;
                        $declined_summary_2[$declined_2->sales_rep]['declined_total'] = 0 ;
                    } elseif (isset($declined_2->status) && $declined_2->status == 5){
                        $estimate_cost = $this->totalEstimateCost($declined_2->estimate_id, $declined_2->property_id, $declined_2->program_id, $declined_2->yard_square_feet);
                        $declined_summary_2[$declined_2->sales_rep]['accepted'] = 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined'] = 1 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined_total'] = $estimate_cost ;
                        $declined_summary_2[$declined_2->sales_rep]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $this->totalEstimateCost($declined_2->estimate_id, $declined_2->property_id, $declined_2->program_id, $declined_2->yard_square_feet);
                        $declined_summary_2[$declined_2->sales_rep]['accepted'] = 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined'] = 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['declined_total'] = 0 ;
                        $declined_summary_2[$declined_2->sales_rep]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }
           $data['declined_summary_1'] = $declined_summary_1;
           $data['declined_summary_2'] = $declined_summary_2;
        //    die(print_r($data['declined_summary_1']));
        //    die(print_r($data['declined_summary_2']));

        $declined_results = [];
        if(isset($data['declined_summary_2']) && !empty($data['declined_summary_2'])){
            foreach($data['declined_summary_1'] as $dSummary1){
                foreach($data['declined_summary_2'] as $dSummary2){
                    if($dSummary1['rep_id'] == $dSummary2['rep_id']){
                        $declined_result = array(
                            'rep_id' => $dSummary1['rep_id'],
                            'rep_name' => $dSummary1['rep_name'],
                            'total_estimates_1' => $dSummary1['total_estimates'],
                            'accepted_1' => $dSummary1['accepted'],
                            'declined_1' => $dSummary1['declined'],
                            'accepted_total_1' => $dSummary1['accepted_total'],
                            'declined_total_1' => $dSummary1['declined_total'],
                            'total_estimates_2' => $dSummary2['total_estimates'],
                            'accepted_2' => $dSummary2['accepted'],
                            'declined_2' => $dSummary2['declined'],
                            'accepted_total_2' => $dSummary2['accepted_total'],
                            'declined_total_2' => $dSummary2['declined_total'],


                        );
                        array_push($declined_results, $declined_result );
                    }
                }
            }
        } else {
            foreach($data['declined_summary_1'] as $dSummary1){
                $declined_result = array(
                    'rep_id' => $dSummary1['rep_id'],
                    'rep_name' => $dSummary1['rep_name'],
                    'total_estimates_1' => $dSummary1['total_estimates'],
                    'accepted_1' => $dSummary1['accepted'],
                    'declined_1' => $dSummary1['declined'],
                    'accepted_total_1' => $dSummary1['accepted_total'],
                    'declined_total_1' => $dSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,
                );
                array_push($declined_results, $declined_result );
            }
        }
        // die(print_r($declined_results));
        $data['declined_results'] = $declined_results;

        $body =  $this->load->view('admin/report/ajax_sales_summary_report_declined', $data, false);
        
        echo $body;

    }

    ## Download CSV for Sales Summary Report
    public function downloadSalesSummaryCsv(){
        $status = '';
        $conditions_1 = array();

         //set conditions for search
         $sales_rep_id = $this->input->post('sales_rep_id');
         $date_range_date_to = $this->input->post('date_range_date_to');
         $date_range_date_from = $this->input->post('date_range_date_from');
         $comparision_range_date_to = $this->input->post('comparision_range_date_to');
         $comparision_range_date_from = $this->input->post('comparision_range_date_from');
 
         if(!empty($sales_rep_id)){
             $conditions_1['search']['sales_rep_id'] = implode(",", $sales_rep_id);
         }
         if(!empty($date_range_date_to)){
             $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
         }
          if(!empty($date_range_date_from)){
             $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
         }

         $conditions_2 = array();

         if(!empty($sales_rep_id)){
            $conditions_2['search']['sales_rep_id'] = implode(",", $sales_rep_id);
        }
         if(!empty($comparision_range_date_to)){
             $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
         }
          if(!empty($comparision_range_date_from)){
             $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
         }
 
        //  die(print_r($conditions_1));
        //  die(print_r($conditions_2));
         
          //get posts data
         $data['sales_summary_1'] = $this->EstimateModal->getAllEstimateSearch($conditions_1);
         $data['sales_summary_2'] = $this->EstimateModal->getAllEstimateSearch($conditions_2);
        //  die(print_r($data['sales_summary_2']));
         ##### Condition #1 #####
         $report_summary_1 = [];
         if($data['sales_summary_1']){
             foreach($data['sales_summary_1'] as $report_1){
                 if(is_array($report_summary_1) && array_key_exists($report_1->sales_rep, $report_summary_1)){
                     $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                    
                     
                     $report_summary_1[$report_1->sales_rep]['total_estimates'] += 1;
                     if(isset($report_1->status) && $report_1->status == 2){
                         $report_summary_1[$report_1->sales_rep]['accepted'] += 1 ;
                         $report_summary_1[$report_1->sales_rep]['accepted_total'] += $estimate_cost;
                         
                     } elseif (isset($report_1->status) && $report_1->status == 5){
                         $report_summary_1[$report_1->sales_rep]['declined'] += 1 ;
                         $report_summary_1[$report_1->sales_rep]['declined_total'] += $estimate_cost;
                     } else {
                         $report_summary_1[$report_1->sales_rep]['accepted'] += 0 ;
                         $report_summary_1[$report_1->sales_rep]['accepted_total'] += 0;
                         $report_summary_1[$report_1->sales_rep]['declined'] += 0 ;
                         $report_summary_1[$report_1->sales_rep]['declined_total'] += 0;
     
                     }
                 } else {
                    $report_summary_1[$report_1->sales_rep]['rep_id'] = $report_1->sales_rep;
                     $report_summary_1[$report_1->sales_rep]['rep_name'] = $report_1->user_first_name.' '.$report_1->user_last_name;
                     $total_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                     $report_summary_1[$report_1->sales_rep]['total_estimates'] = 1;
                   
                     if(isset($report_1->status) && $report_1->status == 2){
                         $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                         $report_summary_1[$report_1->sales_rep]['accepted'] = 1 ;
                         $report_summary_1[$report_1->sales_rep]['declined'] = 0 ;
                         $report_summary_1[$report_1->sales_rep]['accepted_total'] = $estimate_cost ;
                         $report_summary_1[$report_1->sales_rep]['declined_total'] = 0 ;
                     } elseif (isset($report_1->status) && $report_1->status == 5){
                         $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                         $report_summary_1[$report_1->sales_rep]['accepted'] = 0 ;
                         $report_summary_1[$report_1->sales_rep]['declined'] = 1 ;
                         $report_summary_1[$report_1->sales_rep]['declined_total'] = $estimate_cost ;
                         $report_summary_1[$report_1->sales_rep]['accepted_total'] = 0 ;
                     } else {
                         $estimate_cost = $this->totalEstimateCost($report_1->estimate_id, $report_1->property_id, $report_1->program_id, $report_1->yard_square_feet);
                         $report_summary_1[$report_1->sales_rep]['accepted'] = 0 ;
                         $report_summary_1[$report_1->sales_rep]['declined'] = 0 ;
                         $report_summary_1[$report_1->sales_rep]['declined_total'] = 0 ;
                         $report_summary_1[$report_1->sales_rep]['accepted_total'] = 0 ;
                     }
                    
                 }
                
             }
         }
         ##### Condition #2 #####
         $report_summary_2 = [];
         if($data['sales_summary_2']){
             foreach($data['sales_summary_2'] as $report_2){
                 if(is_array($report_summary_2) && array_key_exists($report_2->sales_rep, $report_summary_2)){
                     $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                    
                     
                     $report_summary_2[$report_2->sales_rep]['total_estimates'] += 1;
                     if(isset($report_2->status) && $report_2->status == 2){
                         $report_summary_2[$report_2->sales_rep]['accepted'] += 1 ;
                         $report_summary_2[$report_2->sales_rep]['accepted_total'] += $estimate_cost;
                         
                     } elseif (isset($report_2->status) && $report_2->status == 5){
                         $report_summary_2[$report_2->sales_rep]['declined'] += 1 ;
                         $report_summary_2[$report_2->sales_rep]['declined_total'] += $estimate_cost;
                     } else {
                         $report_summary_2[$report_2->sales_rep]['accepted'] += 0 ;
                         $report_summary_2[$report_2->sales_rep]['accepted_total'] += 0;
                         $report_summary_2[$report_2->sales_rep]['declined'] += 0 ;
                         $report_summary_2[$report_2->sales_rep]['declined_total'] += 0;
     
                     }
                 } else {
                    $report_summary_2[$report_2->sales_rep]['rep_id'] = $report_2->sales_rep;
                     $report_summary_2[$report_2->sales_rep]['rep_name'] = $report_2->user_first_name.' '.$report_2->user_last_name;
                     $total_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                     $report_summary_2[$report_2->sales_rep]['total_estimates'] = 1;
                   
                     if(isset($report_2->status) && $report_2->status == 2){
                         $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                         $report_summary_2[$report_2->sales_rep]['accepted'] = 1 ;
                         $report_summary_2[$report_2->sales_rep]['declined'] = 0 ;
                         $report_summary_2[$report_2->sales_rep]['accepted_total'] = $estimate_cost ;
                         $report_summary_2[$report_2->sales_rep]['declined_total'] = 0 ;
                     } elseif (isset($report_2->status) && $report_2->status == 5){
                         $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                         $report_summary_2[$report_2->sales_rep]['accepted'] = 0 ;
                         $report_summary_2[$report_2->sales_rep]['declined'] = 1 ;
                         $report_summary_2[$report_2->sales_rep]['declined_total'] = $estimate_cost ;
                         $report_summary_2[$report_2->sales_rep]['accepted_total'] = 0 ;
                     } else {
                         $estimate_cost = $this->totalEstimateCost($report_2->estimate_id, $report_2->property_id, $report_2->program_id, $report_2->yard_square_feet);
                         $report_summary_2[$report_2->sales_rep]['accepted'] = 0 ;
                         $report_summary_2[$report_2->sales_rep]['declined'] = 0 ;
                         $report_summary_2[$report_2->sales_rep]['declined_total'] = 0 ;
                         $report_summary_2[$report_2->sales_rep]['accepted_total'] = 0 ;
                     }
                    
                 }
                
             }
         }
            $data['report_summary_1'] = $report_summary_1;
            $data['report_summary_2'] = $report_summary_2;
        //  die(print_r($data['report_summary_1']));
        //  die(print_r($data['report_summary_2']));
         $report_results = [];
        foreach($data['report_summary_1'] as $rSummary1){
            foreach($data['report_summary_2'] as $rSummary2){
                if(($rSummary1['rep_id'] == $rSummary2['rep_id'] )){
                    $report_result = array(
                        'rep_id' => $rSummary1['rep_id'],
                        'rep_name' => $rSummary1['rep_name'],
                        'total_estimates_1' => $rSummary1['total_estimates'],
                        'accepted_1' => $rSummary1['accepted'],
                        'declined_1' => $rSummary1['declined'],
                        'accepted_total_1' => $rSummary1['accepted_total'],
                        'declined_total_1' => $rSummary1['declined_total'],
                        'total_estimates_2' => $rSummary2['total_estimates'],
                        'accepted_2' => $rSummary2['accepted'],
                        'declined_2' => $rSummary2['declined'],
                        'accepted_total_2' => $rSummary2['accepted_total'],
                        'declined_total_2' => $rSummary2['declined_total'],

                    );
                    array_push($report_results, $report_result );
               
                }
            }
        }
        // die(print_r($report_results));
        $data['report_results'] = $report_results;
         

        // echo $this->db->last_query();
        // die();
        if($report_results){
            $delimiter = ",";
            $filename = "report_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Sales Rep/Source','Estimates Created','Estimate Close Rate','Revenue Close Rate','Compare Estimates', 'Comparison Range Close Rate', 'Comparison Range Revenue Close Rate','Change in Close Rate', 'Change in Revenue Close Rate',  'Estimate Accepted', 'Estimate Close Rate', 'Revenue Close Rate', 'Compare Estimates', 'Estimate Close Rate', 'Comparison Range Revenue Close Rate', 'Change in Close Rate', 'Change in Revenue Close Rate','Estimates Declined', 'Estimate Close Rate', 'Revenue Close Rate', 'Compare Estimates', 'Comparison Range Close Rate', 'Comparison Range Revenue Close Rate', 'Change in Close Rate', 'Change in Revenue Close Rate',);
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
            foreach ($report_results as $key => $value) {
                
                $status = 1;
                #####
                // $lineData = array($value['rep_name'],$value['total_estimates'], number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) , number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2));
                #####

                if(($value['accepted_1']+$value['declined_1']) == 0){
                    $close_rate_percent_1 = number_format($value['accepted_1'] ,2);
                }else{
                    $close_rate_percent_1 = number_format(($value['accepted_1']/($value['accepted_1']+$value['declined_1'])) ,2);
                }

                if(($value['accepted_total_1']+$value['declined_total_1']) == 0){
                    $close_rate_dollar_1 = number_format($value['accepted_total_1'] ,2);
                }else{
                    $close_rate_dollar_1 = number_format(($value['accepted_total_1']/($value['accepted_total_1']+$value['declined_total_1'])) ,2);
                }

                if(($value['accepted_1']+$value['declined_1']) == 0){
                    $compare_rate_percent_1 = number_format($value['accepted_1'] ,2);
                }else{
                    $compare_rate_percent_1 = number_format(($value['accepted_1']/($value['accepted_1']+$value['declined_1'])) ,2);
                }

                if(($value['accepted_total_1']+$value['declined_total_1']) == 0){
                    $compare_rate_dollar_1 = number_format($value['accepted_total_1'] ,2);
                }else{
                    $compare_rate_dollar_1 = number_format(($value['accepted_total_1']/($value['accepted_total_1']+$value['declined_total_1'])) ,2);
                }

                $diff_rate_percent_1 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);

                $diff_rate_dollar_1 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                $close_rate_percent_2 = (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
                $close_rate_dollar_2 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $compare_rate_percent_2 = (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
                $compare_rate_dollar_2 = number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2);
                $diff_rate_percent_2 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);
                $diff_rate_dollar_2 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                $close_rate_percent_3 = (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
                $close_rate_dollar_3 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $compare_rate_percent_3 = (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
                $compare_rate_dollar_3 = number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2);
                $diff_rate_percent_3 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);
                $diff_rate_dollar_3 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                // die(print_r($diff_rate_dollar_3));


                $lineData = array($value['rep_name'],$value['total_estimates_1'],$close_rate_percent_1 ,$close_rate_dollar_1, $value['total_estimates_2'], $compare_rate_percent_1 , $compare_rate_dollar_1, $diff_rate_percent_1,$diff_rate_dollar_1,$value['accepted_1'],$close_rate_percent_2 ,$close_rate_dollar_2 , $value['accepted_2'],$compare_rate_percent_2 ,$compare_rate_dollar_2 ,$diff_rate_percent_2 ,$diff_rate_dollar_2 , $value['declined_1'],$close_rate_percent_3 ,$close_rate_dollar_3 , $value['declined_2'],$compare_rate_percent_3 ,$compare_rate_dollar_3 ,$diff_rate_percent_3 ,$diff_rate_dollar_3  );

                fputcsv($f, $lineData, $delimiter);
                
            }

            if ($status==1) {
                //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/reports/salesSummary");
            }                    
        

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/reports/salesSummary");
        }    

    }

    ## Service  Summary Report
    public function serviceSummary($active = 0){   
        //get the posts data
        
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['jobs'] = $this->JobModel->getAllJob(array('jobs.company_id' =>$this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
        $data['SavedFilter'] = $this->ServiceSummarySaveModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));
        // die(print_r($data['jobs']));

        $data['program_details'] = $this->ProgramModel->get_all_program($where_arr);
        $data['service_details'] = $this->JobModel->getJobList($where_arr);

        $where = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['estimates'] = $this->EstimateModal->getAllEstimateDetails(array('t_estimate.company_id' =>$this->session->userdata['company_id']));
        $data['total_estimates'] = $this->EstimateModal->getAllEstimateGroupByID(array('t_estimate.company_id' =>$this->session->userdata['company_id']));
        $data['accepted_estimates'] = $this->EstimateModal->getAllEstimateGroupByID(array('t_estimate.company_id' =>$this->session->userdata['company_id'], 'status' => 2));
        $data['declined_estimates'] = $this->EstimateModal->getAllEstimateGroupByID(array('t_estimate.company_id' =>$this->session->userdata['company_id'], 'status' => 5));
        
        $service_summary = [];
        foreach($data['estimates'] as $service){
            if(is_array($service_summary) && array_key_exists($service->job_id, $service_summary)){
                
                $estimate_cost = $service->job_price;
               
                $service_summary[$service->job_id]['total_estimates'] += 1;
                if(isset($service->status) && $service->status == 2){
                    $service_summary[$service->job_id]['accepted'] += 1 ;
                    $service_summary[$service->job_id]['accepted_total'] += $estimate_cost;
                    
                } elseif (isset($service->status) && $service->status == 5){
                    $service_summary[$service->job_id]['declined'] += 1 ;
                    $service_summary[$service->job_id]['declined_total'] += $estimate_cost;
                } else {
                    $service_summary[$service->job_id]['accepted'] += 0 ;
                    $service_summary[$service->job_id]['declined'] += 0 ;
                    $service_summary[$service->job_id]['accepted_total'] += 0;
                    $service_summary[$service->job_id]['declined_total'] += 0;

                }
            } else {
                
                $service_summary[$service->job_id]['job_name'] = $service->job_name;
                
                $service_summary[$service->job_id]['total_estimates'] = 1;
                $total_cost = $service->job_price;
              
                if(isset($service->status) && $service->status == 2){
                    $estimate_cost = $service->job_price;
                   
                    $service_summary[$service->job_id]['accepted'] = 1 ;
                    $service_summary[$service->job_id]['declined'] = 0 ;
                    $service_summary[$service->job_id]['accepted_total'] = $estimate_cost ;
                    $service_summary[$service->job_id]['declined_total'] = 0 ;
                } elseif (isset($service->status) && $service->status == 5){
                    $estimate_cost = $service->job_price;
                    
                    $service_summary[$service->job_id]['accepted'] = 0 ;
                    $service_summary[$service->job_id]['declined'] = 1 ;
                    $service_summary[$service->job_id]['declined_total'] = $estimate_cost ;
                    $service_summary[$service->job_id]['accepted_total'] = 0 ;
                } else {
                    $estimate_cost = $service->job_price;
                    
                    $service_summary[$service->job_id]['accepted'] = 0 ;
                    $service_summary[$service->job_id]['declined'] = 0 ;
                    $service_summary[$service->job_id]['declined_total'] = 0 ;
                    $service_summary[$service->job_id]['accepted_total'] = 0 ;
                }
            }
        }
        
        $data['total_open_estimate'] = count($data['total_estimates']);
        $data['total_accepeted_estimate'] = count($data['accepted_estimates']);
        $data['total_declined_estimate'] = count($data['declined_estimates']);
		
        $data['service_summary'] = $service_summary;
        $data['active_nav_link'] = $active;
	    $page["active_sidebar"] = "serviceSummary";
        $page["page_name"] = 'Service Summary Report';
        $page["page_content"] = $this->load->view("admin/report/view_service_summary_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
   
    ## ajax data for Service Summary Report
    #### total estimates
     function ajaxServiceSummaryDataNew(){
        $conditions_1 = array();
        
        //set conditions for search
        $job_name = $this->input->post('job_name');
        $ProgramName = $this->input->post("program_ids");
        $sales_rep_id = $this->input->post('sales_rep_id');

        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');
       

        if(!empty($job_name) && $job_name != "null"){
            $conditions_1['search']['job_name'] = $job_name;
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_1['search']['program_name'] = $ProgramName;
        }

        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();
        
        //set conditions for search
        $job_name = $this->input->post('job_name');
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($job_name)){
            $conditions_2['search']['job_name'] = $job_name;
        }
        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_2['search']['program_name'] = $ProgramName;
        }

        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }
        
         //get posts data
       
        $data['estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_1);
        $data['estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_2);
        #### Adding status to conditions1
        $data['total_estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_1);
        $data['total_estimates_1'] = is_array($data['total_estimates_1']) ? count($data['total_estimates_1']) : 0;
        
        
        ####  Adding status to conditions1
        $data['total_estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_2);
        $data['total_estimates_2'] = is_array($data['total_estimates_2']) ? count($data['total_estimates_2']) : 0;
        // die(print_r($data['total_estimates_2']));
       
        $service_summary_1 = [];
        // if($data['sales_summary_1']){
        if($data['estimates_1']){

            foreach($data['estimates_1'] as $service_1){
                if(is_array($service_summary_1) && array_key_exists($service_1->job_id, $service_summary_1)){
                    
                    $estimate_cost = $service_1->job_price;
                   
                    
                    $service_summary_1[$service_1->job_id]['total_estimates'] += 1;
                    if(isset($service_1->status) && $service_1->status == 2){
                        $service_summary_1[$service_1->job_id]['accepted'] += 1 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($service_1->status) && $service_1->status == 5){
                        $service_summary_1[$service_1->job_id]['declined'] += 1 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $service_summary_1[$service_1->job_id]['accepted'] += 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] += 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] += 0;
                        $service_summary_1[$service_1->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $service_summary_1[$service_1->job_id]['job_id'] = $service_1->job_id;
                    $service_summary_1[$service_1->job_id]['job_name'] = $service_1->job_name;
                    
                    $service_summary_1[$service_1->job_id]['total_estimates'] = 1;
                    $total_cost = $service_1->job_price;
                  
                    if(isset($service_1->status) && $service_1->status == 2){
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 1 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = $estimate_cost ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = 0 ;
                    } elseif (isset($service_1->status) && $service_1->status == 5){
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 1 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = $estimate_cost ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = 0 ;
                    }
                   
                }         
            }
        }

        $service_summary_2 = [];
        // if($data['sales_summary_2']){
        if($data['estimates_2']){

            foreach($data['estimates_2'] as $service){
                if(is_array($service_summary_2) && array_key_exists($service->job_id, $service_summary_2)){
                    
                    $estimate_cost = $service->job_price;
                   
                    $service_summary_2[$service->job_id]['total_estimates'] += 1;
                    if(isset($service->status) && $service->status == 2){
                        $service_summary_2[$service->job_id]['accepted'] += 1 ;
                        $service_summary_2[$service->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($service->status) && $service->status == 5){
                        $service_summary_2[$service->job_id]['declined'] += 1 ;
                        $service_summary_2[$service->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $service_summary_2[$service->job_id]['accepted'] += 0 ;
                        $service_summary_2[$service->job_id]['declined'] += 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] += 0;
                        $service_summary_2[$service->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $service_summary_2[$service->job_id]['job_id'] = $service->job_id;
                    $service_summary_2[$service->job_id]['job_name'] = $service->job_name;
                    
                    $service_summary_2[$service->job_id]['total_estimates'] = 1;
                    $total_cost = $service->job_price;
                  
                    if(isset($service->status) && $service->status == 2){
                        $estimate_cost = $service->job_price;
                        
                        $service_summary_2[$service->job_id]['accepted'] = 1 ;
                        $service_summary_2[$service->job_id]['declined'] = 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] = $estimate_cost ;
                        $service_summary_2[$service->job_id]['declined_total'] = 0 ;
                    } elseif (isset($service->status) && $service->status == 5){
                        $estimate_cost = $service->job_price;
                        
                        $service_summary_2[$service->job_id]['accepted'] = 0 ;
                        $service_summary_2[$service->job_id]['declined'] = 1 ;
                        $service_summary_2[$service->job_id]['declined_total'] = $estimate_cost ;
                        $service_summary_2[$service->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $service->job_price;
                       
                        $service_summary_2[$service->job_id]['accepted'] = 0 ;
                        $service_summary_2[$service->job_id]['declined'] = 0 ;
                        $service_summary_2[$service->job_id]['declined_total'] = 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] = 0 ;
                    }                 
                }             
            }
        }

           $data['service_summary_1'] = $service_summary_1;
           $data['service_summary_2'] = $service_summary_2;
        ##### Results for both Conditions
        $report_results = [];
        if(isset($data['service_summary_2']) && !empty($data['service_summary_2'])){
            foreach($data['service_summary_1'] as $rSummary1){
                foreach($data['service_summary_2'] as $rSummary2){
                    if(($rSummary1['job_id'] == $rSummary2['job_id'] )){
                        $report_result = array(
                            'job_id' => $rSummary1['job_id'],
                            'job_name' => $rSummary1['job_name'],
                            'total_estimates_1' => $rSummary1['total_estimates'],
                            'accepted_1' => $rSummary1['accepted'],
                            'declined_1' => $rSummary1['declined'],
                            'accepted_total_1' => $rSummary1['accepted_total'],
                            'declined_total_1' => $rSummary1['declined_total'],
                            'total_estimates_2' => $rSummary2['total_estimates'],
                            'accepted_2' => $rSummary2['accepted'],
                            'declined_2' => $rSummary2['declined'],
                            'accepted_total_2' => $rSummary2['accepted_total'],
                            'declined_total_2' => $rSummary2['declined_total'],

                        );
                        array_push($report_results, $report_result );
                
                    }
                }
            }
        } else {
            foreach($data['service_summary_1'] as $rSummary1){
                $report_result = array(
                    'job_id' => $rSummary1['job_id'],
                    'job_name' => $rSummary1['job_name'],
                    'total_estimates_1' => $rSummary1['total_estimates'],
                    'accepted_1' => $rSummary1['accepted'],
                    'declined_1' => $rSummary1['declined'],
                    'accepted_total_1' => $rSummary1['accepted_total'],
                    'declined_total_1' => $rSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,
                );
                array_push($report_results, $report_result );
            }
        }
        // die(print_r($report_results));
        $data['report_results'] = $report_results;
        $body =  $this->load->view('admin/report/ajax_service_summary_report', $data, false);

        echo $body;

    }
   
    #### accepted estimates
     function ajaxServiceSummaryDataAccepted(){
        $conditions_1 = array();
        
        //set conditions for search
        $job_name = $this->input->post('job_name');
        $sales_rep_id = $this->input->post('sales_rep_id');
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');
        $ProgramName = $this->input->post("program_ids");

        if(!empty($job_name) && $job_name != "null"){
            $conditions_1['search']['job_name'] = $job_name;
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_1['search']['program_name'] = $ProgramName;
        }

        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();
        
        //set conditions for search
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($job_name)){
            $conditions_2['search']['job_name'] = $job_name;
        }
        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }
        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }
        
         //get posts data
        
        $data['estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_1);
        $data['estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_2);
        $conditions_1['search']['status'] = 2;
        
        $data['accepted_estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_1);
        if(is_array($data['accepted_estimates_1'])){
            $data['accepted_estimates_1'] = count($data['accepted_estimates_1']);
        }else{
            $data['accepted_estimates_1'] = 0;
        }
       
        $conditions_2['search']['status'] = 2;
        
        $data['accepted_estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_2);
        if(is_array($data['accepted_estimates_2'])){
            $data['accepted_estimates_2'] = count($data['accepted_estimates_2']);
        }else{
            $data['accepted_estimates_2'] = 0;
        }
        
           #### ACCEPTED SUMMARY CONDITION #1 ####
        $accepted_summary_1 = [];
        if($data['estimates_1']){

            foreach($data['estimates_1'] as $accepted_1){
                if(is_array($accepted_summary_1) && array_key_exists($accepted_1->job_id, $accepted_summary_1)){
                    
                    $estimate_cost = $accepted_1->job_price;
                    
                    $accepted_summary_1[$accepted_1->job_id]['total_estimates'] += 1;
                    if(isset($accepted_1->status) && $accepted_1->status == 2){
                        $accepted_summary_1[$accepted_1->job_id]['accepted'] += 1 ;
                        $accepted_summary_1[$accepted_1->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($accepted_1->status) && $accepted_1->status == 5){
                        $accepted_summary_1[$accepted_1->job_id]['declined'] += 1 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $accepted_summary_1[$accepted_1->job_id]['accepted'] += 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined'] += 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['accepted_total'] += 0;
                        $accepted_summary_1[$accepted_1->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $accepted_summary_1[$accepted_1->job_id]['job_id'] = $accepted_1->job_id;
                    $accepted_summary_1[$accepted_1->job_id]['job_name'] = $accepted_1->job_name;
                    
                    $accepted_summary_1[$accepted_1->job_id]['total_estimates'] = 1;
                    $total_cost = $accepted_1->job_price;
                    
                    if(isset($accepted_1->status) && $accepted_1->status == 2){
                        $estimate_cost = $accepted_1->job_price;
                        
                        $accepted_summary_1[$accepted_1->job_id]['accepted'] = 1 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined'] = 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['accepted_total'] = $estimate_cost ;
                        $accepted_summary_1[$accepted_1->job_id]['declined_total'] = 0 ;
                    } elseif (isset($accepted_1->status) && $accepted_1->status == 5){
                        $estimate_cost = $accepted_1->job_price;
                        
                        $accepted_summary_1[$accepted_1->job_id]['accepted'] = 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined'] = 1 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined_total'] = $estimate_cost ;
                        $accepted_summary_1[$accepted_1->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $accepted_1->job_price;
                        
                        $accepted_summary_1[$accepted_1->job_id]['accepted'] = 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined'] = 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['declined_total'] = 0 ;
                        $accepted_summary_1[$accepted_1->job_id]['accepted_total'] = 0 ;
                    }
                    
                }
                
            }

            
        }
        ### ACCEPTED SUMMARY CONDITION #1 ###
        #### ACCEPTED SUMMARY CONDITION #2 ####
        $accepted_summary_2 = [];
        if($data['estimates_2']){

            foreach($data['estimates_2'] as $accepted_2){
                if(is_array($accepted_summary_2) && array_key_exists($accepted_2->job_id, $accepted_summary_2)){
                   
                    $estimate_cost = $accepted_2->job_price;
                    
                    $accepted_summary_2[$accepted_2->job_id]['total_estimates'] += 1;
                    if(isset($accepted_2->status) && $accepted_2->status == 2){
                        $accepted_summary_2[$accepted_2->job_id]['accepted'] += 1 ;
                        $accepted_summary_2[$accepted_2->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($accepted_2->status) && $accepted_2->status == 5){
                        $accepted_summary_2[$accepted_2->job_id]['declined'] += 1 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $accepted_summary_2[$accepted_2->job_id]['accepted'] += 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined'] += 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['accepted_total'] += 0;
                        $accepted_summary_2[$accepted_2->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $accepted_summary_2[$accepted_2->job_id]['job_id'] = $accepted_2->job_id;
                    $accepted_summary_2[$accepted_2->job_id]['job_name'] = $accepted_2->job_name;
                    
                    $accepted_summary_2[$accepted_2->job_id]['total_estimates'] = 1;
                    $total_cost = $accepted_2->job_price;
                    
                    if(isset($accepted_2->status) && $accepted_2->status == 2){
                        $estimate_cost = $accepted_2->job_price;
                        
                        $accepted_summary_2[$accepted_2->job_id]['accepted'] = 1 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined'] = 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['accepted_total'] = $estimate_cost ;
                        $accepted_summary_2[$accepted_2->job_id]['declined_total'] = 0 ;
                    } elseif (isset($accepted_2->status) && $accepted_2->status == 5){
                        $estimate_cost = $accepted_2->job_price;
                        
                        $accepted_summary_2[$accepted_2->job_id]['accepted'] = 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined'] = 1 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined_total'] = $estimate_cost ;
                        $accepted_summary_2[$accepted_2->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $accepted_2->job_price;
                        
                        $accepted_summary_2[$accepted_2->job_id]['accepted'] = 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined'] = 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['declined_total'] = 0 ;
                        $accepted_summary_2[$accepted_2->job_id]['accepted_total'] = 0 ;
                    }
                    
                }
                
            }

           
        }
        ### ACCEPTED SUMMARY CONDITION #2 ###
           $data['accepted_summary_1'] = $accepted_summary_1;
           $data['accepted_summary_2'] = $accepted_summary_2;

        $accepted_results = [];
        if(isset($data['accepted_summary_2']) && !empty($data['accepted_summary_2'])){
            foreach($data['accepted_summary_1'] as $aSummary1){
                foreach($data['accepted_summary_2'] as $aSummary2){
                    if($aSummary1['job_id'] == $aSummary2['job_id']){
                        $accepted_result = array(
                            'job_id' => $aSummary1['job_id'],
                            'job_name' => $aSummary1['job_name'],
                            'total_estimates_1' => $aSummary1['total_estimates'],
                            'accepted_1' => $aSummary1['accepted'],
                            'declined_1' => $aSummary1['declined'],
                            'accepted_total_1' => $aSummary1['accepted_total'],
                            'declined_total_1' => $aSummary1['declined_total'],
                            'total_estimates_2' => $aSummary2['total_estimates'],
                            'accepted_2' => $aSummary2['accepted'],
                            'declined_2' => $aSummary2['declined'],
                            'accepted_total_2' => $aSummary2['accepted_total'],
                            'declined_total_2' => $aSummary2['declined_total'],
    
    
                        );
                        array_push($accepted_results, $accepted_result );
                    }
                }
            }
        } else {
            foreach($data['accepted_summary_1'] as $aSummary1){
                $accepted_result = array(
                    'job_id' => $aSummary1['job_id'],
                    'job_name' => $aSummary1['job_name'],
                    'total_estimates_1' => $aSummary1['total_estimates'],
                    'accepted_1' => $aSummary1['accepted'],
                    'declined_1' => $aSummary1['declined'],
                    'accepted_total_1' => $aSummary1['accepted_total'],
                    'declined_total_1' => $aSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,
                );
                array_push($accepted_results, $accepted_result );
            }
        }
        // die(print_r($accepted_results));
        $data['accepted_results'] = $accepted_results;
        $body =  $this->load->view('admin/report/ajax_service_summary_report_accepted', $data, false);

        echo $body;

    }
     
    #### declined estimates
     function ajaxServiceSummaryDataDeclined(){
        $conditions_1 = array();
        
        //set conditions for search
        $job_name = $this->input->post('job_name');
        $sales_rep_id = $this->input->post('sales_rep_id');
        $date_range_date_to = $this->input->post('date_range_date_to');
        $date_range_date_from = $this->input->post('date_range_date_from');
        $ProgramName = $this->input->post("program_ids");

        if(!empty($job_name) && $job_name != "null"){
            $conditions_1['search']['job_name'] = $job_name;
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_1['search']['program_name'] = $ProgramName;
        }

        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions_1['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
         if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();
        
        //set conditions for search
        $job_name = $this->input->post('job_name');
        $comparision_range_date_to = $this->input->post('comparision_range_date_to');
        $comparision_range_date_from = $this->input->post('comparision_range_date_from');

        if(!empty($job_name)){
            $conditions_2['search']['job_name'] = $job_name;
        }
        if(!empty($sales_rep_id)){
            $conditions_2['search']['sales_rep_id'] = $sales_rep_id;
        }
        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }
        
         //get posts data
        $data['estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_1);
        $data['estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_2);
        $conditions_1['search']['status'] = 5;
        
        $data['declined_estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_1);
        $data['declined_estimates_1'] = (is_array($data['declined_estimates_1'])) ? count($data['declined_estimates_1']) : 0;
        
        $conditions_2['search']['status'] = 5;
        
        $data['declined_estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearchGroupByID($conditions_2);
        $data['declined_estimates_2'] = (is_array($data['declined_estimates_2'])) ? count($data['declined_estimates_2']) : 0;
        
        ##### DECLINED ESTIMATES CONDITION #1
           $declined_summary_1 = [];
           if($data['estimates_1']){

            foreach($data['estimates_1'] as $declined_1){
                if(is_array($declined_summary_1) && array_key_exists($declined_1->job_id, $declined_summary_1)){
                    
                    $estimate_cost = $declined_1->job_price;
                    
                    $declined_summary_1[$declined_1->job_id]['total_estimates'] += 1;
                    if(isset($declined_1->status) && $declined_1->status == 2){
                        $declined_summary_1[$declined_1->job_id]['accepted'] += 1 ;
                        $declined_summary_1[$declined_1->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($declined_1->status) && $declined_1->status == 5){
                        $declined_summary_1[$declined_1->job_id]['declined'] += 1 ;
                        $declined_summary_1[$declined_1->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $declined_summary_1[$declined_1->job_id]['accepted'] += 0 ;
                        $declined_summary_1[$declined_1->job_id]['declined'] += 0 ;
                        $declined_summary_1[$declined_1->job_id]['accepted_total'] += 0;
                        $declined_summary_1[$declined_1->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $declined_summary_1[$declined_1->job_id]['job_id'] = $declined_1->job_id;
                    $declined_summary_1[$declined_1->job_id]['job_name'] = $declined_1->job_name;
                    
                    $declined_summary_1[$declined_1->job_id]['total_estimates'] = 1;
                    $total_cost = $declined_1->job_price;
                    
                    if(isset($declined_1->status) && $declined_1->status == 2){
                        $estimate_cost = $declined_1->job_price;
                        
                        $declined_summary_1[$declined_1->job_id]['accepted'] = 1 ;
                        $declined_summary_1[$declined_1->job_id]['declined'] = 0 ;
                        $declined_summary_1[$declined_1->job_id]['accepted_total'] = $estimate_cost ;
                        $declined_summary_1[$declined_1->job_id]['declined_total'] = 0 ;
                    } elseif (isset($declined_1->status) && $declined_1->status == 5){
                        $estimate_cost = $declined_1->job_price;
                        
                        $declined_summary_1[$declined_1->job_id]['accepted'] = 0 ;
                        $declined_summary_1[$declined_1->job_id]['declined'] = 1 ;
                        $declined_summary_1[$declined_1->job_id]['declined_total'] = $estimate_cost ;
                        $declined_summary_1[$declined_1->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $declined_1->job_price;
                        
                        $declined_summary_1[$declined_1->job_id]['accepted'] = 0 ;
                        $declined_summary_1[$declined_1->job_id]['declined'] = 0 ;
                        $declined_summary_1[$declined_1->job_id]['declined_total'] = 0 ;
                        $declined_summary_1[$declined_1->job_id]['accepted_total'] = 0 ;
                    }
                    
                }
                
            }
        }
        
        ##### DECLINED ESTIMATES CONDITION #2
        $declined_summary_2 = [];
        if($data['estimates_2']){

            foreach($data['estimates_2'] as $declined_2){
                if(is_array($declined_summary_2) && array_key_exists($declined_2->job_id, $declined_summary_2)){
                    
                    $estimate_cost = $declined_2->job_price;
                    
                    $declined_summary_2[$declined_2->job_id]['total_estimates'] += 1;
                    if(isset($declined_2->status) && $declined_2->status == 2){
                        $declined_summary_2[$declined_2->job_id]['accepted'] += 1 ;
                        $declined_summary_2[$declined_2->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($declined_2->status) && $declined_2->status == 5){
                        $declined_summary_2[$declined_2->job_id]['declined'] += 1 ;
                        $declined_summary_2[$declined_2->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $declined_summary_2[$declined_2->job_id]['accepted'] += 0 ;
                        $declined_summary_2[$declined_2->job_id]['declined'] += 0 ;
                        $declined_summary_2[$declined_2->job_id]['accepted_total'] += 0;
                        $declined_summary_2[$declined_2->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $declined_summary_2[$declined_2->job_id]['job_id'] = $declined_2->job_id;
                    $declined_summary_2[$declined_2->job_id]['job_name'] = $declined_2->job_name;
                    
                    $declined_summary_2[$declined_2->job_id]['total_estimates'] = 1;
                    $total_cost = $declined_2->job_price;
                    
                    if(isset($declined_2->status) && $declined_2->status == 2){
                        $estimate_cost = $declined_2->job_price;
                        
                        $declined_summary_2[$declined_2->job_id]['accepted'] = 1 ;
                        $declined_summary_2[$declined_2->job_id]['declined'] = 0 ;
                        $declined_summary_2[$declined_2->job_id]['accepted_total'] = $estimate_cost ;
                        $declined_summary_2[$declined_2->job_id]['declined_total'] = 0 ;
                    } elseif (isset($declined_2->status) && $declined_2->status == 5){
                        $estimate_cost = $declined_2->job_price;
                        
                        $declined_summary_2[$declined_2->job_id]['accepted'] = 0 ;
                        $declined_summary_2[$declined_2->job_id]['declined'] = 1 ;
                        $declined_summary_2[$declined_2->job_id]['declined_total'] = $estimate_cost ;
                        $declined_summary_2[$declined_2->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $declined_2->job_price;
                        
                        $declined_summary_2[$declined_2->job_id]['accepted'] = 0 ;
                        $declined_summary_2[$declined_2->job_id]['declined'] = 0 ;
                        $declined_summary_2[$declined_2->job_id]['declined_total'] = 0 ;
                        $declined_summary_2[$declined_2->job_id]['accepted_total'] = 0 ;
                    }
                    
                }
                
            }

           
        }
       
           $data['declined_summary_1'] = $declined_summary_1;
           $data['declined_summary_2'] = $declined_summary_2;

        $declined_results = [];
        if(isset($data['declined_summary_2']) && !empty($data['declined_summary_2'])){
            foreach($data['declined_summary_1'] as $dSummary1){
                foreach($data['declined_summary_2'] as $dSummary2){
                    if($dSummary1['job_id'] == $dSummary2['job_id']){
                        $declined_result = array(
                            'job_id' => $dSummary1['job_id'],
                            'job_name' => $dSummary1['job_name'],
                            'total_estimates_1' => $dSummary1['total_estimates'],
                            'accepted_1' => $dSummary1['accepted'],
                            'declined_1' => $dSummary1['declined'],
                            'accepted_total_1' => $dSummary1['accepted_total'],
                            'declined_total_1' => $dSummary1['declined_total'],
                            'total_estimates_2' => $dSummary2['total_estimates'],
                            'accepted_2' => $dSummary2['accepted'],
                            'declined_2' => $dSummary2['declined'],
                            'accepted_total_2' => $dSummary2['accepted_total'],
                            'declined_total_2' => $dSummary2['declined_total'],
                        );
                        array_push($declined_results, $declined_result );
                    }
                }
            }
        } else {
            foreach($data['declined_summary_1'] as $dSummary1){
                $declined_result = array(
                    'job_id' => $dSummary1['job_id'],
                    'job_name' => $dSummary1['job_name'],
                    'total_estimates_1' => $dSummary1['total_estimates'],
                    'accepted_1' => $dSummary1['accepted'],
                    'declined_1' => $dSummary1['declined'],
                    'accepted_total_1' => $dSummary1['accepted_total'],
                    'declined_total_1' => $dSummary1['declined_total'],
                    'total_estimates_2' => 0,
                    'accepted_2' => 0,
                    'declined_2' => 0,
                    'accepted_total_2' => 0,
                    'declined_total_2' => 0,
                );
                array_push($declined_results, $declined_result );
            }
        }
        // die(print_r($declined_results));
        $data['declined_results'] = $declined_results;

        $body =  $this->load->view('admin/report/ajax_service_summary_report_declined', $data, false);

        echo $body;

    }

    ## Download CSV for Service Summary Report
    public function downloadServiceSummaryCsv(){

        $status = '';
        $conditions_1 = array();
       //set conditions for search
       $job_id = $this->input->post('job_id');
       $job_name = $this->input->post('job_name');
       $sales_rep_id = $this->input->post('sales_rep_id');
       $date_range_date_to = $this->input->post('date_range_date_to');
       $date_range_date_from = $this->input->post('date_range_date_from');
       $comparision_range_date_to = $this->input->post('comparision_range_date_to');
       $comparision_range_date_from = $this->input->post('comparision_range_date_from');
       $ProgramName = $this->input->post("program_ids");

        if(!empty($job_id)){
            $conditions_1['search']['job_id'] = $job_id;
        }
        if(!empty($job_name)){
            $conditions_1['search']['job_name'] = implode(",", $job_name);
        }
        if(!empty($sales_rep_id)){
            $conditions_1['search']['sales_rep_id'] = implode(",", $sales_rep_id);
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_1['search']['program_name'] = implode(",", $ProgramName);
        }

        if(!empty($date_range_date_to)){
            $conditions_1['search']['date_range_date_to'] = $date_range_date_to;
        }
        if(!empty($date_range_date_from)){
            $conditions_1['search']['date_range_date_from'] = $date_range_date_from;
        }
        
        $conditions_2 = array();

        if(!empty($job_id)){
            $conditions_2['search']['job_id'] = $job_id;
        }
        
        if(!empty($job_name)){
            $conditions_1['search']['job_name'] = implode(",", $job_name);
        }
        if(!empty($sales_rep_id)){
            $conditions_1['search']['sales_rep_id'] = implode(",", $sales_rep_id);
        }

        if(!empty($ProgramName) && $ProgramName != "null"){
            $conditions_1['search']['program_name'] = implode(",", $ProgramName);
        }

        if(!empty($comparision_range_date_to)){
            $conditions_2['search']['comparision_range_date_to'] = $comparision_range_date_to;
        }
         if(!empty($comparision_range_date_from)){
            $conditions_2['search']['comparision_range_date_from'] = $comparision_range_date_from;
        }
       
         //get posts data
        $data['estimates_1'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_1);
        $data['estimates_2'] = $this->EstimateModal->getAllEstimateDetailsSearch($conditions_2);
        
        $service_summary_1 = [];
        if($data['estimates_1']){

            foreach($data['estimates_1'] as $service_1){
                if(is_array($service_summary_1) && array_key_exists($service_1->job_id, $service_summary_1)){
                    
                    $estimate_cost = $service_1->job_price;
                   
                    $service_summary_1[$service_1->job_id]['total_estimates'] += 1;
                    if(isset($service_1->status) && $service_1->status == 2){
                        $service_summary_1[$service_1->job_id]['accepted'] += 1 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($service_1->status) && $service_1->status == 5){
                        $service_summary_1[$service_1->job_id]['declined'] += 1 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $service_summary_1[$service_1->job_id]['accepted'] += 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] += 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] += 0;
                        $service_summary_1[$service_1->job_id]['declined_total'] += 0;
                    }
                } else {
                    $service_summary_1[$service_1->job_id]['job_id'] = $service_1->job_id;
                    $service_summary_1[$service_1->job_id]['job_name'] = $service_1->job_name;
                   
                    $service_summary_1[$service_1->job_id]['total_estimates'] = 1;
                    $total_cost = $service_1->job_price;
                  
                    if(isset($service_1->status) && $service_1->status == 2){
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 1 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = $estimate_cost ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = 0 ;
                    } elseif (isset($service_1->status) && $service_1->status == 5){
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 1 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = $estimate_cost ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $service_1->job_price;
                        
                        $service_summary_1[$service_1->job_id]['accepted'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined'] = 0 ;
                        $service_summary_1[$service_1->job_id]['declined_total'] = 0 ;
                        $service_summary_1[$service_1->job_id]['accepted_total'] = 0 ;
                    }
                   
                }
               
            }
        }

        $service_summary_2 = [];
        if($data['estimates_2']){

            foreach($data['estimates_2'] as $service){
                if(is_array($service_summary_2) && array_key_exists($service->job_id, $service_summary_2)){
                    
                    $estimate_cost = $service->job_price;
                   
                    $service_summary_2[$service->job_id]['total_estimates'] += 1;
                    if(isset($service->status) && $service->status == 2){
                        $service_summary_2[$service->job_id]['accepted'] += 1 ;
                        $service_summary_2[$service->job_id]['accepted_total'] += $estimate_cost;
                        
                    } elseif (isset($service->status) && $service->status == 5){
                        $service_summary_2[$service->job_id]['declined'] += 1 ;
                        $service_summary_2[$service->job_id]['declined_total'] += $estimate_cost;
                    } else {
                        $service_summary_2[$service->job_id]['accepted'] += 0 ;
                        $service_summary_2[$service->job_id]['declined'] += 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] += 0;
                        $service_summary_2[$service->job_id]['declined_total'] += 0;
    
                    }
                } else {
                    $service_summary_2[$service->job_id]['job_id'] = $service->job_id;
                    $service_summary_2[$service->job_id]['job_name'] = $service->job_name;
                    
                    $service_summary_2[$service->job_id]['total_estimates'] = 1;
                    $total_cost = $service->job_price;
                  
                    if(isset($service->status) && $service->status == 2){
                        $estimate_cost = $service->job_price;
                        
                        $service_summary_2[$service->job_id]['accepted'] = 1 ;
                        $service_summary_2[$service->job_id]['declined'] = 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] = $estimate_cost ;
                        $service_summary_2[$service->job_id]['declined_total'] = 0 ;
                    } elseif (isset($service->status) && $service->status == 5){
                        $estimate_cost = $service->job_price;
                        
                        $service_summary_2[$service->job_id]['accepted'] = 0 ;
                        $service_summary_2[$service->job_id]['declined'] = 1 ;
                        $service_summary_2[$service->job_id]['declined_total'] = $estimate_cost ;
                        $service_summary_2[$service->job_id]['accepted_total'] = 0 ;
                    } else {
                        $estimate_cost = $service->job_price;
                        
                        $service_summary_2[$service->job_id]['accepted'] = 0 ;
                        $service_summary_2[$service->job_id]['declined'] = 0 ;
                        $service_summary_2[$service->job_id]['declined_total'] = 0 ;
                        $service_summary_2[$service->job_id]['accepted_total'] = 0 ;
                    }
                   
                }           
            }
        }

           $data['service_summary_1'] = $service_summary_1;
           $data['service_summary_2'] = $service_summary_2;
        
        ##### Results for both Conditions
        $report_results = [];
        foreach($data['service_summary_1'] as $rSummary1){
            foreach($data['service_summary_2'] as $rSummary2){
                if(($rSummary1['job_id'] == $rSummary2['job_id'] )){
                    $report_result = array(
                        'job_id' => $rSummary1['job_id'],
                        'job_name' => $rSummary1['job_name'],
                        'total_estimates_1' => $rSummary1['total_estimates'],
                        'accepted_1' => $rSummary1['accepted'],
                        'declined_1' => $rSummary1['declined'],
                        'accepted_total_1' => $rSummary1['accepted_total'],
                        'declined_total_1' => $rSummary1['declined_total'],
                        'total_estimates_2' => $rSummary2['total_estimates'],
                        'accepted_2' => $rSummary2['accepted'],
                        'declined_2' => $rSummary2['declined'],
                        'accepted_total_2' => $rSummary2['accepted_total'],
                        'declined_total_2' => $rSummary2['declined_total'],


                    );
                    array_push($report_results, $report_result );
               
                }
            }
        }
       
        $data['report_results'] = $report_results;
       
        if($report_results){
            $delimiter = ",";
            $filename = "report_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            // $fields = array('Service','Total New Estimates','Difference Close Rate %','Difference Close Rate $.',);
            $fields = array('Service','Estimates Created','Estimate Close Rate','Revenue Close Rate','Compare Estimates', 'Comparison Range Close Rate', 'Comparison Range Revenue Close Rate','Change in Close Rate', 'Change in Revenue Close Rate',  'Estimate Accepted', 'Estimate Close Rate', 'Revenue Close Rate', 'Compare Estimates', 'Comparison Range Close Rate', 'Comparison Range Revenue Close Rate', 'Change in Close Rate', 'Change in Revenue Close Rate','Estimates Declined', 'Estimate Close Rate', 'Revenue Close Rate', 'Compare Estimates', 'Comparison Range Close Rate', 'Comparison Range Revenue Close Rate', 'Change in Close Rate', 'Change in Revenue Close Rate',);
            fputcsv($f, $fields, $delimiter);

            
            //output each row of the data, format line as csv and write to file pointer
            
            
            foreach ($report_results as $key => $value) {
                
                $status = 1;

                $close_rate_percent_1 = number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2);
                $close_rate_dollar_1 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $compare_rate_percent_1 = number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2);
                $compare_rate_dollar_1 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $diff_rate_percent_1 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);
                $diff_rate_dollar_1 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                $close_rate_percent_2 = (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
                $close_rate_dollar_2 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $compare_rate_percent_2 = (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
                $compare_rate_dollar_2 = number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2);
                $diff_rate_percent_2 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);
                $diff_rate_dollar_2 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                $close_rate_percent_3 = (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
                $close_rate_dollar_3 = number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2);
                $compare_rate_percent_3 = (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
                $compare_rate_dollar_3 = number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2);
                $diff_rate_percent_3 = (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100);
                $diff_rate_dollar_3 = (number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100);
                // die(print_r($diff_rate_dollar_3));


                $lineData = array($value['job_name'],$value['total_estimates_1'],$close_rate_percent_1 ,$close_rate_dollar_1, $value['total_estimates_2'], $compare_rate_percent_1 , $compare_rate_dollar_1, $diff_rate_percent_1,$diff_rate_dollar_1,$value['accepted_1'],$close_rate_percent_2 ,$close_rate_dollar_2 , $value['accepted_2'],$compare_rate_percent_2 ,$compare_rate_dollar_2 ,$diff_rate_percent_2 ,$diff_rate_dollar_2 , $value['declined_1'],$close_rate_percent_3 ,$close_rate_dollar_3 , $value['declined_2'],$compare_rate_percent_3 ,$compare_rate_dollar_3 ,$diff_rate_percent_3 ,$diff_rate_dollar_3  );

                fputcsv($f, $lineData, $delimiter);
                
            }

            if ($status==1) {

                //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                    //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/reports/serviceSummary");
            }                    
            
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/reports/serviceSummary");
        }    

    }

	## Sales Pipeline Detail Report
    public function salesPipelineDetail(){   
        //get the posts data
        // $data['pipeline_details'] = $this->EstimateModal->getAllEstimate();
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['SavedFilter'] = $this->SaveSalesPipelineFilterModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
        $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);
        $data['pipeline_details'] = $this->EstimateModal->getAllEstimate($where);
		// die(print_r($data['pipeline_details']));
	    $page["active_sidebar"] = "pipelineDetail";
        $page["page_name"] = 'Sales Pipeline Detail Report';
        $page["page_content"] = $this->load->view("admin/report/view_pipeline_detail_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
 
   
## ajax data for Sales Summary Report
     function ajaxPipelineDetailData(){
        $conditions = array();
        
        //set conditions for search
        $customer_name = $this->input->post('customer_name');
        $sales_rep_id = $this->input->post('sales_rep_id');
        $property_address = $this->input->post('property_address');
        $program_name = $this->input->post('program_name');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        
        if(!empty($customer_name)){
            $conditions['search']['customer_name'] = $customer_name;
        }
        if(!empty($sales_rep_id) && $sales_rep_id != "null"){
            $conditions['search']['sales_rep_id'] = $sales_rep_id;
        }

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
         if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }

         if(!empty($property_address)){
            $conditions['search']['property_address'] = $property_address;
        }
         if(!empty($program_name)){
            $conditions['search']['program_name'] = $program_name;
        }
        // die(print_r($conditions));
         //get posts data
           $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['pipeline_details'] = $this->EstimateModal->getAllEstimateSearch($conditions); 
        // die(print_r($data['pipeline_details']));
        $body =  $this->load->view('admin/report/ajax_pipeline_details_report', $data, false);

        echo $body;

    }

    ## Download CSV for Pipeline Detail Report
    public function downloadPipelineDetailCsv(){

        $status = '';
        $conditions = array();
        //set conditions for search
        $customer_name = $this->input->post('customer_name');
        $sales_rep_id = $this->input->post('sales_rep_id');
        $property_address = $this->input->post('property_address');
        $program_name = $this->input->post('program_name');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        
        if(!empty($customer_name)){
            $conditions['search']['customer_name'] = $customer_name;
        }
        if(!empty($sales_rep_id)){
            $conditions['search']['sales_rep_id'] = implode(",", $sales_rep_id);
        }

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
         if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }

         if(!empty($property_address)){
            $conditions['search']['property_address'] = $property_address;
        }
         if(!empty($program_name)){
            $conditions['search']['program_name'] = $program_name;
        }
        // die(print_r($conditions));
         //get posts data
           $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['pipeline_details'] = $this->EstimateModal->getAllEstimateSearch($conditions);
        // die(print_r($this->db->last_query()));
        // die(print_r($data['pipeline_details']));
        $pipeline_detail = [];
        if($data['pipeline_details']){
            foreach($data['pipeline_details'] as $detail){
                
                $estimate_cost = $this->totalEstimateCost($detail->estimate_id, $detail->property_id, $detail->program_id, $detail->yard_square_feet);

                $details = array(
                    'customer' => $detail->first_name.' '.$detail->last_name,
                    'property' => $detail->property_address,
                    'program_service' => $detail->program_name,
                    'estimate' => $estimate_cost,
                    'estimate_date' => date('m-d-Y', strtotime($detail->estimate_date)),
                    'property_status' => $detail->property_status,
                );
                array_push($pipeline_detail, $details);
            }
        }

        // die(print_r($pipeline_detail));
        if($pipeline_detail){
            $delimiter = ",";
            $filename = "report_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Customer','Property','Estimate Program/Service','Estimate','Estimate Date', 'Property Status');
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
                      
            foreach ($pipeline_detail as $key => $value) {
                $status = 1;

                $lineData = array($value['customer'],$value['property'], $value['program_service'] ,number_format(($value['estimate']) ,2),$value['estimate_date'], $value['property_status'] );

                fputcsv($f, $lineData, $delimiter);
                
            }

            if ($status==1) {
                //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/reports/salesPipelineDetail");
            }  

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/reports/salesPipelineDetail");

        }    

    }

	## Commission Report #
    public function salesCommissionReport(){   
        //get the posts data
        $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);
        $data['estimates_completed'] = $this->EstimateModal->getAllEstimateCompleted($where);
        // die(print_r($data['estimates_completed']));
        // die(print_r($this->db->last_query()));
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
		// die(print_r($data['users']));

        $data['commission_report'] =  $this->RP->getAllCommissionReports(array('report.company_id' =>$this->session->userdata['company_id'], 'estimate_status' => 2));
        // die(print_r($data['commission_report']));
        $data['all_commission'] = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id']));
        // die(print_r($data['all_commission']));
        $primary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 1));
        
        $secondary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 2));
        // die(print_r($primary_comm));
        // die(print_r($secondary_comm));
        // need to create an array of the sources for these estimates to see if the number needs to increase or not
        $sources_from_estimates = array();
        foreach($data["estimates_completed"] as $est) {
            $sources_from_estimates[] = $est->source;
        }
        if(!empty($primary_comm) && !empty($secondary_comm)){
            $primary_commission_percentage = isset($primary_comm[0]->commission_value) ? $primary_comm[0]->commission_value : 0;
           
            $secondary_commission_percentage = isset($secondary_comm[0]->commission_value) ? $secondary_comm[0]->commission_value : 0;

             $commission_summary = [];
        foreach($data['commission_report'] as $commission){
            if(is_array($commission_summary) && array_key_exists($commission->sales_rep, $commission_summary)){
                
                $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                $estimate_cost = $commission->cost;
                $total_cost = $commission->cost;
                // die(print_r($commission));
                
                if(isset($commission->commission_type) && $commission->commission_type == 1 && $commission->sales_rep != ''){
                    $commission_summary[$commission->sales_rep]['primary_service'] += 1 ;
                    $commission_summary[$commission->sales_rep]['primary_service_total'] += $estimate_cost;
                    $commission_summary[$commission->sales_rep]['primary_commission'] += $primary_commission;
                    $commission_summary[$commission->sales_rep]['total_primary_commission'] += $total_primary_commission;
                    $commission_summary[$commission->sales_rep]['total_estimates'] += 1;
                    $commission_summary[$commission->sales_rep]['total_cost'] += $total_cost;
                } elseif (isset($commission->commission_type) && $commission->commission_type == 2 && $commission->sales_rep != ''){
                   
                    $commission_summary[$commission->sales_rep]['secondary_service'] += 1 ;
                    $commission_summary[$commission->sales_rep]['secondary_service_total'] += $estimate_cost;
                    $commission_summary[$commission->sales_rep]['secondary_commission'] += $secondary_commission;
                    $commission_summary[$commission->sales_rep]['total_secondary_commission'] += $total_secondary_commission;
                    $commission_summary[$commission->sales_rep]['total_estimates'] += 1;
                    $commission_summary[$commission->sales_rep]['total_cost'] += $total_cost;
                } else {
                    $commission_summary[$commission->sales_rep]['primary_service'] += 0 ;
                    $commission_summary[$commission->sales_rep]['primary_service_total'] += 0;
                    $commission_summary[$commission->sales_rep]['secondary_service'] += 0 ;
                    $commission_summary[$commission->sales_rep]['secondary_service_total'] += 0;

                }

                if(isset($commission->user_id) && $commission->user_id == $commission->source){
                    $commission_summary[$commission->sales_rep]['sold_by'] += 1 ;
                } else {
                    $commission_summary[$commission->sales_rep]['sold_by'] += 0 ;
                }

            } else {
                if(isset($commission->sales_rep) && $commission->sales_rep != '' && $commission->sales_rep !='0'){
                    $estimate_cost = $commission->cost;
                    $total_cost = $commission->cost;
                    $commission_summary[$commission->sales_rep]['rep_name'] = $commission->sales_f_name.' '.$commission->sales_l_name;
                    
                    $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                    $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                    
                    $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                    $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                    
                    $commission_summary[$commission->sales_rep]['total_cost'] = $total_cost;
                    // die(print_r($estimate_cost));
    
                  
                    if(isset($commission->commission_type) && $commission->commission_type == 1 && $commission->sales_rep != ''){
                        $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                        
                        $estimate_cost = $commission->cost;
                        $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                        $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
    
                        $commission_summary[$commission->sales_rep]['primary_commission'] = $primary_commission;
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] = 0;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] = 0;
                        $commission_summary[$commission->sales_rep]['primary_service'] = 1 ;
                        $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] = $estimate_cost ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                    } elseif (isset($commission->commission_type) && $commission->commission_type == 2 && $commission->sales_rep != ''){
                        $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                        
                        $estimate_cost = $commission->cost;
                        $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                        $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
    
                        $commission_summary[$commission->sales_rep]['primary_commission'] = 0;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] = $secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] = 0;
    
                        $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_service'] = 1 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] = $estimate_cost ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                    } else {
                        $estimate_cost = $commission->cost;
                        
                        $commission_summary[$commission->sales_rep]['total_estimates'] = 0 ;
                        $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                        $commission_summary[$commission->sales_rep]['primary_commission'] = 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] = 0 ;
                    }
    
                    
                    if(isset($commission->user_id) && $commission->user_id == $commission->source){
                        $commission_summary[$commission->sales_rep]['sold_by'] = 1 ;
                    } else {
                        $commission_summary[$commission->sales_rep]['sold_by'] = 0 ;
                    }
                }
               
            }
        }

        $data['commission_summary'] = $commission_summary;
        // die(print_r($data['commission_summary']));

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong>Primary or Secondary commission percentages in settings </div>');
            // redirect("admin/reports/salesCommissionReport");
        }
        
        
	    $page["active_sidebar"] = "commissionReport";
        $page["page_name"] = 'Commission Report';
        $page["page_content"] = $this->load->view("admin/report/view_commission_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
 
    ## ajax data for Commission Report
     function ajaxCommissionReportData(){
        $conditions = array();
        // die(print_r($conditions));
        //set conditions for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');
        // die(print_r($sales_rep_id));
        if(!empty($sales_rep_id)){
            $conditions['search']['sales_rep_id'] = $sales_rep_id;
        }
        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
        if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
         if(!empty($job_completed_date_to)){
            $conditions['search']['job_completed_date_to'] = $job_completed_date_to;
        }
       
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }
        $conditions['search']['estimate_status'] = 2;
       
         //get posts data
         $data['commission_report'] = $this->RP->getAllCommissionReportsSearch($conditions);
       
         $data['all_commission'] = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id']));
        
        $primary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 1));
        $primary_commission_percentage = isset($primary_comm[0]->commission_value) ? $primary_comm[0]->commission_value : 0;
        $secondary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 2));
        $secondary_commission_percentage = isset($secondary_comm[0]->commission_value) ? $secondary_comm[0]->commission_value : 0;
        
        $commission_summary = [];
        if($data['commission_report']){

            foreach($data['commission_report'] as $commission){
                if(is_array($commission_summary) && array_key_exists($commission->sales_rep, $commission_summary)){
                   
                    $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                    $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                    $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                    $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                    $estimate_cost = $commission->cost;
                    $total_cost = $commission->cost;
                    
                    $commission_summary[$commission->sales_rep]['total_cost'] += $total_cost;
                    if(isset($commission->commission_type) && $commission->commission_type == 1){
                        $commission_summary[$commission->sales_rep]['primary_service'] += 1 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] += $estimate_cost;
                        $commission_summary[$commission->sales_rep]['primary_commission'] += $primary_commission;
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] += $total_primary_commission;
                        
                    } elseif (isset($commission->commission_type) && $commission->commission_type == 2){
                       
                        $commission_summary[$commission->sales_rep]['secondary_service'] += 1 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] += $estimate_cost;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] += $secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] += $total_secondary_commission;
                    } else {
                        $commission_summary[$commission->sales_rep]['primary_service'] += 0 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] += 0;
                        $commission_summary[$commission->sales_rep]['secondary_service'] += 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] += 0;
                        $commission_summary[$commission->sales_rep]['total_estimates'] += 0;
                        $commission_summary[$commission->sales_rep]['primary_commission'] += 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] += 0 ;
    
                    }
    
                    if(isset($commission->user_id) && $commission->user_id == $commission->source){
                        $commission_summary[$commission->sales_rep]['sold_by'] += 1 ;
                    } else {
                        $commission_summary[$commission->sales_rep]['sold_by'] += 0 ;
                    }
    
                } else {
                    if(isset($commission->sales_rep) && $commission->sales_rep != '' && $commission->sales_rep !='0'){

                        $commission_summary[$commission->sales_rep]['rep_name'] = $commission->sales_f_name.' '.$commission->sales_l_name;
                        $estimate_cost = $commission->cost;
                        $total_cost = $commission->cost;
                        
                        $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                        $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                        
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                        
                        $commission_summary[$commission->sales_rep]['total_cost'] = $total_cost;
        
                        if(isset($commission->commission_type) && $commission->commission_type == 1){
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                            
                            $estimate_cost = $commission->cost;
                            $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                            $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
        
                            $commission_summary[$commission->sales_rep]['primary_commission'] = $primary_commission;
                            $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['total_secondary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['primary_service'] = 1 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = $estimate_cost ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                        } elseif (isset($commission->commission_type) && $commission->commission_type == 2){
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                            
                            $estimate_cost = $commission->cost;
                            $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                            $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
        
                            $commission_summary[$commission->sales_rep]['primary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = $secondary_commission;
                            $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                            $commission_summary[$commission->sales_rep]['total_primary_commission'] = 0;
        
                            $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 1 ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = $estimate_cost ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                        } else {
                            $estimate_cost = $commission->cost;
                            
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_commission'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = 0 ;
                        }
        
                        
                        if(isset($commission->user_id) && $commission->user_id == $commission->source){
                            $commission_summary[$commission->sales_rep]['sold_by'] = 1 ;
                        } else {
                            $commission_summary[$commission->sales_rep]['sold_by'] = 0 ;
                        }
                    }
                   
                }
            }
        }
        
         //get posts data
        $data['commission_summary'] = $commission_summary;
        // die(print_r($data['commission_summary']));
           
        $body =  $this->load->view('admin/report/ajax_commission_report', $data, false);

        echo $body;

    }

    ## Download CSV for Commission Report
    public function downloadCommissionReportCsv(){

        $status = '';
        $conditions = array();
        //set conditions for search
        $sales_rep_id = $this->input->post('sales_rep_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');

        if(!empty($sales_rep_id)){
            $conditions['search']['sales_rep_id'] = $sales_rep_id;
        }
        if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
 
        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
        if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }
        if(!empty($job_completed_date_to)){
            $conditions['search']['job_completed_date_to'] = $job_completed_date_to;
        }
       
          //get posts data
          $data['commission_report'] = $this->RP->getAllCommissionReportsSearch($conditions);
        //  die(print_r($data['commission_report']));
          $data['all_commission'] = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id']));
        //  die(print_r($data['all_commission']));
         $primary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 1));
        $primary_commission_percentage = $primary_comm[0]->commission_value;
        $secondary_comm = $this->CommissionModel->getAllCommission(array('company_id' =>$this->session->userdata['company_id'], 'commission_type' => 2));
        $secondary_commission_percentage = $secondary_comm[0]->commission_value;
        // die(print_r($primary_comm));
        // die(print_r($secondary_comm));
        $commission_summary = [];
        if($data['commission_report']){

            foreach($data['commission_report'] as $commission){
                if(is_array($commission_summary) && array_key_exists($commission->sales_rep, $commission_summary)){
                    
                    $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                    $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                    $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                    $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                    $estimate_cost = $commission->cost;
                    $total_cost = $commission->cost;
                    // die(print_r($commission));
                   
                    if(isset($commission->commission_type) && $commission->commission_type == 1 && $commission->sales_rep != ''){
                        $commission_summary[$commission->sales_rep]['primary_service'] += 1 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] += $estimate_cost;
                        $commission_summary[$commission->sales_rep]['primary_commission'] += $primary_commission;
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] += $total_primary_commission;
                        $commission_summary[$commission->sales_rep]['total_estimates'] += 1;
                        $commission_summary[$commission->sales_rep]['total_cost'] += $total_cost;
                    } elseif (isset($commission->commission_type) && $commission->commission_type == 2 && $commission->sales_rep != ''){
                       
                        $commission_summary[$commission->sales_rep]['secondary_service'] += 1 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] += $estimate_cost;
                        $commission_summary[$commission->sales_rep]['secondary_commission'] += $secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] += $total_secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_estimates'] += 1;
                        $commission_summary[$commission->sales_rep]['total_cost'] += $total_cost;
                    } else {
                        $commission_summary[$commission->sales_rep]['primary_service'] += 0 ;
                        $commission_summary[$commission->sales_rep]['primary_service_total'] += 0;
                        $commission_summary[$commission->sales_rep]['secondary_service'] += 0 ;
                        $commission_summary[$commission->sales_rep]['secondary_service_total'] += 0;
    
                    }
    
                    if(isset($commission->user_id) && $commission->user_id == $commission->source){
                        $commission_summary[$commission->sales_rep]['sold_by'] += 1 ;
                    } else {
                        $commission_summary[$commission->sales_rep]['sold_by'] += 0 ;
                    }
    
                } else {
                    if(isset($commission->sales_rep) && $commission->sales_rep != '' && $commission->sales_rep !='0'){
                        $estimate_cost = $commission->cost;
                        $total_cost = $commission->cost;
                        $commission_summary[$commission->sales_rep]['rep_name'] = $commission->sales_f_name.' '.$commission->sales_l_name;
                        
                        $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                        $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                        
                        $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                        $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                        $commission_summary[$commission->sales_rep]['total_cost'] = $total_cost;
                        // die(print_r($estimate_cost));
        
                      
                        if(isset($commission->commission_type) && $commission->commission_type == 1 && $commission->sales_rep != ''){
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                           
                            $estimate_cost = $commission->cost;
                            $primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
                            $total_primary_commission = (($estimate_cost*$primary_commission_percentage)/100);
        
                            $commission_summary[$commission->sales_rep]['primary_commission'] = $primary_commission;
                            $commission_summary[$commission->sales_rep]['total_primary_commission'] = $total_primary_commission;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['total_secondary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['primary_service'] = 1 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = $estimate_cost ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                        } elseif (isset($commission->commission_type) && $commission->commission_type == 2 && $commission->sales_rep != ''){
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 1;
                            
                            $estimate_cost = $commission->cost;
                            $secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
                            $total_secondary_commission = (($estimate_cost*$secondary_commission_percentage)/100);
        
                            $commission_summary[$commission->sales_rep]['primary_commission'] = 0;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = $secondary_commission;
                            $commission_summary[$commission->sales_rep]['total_secondary_commission'] = $total_secondary_commission;
                            $commission_summary[$commission->sales_rep]['total_primary_commission'] = 0;
        
                            $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 1 ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = $estimate_cost ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                        } else {
                            $estimate_cost = $commission->cost;
                            
                            $commission_summary[$commission->sales_rep]['total_estimates'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_service_total'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_service_total'] = 0 ;
                            $commission_summary[$commission->sales_rep]['primary_commission'] = 0 ;
                            $commission_summary[$commission->sales_rep]['secondary_commission'] = 0 ;
                        }
        
                        
                        if(isset($commission->user_id) && $commission->user_id == $commission->source){
                            $commission_summary[$commission->sales_rep]['sold_by'] = 1 ;
                        } else {
                            $commission_summary[$commission->sales_rep]['sold_by'] = 0 ;
                        }
                    }
                   
                }
            }

            
        }
         
          //get posts data
         $data['commission_summary'] = $commission_summary;
        //  die(print_r($commission_summary));

        // echo $this->db->last_query();
        // die();
        if($commission_summary){
            
            $delimiter = ",";
            $filename = "report_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Sales Rep Name','Total $ of Produced Primary Services','Total Primary Commissions (Produced Primary Services $ * Primary Service Commission %)','Total $ of Produced Secondary Services','Total Secondary Commission (Produced Secondary $ * Secondary Commission %)','Total Sales Produced','Total Commissions','New Sales Source',);
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
        
        
            foreach ($commission_summary as $key => $value) {
            
            $status = 1;

            $lineData = array($value['rep_name'],number_format(($value['primary_service_total']) ,2), number_format(($value['primary_commission']) ,2), number_format(($value['secondary_service_total']) ,2), number_format(($value['secondary_commission']) ,2), number_format(($value['primary_service_total']+$value['secondary_service_total']) ,2), number_format(($value['primary_commission']+$value['secondary_commission']) ,2), $value['sold_by']);

            fputcsv($f, $lineData, $delimiter);
            
            }


            if ($status==1) {

                            //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                    //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/reports/salesCommissionReport");
            }                    

        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/reports/salesCommissionReport");
        }    

    }
    
	## Bonus Report
    public function salesBonusReport(){  
        //get the posts data
        $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);
        $data['estimates_completed'] = $this->EstimateModal->getAllEstimateCompleted($where);
        // die(print_r($data['estimates_completed']));
        // die(print_r($this->db->last_query()));
        $data['bonus_report'] =  $this->RP->getAllBonusReports(array('report.company_id' =>$this->session->userdata['company_id'], 'estimate_status' => 2));
        // die(print_r($data['bonus_report']));
        $data['all_bonuses'] = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id']));
        // die(print_r($data['all_bonuses']));
        $primaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 1));
        $secondaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 2));
        // die(print_r($primaryBonus));

        if(!empty($primaryBonus) || !empty($secondaryBonus)){
            $primary_bonus_percentage = $primaryBonus[0]->bonus_value;
            $secondary_bonus_percentage = $secondaryBonus[0]->bonus_value;

              $bonus_summary = [];
        foreach($data['bonus_report'] as $bonus){
            if(is_array($bonus_summary) && array_key_exists($bonus->sales_rep, $bonus_summary)){
                
                $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                $estimate_cost = $bonus->cost;
                $total_cost = $bonus->cost;
                
                if(isset($bonus->bonus_type) && $bonus->bonus_type == 1 && $bonus->sales_rep != ''){
                    $bonus_summary[$bonus->sales_rep]['primary_service'] += 1 ;
                    $bonus_summary[$bonus->sales_rep]['primary_service_total'] += $estimate_cost;
                    $bonus_summary[$bonus->sales_rep]['primary_bonus'] += $primary_bonus;
                    $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] += $total_primary_bonus;
                    $bonus_summary[$bonus->sales_rep]['total_estimates'] += 1;
                    // $bonus_summary[$bonus->sales_rep]['total_cost'] += $total_cost;

                } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2 && $bonus->sales_rep != ''){
                   
                    $bonus_summary[$bonus->sales_rep]['secondary_service'] += 1 ;
                    $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += $estimate_cost;
                    $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += $secondary_bonus;
                    $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] += $total_secondary_bonus;
                    $bonus_summary[$bonus->sales_rep]['total_estimates'] += 1;
                    // $bonus_summary[$bonus->sales_rep]['total_cost'] += $total_cost;
                    
                } else {
                    $bonus_summary[$bonus->sales_rep]['primary_service'] += 0 ;
                    $bonus_summary[$bonus->sales_rep]['primary_service_total'] += 0;
                    $bonus_summary[$bonus->sales_rep]['secondary_service'] += 0 ;
                    $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += 0;
                    $bonus_summary[$bonus->sales_rep]['primary_bonus'] += 0;
                    $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += 0;
                    $bonus_summary[$bonus->sales_rep]['total_estimates'] += 0;

                }
                if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                    $bonus_summary[$bonus->sales_rep]['sold_by'] += 1 ;
                } else {
                    $bonus_summary[$bonus->sales_rep]['sold_by'] += 0 ;
                }

            } else {
                if(isset($bonus->sales_rep) && $bonus->sales_rep != '' && $bonus->sales_rep !='0'){
                    $bonus_summary[$bonus->sales_rep]['tech_name'] = $bonus->sales_f_name.' '.$bonus->sales_l_name;
                    $estimate_cost = $bonus->cost;
                    // $total_cost = $bonus->cost;
                    
                    $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                    $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                    
                    $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                    $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                    
                    if(isset($bonus->bonus_type) && $bonus->bonus_type == 1 && $bonus->sales_rep != ''){
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                        $estimate_cost = $bonus->cost;
                        
                        $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                        $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);

                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] = $primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = 0;
                        $bonus_summary[$bonus->sales_rep]['primary_service'] = 1 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] = $estimate_cost ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;

                    } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2 && $bonus->sales_rep != ''){
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                        $estimate_cost = $bonus->cost;
                    
                        $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                        $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);

                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = $secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = 0;

                        $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] = 1 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = $estimate_cost ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;

                    } else {
                        $estimate_cost = $bonus->cost;
                        
                        $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0 ;
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] = 0 ;
                    }

                    
                    if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                        $bonus_summary[$bonus->sales_rep]['sold_by'] = 1 ;
                    } else {
                        $bonus_summary[$bonus->sales_rep]['sold_by'] = 0 ;
                    }
                }
            }
        }
       
        $data['bonus_summary'] = $bonus_summary;

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong>Primary or Secondary bonus percentages in settings </div>');
            // redirect("admin/reports/salesBonusReport");
        }
        // die(print_r($secondaryBonus));
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        $data['users'] = $this->Administrator->getAllAdmin($where_arr);
		// die(print_r($data));
	    $page["active_sidebar"] = "bonusReport";
        $page["page_name"] = 'Bonus Report';
        $page["page_content"] = $this->load->view("admin/report/view_bonus_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }
 
    ## ajax data for Bonus Report
     function ajaxBonusReportData(){
        $conditions = array();
        // die(print_r($conditions));
        //set conditions for search
        $technician_id = $this->input->post('technician_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');

        if(!empty($technician_id)){
            $conditions['search']['technician_id'] = $technician_id;
        }
        

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
        if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
         if(!empty($job_completed_date_to)){
            $conditions['search']['job_completed_date_to'] = $job_completed_date_to;
        }
        
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }
        $conditions['search']['estimate_status'] = 2;
        // die(print_r($conditions));

        $data['bonus_report'] =  $this->RP->getAllBonusReportsSearch($conditions);
        // die(print_r($data['bonus_report']));
        $data['all_bonuses'] = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id']));
        // die(print_r($data['all_bonuses']));
        $primaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 1));
        $secondaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 2));
        $primary_bonus_percentage = (isset($primaryBonus[0]->bonus_value)?$primaryBonus[0]->bonus_value:0);
        $secondary_bonus_percentage = (isset($secondaryBonus[0]->bonus_value)?$secondaryBonus[0]->bonus_value:0);
        // die(print_r($primaryBonus));
        // die(print_r($secondaryBonus));
         $bonus_summary = [];
         if(!empty($data['bonus_report'])){

             foreach($data['bonus_report'] as $bonus){
                if(is_array($bonus_summary) && array_key_exists($bonus->sales_rep, $bonus_summary)){
                    
                    $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                    $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                    $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                    $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                    $estimate_cost = $bonus->cost;
                    $total_cost = $bonus->cost;
                   
                    $bonus_summary[$bonus->sales_rep]['total_cost'] += $total_cost;
                    if(isset($bonus->bonus_type) && $bonus->bonus_type == 1){
                        $bonus_summary[$bonus->sales_rep]['primary_service'] += 1 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] += $estimate_cost;
                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] += $primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] += $total_primary_bonus;
                        
                    } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2){
                    
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] += 1 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += $estimate_cost;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += $secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] += $total_secondary_bonus;
                    } else {
                        $bonus_summary[$bonus->sales_rep]['primary_service'] += 0 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] += 0;
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] += 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += 0;
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] += 0;
                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] += 0;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += 0;
    
                    }
    
                    if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                        $bonus_summary[$bonus->sales_rep]['sold_by'] += 1 ;
                    } else {
                        $bonus_summary[$bonus->sales_rep]['sold_by'] += 0 ;
                    }
    
                } else {
                    if(isset($bonus->sales_rep) && $bonus->sales_rep != '' && $bonus->sales_rep !='0'){
                        $estimate_cost = $bonus->cost;
                        $total_cost = $bonus->cost;
                        $bonus_summary[$bonus->sales_rep]['tech_name'] = $bonus->sales_f_name.' '.$bonus->sales_l_name;
                       
                        $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                        $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                        
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                       
                        $bonus_summary[$bonus->sales_rep]['total_cost'] = $total_cost;
        
                      
                        if(isset($bonus->bonus_type) && $bonus->bonus_type == 1){
                            $estimate_cost = $bonus->cost;
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                        
                            $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                            $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
        
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = $primary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 1 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = $estimate_cost ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;
                        } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2){
                            $estimate_cost = $bonus->cost;
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                            
                            $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                            $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
        
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = $secondary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = 0;
        
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 1 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = $estimate_cost ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;
                        } else {
                            $estimate_cost = $bonus->cost;
                            
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0 ;
                        }
        
                        
                        if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                            $bonus_summary[$bonus->sales_rep]['sold_by'] = 1 ;
                        } else {
                            $bonus_summary[$bonus->sales_rep]['sold_by'] = 0 ;
                        }
                       
                    }
                }
             }
         }
        
         //get posts data
        $data['bonus_summary'] = $bonus_summary;
        // die(print_r($bonus_summary));
           
        $body =  $this->load->view('admin/report/ajax_bonus_report', $data, false);

        echo $body;

    }

    ## Download CSV for Bonus Report
    public function downloadBonusReportCsv(){

        $status = '';
        $conditions = array();
        $technician_id = $this->input->post('technician_id');
        $estimate_created_date_to = $this->input->post('estimate_created_date_to');
        $estimate_created_date_from = $this->input->post('estimate_created_date_from');
        $job_completed_date_to = $this->input->post('job_completed_date_to');
        $job_completed_date_from = $this->input->post('job_completed_date_from');

        if(!empty($technician_id)){
            $conditions['search']['technician_id'] = $technician_id;
        }
        

        if(!empty($estimate_created_date_to)){
            $conditions['search']['estimate_created_date_to'] = $estimate_created_date_to;
        }
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }
        if(!empty($estimate_created_date_from)){
            $conditions['search']['estimate_created_date_from'] = $estimate_created_date_from;
        }
         if(!empty($job_completed_date_from)){
            $conditions['search']['job_completed_date_from'] = $job_completed_date_from;
        }
        // die(print_r($conditions));

        $data['bonus_report'] =  $this->RP->getAllBonusReportsSearch($conditions);
        // die(print_r($data['bonus_report']));
        $data['all_bonuses'] = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id']));
        // die(print_r($data['all_bonuses']));
        $primaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 1));
        $secondaryBonus = $this->BonusModel->getAllBonus(array('company_id' =>$this->session->userdata['company_id'], 'bonus_type' => 2));
        $primary_bonus_percentage = $primaryBonus[0]->bonus_value;
        $secondary_bonus_percentage = $secondaryBonus[0]->bonus_value; 
        // die(print_r($primary_bonus));
         $bonus_summary = [];
         if($data['bonus_report']){

            foreach($data['bonus_report'] as $bonus){
                if(is_array($bonus_summary) && array_key_exists($bonus->sales_rep, $bonus_summary)){
                    
                    $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                    $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                    $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                    $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                    $estimate_cost = $bonus->cost;
                    $total_cost = $bonus->cost;
                    
                    if(isset($bonus->bonus_type) && $bonus->bonus_type == 1 && $bonus->sales_rep != ''){
                        $bonus_summary[$bonus->sales_rep]['primary_service'] += 1 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] += $estimate_cost;
                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] += $primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] += $total_primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] += 1;
                        $bonus_summary[$bonus->sales_rep]['total_cost'] += $total_cost;
                    } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2 && $bonus->sales_rep != ''){
                       
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] += 1 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += $estimate_cost;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += $secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] += $total_secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] += 1;
                        $bonus_summary[$bonus->sales_rep]['total_cost'] += $total_cost;
                    } else {
                        $bonus_summary[$bonus->sales_rep]['primary_service'] += 0 ;
                        $bonus_summary[$bonus->sales_rep]['primary_service_total'] += 0;
                        $bonus_summary[$bonus->sales_rep]['secondary_service'] += 0 ;
                        $bonus_summary[$bonus->sales_rep]['secondary_service_total'] += 0;
                        $bonus_summary[$bonus->sales_rep]['primary_bonus'] += 0;
                        $bonus_summary[$bonus->sales_rep]['secondary_bonus'] += 0;
                        $bonus_summary[$bonus->sales_rep]['total_estimates'] += 0;
    
                    }
    
                    if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                        $bonus_summary[$bonus->sales_rep]['sold_by'] += 1 ;
                    } else {
                        $bonus_summary[$bonus->sales_rep]['sold_by'] += 0 ;
                    }
    
                } else {
                    if(isset($bonus->sales_rep) && $bonus->sales_rep != '' && $bonus->sales_rep !='0'){
                        $bonus_summary[$bonus->sales_rep]['tech_name'] = $bonus->sales_f_name.' '.$bonus->sales_l_name;
                        $estimate_cost = $bonus->cost;
                        $total_cost = $bonus->cost;
                       
                        $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                        $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                        
                        $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                        $bonus_summary[$bonus->sales_rep]['total_cost'] = $total_cost;
    
                    
                        if(isset($bonus->bonus_type) && $bonus->bonus_type == 1 && $bonus->sales_rep != ''){
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                            $estimate_cost = $bonus->cost;
                            $primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
                            $total_primary_bonus = (($estimate_cost*$primary_bonus_percentage)/100);
    
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = $primary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = $total_primary_bonus;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 1 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = $estimate_cost ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;
                        } elseif (isset($bonus->bonus_type) && $bonus->bonus_type == 2 && $bonus->sales_rep != ''){
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 1;
                            $estimate_cost = $bonus->cost;
                            $secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
                            $total_secondary_bonus = (($estimate_cost*$secondary_bonus_percentage)/100);
    
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = $secondary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_secondary_bonus'] = $total_secondary_bonus;
                            $bonus_summary[$bonus->sales_rep]['total_primary_bonus'] = 0;
    
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 1 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = $estimate_cost ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;
                        } else {
                            $estimate_cost = $bonus->cost;
                            $bonus_summary[$bonus->sales_rep]['primary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_service_total'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_service_total'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['primary_bonus'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['secondary_bonus'] = 0 ;
                            $bonus_summary[$bonus->sales_rep]['total_estimates'] = 0 ;
                        }
    
                        
                        if(isset($bonus->user_id) && $bonus->user_id == $bonus->source){
                            $bonus_summary[$bonus->sales_rep]['sold_by'] = 1 ;
                        } else {
                            $bonus_summary[$bonus->sales_rep]['sold_by'] = 0 ;
                        }
                    }
                }
            }
         }
        
         //get posts data
        $data['bonus_summary'] = $bonus_summary;
        // die(print_r($bonus_summary));
        if($bonus_summary){

            
            $delimiter = ",";
            $filename = "report_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Technician Name','Total $ Primary Services Completed','Total Primary Bonuses','Total $ Secondary Services Completed','Total Secondary Bonuses','Total $ Production','Total Bonuses','Source New Sales',);
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
            
                   
            foreach ($bonus_summary as $key => $value) {
                
                $status = 1;

                $lineData = array($value['tech_name'],number_format(($value['primary_service_total']) ,2), number_format(($value['primary_bonus']) ,2), number_format(($value['secondary_service_total']) ,2), number_format(($value['secondary_bonus']) ,2), number_format(($value['primary_service_total']+$value['secondary_service_total']) ,2), number_format(($value['primary_bonus']+$value['secondary_bonus']) ,2), $value['sold_by']);

                fputcsv($f, $lineData, $delimiter);
                
                }


            if ($status==1) {

                            //move back to beginning of file
                fseek($f, 0);
                
                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                    //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' .$filename. '";');
                
                //output all remaining data on a file pointer
                fpassthru($f);
                
            } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                    redirect("admin/reports/salesBonusReport");


            }                    
                

        } else {


                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
                redirect("admin/reports/salesBonusReport");

        }    

    }

    #cancel report
	public function cancelReport(){
		$data['user_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
		#not seeing specific role for sales rep so getting all users 
		$report_data = array(
			'total_cancelled_properties'=> 0,
			'total_cancelled_services'=> 0,
			'total_cancelled_revenue'=> 0,
			'lost_total_new_cancelled_props'=> 0,
            'lost_total_new_cancelled_servs'=> 0,
			'total_new_revenue_lost'=> 0,
			'total_sales'=> 0,
			'total_sales_revenue'=> 0
		);
		
		#get cancelled properties
		$cancelled_properties = $this->PropertyModel->getCancelledPropertyByDateRange(array('property_tbl.company_id'=> $this->session->userdata['company_id'], 'property_tbl.property_status IN(0,7,8,9)'));

        $data["AllCancelledProperty"] = $cancelled_properties;
        $data['ServiceArea'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);

        $company_id = $this->session->userdata['company_id'];

        $data["AllCancelledProperty"] = $cancelled_properties;
        $data["setting_details"] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

        foreach($data["AllCancelledProperty"] as $CanProIndex => $CanPropers){
            $ServiceProgCancelled = array();
            $ProgramCancelledArray = array();
            $AllServicesOfCustomer = $this->CancelledModel->getCancelledServicesByProperty($CanPropers->property_id);
            $cost = 0;

            foreach($AllServicesOfCustomer as $all_services) {
                $PrmName = $this->CancelledModel->getCancelledProgramName($all_services->program_id);
                $ProgramCancelledArray[] = @$PrmName->program_name;

                $propertyDetails = $this->PropertyModel->getOnePropertyDetail($all_services->property_id);
                $jobDetails = $this->JobModel->getOneJob(array('job_id' => $all_services->job_id));
                // got this math from updateProgram - used to calculate price of job when not pulling it from an invoice
                $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $all_services->property_id, 'program_id' => $all_services->program_id));
                    
                    if ($priceOverrideData->is_price_override_set == 1) {
                        $cost +=  $priceOverrideData->price_override;
                    } else {
                        //else no price overrides, then calculate job cost
                        $lawn_sqf = $propertyDetails->yard_square_feet;
                        $job_price = $jobDetails->job_price;

                        //get property difficulty level
                        if (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 2) {
                            $difficulty_multiplier = $data['setting_details']->dlmult_2;
                        } elseif (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 3) {
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
                            $cost += $cost_per_sqf;
                        } else {
                            $cost += $min_fee;
                        }
                }

                $ServiceProgCancelled[] = $jobDetails->job_name;
            }

            $where_estimate = array(
                'customers.customer_id' => $CanPropers->customer_id,
                'property_tbl.property_id' => $CanPropers->property_id,
                'sales_rep !=' => ''
            );
            
            $estimate_job_details = $this->EstimateModal->getAllEstimate($where_estimate);

            $SalesRepArray = array();
            foreach($estimate_job_details as $EST){
                $SaleRepRow = $this->EstimateModal->getAllSalesRepEstimate(array("estimate_id" => $EST->estimate_id));
                $SalesRepArray[] = @$SaleRepRow[0]->user_first_name." ".@$SaleRepRow[0]->user_last_name;
            }

            $SalesRepArray = array_unique($SalesRepArray);

            $ProgramCancelledArray = array_values($ProgramCancelledArray);
            $ProgramCancelledArray = array_unique($ProgramCancelledArray);

            $ServiceProgCancelled = array_values($ServiceProgCancelled);
            $ServiceProgCancelled = array_unique($ServiceProgCancelled);

            $data["AllCancelledProperty"][$CanProIndex]->job_cost = $cost;
            $data["AllCancelledProperty"][$CanProIndex]->program_cancelled = implode(", ", $ProgramCancelledArray);
            $data["AllCancelledProperty"][$CanProIndex]->service_cancelled = implode(", ", $ServiceProgCancelled);
            $data["AllCancelledProperty"][$CanProIndex]->SalesRep = implode(", ", $SalesRepArray);
        }

		#get cancelled services
        $query = array(
            'cancelled_services_tbl.company_id' => $this->session->userdata['company_id'],
        );
        $all_cancelled = $this->CancelledModel->getCancelledServiceInfoDetailsBetween($query, "", "");
        if(!empty($all_cancelled)){
            $properties = [];
            $total_cancelled_properties = [];
            $total_cancelled_revenue = 0;
            $lost_total_new_cancelled_props = [];
            $lost_total_new_cancelled_servs = [];
            $total_new_revenue_lost = 0;
            $one_year_ago = date('Y-m-d', strtotime('-1 year'));
            foreach($all_cancelled as $key=>$value){
                $total_cancelled_properties[] = $value->property_id;
                
                #get job cost
                $job_cost = $this->getJobCost($value->job_id,$value->customer_id,$value->property_id,$value->program_id);
                $total_cancelled_revenue += $job_cost;
                
                if(strtotime($value->property_created) > strtotime($one_year_ago)){
                    
                    #Only include revenue lost if customer property signed up in the last 12 months
                    $total_new_revenue_lost += $job_cost;

                     #Property added within the last 12 months
                    $lost_total_new_cancelled_servs[] = $value->property_id;
                    
                }
                #prep sales data 
                if(!isset($properties[$value->property_id])){
                   $properties[$value->property_id] = ['customer_id'=>$value->customer_id];
                }
            }

            foreach($cancelled_properties as $$key=>$value){
                if(strtotime($value->property_created) > strtotime($one_year_ago)){
                    #Property added within the last 12 months
                    $lost_total_new_cancelled_props[] = $value->property_id;
                }
            }

            $report_data['total_cancelled_properties'] = count($cancelled_properties);
            $report_data['total_cancelled_services'] = count($all_cancelled);
            $report_data['total_cancelled_revenue'] = number_format($total_cancelled_revenue,2);
            $report_data['lost_total_new_cancelled_props'] = count($lost_total_new_cancelled_props);
            $report_data['lost_total_new_cancelled_servs'] = count($lost_total_new_cancelled_servs);
            $report_data['total_new_revenue_lost'] = number_format($total_new_revenue_lost,2);
        }
        #get total sales for customer properties
        $total_sales = 0;
        $total_sales_revenue = 0;
        if(!empty($properties)){
            foreach($properties as $property_id => $details){
                $programs = [];
                $getPropertyProgramJobs = $this->PropertyModel->getPropertyProgramJobs($property_id);
                if(!empty($getPropertyProgramJobs)){
                    $i = 0;
                    while(count($getPropertyProgramJobs) > $i){
                        if(is_array($programs) && !in_array($getPropertyProgramJobs[$i]->program_id,$programs)){
                            $programs[]=$getPropertyProgramJobs[$i]->program_id;
                        }
                        #get job cost
                        $job_cost = $this->getJobCost($getPropertyProgramJobs[$i]->job_id,$details['customer_id'],$property_id,$getPropertyProgramJobs[$i]->program_id);
                        $total_sales_revenue += $job_cost; 
                        
                        $i++;
                    }
                }
                $total_sales += count($programs);

            }
        }

        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasons($this->session->userdata['company_id']);

        $report_data['total_sales'] = $total_sales;
        $report_data['total_sales_revenue'] = number_format($total_sales_revenue,2);
		$data['report_details'] = $report_data;
		$page["active_sidebar"] = "cancelReport";
		$page["page_name"] = 'Cancel Summary';
		$page["page_content"] = $this->load->view("admin/report/view_cancel_report", $data, TRUE);
		$this->layout->superAdminTemplateTable($page);
  	}
	
    public function ajaxDataForCancelReport(){
		$company_id = $this->session->userdata['company_id'];
        $user = $this->input->post('user');
		$start = !empty($this->input->post('date_from')) ? date('y-m-d H:i:s',strtotime($this->input->post('date_from'))) : '';
		$end = !empty($this->input->post('date_to')) ? date('y-m-d H:i:s',strtotime($this->input->post('date_to'))) : date('y-m-d H:i:s',strtotime('now'));
    
		$report_data = array(
			'total_cancelled_properties'=> 0,
			'total_cancelled_services'=> 0,
			'total_cancelled_revenue'=> 0,
			'lost_total_new_cancelled_props'=> 0,
            'lost_total_new_cancelled_servs'=> 0,
			'total_new_revenue_lost'=> 0,
			'total_sales'=> 0,
			'total_sales_revenue'=> 0
		);
		$query = array(
			'cancelled_services_tbl.company_id' => $this->session->userdata['company_id'],
		);

		#get cancelled properties
        $ConditionProperty = array();
        $ConditionProperty['property_tbl.company_id'] = $company_id;
        if($user != 'all'){
            $ConditionProperty['t_estimate.sales_rep'] = $user;
        }
		
        if($this->input->post("serviceArea") != ""){
            $ConditionProperty['property_tbl.property_area'] = $this->input->post("serviceArea");
        }

        if($this->input->post("newExisting") == "1"){
            $ConditionProperty['customers.created_at >='] = date("Y-m-d 00:00:00", strtotime("-1 year"));
        }

        if($this->input->post("newExisting") == "0"){
            $ConditionProperty['customers.created_at <='] = date("Y-m-d 00:00:00", strtotime("-1 year"));
        }

        if($this->input->post("reason") != ""){
            $ConditionProperty['property_tbl.cancel_reason like '] = "%".$this->input->post("reason")."%";
        }

        if($this->input->post("CancelStatus") != ""){
            $ConditionProperty['property_tbl.property_status'] = $this->input->post("CancelStatus");
        }

        $cancelled_properties = $this->PropertyModel->getCancelledPropertyByDateRange($ConditionProperty, $start, $end);

        $data["AllCancelledProperty"] = $cancelled_properties;
        $data["setting_details"] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

        foreach($data["AllCancelledProperty"] as $CanProIndex => $CanPropers){
            $ServiceProgCancelled = array();
            $ProgramCancelledArray = array();
            $AllServicesOfCustomer = $this->CancelledModel->getCancelledServicesByProperty($CanPropers->property_id);
            $cost = 0;

            foreach($AllServicesOfCustomer as $all_services) {
                $PrmName = $this->CancelledModel->getCancelledProgramName($all_services->program_id);
                $ProgramCancelledArray[] = @$PrmName->program_name;

                $propertyDetails = $this->PropertyModel->getOnePropertyDetail($all_services->property_id);
                $jobDetails = $this->JobModel->getOneJob(array('job_id' => $all_services->job_id));
                // got this math from updateProgram - used to calculate price of job when not pulling it from an invoice
                $priceOverrideData  = $this->Tech->getOnePriceOverride(array('property_id' => $all_services->property_id, 'program_id' => $all_services->program_id));
                    
                    if ($priceOverrideData->is_price_override_set == 1) {
                        $cost +=  $priceOverrideData->price_override;
                    } else {
                        //else no price overrides, then calculate job cost
                        $lawn_sqf = $propertyDetails->yard_square_feet;
                        $job_price = $jobDetails->job_price;

                        //get property difficulty level
                        if (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 2) {
                            $difficulty_multiplier = $data['setting_details']->dlmult_2;
                        } elseif (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 3) {
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
                            $cost += $cost_per_sqf;
                        } else {
                            $cost += $min_fee;
                        }
                }

                $ServiceProgCancelled[] = $jobDetails->job_name;
            }

            $where_estimate = array(
                'customers.customer_id' => $CanPropers->customer_id,
                'property_tbl.property_id' => $CanPropers->property_id,
                'sales_rep !=' => ''
            );
            
            $estimate_job_details = $this->EstimateModal->getAllEstimate($where_estimate);

            $SalesRepArray = array();
            foreach($estimate_job_details as $EST){
                $SaleRepRow = $this->EstimateModal->getAllSalesRepEstimate(array("estimate_id" => $EST->estimate_id));
                $SalesRepArray[] = @$SaleRepRow[0]->user_first_name." ".@$SaleRepRow[0]->user_last_name;
            }

            $SalesRepArray = array_unique($SalesRepArray);

            $ProgramCancelledArray = array_values($ProgramCancelledArray);
            $ProgramCancelledArray = array_unique($ProgramCancelledArray);

            $ServiceProgCancelled = array_values($ServiceProgCancelled);
            $ServiceProgCancelled = array_unique($ServiceProgCancelled);

            $data["AllCancelledProperty"][$CanProIndex]->job_cost = $cost;
            $data["AllCancelledProperty"][$CanProIndex]->program_cancelled = implode(", ", $ProgramCancelledArray);
            $data["AllCancelledProperty"][$CanProIndex]->service_cancelled = implode(", ", $ServiceProgCancelled);
            $data["AllCancelledProperty"][$CanProIndex]->SalesRep = implode(", ", $SalesRepArray);
        }

		#get cancelled services
		$all_cancelled = $this->CancelledModel->getCancelledServiceInfoDetailsBetween($query,$start,$end);

		if(!empty($all_cancelled)){
            $properties = [];
			$total_cancelled_properties = [];
			$total_cancelled_revenue = 0;
            $lost_total_new_cancelled_props = [];
            $lost_total_new_cancelled_servs = [];
			$total_new_revenue_lost = 0;
			$one_year_ago = date('Y-m-d', strtotime('-1 year'));
			foreach($all_cancelled as $key=>$value){
				$total_cancelled_properties[] = $value->property_id;
				
				#get job cost
				$job_cost = $this->getJobCost($value->job_id,$value->customer_id,$value->property_id,$value->program_id);
				$total_cancelled_revenue += $job_cost;
				
				if(strtotime($value->property_created) > strtotime($one_year_ago)){
					
					#Only include revenue lost if customer property signed up in the last 12 months
					$total_new_revenue_lost += $job_cost;

                     #Property added within the last 12 months
					$lost_total_new_cancelled_servs[] = $value->property_id;
					
				}
                #prep sales data 
                if(!isset($properties[$value->property_id])){
                   $properties[$value->property_id] = ['customer_id'=>$value->customer_id];
                }
			}

            foreach($cancelled_properties as $$key=>$value){
                if(strtotime($value->property_created) > strtotime($one_year_ago)){
					#Property added within the last 12 months
					$lost_total_new_cancelled_props[] = $value->property_id;
				}
            }

			$report_data['total_cancelled_properties'] = count($data["AllCancelledProperty"]);
			$report_data['total_cancelled_services'] = count($all_cancelled);
			$report_data['total_cancelled_revenue'] = number_format($total_cancelled_revenue,2);
			$report_data['lost_total_new_cancelled_props'] = count($lost_total_new_cancelled_props);
            $report_data['lost_total_new_cancelled_servs'] = count($lost_total_new_cancelled_servs);
			$report_data['total_new_revenue_lost'] = number_format($total_new_revenue_lost,2);
		}
        #get total sales for customer properties
        $total_sales = 0;
        $total_sales_revenue = 0;
        if(!empty($properties)){
            foreach($properties as $property_id => $details){
                $programs = [];
                $getPropertyProgramJobs = $this->PropertyModel->getPropertyProgramJobs($property_id);
                if(!empty($getPropertyProgramJobs)){
                    $i = 0;
                    while(count($getPropertyProgramJobs) > $i){
                        if(is_array($programs) && !in_array($getPropertyProgramJobs[$i]->program_id,$programs)){
                            $programs[]=$getPropertyProgramJobs[$i]->program_id;
                        }
                        #get job cost
				        $job_cost = $this->getJobCost($getPropertyProgramJobs[$i]->job_id,$details['customer_id'],$property_id,$getPropertyProgramJobs[$i]->program_id);
				        $total_sales_revenue += $job_cost; 
                        
                        $i++;
                    }
                }
                $total_sales += count($programs);

            }
        }
        $report_data['total_sales'] = $total_sales;
        $report_data['total_sales_revenue'] = number_format($total_sales_revenue,2);
		$data['report_details'] = $report_data;
		$body =  $this->load->view('admin/report/ajax_cancel_report', $data, false);
	}

	public function downloadCancelReport(){
		$company_id = $this->session->userdata['company_id'];
        $user = $this->input->post('user');
		$start = !empty($this->input->post('date_from')) ? date('y-m-d H:i:s',strtotime($this->input->post('date_from'))) : '';
		$end = !empty($this->input->post('date_to')) ? date('y-m-d H:i:s',strtotime($this->input->post('date_to'))) : date('y-m-d H:i:s',strtotime('now'));
		$report_data = array(
			'total_cancelled_properties'=> 0,
			'total_cancelled_services'=> 0,
			'total_cancelled_revenue'=> 0,
            'lost_total_new_cancelled_props'=> 0,
            'lost_total_new_cancelled_servs'=> 0,
			'total_new_revenue_lost'=> 0,
			'total_sales'=> 0,
			'total_sales_revenue'=> 0
		);
		$query = array(
			'cancelled_services_tbl.company_id' => $this->session->userdata['company_id'],
		);
		
		if($user != 'all'){
			$query['cancelled_services_tbl.user_id'] = $user;
		}
		#get cancelled properties
		$cancelled_properties = $this->PropertyModel->getCancelledPropertyByDateRange(array('property_tbl.company_id'=>$company_id),$start,$end);

		#get cancelled services
		$all_cancelled = $this->CancelledModel->getCancelledServiceInfoDetailsBetween($query,$start,$end);

		if(!empty($all_cancelled)){
            $properties = [];
			$total_cancelled_properties = [];
			$total_cancelled_revenue = 0;
			$lost_total_new_cancelled_props = [];
            $lost_total_new_cancelled_servs = [];
			$total_new_revenue_lost = 0;
			$one_year_ago = date('Y-m-d', strtotime('-1 year'));
			foreach($all_cancelled as $key=>$value){
				$total_cancelled_properties[] = $value->property_id;
				
				#get job cost
				$job_cost = $this->getJobCost($value->job_id,$value->customer_id,$value->property_id,$value->program_id);
				$total_cancelled_revenue += $job_cost;
				
				if(strtotime($value->property_created) > strtotime($one_year_ago)){

					 #Property added within the last 12 months
                     $lost_total_new_cancelled_servs[] = $value->property_id;
					#Only include revenue lost if customer property signed up in the last 12 months
					$total_new_revenue_lost += $job_cost;
					
				}

                foreach($cancelled_properties as $$key=>$value){
                    if(strtotime($value->property_created) > strtotime($one_year_ago)){
                        #Property added within the last 12 months
                        $lost_total_new_cancelled_props[] = $value->property_id;
                    }
                }

                #prep sales data 
                if(!isset($properties[$value->property_id])){
                   $properties[$value->property_id] = ['customer_id'=>$value->customer_id];
                }
			}
			$report_data['total_cancelled_properties'] = count($cancelled_properties);
			$report_data['total_cancelled_services'] = count($all_cancelled);
			$report_data['total_cancelled_revenue'] = number_format($total_cancelled_revenue,2);
			$report_data['lost_total_new_cancelled_props'] = count($lost_total_new_cancelled_props);
            $report_data['lost_total_new_cancelled_servs'] = count($lost_total_new_cancelled_servs);
			$report_data['total_new_revenue_lost'] = number_format($total_new_revenue_lost,2);
		}
        #get total sales for customer properties
        $total_sales = 0;
        $total_sales_revenue = 0;
        if(!empty($properties)){
            foreach($properties as $property_id => $details){
                $programs = [];
                $getPropertyProgramJobs = $this->PropertyModel->getPropertyProgramJobs($property_id);
                if(!empty($getPropertyProgramJobs)){
                    $i = 0;
                    while(count($getPropertyProgramJobs) > $i){
                        if(is_array($programs) && !in_array($getPropertyProgramJobs[$i]->program_id,$programs)){
                            $programs[]=$getPropertyProgramJobs[$i]->program_id;
                        }
                        #get job cost
				        $job_cost = $this->getJobCost($getPropertyProgramJobs[$i]->job_id,$details['customer_id'],$property_id,$getPropertyProgramJobs[$i]->program_id);
				        $total_sales_revenue += $job_cost; 
                        
                        $i++;
                    }
                }
                $total_sales += count($programs);

            }
        }
        $report_data['total_sales'] = $total_sales;
        $report_data['total_sales_revenue'] = number_format($total_sales_revenue,2);
		$data['report_details'] = $report_data;
		
		if(is_array($data['report_details']) && count($data['report_details']) > 0){
			$delimiter = ",";
            $filename = "cancel_report_" . date('Y-m-d') . ".csv";
			
			#create a file pointer
            $f = fopen('php://memory', 'w');
     		
			#set column headers
			$fields = array('Total Cancelled Properties','Total Cancelled Services','Total Cancelled Revenue','New Customer Cancelled Properties','New Customer Cancelled Services','New Customer Revenue Lost','Total Sales','Total Sales Revenue');
			fputcsv($f, $fields, $delimiter);
				
			#output each row of the data, format line as csv and write to file pointer
			$lineData = $data['report_details'];
 
           	fputcsv($f, $lineData, $delimiter);
					
			#move back to beginning of file
			fseek($f, 0);

			#set headers to download file rather than displayed
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' .$filename. '";');

			#output all remaining data on a file pointer
			fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/cancelReport");
        }  
	}
    #customer growth report
	public function customerGrowthReport(){
        $company_id = $this->session->userdata['company_id'];
		$report_data = array(
            'date_range'=>'All Time',  //Should show year over year by default Columns
			'total_starting_properties'=> 0,
			'total_new_properties'=> 0,
			'total_cancels'=> 0,
			'total_cancelled_percent'=> 0,
			'total_cancels_vs_sales'=> 0,
			'total_ending_property_growth'=> 0 //((Ending Properties - Starting Properties)/starting properties)
		);
        $all_properties = $this->PropertyModel->getPropertyByDateRange(array('property_tbl.company_id'=>$company_id));
        
        $chart_data = [];
        $labels = [];
        $properties = [];
        $cancelled_properties = [];
        if(!empty($all_properties)){
            #get month count
            $first = strtotime($all_properties[0]->property_created);
            $last = strtotime('now'); //$last = strtotime(end($all_properties)->property_created);

            $first_year = date('Y', $first);
            $last_year = date('Y', $last);

            $first_month = date('m', $first);
            $end_month = date('m', $last);

            $diff = (($last_year - $first_year) * 12) + ($end_month - $first_month);
            $data['month_count'] = $diff;
            
            #create chart labels
            $starting_month = date('M Y',strtotime($all_properties[0]->property_created.' -1 month'));
            $labels[] = $starting_month;
            $chart_data[$starting_month] = 0;
            $chart_data_cancelled[$starting_month] = 0;
            $i = 0;
            while($i <= $data['month_count']){
                $month = date('M Y',strtotime($all_properties[0]->property_created.' +'.$i.' month'));
                $labels[] = $month;
                $chart_data[$month] = 0;
                $chart_data_cancelled[$month] = 0;
                $i++;
            }
            
            foreach($all_properties as $property){
                $property_created_label = date('M Y',strtotime($property->property_created));
                $chart_data[$property_created_label] += 1;
                $properties[$property->property_id] = ['property_created' => $property->property_created];
                
                if(isset($property->property_status) && $property->property_status == 0 && isset($property->property_cancelled)){
					#only include cancelled properties (not all inactive)
					$cancelled_properties[] = $property->property_id;
					$property_cancel_label = date('M Y',strtotime($property->property_cancelled));
					$chart_data_cancelled[$property_cancel_label] += 1;
				}
            }

            $report_data['total_new_properties'] = count($properties);
            $report_data['total_cancels'] = count($cancelled_properties);
            $cancel_percent = count($cancelled_properties)/count($properties) * 100;
			$cancel_v_sales = count($cancelled_properties)/count($properties);
            $report_data['total_cancelled_percent'] = number_format($cancel_percent,1);
            $report_data['total_cancels_vs_sales'] = number_format($cancel_v_sales,1);
            
        }else{
            #if no properties found...
            $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            if(isset($company_details->created_at) && $company_details->created_at != '0000-00-00 00:00:00'){
                $month = date('M Y',strtotime($company_details->created_at));
                $labels[] = $month;
                $chart_data[$month] = 0;
                $chart_data_cancelled[$month] = 0;
            }else{
                $month = date('M Y',strtotime('2020-01-01'));//created default based on first company date
                $labels[] = $month;
                $chart_data[$month] = 0;
                $chart_data_cancelled[$month] = 0;
            }
        }
         
        $existing_properties = 0; //default range = all time
        $ending_properties = $existing_properties + count($properties);
        $total_ending_property_growth = 100; // default growth for all time = 100% 
        $report_data['total_ending_property_growth'] = number_format($total_ending_property_growth,1); 

        $data['service_areas'] = $this->ServiceArea->getAllServiceAreaMarketing(array('company_id'=>$this->session->userdata['company_id']));
        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $this->session->userdata['company_id']));
        $data['servicelist'] = $this->RP->GetServiceName(array('jobs.company_id' => $this->session->userdata['company_id']));
		$data['report_details'] = $report_data;
        $data['labels'] = $labels;
        $data['chart_data'] = $chart_data;
        $data['cancel_chart_data'] = $chart_data_cancelled;
		$page["active_sidebar"] = "customerGrowthReport";
		$page["page_name"] = 'Customer Growth Analysis Report';
		$page["page_content"] = $this->load->view("admin/report/view_customer_growth_report", $data, TRUE);
		$this->layout->superAdminReportTemplateTable($page);
  	}
    
    public function ajaxDataForCustomerGrowthReport(){
        $company_id = $this->session->userdata['company_id'];
		
        $start = !empty($this->input->post('start_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))) : '';
		$end = !empty($this->input->post('end_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))) : date('Y-m-d H:i:s',strtotime('now'));
        
        if((strtotime('now') - strtotime($end)) < 86400){
            $end = date('Y-m-d H:i:s',strtotime('now'));
        }

        $comparison_start = !empty($this->input->post('comparison_start_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('comparison_start_date'))) : '';
		$comparison_end = !empty($this->input->post('comparison_end_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('comparison_end_date'))) : '';
        
        if($comparison_start && $comparison_end && (strtotime('now') - strtotime($comparison_end)) < 86400){
            $comparison_end = date('Y-m-d H:i:s',strtotime('now'));
        }
        // die(print_r($end)); 

        $chart_data = [];
        $chart_data_cancelled = [];
        $labels = [];
        $properties = [];
        //$cancelled_properties = [];
        $report_data = array(
            'date_range'=>'All',
			'total_starting_properties'=> 0,
			'total_new_properties'=> 0,
			'total_cancels'=> 0,
			'total_cancelled_percent'=> 0,
			'total_cancels_vs_sales'=> 0,
			'total_ending_property_growth'=> 0
		);
                
        #get existing properties
        $existing_properties = [];
        $existing_cancelled_properties = [];

        $PropertyConditionArray = array();

        $PropertyConditionArray['property_tbl.company_id'] = $company_id;
        if($this->input->post("rescom") != ""){
            $PropertyConditionArray['property_tbl.property_type'] = $this->input->post("rescom");
        }
        if($this->input->post("serviceArea") != "" && $this->input->post("serviceArea") != "null"){
            $PropertyConditionArray['property_area'] = $this->input->post("serviceArea");
        }

        if($this->input->post("assignProgram") != "" && $this->input->post("assignProgram") != "null"){
            $PropertyConditionArray['assignProgram'] = $this->input->post("assignProgram");
        }
        if($this->input->post("assignService") != "" && $this->input->post("assignService") != "null"){
            $PropertyConditionArray['assignService'] = $this->input->post("assignService");
        }

        if(!empty($start)){
            $existing_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,'',$start);
            $ConArray = $PropertyConditionArray;
            $ConArray['property_tbl.property_status'] = 0;
            $existing_cancelled_properties = $this->PropertyModel->getPropertyByDateRange($ConArray,'',$start);
        }
        $report_data['total_starting_properties'] = count($existing_properties);

        #get new properties
        $new_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,$start,$end);
        $report_data['total_new_properties'] = count($new_properties);
        
        #handle chart labels
        if(!empty($start)){
            $starting_month = date('M Y',strtotime($start));
            $first = strtotime($start);
        }elseif(!empty($new_properties) && isset($new_properties[0]->property_created)){
            $first = strtotime($new_properties[0]->property_created);
            $starting_month = date('M Y',strtotime($new_properties[0]->property_created));
        }else{
            #if no start date and no properties...use company created date
            $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            if(isset($company_details->created_at) && $company_details->created_at != '0000-00-00 00:00:00'){
                $starting_month = date('M Y',strtotime($company_details->created_at));;
                $first = strtotime($company_details->created_at);           
            }else{
                #created default based on first company date
                $starting_month = date('M Y',strtotime('2020-01-01'));
                $first = strtotime('2020-01-01'); 
            }
        }
        
        if(!empty($end)){
            $last = strtotime($end);
        }else{
            $last = strtotime('now');
        }
        $report_data['date_range'] = date('m/d/Y',$first)."-".date('m/d/Y',$last);
        $first_year = date('Y', $first);
        $last_year = date('Y', $last);

        $first_month = date('m', $first);
        $end_month = date('m', $last);

        $diff = (($last_year - $first_year) * 12) + ($end_month - $first_month);
        $data['month_count'] = $diff;
        
        #create chart labels
		$chart_data['Starting'] = $report_data['total_starting_properties'];
        $chart_data_cancelled['Starting'] = 0;
		$chart_data_cancelled['Starting'] += !empty($existing_cancelled_properties) ? count($existing_cancelled_properties) : 0;
        $labels[] = 'Starting';
        $i = 0;
        while($i <= $data['month_count']){
            $month = date('M Y',strtotime($starting_month.' +'.$i.' month'));
            $labels[] = $month;
            $chart_data[$month] = 0;
            $chart_data_cancelled[$month] = 0;
            $i++;
        }
	
        #handle new properties
        $new_cancelled = [];
        //$total_cancels = 0;
        if(!empty($new_properties)){
            foreach($new_properties as $new){
                $property_created_label = date('M Y',strtotime($new->property_created));
                $chart_data[$property_created_label] += 1;
				
				if(isset($new->property_status) && $new->property_status == 0 && isset($new->property_cancelled)){
					#only include cancelled properties (not all inactive)
					$new_cancelled[] = $new->property_id;
					$property_cancel_label = date('M Y',strtotime($new->property_cancelled));
					$chart_data_cancelled[$property_cancel_label] += 1;
				}
            }
//			$overall_cancel_percent = count($existing_cancelled_properties) + count($new_cancelled) / count($new_cancelled);
            $report_data['total_cancels'] = count($new_cancelled);
            $cancel_percent = count($new_cancelled)/count($new_properties) * 100;
			$cancel_v_sales = count($new_cancelled)/count($new_properties);
            $report_data['total_cancelled_percent'] = number_format($cancel_percent,1);
            $report_data['total_cancels_vs_sales'] = number_format($cancel_v_sales,1);
        }

        #need to add data for total ending property growth below is wip
        $ending_properties = count($existing_properties) + count($new_properties);//.....does the ending properties exclude cancelled?
        if(count($existing_properties)>0){
            $total_ending_property_growth = (($ending_properties - count($existing_properties))/count($existing_properties))*100;
        }else{
            $total_ending_property_growth = 100; // default growth for all time if no start date = 100% 
        }

        $report_data['total_ending_property_growth'] = number_format($total_ending_property_growth,1); 
		//die(print_r($report_data['total_new_properties']));
		$data['report_details'] = $report_data;
        $data['labels'] = $labels;
        $data['chart_data'] = $chart_data;
        $data['cancel_chart_data'] = $chart_data_cancelled;
        
        #handle comparison data
        $comparison_chart_data = [];
        $comparison_properties = [];
        $comparison_chart_data_cancelled = [];
        $comparison_chart_data_cancelled['Starting'] = 0;
        $combined_labels = [];

        //$comparison_cancelled_properties = [];
        $comparison_data = array(
            'date_range'=>'All',
			'total_starting_properties'=> 0,
			'total_new_properties'=> 0,
			'total_cancels'=> 0,
			'total_cancelled_percent'=> 0,
			'total_cancels_vs_sales'=> 0,
			'total_ending_property_growth'=> 0
		);


                
        #get existing properties
        $comparison_existing_properties = [];
        $comparison_existing_cancelled_properties = [];
        unset($PropertyConditionArray['assignProgram']);
        unset($PropertyConditionArray['assignService']);

        if($this->input->post("assignProgramCompare") != "" && $this->input->post("assignProgramCompare") != "null"){
            $PropertyConditionArray['assignProgram'] = $this->input->post("assignProgramCompare");
        }
        if($this->input->post("assignServiceCompare") != "" && $this->input->post("assignServiceCompare") != "null"){
            $PropertyConditionArray['assignService'] = $this->input->post("assignServiceCompare");
        }


        if(!empty($comparison_start)){
            if(empty($comparison_end)){
                $comparison_end = date('Y-m-d H:i:s',strtotime('now'));
            }
            $comparison_existing_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,'',$comparison_start);

            $ConArray = $PropertyConditionArray;
            $ConArray['property_tbl.property_status'] = 0;
            
            $comparison_existing_cancelled_properties = $this->PropertyModel->getPropertyByDateRange($ConArray,'',$comparison_start);
        }
        $comparison_data['total_starting_properties'] = count($comparison_existing_properties);


        $comparison_new_properties = new stdClass();
        #get new properties
        if(!empty($comparison_start && $comparison_end)){
        $comparison_new_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,$comparison_start,$comparison_end);
        $comparison_data['total_new_properties'] = count($comparison_new_properties);
        }
        
        #handle chart labels
        if(!empty($comparison_start && $comparison_end)){
            $comparison_starting_month = date('M Y',strtotime($comparison_start));
            $comparison_first = strtotime($comparison_start);
        } else if(!empty($comparison_end)){
            if(!empty($comparison_new_properties) && isset($comparison_new_properties[0]->property_created)){
                $comparison_first = strtotime($comparison_new_properties[0]->property_created);
                $comparison_starting_month = date('M Y',strtotime($comparison_new_properties[0]->property_created));
            } else {
                #if no start date and no properties...use company created date
                $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                if(isset($company_details->created_at) && $company_details->created_at != '0000-00-00 00:00:00'){
                    $comparison_starting_month = date('M Y',strtotime($company_details->created_at));;
                    $comparison_first = strtotime($company_details->created_at);           
                }else{
                    #created default based on first company date
                    $comparison_starting_month = date('M Y',strtotime('2020-01-01'));
                    $comparison_first = strtotime('2020-01-01'); 
                }
            }
        }
        
        if(!empty($comparison_end)){
            $comparison_last = strtotime($comparison_end);
            if((strtotime('now') - $comparison_last) < 86400){
                $comparison_last = strtotime('now');
            }
        }else{
            $comparison_last = strtotime('now');
        }
        if(!isset($comparison_first)) {
            $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            $comparison_first = strtotime($company_details->created_at);
            $comparison_starting_month = date('M Y',strtotime($company_details->created_at));
        }
        $comparison_data['date_range'] = date('m/d/Y',$comparison_first)."-".date('m/d/Y',$comparison_last);
        $comparison_first_year = date('Y', $comparison_first);
        $comparison_last_year = date('Y', $comparison_last);

        $comparison_first_month = date('m', $comparison_first);
        $comparison_end_month = date('m', $comparison_last);

        $comparison_diff = (($comparison_last_year - $comparison_first_year) * 12) + ($comparison_end_month - $comparison_first_month);
        $data['comparison_month_count'] = $comparison_diff;
        
        #create chart labels
		$comparison_chart_data['Starting'] = $comparison_data['total_starting_properties'];
		$comparison_chart_data_cancelled['Starting'] += !empty($comparison_existing_cancelled_properties) ? count($comparison_existing_cancelled_properties) : 0;
        $comparison_labels[] = 'Starting';
        $i = 0;
        while($i <= $data['comparison_month_count']){
            $comparison_month = date('M Y',strtotime($comparison_starting_month.' +'.$i.' month'));
            $comparison_labels[] = $comparison_month;
            $comparison_chart_data[$comparison_month] = 0;
            $comparison_chart_data_cancelled[$month] = 0;
            $i++;
        }
        #handle comparison new properties
        $comparison_new_cancelled = [];
        //$comparison_total_cancels = 0;
        if(!empty($comparison_new_properties)){
            foreach($comparison_new_properties as $comparison_new){
                $comparison_property_created_label = date('M Y',strtotime($comparison_new->property_created));
                $comparison_chart_data[$comparison_property_created_label] += 1;
				
				if(isset($comparison_new->property_status) && $comparison_new->property_status == 0 && isset($comparison_new->property_cancelled)){
					#only include cancelled properties (not all inactive)
					$comparison_new_cancelled[] = $comparison_new->property_id;
					$comparison_property_cancel_label = date('M Y',strtotime($comparison_new->property_cancelled));
					$comparison_chart_data_cancelled[$comparison_property_cancel_label] += 1;
				}
            }
//			$overall_cancel_percent = count($existing_cancelled_properties) + count($new_cancelled) / count($new_cancelled);
            $comparison_data['total_cancels'] = count($comparison_new_cancelled);

            $comparison_new_properties = json_encode($comparison_new_properties);
            $comparison_new_properties = json_decode($comparison_new_properties, true);

            if(count($comparison_new_properties) > 0){
                $comparison_cancel_percent = count($comparison_new_cancelled)/count($comparison_new_properties) * 100;
            }else{
                $comparison_cancel_percent = 0;
            }

            if(count($comparison_new_properties) > 0){
    			$comparison_cancel_v_sales = count($comparison_new_cancelled)/count($comparison_new_properties);
            }else{
                $comparison_cancel_v_sales = 0;
            }
            $comparison_data['total_cancelled_percent'] = number_format($comparison_cancel_percent,1);
            $comparison_data['total_cancels_vs_sales'] = number_format($comparison_cancel_v_sales,1);
        }

        #need to add data for total ending property growth below is wip
        $comparison_ending_properties = count($comparison_existing_properties) + count($comparison_new_properties);//.....does the ending properties exclude cancelled?
        if(count($comparison_existing_properties)>0){
            $comparison_total_ending_property_growth = (($comparison_ending_properties - count($comparison_existing_properties))/count($comparison_existing_properties))*100;
        }else{
            $comparison_total_ending_property_growth = 100; // default growth for all time if no start date = 100% 
        }

        $comparison_data['total_ending_property_growth'] = number_format($comparison_total_ending_property_growth,1); 
		//die(print_r($report_data['total_new_properties']));
		$data['comparison_details'] = !empty($comparison_start) && !empty($comparison_end) ? $comparison_data : [];
        $data['comparison_labels'] = $comparison_labels;
        $data['comparison_chart_data'] = $comparison_chart_data;
        $data['comparison_cancel_chart_data'] = $comparison_chart_data_cancelled;

		$body =  $this->load->view('admin/report/ajax_customer_growth_report', $data, false);
  	}
    public function downloadCustomerGrowthReport(){
		$company_id = $this->session->userdata['company_id'];
		$start = !empty($this->input->post('start_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))) : '';
		$end = !empty($this->input->post('end_date')) ? date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))) : date('Y-m-d H:i:s',strtotime('now'));
        $chart_data = [];
        $chart_data_cancelled = [];
        $chart_data_cancelled['Starting'] = 0;
        $labels = [];
        $properties = [];
        $report_data = array(
            'date_range'=>'all',
			'total_starting_properties'=> 0,
			'total_new_properties'=> 0,
			'total_cancels'=> 0,
			'total_cancelled_percent'=> 0,
			'total_cancels_vs_sales'=> 0,
			'total_ending_property_growth'=> 0
		);
        #get existing properties
        $existing_properties = [];
        $existing_cancelled_properties = [];

        $PropertyConditionArray['property_tbl.company_id'] = $company_id;
        if($this->input->post("rescom") != ""){
            $PropertyConditionArray['property_tbl.property_type'] = $this->input->post("rescom");
        }
        if($this->input->post("serviceArea") != ""){
            $PropertyConditionArray['property_area'] = implode(",", $this->input->post("serviceArea"));
        }

        if($this->input->post("assignProgram") != ""){
            $PropertyConditionArray['assignProgram'] = implode(",", $this->input->post("assignProgram"));
        }

        if(!empty($start)){
            $existing_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,'',$start);
            $existing_cancelled_properties = $this->PropertyModel->getPropertyByDateRange(array($PropertyConditionArray,'property_tbl.property_status'=>0),'',$start);
        }
        $report_data['total_starting_properties'] = count($existing_properties);

        #get new properties
        $new_properties = $this->PropertyModel->getPropertyByDateRange($PropertyConditionArray,$start,$end);
        $report_data['total_new_properties'] = count($new_properties);
        
        #handle chart labels
        if(!empty($start)){
            $starting_month = date('M Y',strtotime($start));
            $first = strtotime($start);
        }elseif(!empty($new_properties) && isset($new_properties[0]->property_created)){
            $first = strtotime($new_properties[0]->property_created);
            $starting_month = date('M Y',strtotime($new_properties[0]->property_created));
        }else{
            #if no start date and no properties...use company created date
            $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
            if(isset($company_details->created_at) && $company_details->created_at != '0000-00-00 00:00:00'){
                $starting_month = date('M Y',strtotime($company_details->created_at));;
                $first = strtotime($company_details->created_at);           
            }else{
                #created default based on first company date
                $starting_month = date('M Y',strtotime('2020-01-01'));
                $first = strtotime('2020-01-01'); 
            }
        }
        
        if(!empty($end)){
            $last = strtotime($end);
        }else{
            $last = strtotime('now');
        }
        $report_data['date_range'] = date('m/d/Y',$first)."-".date('m/d/Y',$last);
        $first_year = date('Y', $first);
        $last_year = date('Y', $last);

        $first_month = date('m', $first);
        $end_month = date('m', $last);

        $diff = (($last_year - $first_year) * 12) + ($end_month - $first_month);
        $data['month_count'] = $diff;
        
        #create chart labels
		$chart_data['Starting'] = $report_data['total_starting_properties'];
		$chart_data_cancelled['Starting'] += !empty($existing_cancelled_properties) ? count($existing_cancelled_properties) : 0;
        $labels[] = 'Starting';
        $i = 0;
        while($i <= $data['month_count']){
            $month = date('M Y',strtotime($starting_month.' +'.$i.' month'));
            $labels[] = $month;
            $chart_data[$month] = 0;
            $chart_data_cancelled[$month] = 0;
            $i++;
        }
	
        #handle new properties
        $new_cancelled = [];
        //$total_cancels = 0;
        if(!empty($new_properties)){
            foreach($new_properties as $new){
                $property_created_label = date('M Y',strtotime($new->property_created));
                $chart_data[$property_created_label] += 1;
				
				if(isset($new->property_status) && $new->property_status == 0 && isset($new->property_cancelled)){
					#only include cancelled properties (not all inactive)
					$new_cancelled[] = $new->property_id;
					$property_cancel_label = date('M Y',strtotime($new->property_cancelled));
					$chart_data_cancelled[$property_cancel_label] += 1;
				}
            }
            $report_data['total_cancels'] = count($new_cancelled);
            $cancel_percent = count($new_cancelled)/count($new_properties) * 100;
			$cancel_v_sales = count($new_cancelled)/count($new_properties);
            $report_data['total_cancelled_percent'] = number_format($cancel_percent,1);
            $report_data['total_cancels_vs_sales'] = number_format($cancel_v_sales,1);
        }

        $ending_properties = count($existing_properties) + count($new_properties);//.....does the ending properties exclude cancelled?
        if(count($existing_properties)>0){
            $total_ending_property_growth = (($ending_properties - count($existing_properties))/count($existing_properties))*100;
        }else{
            $total_ending_property_growth = 100; // default growth for all time if no start date = 100% 
        }
		$report_data['total_ending_property_growth'] = number_format($total_ending_property_growth,1);
        $data['report_details'] = $report_data;
		if(is_array($data['report_details']) && count($data['report_details']) > 0){
			$delimiter = ",";
            $filename = "customer_growth_report_" . date('Y-m-d') . ".csv";
			
			#create a file pointer
            $f = fopen('php://memory', 'w');
     		
			#set column headers
			$fields = array('Date Range','Total Starting Properties','Total New Properties','Total Cancels','Cancel %','# of Cancels/Total # New Sales','Total Ending Properties Growth Rate');
			fputcsv($f, $fields, $delimiter);
				
			#output each row of the data, format line as csv and write to file pointer
			$lineData = $data['report_details'];
 
           	fputcsv($f, $lineData, $delimiter);
					
			#move back to beginning of file
			fseek($f, 0);

			#set headers to download file rather than displayed
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' .$filename. '";');

			#output all remaining data on a file pointer
			fpassthru($f);

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/customerGrowthReport");
        } 
    }
	public function getJobCost($job_id,$customer_id,$property_id,$program_id){
		#check for estimate price override
		$estimate_price_override = GetOneEstimateJobPriceOverride(array('customer_id' => $customer_id, 'property_id' => $property_id, 'program_id' => $program_id, 'job_id' => $job_id));
        if($estimate_price_override){
             $job_cost = $estimate_price_override->price_override;
        }else{
			#check property program assign for price override 
			$priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));
			if($priceOverrideData->is_price_override_set == 1) {
                $job_cost =  $priceOverrideData->price_override;
            }else{
				#calculate job cost
				$jobDetails = $this->JobModel->getOneJob(array('job_id' => $job_id));
				$propertyDetails = $this->PropertyModel->getOnePropertyDetail($property_id);
				#get property difficulty level
				$setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
				if (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 2) {
					$difficulty_multiplier = $setting_details->dlmult_2;
				} elseif (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 3) {
					$difficulty_multiplier = $setting_details->dlmult_3;
				} else {
					$difficulty_multiplier = $setting_details->dlmult_1;
				}
				#get base fee 
				if (isset($jobDetails->base_fee_override)) {
					$base_fee = $jobDetails->base_fee_override;
				} else {
					$base_fee = $setting_details->base_service_fee;
				}
				#calculate cost per sf
				$cost_per_sqf = $base_fee + ($jobDetails->job_price * $propertyDetails->yard_square_feet * $difficulty_multiplier) / 1000;
				#get min. service fee
				if (isset($jobDetails->min_fee_override)) {
					$min_fee = $jobDetails->min_fee_override;
				} else {
					$min_fee = $setting_details->minimum_service_fee;
				}
				#Compare cost per sf with min service fee
				if ($cost_per_sqf > $min_fee) {
					$job_cost = $cost_per_sqf;
				} else {
					$job_cost = $min_fee;
				}
			}
		}
		return $job_cost;
	}

    ## Material Resource Planning Report
    public function MaterialResourcePlanningReport(){  
        //get the posts data
        $company_id = $this->session->userdata['company_id'];

        $where = array('company_id' => $company_id);
        $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
        // die(print_r($data['joblist']));
        $grass_type = '';
        $job_ids = [];
        foreach($joblist as $job){
            $job = $job->job_id;
            array_push($job_ids, $job);
        }
        // die(print_r($job_ids));
        #### Code from Ajax function below
        if($job_ids){
           
            $allProductIDs = []; 
            $allProductNames = []; 
            $allProductInfo = []; 
            foreach($job_ids as $jd){
                $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd, $grass_type);
                // die(print_r($this->db->last_query()));
                // die(print_r($product_arr));
                if(!empty($product_arr)){
                    array_push($allProductInfo,$product_arr);
                }
            }
            // die(print_r($this->db->last_query()));
            // die(print_r($allProductInfo));
            if(!empty($allProductInfo)){
                $info_arr = array();
                $sqft_arr = array();
                $estimate_product_arr = array();
                $unit_arr = array();
                $qty_arr = array();
                foreach($allProductInfo as $info){
                    if(!empty($info)){
                        foreach($info as $inf){
                            if(isset($inf->product_id)){
                                $prod = $inf->product_id;
                                if(!isset($info_arr[$prod])){
                                    $info_arr[$prod] = 0;
                                }
                                $info_arr[$prod]++; 
                            }
                            if(isset($inf->yard_square_feet)){
                                
                                    if(!isset($sqft_arr[$prod])){
                                        $sqft_arr[$prod] = $inf->yard_square_feet;
                                        $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    } else {
                                        $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                        $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    }
                            }
                                
                            if(!isset($unit_arr[$prod])){
                                $unit_arr[$prod] = $inf->application_unit;
                            }
                            if(!isset($qty_arr[$prod])){
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            } else {
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            }

                            if(!in_array($inf->product_id, $allProductIDs)){
                                array_push($allProductIDs, $inf->product_id);
                                array_push($allProductNames, $inf->product_name);
                            }
                        }

                            
                    }
                }    
            }
            // die(print_r($allProductInfo));
            
            $data = [];
            $product_objs = [];
            if(!empty($allProductIDs)){
                foreach($allProductIDs as $pid){
                    $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                    $outstanding_ct = $info_arr[$pid];
                    $outstanding_sqft = $sqft_arr[$pid];

                    $product_obj = new stdClass();
                    $product_obj->product_name = $product_name;
                    $product_obj->outstanding_ct = $outstanding_ct;
                    $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                    $product_obj->product_needed =  number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('(s', $unit_arr[$pid])[0] . '(s)';

                    array_push($product_objs, $product_obj);
                }
            }
            $data['product_objs'] = $product_objs;
           
        }
        // die(print_r($data['product_objs']));
        #### End Code from Ajax function below
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['joblist'] = $joblist;
        // $data['setting_details'] = $this->CompanyModel->getOneCompany($where_arr);
        // $data['users'] = $this->Administrator->getAllAdmin($where_arr);
        // die(print_r($data));
        $page["active_sidebar"] = "materialResourcePlanningReport";
        $page["page_name"] = 'Material Resource Planning Report';
        $page["page_content"] = $this->load->view("admin/report/view_material_resource_planning_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }


    function ajaxMaterialResourcePlanningData(){        
        $company_id = $this->session->userdata['company_id'];
        //set conditions for search
        $job_list = $this->input->post('job_list');
        $grass_type = $this->input->post('grass_type');
       
        if(isset($job_list) && !empty($job_list) &&  explode(',', $job_list)[0] != 'null'){
            $job_arr = explode(',', $job_list);
       
            $joblist = $this->ProgramModel->getJobListWhereIn('job_id', $job_arr);
        // die(print_r($joblist));
            if($joblist){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($joblist as $jd){
                    $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd->job_id, $grass_type);
                    // die(print_r($this->db->last_query()));
                    // die(print_r($product_arr));
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if($grass_type != ''){
                                    
                                    if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                        if(isset($inf->front_yard_grass)){
                                            print('Isset Front');
                                            if($inf->front_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                }
                                            }
                                        }
        
                                        if(isset($inf->back_yard_grass)){
                                            print('Isset Back');
                                            if($inf->back_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                }
                                            }
                                        }
    
                                    }
                                    else if(isset($inf->total_yard_grass)){
                                        
                                                                 
                                        if($inf->total_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            } else {
                                                $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                                $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            }
                                        }
                                    }
                                }
                                else{
                                    if(isset($inf->yard_square_feet)){
                                
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }   
                }
                // die(print_r($allProductInfo));
                
                
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];

                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed =  number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('(s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }
            
            $body =  $this->load->view('admin/report/ajax_material_resource_planning_report', $data, false);

            echo $body;
            
        } else if($grass_type != '') {
            $where = array('company_id' => $company_id);
            $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
            
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }

            if($job_ids){
           
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd, $grass_type);
                    // die(print_r($this->db->last_query()));
                    // die(print_r($product_arr));
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                // die(print_r($this->db->last_query()));
                // die(print_r($allProductInfo));
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                    if(isset($inf->front_yard_grass)){
                                        print('Isset Front');
                                        if($inf->front_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->front_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->front_yard_square_feet);
                                            }
                                        }
                                    }
    
                                    if(isset($inf->back_yard_grass)){
                                        print('Isset Back');
                                        if($inf->back_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            }
                                        }
                                    }

                                }
                                else if(isset($inf->total_yard_grass)){
                                    
                                                             
                                    if($inf->total_yard_grass == $grass_type){
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }
                }      
                // die(print_r($allProductInfo));
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];

                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed =  number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('(s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }

            $where_arr = array('company_id' =>$this->session->userdata['company_id']);
            $data['joblist'] = $joblist;
            $body =  $this->load->view('admin/report/ajax_material_resource_planning_report', $data, false);

            echo $body;

        } else {
            //get the posts data
        
        $where = array('company_id' => $company_id);
        $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
        // die(print_r($data['joblist']));
        $grass_type = '';
        $job_ids = [];
        foreach($joblist as $job){
            $job = $job->job_id;
            array_push($job_ids, $job);
        }
        // die(print_r($job_ids));
        #### Code from Ajax function below
        if($job_ids){
           
            $allProductIDs = []; 
            $allProductNames = []; 
            $allProductInfo = []; 
            foreach($job_ids as $jd){
                $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd, $grass_type);
                // die(print_r($this->db->last_query()));
                // die(print_r($product_arr));
                if(!empty($product_arr)){
                    array_push($allProductInfo,$product_arr);
                }
            }
            // die(print_r($this->db->last_query()));
            // die(print_r($allProductInfo));
            if(!empty($allProductInfo)){
                $info_arr = array();
                $sqft_arr = array();
                $estimate_product_arr = array();
                $unit_arr = array();
                $qty_arr = array();
                foreach($allProductInfo as $info){
                    if(!empty($info)){
                        foreach($info as $inf){
                            if(isset($inf->product_id)){
                                $prod = $inf->product_id;
                                if(!isset($info_arr[$prod])){
                                    $info_arr[$prod] = 0;
                                }
                                $info_arr[$prod]++; 
                            }
                            if(isset($inf->yard_square_feet)){
                                
                                                         
                                if(!isset($sqft_arr[$prod])){
                                    $sqft_arr[$prod] = $inf->yard_square_feet;
                                    $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                } else {
                                    $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                    $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                }
                            }
                                
                            if(!isset($unit_arr[$prod])){
                                $unit_arr[$prod] = $inf->application_unit;
                            }
                            if(!isset($qty_arr[$prod])){
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            } else {
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            }

                            if(!in_array($inf->product_id, $allProductIDs)){
                                array_push($allProductIDs, $inf->product_id);
                                array_push($allProductNames, $inf->product_name);
                            }
                        }

                            
                    }
                }     
            }
            // die(print_r($allProductInfo));
            
            $data = [];
            $product_objs = [];
            if(!empty($allProductIDs)){
                foreach($allProductIDs as $pid){
                    $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                    $outstanding_ct = $info_arr[$pid];
                    $outstanding_sqft = $sqft_arr[$pid];

                    $product_obj = new stdClass();
                    $product_obj->product_name = $product_name;
                    $product_obj->outstanding_ct = $outstanding_ct;
                    $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                    $product_obj->product_needed =  number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('(s', $unit_arr[$pid])[0] . '(s)';

                    array_push($product_objs, $product_obj);
                }
            }
            $data['product_objs'] = $product_objs;
           
        }
        // die(print_r($data['product_objs']));
        #### End Code from Ajax function below
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['joblist'] = $joblist;
        $body =  $this->load->view('admin/report/ajax_material_resource_planning_report', $data, false);

        echo $body;
        }

    }

    public function calculateProductNeeded($per, $amount, $sqft){
        $calculation = 0;
        $footage = 0;
        if($per == '1 Acre'){
            $footage = (int)$sqft/43560;
        } else {
            $footage = (int)$sqft/1000;
        }

        $calculation = $amount * $footage;
         
        return number_format((float)$calculation, 2, '.', '');
    }

    public function downloadMaterialResourceCsv(){
        $data = $this->input->post();
       
        if(isset($data['material_job_tmp']) &&!empty($data['material_job_tmp'])){

            $job_arr = $data['material_job_tmp'];
        } 
       
        $grass_type = $data['grass_type'];
        $company_id = $this->session->userdata['company_id'];
       
        if(isset($job_arr) && !empty($job_arr)){
            $joblist = $this->ProgramModel->getJobListWhereIn('job_id', $job_arr);
           
            if($joblist){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($joblist as $jd){
                    $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd->job_id, $grass_type);
                   
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if($grass_type != ''){
                                    
                                    if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                        if(isset($inf->front_yard_grass)){
                                            print('Isset Front');
                                            if($inf->front_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                }
                                            }
                                        }
        
                                        if(isset($inf->back_yard_grass)){
                                            print('Isset Back');
                                            if($inf->back_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                }
                                            }
                                        }
    
                                    }
                                    else if(isset($inf->total_yard_grass)){
                                        
                                                                 
                                        if($inf->total_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            } else {
                                                $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                                $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            }
                                        }
                                    }
                                } else{
                                    if(isset($inf->yard_square_feet)){
                                
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }     
                }

                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }
            
        } else if($grass_type != '') {

           $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));

            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }
            
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }

            if($job_ids){
           
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd->job_id, $grass_type);
                    
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
               
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                    if(isset($inf->front_yard_grass)){
                                        print('Isset Front');
                                        if($inf->front_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            }
                                        }
                                    }
    
                                    if(isset($inf->back_yard_grass)){
                                        print('Isset Back');
                                        if($inf->back_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            }
                                        }
                                    }

                                }
                                else if(isset($inf->total_yard_grass)){
                                    
                                                             
                                    if($inf->total_yard_grass == $grass_type){
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }     
                }
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                       
                        
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                       
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }

        } else {
          
            $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));

            $grass_type = '';
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }
            
            #### Code from Ajax function below
            if($job_ids){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->DashboardModel->getUnassignJobsWhere($jd, $grass_type);
                    
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->yard_square_feet)){
                                    
                                                             
                                    if(!isset($sqft_arr[$prod])){
                                        $sqft_arr[$prod] = $inf->yard_square_feet;
                                        $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    } else {
                                        $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                        $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }      
                }
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        
                        
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            
            }
        }
        
        if($product_objs){
  
            $delimiter = ",";
            $filename = "material_resource_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Product Names','Outstanding Services','Outstanding Square Feet','Estimate Amount of Product Needed');
           
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
           
            foreach ($product_objs as $key => $value) {
               $lineData = array($value->product_name,$value->outstanding_ct,$value->outstanding_sqft, $value->product_needed,$value->onhand,$value->ordered,$value->overage);
    
                fputcsv($f, $lineData, $delimiter);
               
            }
    
            //move back to beginning of file
            fseek($f, 0);
            
            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
              //  $pathName =  "down/".$filename;
            header('Content-Disposition: attachment; filename="' .$filename. '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);
    
          } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/Reports/MaterialResourcePlanningReport");
        }   
    }


    #marketing report
	public function marketingCustomerDataReport(){
		$data['user_details'] = $this->Administrator->getAllAdminMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['service_areas'] = $this->ServiceArea->getAllServiceAreaMarketing(array('company_id'=>$this->session->userdata['company_id']));
        $data['source_list'] = $this->SourceModel->getAllSourceMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['program_details'] = $this->ProgramModel->get_all_program_marketing(array('company_id' => $this->session->userdata['company_id']));
        $data['taglist'] = $this->PropertyModel->getTagsList(array('company_id' => $this->session->userdata['company_id']));
        $data['zip_codes'] = $this->PropertyModel->getAllZipCodes(array('company_id' => $this->session->userdata['company_id']));
        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasonsMarketing($this->session->userdata['company_id']);
        $data['outstanding_services'] = $this->DashboardModel->getWorkReportOutstanding(array('property_tbl.company_id' => $this->session->userdata['company_id']));
        //$data['customers'] = $this->CustomerModel->get_all_customer_marketing(array('company_id'=>$this->session->userdata['company_id']));
        
        $source = [];
        foreach($data['user_details'] as $user){
            $source = (object) array(
                'source_name' => $user->user_first_name.' '.$user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            ) ;
            array_push( $data['source_list'], $source);
        }
		$report_data = array();
        
		$data['report_details'] = $report_data;
		$page["active_sidebar"] = "marketingCustomerDataReport";
		$page["page_name"] = 'Marketing / Customer Data Report';
		$page["page_content"] = $this->load->view("admin/report/view_marketing_report", $data, TRUE);
		$this->layout->superAdminReportTemplateTable($page);
  	}


	public function ajaxDataForMarketingCustomerDataReport(){
		$company_id = $this->session->userdata['company_id'];
        // making arrays to set up any filters brought in
        $filters_array = array("sources"=>explode(',',$this->input->post('sources_multi')));
        $filters_array["lead_start_date_start"] = $this->input->post('lead_start_date_start');
        $filters_array["lead_start_date_end"] = $this->input->post('lead_start_date_end');
        $filters_array["revenue_total_start"] = $this->input->post('revenue_total_start');
        $filters_array["revenue_total_end"] = $this->input->post('revenue_total_end');
        $filters_array["sale_start_date_start"] = $this->input->post('sale_start_date_start');
        $filters_array["sale_start_date_end"] = $this->input->post('sale_start_date_end');
        // we need to see if we need to set the end date or not
        if($filters_array["sale_start_date_end"] != "" && $filters_array["sale_start_date_start"] == "") {
            $date_company_created_at = $this->RP->get_company_created_at_date($this->session->userdata['company_id']);
            $filters_array["sale_start_date_start"] = $date_company_created_at->created_at;
        }
        
        $filters_array["cancelation_date_start"] = $this->input->post('cancelation_date_start');
        $filters_array["cancelation_date_end"] = $this->input->post('cancelation_date_end');
        $filters_array["tags_multi"] = explode(',', $this->input->post('tags_multi'));
        $filters_array["last_date_program_start"] = $this->input->post('last_date_program_start');
        $filters_array["last_date_program_end"] = $this->input->post('last_date_program_end');
        $filters_array["service_areas_multi"] = explode(',', $this->input->post('service_areas_multi'));
        $filters_array["res_or_com"] = $this->input->post('res_or_com');
        $filters_array["preservice_notifications_multi"] = explode(',', $this->input->post('preservice_notifications_multi'));
        $filters_array["zip_codes_multi"] = explode(',', $this->input->post('zip_codes_multi'));
        $filters_array["cancel_reasons_multi"] = explode(',', $this->input->post('cancel_reasons_multi'));
        $filters_array["outstanding_services_multi"] = explode(',', $this->input->post('outstanding_services_multi'));
        $filters_array["customer_status"] = $this->input->post('customer_status');
        $filters_array["estimate_accpeted"] = $this->input->post('estimate_accpeted');
        $filters_array["all_tags"] = $this->input->post('all_tags');
        $filters_array["all_programs"] = $this->input->post('all_programs');
        $filters_array["all_pre_service"] = $this->input->post('all_pre_service');
        $filters_array["all_outstanding"] = $this->input->post('all_outstanding');

        $filters_array["front_yard_grass"] = $this->input->post('front_yard_grass');
        $filters_array["back_yard_grass"] = $this->input->post('back_yard_grass');
        $filters_array["total_yard_grass"] = $this->input->post('total_yard_grass');

        $HowManyServiceCompleted = $this->input->post('serviceCompleted');
        $HowManyServiceCompletedEnd = $this->input->post('serviceCompletedEnd');
        $MultiplePrograms = explode(',', $this->input->post('programs_multi'));

        $ExcludedPrograms = explode(",", $this->input->post("customerExclude"));

        
		$data['user_details'] = $this->Administrator->getAllAdminMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['source_list'] = $this->SourceModel->getAllSourceMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['customers'] = $this->CustomerModel->get_all_customer_marketing(array('company_id'=>$this->session->userdata['company_id']));
        $source = [];
        foreach($data['user_details'] as $user){
            $source = (object) array(
                'source_name' => $user->user_first_name.' '.$user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            ) ;
            array_push( $data['source_list'], $source);
        }
		#not seeing specific role for sales rep so getting all users 
		$report_data = array();
        foreach($data['customers'] as $customer) {
            $IsContine = true;
            if($HowManyServiceCompleted != ""){
                $IdString = "property_program_assign.program_id IN (";
                foreach($MultiplePrograms as $TcID){
                    $IdString .= "'".$TcID."',";
                }
                $IdString = substr($IdString, 0, -1);
                $IdString .= ")";

                $ServicesByCustomer = $this->DashboardModel->getCustomerAllServicesForReport(array('property_tbl.company_id' => $company_id, "customers.customer_id" => $customer->customer_id), $IdString);

                $TotalServiceCompleted = 0;

                foreach($ServicesByCustomer as $SBC){
                    if($SBC->is_job_mode == 1){
                        $TotalServiceCompleted++;
                    }
                }

                if($TotalServiceCompleted >= $HowManyServiceCompleted && $TotalServiceCompleted <= $HowManyServiceCompletedEnd){
                    $IsContine = false;
                }
            }

            if($HowManyServiceCompleted != "" && $IsContine){
                continue;
            }

            $IsContine = true;
            if(count($ExcludedPrograms) > 0){
                $IdString = "property_program_assign.program_id IN (";
                foreach($ExcludedPrograms as $TcID){
                    $IdString .= "'".$TcID."',";
                }
                $IdString = substr($IdString, 0, -1);
                $IdString .= ")";

                $ServicesByCustomer = $this->DashboardModel->getCustomerAllServicesForReport(array('property_tbl.company_id' => $company_id, "customers.customer_id" => $customer->customer_id), $IdString);

                if(count($ServicesByCustomer) > 0){
                    $IsContine = false;
                }
            }

            if(count($ExcludedPrograms) > 0 && !$IsContine){
                continue;
            }


            if($this->input->post('serviceSoldNotNow') != "" && $this->input->post('serviceSoldNotNow') != "null"){
                $ExploseSoldService = explode(",", $this->input->post('serviceSoldNotNow'));
                $ServiceSoldShowCustomer = 0;

                foreach($ExploseSoldService as $ESS){
                    $ServicesByCustomer = $this->DashboardModel->getCustomerAllServicesForReport(array('jobs.company_id' => $company_id, 'property_tbl.company_id' => $company_id, "customers.customer_id" => $customer->customer_id, 'jobs.job_id' => $ESS, "job_assign_date >=" => $this->input->post('ServiceSoldNotNowStart'), "job_assign_date <=" => $this->input->post('ServiceSoldNotNowEnd')));
                    if(count($ServicesByCustomer) == 0){
                        $ServiceSoldShowCustomer = 1;
                    }
                }
                if($ServiceSoldShowCustomer == 0){
                    continue;
                }
            }

            //var_dump(memory_get_usage());
            $data['customer_properties_data'] = $this->PropertyModel->getAllCustomerPropertiesMarketing($customer->customer_id);
            // this needs to be set to blank at the top of every customer loop
            $properties_still_going = array();
            $filters_array['programs_multi'] = explode(',', $this->input->post('programs_multi'));

            foreach($data['customer_properties_data'] as $props) {                   
                $properties_still_going[] = $this->RP->find_property_from_filter(array('filters'=>$filters_array, 'prop_id'=>$props->property_id));
            }

            unset($data['customer_properties_data']);
            // the above line is sometimes adding a blank array to the array of properties, we need to get rid of those to see if the customer still needs to be shown
            $properties_still_going = array_filter($properties_still_going);

            $invocies_for_this_customer = array();
            $lot_size = $revenue = $revenue_ytd = $annual_per_1000 = $lot_size_for_1000_calc = $projected_annual_total = 0;
            $invoices_to_be_checked = $ids_already_checked = array();
            $got_rid_of_all_properties = true;
            $filters_set = false;

            foreach($filters_array as $fa) {
                if(is_array($fa)) {
                    if($fa[0] != "null" && $fa[0] != "" && $fa[0] != null && $fa[0] != "false" && $fa[0] != false ) {
                        $filters_set = true;
                    }
                } else {
                    if($fa != "null" && $fa != "" && $fa != null && $fa != "false" && $fa != false ) {
                        $filters_set = true;
                    }
                }
            }
            
            if(count($properties_still_going) > 0 || $filters_set == false) {
                foreach($properties_still_going as $psg) {
                    $use_this_property = true;
                    if($filters_array["outstanding_services_multi"][0] != 'null') {
                        $still_in_use = $this->RP->get_outstanding_service_properties($filters_array);
                        foreach($still_in_use as $siu) {
                            if($siu->property_id == $psg[0]->property_id) {
                                $got_rid_of_all_properties = false;
                            } else {
                                $use_this_property = false;
                            }
                        }
                        if($use_this_property == false) {
                            continue;
                        }
                    } else {
                        $got_rid_of_all_properties = false;
                    }
                    
                    $all_program_ids = explode(",", $psg[0]->program_ids);
                    $all_program_ids = array_unique($all_program_ids);
                    foreach($all_program_ids as $apid) {
                        // use the property id and program id to go and get the invoice for that job
                        $invoices_to_be_checked_comma = $this->RP->get_invoice_number_from_programs($apid, $psg[0]->property_id);
                        foreach($invoices_to_be_checked_comma as $itbcc) {
                            $invoices_to_be_checked[] = explode(',', $itbcc->invoice_ids);
                        }
                        unset($invoices_to_be_checked_comma);
                        if(!empty($invoices_to_be_checked)) {
                            $invoices_to_be_checked = array_filter($invoices_to_be_checked);
                            foreach($invoices_to_be_checked as $itbc_array) {
                                $itbc_array_for_foreach = array_unique($itbc_array);
                                foreach($itbc_array_for_foreach as $itbc) {
                                    if($itbc != "" && $itbc != NULL) {
                                        $invoice_info = array();
                                        $invoice_info[] = $this->RP->get_all_invoice_info($itbc, $customer->customer_id);
                                        $invoice_info = array_filter($invoice_info);
                                        $invoice_info = array_unique($invoice_info);
                                        foreach($invoice_info as $ii) {                                            
                                            foreach($ii as $i) {
                                                // foreach job add up that lot size
                                                if(!in_array($i->invoice_id, $ids_already_checked)) {
                                                    $revenue = $revenue + ($i->partial_payment - $i->refund_amount_total);
                                                    if(($i->invoice_date >= date('Y-m-d',strtotime('Jan 01'))) && ($i->invoice_date <= date('Y-m-d',strtotime('today')))) {
                                                        $revenue_ytd = $revenue_ytd + ($i->partial_payment - $i->refund_amount_total);
                                                    }
                                                    $ids_already_checked[] = $i->invoice_id;
                                                }
                                            }
                                        }
                                        unset($ii);
                                        unset($invoice_info);
                                    }
                                }
                                unset($itbc_array_for_foreach);
                            }
                        }
                        unset($invoices_to_be_checked);
                    }
                    unset($all_program_ids);
                    $lot_size_for_1000_calc = $lot_size_for_1000_calc + $psg[0]->yard_square_feet;
                    $rev_per_1000_calc = $lot_size_for_1000_calc / 1000;
                    if($rev_per_1000_calc != 0) {
                        $annual_per_1000 = $revenue_ytd / $rev_per_1000_calc;
                        $annual_per_1000 = number_format($annual_per_1000,2);
                    }

                    $lot_size = $lot_size + $psg[0]->yard_square_feet;
                }
                
                
                if($got_rid_of_all_properties == true && $filters_set == true) {
                    continue;
                }
                // if they set the YTD revenue, annual revenue, projected, or lot size we need to skip this person if they dont fall into that range
                if(($this->input->post('ytd_revenue_start') != "" && floatval($this->input->post('ytd_revenue_start')) > $revenue_ytd)) {
                    continue;
                }
                if($this->input->post('ytd_revenue_end') != "" && floatval($this->input->post('ytd_revenue_end')) < $revenue_ytd) {
                    continue;
                }
                if(($this->input->post('lot_size_start') != "" && floatval($this->input->post('lot_size_start')) > $lot_size)) {
                    continue;
                }
                if($this->input->post('lot_size_end') != "" && floatval($this->input->post('lot_size_end')) < $lot_size) {
                    continue;
                }
                if($this->input->post('annual_revenue_per_1000_start') != "" && floatval($this->input->post('annual_revenue_per_1000_start')) > $annual_per_1000) {
                    continue;
                }
                if($this->input->post('annual_revenue_per_1000_end') != "" && floatval($this->input->post('annual_revenue_per_1000_end')) < $annual_per_1000) {
                    continue;
                }
                if($this->input->post('revenue_total_start') != "" && floatval($this->input->post('revenue_total_start')) > $revenue) {
                    continue;
                }
                if($this->input->post('revenue_total_end') != "" && floatval($this->input->post('revenue_total_end')) < $revenue) {
                    continue;
                }

                $projected_annual = $this->RP->get_projected_annual($customer->customer_id);
                
                foreach($projected_annual as $pa) {
                    $projected_annual_total = $projected_annual_total + ($pa->partial_payment - $pa->refund_amount_total);
                }
                unset($projected_annual);
                if($this->input->post('projected_annual_revenue_start') != "" && floatval($this->input->post('projected_annual_revenue_start')) > $projected_annual_total) {
                    continue;
                }
                if($this->input->post('projected_annual_revenue_end') != "" && floatval($this->input->post('projected_annual_revenue_end')) < $projected_annual_total) {
                    continue;
                }
                
                if($revenue == 0) {
                    // this means that all of the properties were skipped (usually based on the outstanding services) so we don't need this user
                    //continue; commented out on 9/14 as they said they were missing data but really it was just 0 revenue
                }
                $customer_phone = '('.substr($customer->phone, 0, 3).') '.substr($customer->phone, 3, 3).'-'.substr($customer->phone,6);
                $customer_work_phone = '('.substr($customer->work_phone, 0, 3).') '.substr($customer->work_phone, 3, 3).'-'.substr($customer->work_phone,6);
                $customer_number_link = "<a href='".base_url("admin/editCustomer/").$customer->customer_id."'>".$customer->customer_id."</a>";
                $report_data[$customer->customer_id] = array(
                    'customer_number_link'=> $customer_number_link,
                    'first_name'=> $customer->first_name,
                    'last_name'=> $customer->last_name,
                    'email'=> $customer->email,
                    'second_email'=> $customer->secondary_email,
                    'address'=> $customer->billing_street." ".$customer->billing_city.", ".$customer->billing_state." ".$customer->billing_zipcode,
                    'cell_phone'=> ($customer_phone!="() -"?$customer_phone:""),
                    'phone' => ($customer_work_phone!="(0) -"?$customer_work_phone:""),
                    'revenue_by_product' => $revenue,
                    'ytd_revenue' => $revenue_ytd,
                    'projected_annual_revenue' => $projected_annual_total,
                    'lot_size' => $lot_size,
                    'annual_revenue_per_1000' => $annual_per_1000
                );
            }
            unset($properties_still_going);
            unset($invocies_for_this_customer);
            unset($lot_size);
            unset($revenue);
            unset($revenue_ytd);
            unset($annual_per_1000);
            unset($lot_size_for_1000_calc);
            unset($projected_annual_total);
            unset($invoices_to_be_checked);
            unset($ids_already_checked);
            unset($got_rid_of_all_properties);
            unset($filters_set);
            unset($use_this_property);
            unset($all_program_ids);
            unset($invoices_to_be_checked_comma);
            unset($invoice_info);
            unset($projected_annual);
            unset($customer_phone);
            unset($customer_work_phone);
            unset($customer_number_link);
        }
        
		$data['report_details'] = $report_data;
		$body =  $this->load->view('admin/report/ajax_marketing_customer_data_report', $data, false);

	}
	public function downloadMarketingCustomerDataReport(){
		$company_id = $this->session->userdata['company_id'];
        $filters_array = array();
        $filters_array["sources"] = $this->input->post('sources_multi');
        $filters_array["lead_start_date_start"] = $this->input->post('lead_start_date_start');
        $filters_array["lead_start_date_end"] = $this->input->post('lead_start_date_end');
        $filters_array["revenue_total_start"] = $this->input->post('revenue_total_start');
        $filters_array["revenue_total_end"] = $this->input->post('revenue_total_end');
        $filters_array["sale_start_date_start"] = $this->input->post('sale_start_date_start');
        $filters_array["sale_start_date_end"] = $this->input->post('sale_start_date_end');
        // we need to see if we need to set the end date or not
        if($filters_array["sale_start_date_end"] != "" && $filters_array["sale_start_date_start"] == "") {
            $date_company_created_at = $this->RP->get_company_created_at_date($this->session->userdata['company_id']);
            $filters_array["sale_start_date_start"] = $date_company_created_at->created_at;
        }
        $filters_array["programs_multi"] = $this->input->post('programs_multi');
        $filters_array["cancelation_date_start"] = $this->input->post('cancelation_date_start');
        $filters_array["cancelation_date_end"] = $this->input->post('cancelation_date_end');
        $filters_array["tags_multi"] = $this->input->post('tags_multi');
        $filters_array["last_date_program_start"] = $this->input->post('last_date_program_start');
        $filters_array["last_date_program_end"] = $this->input->post('last_date_program_end');
        $filters_array["service_areas_multi"] = $this->input->post('service_areas_multi');
        $filters_array["res_or_com"] = $this->input->post('res_or_com');
        $filters_array["preservice_notifications_multi"] = $this->input->post('preservice_notifications_multi');
        $filters_array["zip_codes_multi"] = $this->input->post('zip_codes_multi');
        $filters_array["cancel_reasons_multi"] = $this->input->post('cancel_reasons_multi');
        $filters_array["outstanding_services_multi"] = $this->input->post('outstanding_services_multi');
        $filters_array["customer_status"] = $this->input->post('customer_status');
        $filters_array["estimate_accpeted"] = $this->input->post('estimate_accpeted');
        $filters_array["all_tags"] = $this->input->post('all_tags');
        $filters_array["all_programs"] = $this->input->post('all_programs');
        $filters_array["all_pre_service"] = $this->input->post('all_pre_service');
        $filters_array["all_outstanding"] = $this->input->post('all_outstanding');
        
		$data['user_details'] = $this->Administrator->getAllAdminMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['source_list'] = $this->SourceModel->getAllSourceMarketing(array('company_id' => $this->session->userdata['company_id']));
        $data['customers'] = $this->CustomerModel->get_all_customer_marketing(array('company_id'=>$this->session->userdata['company_id']));

        echo '<pre>';
        print_r($data['customers']);
        
        $source = [];
        foreach($data['user_details'] as $user){
            $source = (object) array(
                'source_name' => $user->user_first_name.' '.$user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            ) ;
            array_push( $data['source_list'], $source);
        }
		#not seeing specific role for sales rep so getting all users 
		$report_data = array();
        foreach($data['customers'] as $customer) {
            $data['customer_properties_data'] = $this->PropertyModel->getAllCustomerPropertiesMarketing($customer->customer_id);
            // this needs to be set to blank at the top of every customer loop
            $properties_still_going = array();
            foreach($data['customer_properties_data'] as $props) {                   
                $properties_still_going[] = $this->RP->find_property_from_filter(array('filters'=>$filters_array, 'prop_id'=>$props->property_id));
            }

            // the above line is sometimes adding a blank array to the array of properties, we need to get rid of those to see if the customer still needs to be shown
            $properties_still_going = array_filter($properties_still_going);
            $invocies_for_this_customer = array();
            $lot_size = $revenue = $revenue_ytd = $annual_per_1000 = $lot_size_for_1000_calc = $projected_annual_total = 0;
            $invoices_to_be_checked = $ids_already_checked = array();
            $got_rid_of_all_properties = true;
            $filters_set = false;
            foreach($filters_array as $fa) {
                if(is_array($fa)) {
                    if($fa[0] != "null" && $fa[0] != "" && $fa[0] != null && $fa[0] != "false" && $fa[0] != false ) {
                        $filters_set = true;
                    }
                } else {
                    if($fa != "null" && $fa != "" && $fa != null && $fa != "false" && $fa != false ) {
                        $filters_set = true;
                    }
                }
            }
            
            if(!empty($properties_still_going) || $filters_set == false) {
                foreach($properties_still_going as $psg) {
                    $use_this_property = true;
                    if($filters_array["outstanding_services_multi"][0] != 'null') {
                        $still_in_use = $this->RP->get_outstanding_service_properties($filters_array);
                        foreach($still_in_use as $siu) {
                            if($siu->property_id == $psg[0]->property_id) {
                                $got_rid_of_all_properties = false;
                            } else {
                                $use_this_property = false;
                            }
                        }
                        if($use_this_property == false) {
                            continue;
                        }
                    } else {
                        $got_rid_of_all_properties = false;
                    }
                    
                    $all_program_ids = explode(",", $psg[0]->program_ids);
                    $all_program_ids = array_unique($all_program_ids);
                    foreach($all_program_ids as $apid) {
                        // use the property id and program id to go and get the invoice for that job
                        $invoices_to_be_checked_comma = $this->RP->get_invoice_number_from_programs($apid, $psg[0]->property_id);
                        foreach($invoices_to_be_checked_comma as $itbcc) {
                            $invoices_to_be_checked[] = explode(',', $itbcc->invoice_ids);
                        }
                        unset($invoices_to_be_checked_comma);
                        if(!empty($invoices_to_be_checked)) {
                            $invoices_to_be_checked = array_filter($invoices_to_be_checked);
                            foreach($invoices_to_be_checked as $itbc_array) {
                                $itbc_array_for_foreach = array_unique($itbc_array);
                                foreach($itbc_array_for_foreach as $itbc) {
                                    if($itbc != "" && $itbc != NULL) {
                                        $invoice_info = array();
                                        $invoice_info[] = $this->RP->get_all_invoice_info($itbc, $customer->customer_id);
                                        $invoice_info = array_filter($invoice_info);
                                        $invoice_info = array_unique($invoice_info);
                                        foreach($invoice_info as $ii) {                                            
                                            foreach($ii as $i) {
                                                // foreach job add up that lot size
                                                if(!in_array($i->invoice_id, $ids_already_checked)) {
                                                    $revenue = $revenue + ($i->partial_payment - $i->refund_amount_total);
                                                    if(($i->invoice_date >= date('Y-m-d',strtotime('Jan 01'))) && ($i->invoice_date <= date('Y-m-d',strtotime('today')))) {
                                                        $revenue_ytd = $revenue_ytd + ($i->partial_payment - $i->refund_amount_total);
                                                    }
                                                    $ids_already_checked[] = $i->invoice_id;
                                                }
                                            }
                                        }
                                        unset($invoice_info);
                                    }
                                }
                                unset($itbc_array_for_foreach);
                            }
                        }
                        unset($invoices_to_be_checked);
                    }
                    $lot_size_for_1000_calc = $lot_size_for_1000_calc + $psg[0]->yard_square_feet;
                    $rev_per_1000_calc = $lot_size_for_1000_calc / 1000;
                    if($rev_per_1000_calc != 0) {
                        $annual_per_1000 = $revenue_ytd / $rev_per_1000_calc;
                        $annual_per_1000 = number_format($annual_per_1000,2);
                    }

                    $lot_size = $lot_size + $psg[0]->yard_square_feet;
                }
                
                if($got_rid_of_all_properties == true && $filters_set == true) {
                    continue;
                }
                // if they set the YTD revenue, annual revenue, projected, or lot size we need to skip this person if they dont fall into that range
                if(($this->input->post('ytd_revenue_start') != "" && floatval($this->input->post('ytd_revenue_start')) > $revenue_ytd)) {
                    continue;
                }
                if($this->input->post('ytd_revenue_end') != "" && floatval($this->input->post('ytd_revenue_end')) < $revenue_ytd) {
                    continue;
                }
                if(($this->input->post('lot_size_start') != "" && floatval($this->input->post('lot_size_start')) > $lot_size)) {
                    continue;
                }
                if($this->input->post('lot_size_end') != "" && floatval($this->input->post('lot_size_end')) < $lot_size) {
                    continue;
                }
                if($this->input->post('annual_revenue_per_1000_start') != "" && floatval($this->input->post('annual_revenue_per_1000_start')) > $annual_per_1000) {
                    continue;
                }
                if($this->input->post('annual_revenue_per_1000_end') != "" && floatval($this->input->post('annual_revenue_per_1000_end')) < $annual_per_1000) {
                    continue;
                }
                if($this->input->post('revenue_total_start') != "" && floatval($this->input->post('revenue_total_start')) > $revenue) {
                    continue;
                }
                if($this->input->post('revenue_total_end') != "" && floatval($this->input->post('revenue_total_end')) < $revenue) {
                    continue;
                }

                $projected_annual = $this->RP->get_projected_annual($customer->customer_id);
                
                foreach($projected_annual as $pa) {
                    $projected_annual_total = $projected_annual_total + ($pa->partial_payment - $pa->refund_amount_total);
                }
                if($this->input->post('projected_annual_revenue_start') != "" && floatval($this->input->post('projected_annual_revenue_start')) > $projected_annual_total) {
                    continue;
                }
                if($this->input->post('projected_annual_revenue_end') != "" && floatval($this->input->post('projected_annual_revenue_end')) < $projected_annual_total) {
                    continue;
                }
                
                if($revenue == 0) {
                    // this means that all of the properties were skipped (usually based on the outstanding services) so we don't need this user
                    //continue; commented out on 9/14 as they said they were missing data but really it was just 0 revenue
                }
                $customer_phone = '('.substr($customer->phone, 0, 3).') '.substr($customer->phone, 3, 3).'-'.substr($customer->phone,6);
                $customer_work_phone = '('.substr($customer->work_phone, 0, 3).') '.substr($customer->work_phone, 3, 3).'-'.substr($customer->work_phone,6);
                $report_data[$customer->customer_id] = array(
                    'first_name'=> $customer->first_name,
                    'last_name'=> $customer->last_name,
                    'email'=> $customer->email,
                    'second_email'=> $customer->secondary_email,
                    'address'=> $customer->billing_street." ".$customer->billing_city.", ".$customer->billing_state." ".$customer->billing_zipcode,
                    'cell_phone'=> ($customer_phone!="() -"?$customer_phone:""),
                    'phone' => ($customer_work_phone!="(0) -"?$customer_work_phone:""),
                    'revenue_by_product' => $revenue,
                    'ytd_revenue' => $revenue_ytd,
                    'projected_annual_revenue' => $projected_annual_total,
                    'lot_size' => $lot_size,
                    'annual_revenue_per_1000' => $annual_per_1000
                );
            } 
        }

        $data['report_details'] = $report_data;

        echo '<pre>';
        print_r($data);
        die;

		if(is_array($data['report_details']) && count($data['report_details']) > 0){
            if($this->input->post('SendButtonEmail') == 3){
    			$delimiter = ",";
                $filename = "marketing_report_" . date('Y-m-d') . ".csv";
    			
    			#create a file pointer
                $f = fopen('php://memory', 'w');
         		
    			#set column headers
    			$fields = array('Customer Number','First Name','Last Name','Email','Second Email','Address','Cell Phone', 'Phone' , 'Revenue by Program', 'YTD Revenue', 'Projected Annual Revenue', 'Lot Size', 'Annual Revenue Per 1000 Sq Ft');
    			fputcsv($f, $fields, $delimiter);
    				
    			#output each row of the data, format line as csv and write to file pointer
    			$lineData = $data['report_details'];
                
                foreach($lineData as $ld) {
                    fputcsv($f, $ld, $delimiter);
                }
    					
    			#move back to beginning of file
    			fseek($f, 0);

    			#set headers to download file rather than displayed
    			header('Content-Type: text/csv'); 
    			header('Content-Disposition: attachment; filename="' .$filename. '";');

    			#output all remaining data on a file pointer
    			fpassthru($f);
            }
            if($this->input->post('SendButtonEmail') == 2){
                $CustomerArray = array();
                foreach($data["customers"] as $CusDat){
                    $CustomerArray[] = $CusDat->customer_id;
                }
                $Data = array(
                    "company_id" => $company_id,
                    "cusotmer_id" => implode(",", $CustomerArray),
                    "programmes_id" => implode(",", $_POST['MassProgramms']),
                    "mail_text" => $_POST['mailText'],
                    "email_subject" => $_POST['email_subject'],
                    "status" => 0
                );
                $this->MassEmailModel->saveMassEmailData($Data);
                redirect("admin/reports/marketingCustomerDataReport");
            }

            if($this->input->post('SendButtonEmail') == 1){
                $CustomerArray = array();
                foreach($data["customers"] as $CusDat){
                    $CustomerArray[] = $CusDat->customer_id;
                }
                $Data = array(
                    "company_id" => $company_id,
                    "cusotmer_id" => implode(",", $CustomerArray),
                    "programmes_id" => implode(",", $_POST['MassProgramms']),
                    "mail_text" => $_POST['mailText'],
                    "email_subject" => $_POST['email_subject'],
                    "status" => 1,
                    "send_date" => date("Y-m-d")
                );
                $ModelID = $this->MassEmailModel->saveMassEmailData($Data);
                $this->sendMassEmail($ModelID);
                
                redirect("admin/reports/marketingCustomerDataReport");
            }

        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
             redirect("admin/reports/marketingCustomerDataReport");
        }  
        unset($properties_still_going);
        unset($invocies_for_this_customer);
        unset($lot_size);
        unset($revenue);
        unset($revenue_ytd);
        unset($annual_per_1000);
        unset($lot_size_for_1000_calc);
        unset($projected_annual_total);
        unset($invoices_to_be_checked);
        unset($ids_already_checked);
        unset($got_rid_of_all_properties);
        unset($filters_set);
        unset($use_this_property);
        unset($all_program_ids);
        unset($invoices_to_be_checked_comma);
        unset($invoice_info);
        unset($projected_annual);
        unset($customer_phone);
        unset($customer_work_phone);
        unset($customer_number_link);
	}

    public function sendMassEmail($ModelID){
        $Data = $ModelID = $this->MassEmailModel->getMassEmailData(array("id" => $ModelID));
        $CustomerArray = explode(",", $Data->cusotmer_id);
        $ProgrammArray = explode(",", $Data->programmes_id);

        $body = $Data->mail_text;
        $where_company = array('company_id' =>  $this->session->userdata['company_id']);
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
        if (!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        }

        foreach($CustomerArray as $CusData){
            $CustomerDetails = $this->CustomerModel->getOneCustomerDetail($CusData);
            if($CustomerDetails->email != ""){
                $body = str_replace('{CUSTOMER_FIRST_NAME}', $CustomerDetails->first_name, $body);
                $body = str_replace('{CUSTOMER_LAST_NAME}', $CustomerDetails->last_name, $body);
                $GetProperty = $this->PropertyModel->getAllCustomerProperties($CusData);

                $body = str_replace('{PROPERTY_NAME}', $GetProperty[0]->property_title, $body);
                $body = str_replace('{PROPERTY_ADDRESS}', $GetProperty[0]->property_address, $body);

                $AllProgrammNames = array();
                foreach($ProgrammArray as $PrmArr){
                    $IsSendEmail = 0;
                    foreach($GetProperty as $GPS){
                        $CheckAssignment = $this->ProgramModel->getAllproperty(array("property_tbl.property_id" => $GPS->property_id, "program_id" => $PrmArr));

                        if(count($CheckAssignment) > 0){
                            $IsSendEmail = 1;
                        }
                    }

                    if($IsSendEmail == 1){
                        $GetProgramName = $this->PropertyModel->getProgramList(array("program_id" => $PrmArr));
                        $AllProgrammNames[] = $GetProgramName[0]->program_name;
                    }
                }

                $body = str_replace('{PROGRAMM_NAME}', implode(", ", $AllProgrammNames), $body);

                if(count($AllProgrammNames) > 0){
                    Send_Mail_dynamic(
                        $company_email_details,
                        $CustomerDetails->email,
                        array(
                            "name" => $this->session->userdata['compny_details']->company_name,
                            "email" => $this->session->userdata['compny_details']->company_email
                        ),
                        $body,
                        $Data->email_subject,
                        $CustomerDetails->secondary_email
                    );
                }
            }
        }
    }

    public function saveTechnicianFilter(){
        $data = $this->input->post();
        $data["user_id"] = $this->session->userdata['id'];
        
        $CheckReport = $this->TechEffReportModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));

        if(!isset($CheckReport["id"])){
            $this->TechEffReportModel->createSaveReport($data);
        }else{
            $this->TechEffReportModel->updateSaveReport(array("user_id" => $this->session->userdata['id']), $data);
        }
    }

    public function saveSalesSummaryFilters(){
        $data = $this->input->post();
        $data["user_id"] = $this->session->userdata['id'];
        
        $CheckReport = $this->SalesSummarySaveModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));

        if(!isset($CheckReport["id"])){
            $this->SalesSummarySaveModel->createSaveReport($data);
        }else{
            $this->SalesSummarySaveModel->updateSaveReport(array("user_id" => $this->session->userdata['id']), $data);
        }
    }

    public function saveServiceSummaryFilters(){
        $data = $this->input->post();
        $data["user_id"] = $this->session->userdata['id'];
        
        $CheckReport = $this->ServiceSummarySaveModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));

        if(!isset($CheckReport["id"])){
            $this->ServiceSummarySaveModel->createSaveReport($data);
        }else{
            $this->ServiceSummarySaveModel->updateSaveReport(array("user_id" => $this->session->userdata['id']), $data);
        }
    }

    public function saveSalesPipelineFilters(){
        $data = $this->input->post();
        $data["user_id"] = $this->session->userdata['id'];
        
        $CheckReport = $this->SaveSalesPipelineFilterModel->getTechSavedReport(array("user_id" => $this->session->userdata['id']));

        if(!isset($CheckReport["id"])){
            $this->SaveSalesPipelineFilterModel->createSaveReport($data);
        }else{
            $this->SaveSalesPipelineFilterModel->updateSaveReport(array("user_id" => $this->session->userdata['id']), $data);
        }
    }

    public function emailMarketing(){
        $company_id = $this->session->userdata['company_id'];
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $GetData = $this->MassEmailModel->getMassEmailData($where_arr);
        $data['EmailList'] = $GetData;
        $page["active_sidebar"] = "email_marketing";
        $page["page_name"] = 'Email Marketing';
        $page["page_content"] = $this->load->view("admin/report/view_email_marketing", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }
}