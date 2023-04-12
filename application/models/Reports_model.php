<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Reports_model extends CI_Model{   

    const TJATBL="technician_job_assign";
    const RPT="report";



    public function getAllRepots($params = array()){
        $this->db->select('*, report.report_id as thereportid');
        $this->db->from(self::RPT);
        $this->db->join("report_product","report_product.report_id = report.report_id","left");
		$this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id','inner');
		//$this->db->join("property_program_job_invoice","property_program_job_invoice.report_id = report.report_id","left");
		//$this->db->join('invoice_tbl','technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','inner');
		//$this->db->join('invoice_tbl','invoice_tbl.job_id = technician_job_assign.job_id and technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','left');
	
     	//$this->db->where('report.company_id',$this->session->userdata['company_id']);
         $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);

         if (array_key_exists("where_condition",$params)) {

            $this->db->where($params['where_condition']);
             
         }

        if(!empty($params['search']['job_completed_date_to']) && empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
        }
        else if(empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
        }

        else if(!empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
           $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
        }



        if(!empty($params['search']['customer_name'])) {
   
            $this->db->where("(`first_name` LIKE '%".$params['search']['customer_name']."%' OR `last_name` LIKE '%".$params['search']['customer_name']."%')");
        } 
       
       
        if(!empty($params['search']['technician_name'])){            
          
           $this->db->where("(`user_first_name` LIKE '%".$params['search']['technician_name']."%' OR `user_last_name` LIKE '%".$params['search']['technician_name']."%')");
        }


        if(!empty($params['search']['product_name'])){            
          
           $this->db->where(" `product_name` LIKE '%".$params['search']['product_name']."%' ");
        }

        $this->db->group_by('report.report_id');
        $this->db->order_by('report.technician_job_assign_id','desc');
      
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
         //get records
           $query = $this->db->get();
		//die($this->db->last_query());
            //return fetched data
         return ($query->num_rows() > 0)?$query->result():FALSE;        
      
    }


    public function getOneRepots($where_arr=''){
        $this->db->select('*');
        $this->db->from(self::RPT);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }          
       return  $this->db->get()->row();
 
    }

    public function ajaxDataForInvoiceAgeReport($company_id){
//        $company_id = $company_id;
//        $customer = $this->input->post('customer');
//        $service_area = $this->input->post('service_area');
//        $tax_name = $this->input->post('tax_name');
//        $interval = $this->input->post('interval');

//        if(!empty($interval)){
//            $data['interval'] = $interval;
//        }else{
            $data['interval'] = "30";
        //}
       // if(!empty($customer)){
           // $customers = $this->CustomerModel->getCustomerList(array('customer_id'=>$customer));
        //}else{
        //die($company_id);
            $customers = $this->Customer->getCustomerList(array('company_id'=>$company_id));
        //}


        //die(print_r($customers));
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

        foreach($customers as $customer){
            //echo "CUSTOMER: ".$customer->customer_id."<br>";
            $customer_invoices[$customer->customer_id] = array();

            $whereArr = array(
                'customer_id'=>$customer->customer_id,
                'status !='=>0, //where status != unsent
                'payment_status !='=>2, //where payment_status != paid
                'is_archived'=>0, //where not archived
            );

            $invoices = $this->INV->getAllSalesInvoice($whereArr);

            $current_amount_due = 0;
            $aged30_amount_due = 0;
            $aged60_amount_due = 0;
            $aged90_amount_due = 0;

            foreach($invoices as $invoice){
                //echo 'Invoice: '.$invoice->invoice_id;
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
                $invoice_date = new DateTime($invoice->invoice_date);
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



        }
        $list_customers = [];
        foreach ($customer_invoices as $value){
            if ($value['customer_total_due'] != 0) array_push($list_customers,$value['customer_id']);
        }
        //die(print_r($list_customers));

        return $list_customers;

    }

}
 
