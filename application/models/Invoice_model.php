<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Dompdf\Dompdf;
class Invoice_model extends CI_Model{   
      const INVTBL="invoice_tbl";
      const INTX="invoice_sales_tax";
    const RPT="report";

public function getOneInvoice($invoice_id) {
	 $this->db->select('*');  
     $this->db->from('invoice_tbl');
	 $this->db->where('invoice_id',$invoice_id);
	 $result = $this->db->get();
     $data = $result->row(); 
	 return $data;
	
}
public function getOneProgram($program_id) {
	 $this->db->select('*');  
     $this->db->from('programs');
	 $this->db->where('program_id',$program_id);
	 $result = $this->db->get();
     $data = $result->row(); 
	 return $data;
	
}
public function	getPPJOBINVdetails($where_arr = ''){
	 $this->db->select('*');  
     $this->db->from('property_program_job_invoice');
     $this->db->join('programs','programs.program_id = property_program_job_invoice.program_id','inner');
     if (is_array($where_arr)) {
            $this->db->where($where_arr);
     }
	 $result = $this->db->get();
	 $data = $result->result_array();
     return $data;
}
     public function getinvoicedata($where_arr = '') {
           
        $this->db->select("technician_job_assign_id,first_name,last_name,customers.email,jobs.job_id,job_price,yard_square_feet,job_assign_date,job_assign_updated_date");
        
        $this->db->from('technician_job_assign');

        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
 
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');        

       $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');

        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','inner');


        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');

       
       
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->or_where('job_assign_date >',date("Y-m-d")); 
        
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }



    public function createOneInvoice($post) {
          $query = $this->db->insert(self::INVTBL, $post);
        return $this->db->insert_id();
    }

    public function getAllInvoive($where_arr='') {       
        $this->db->select('*');  
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');
        
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }
           $result = $this->db->get();

        $data = $result->result();
        return $data;    
    }

    public function getOneInvoive($where_arr='') {        
        $this->db->select('*,invoice_tbl.company_id');  
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','inner');     
        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');     
             
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }
           $result = $this->db->get();
           

        $data = $result->row();        
        return $data;    
    }

    public function getOneInvoiveWhereIn($search_column, $where_arr='') {

        $this->db->select('*,invoice_tbl.invoice_id,invoice_tbl.company_id');

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');

        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');

        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');

        //$this->db->join('property_program_job_invoice','property_program_job_invoice.invoice_id = invoice_tbl.invoice_id','left'); 

       // $this->db->join('jobs','(jobs.job_id = property_program_job_invoice.job_id OR jobs.job_id = invoice_tbl.job_id)','left');           

        // if (is_array($where_arr)) {

            $this->db->where_in($search_column, $where_arr);
            // die('admin');
        // }

        $this->db->order_by('invoice_tbl.invoice_id','desc');

        $result = $this->db->get();

		

        $data = $result->row();

        //die(print_r($data));

        return $data;

    }

    public function getAllInvoiceSalesTax($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('invoice_sales_tax');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }


    public function updateInvoive($wherearr, $updatearr) {

       
        $this->db->where($wherearr);
        $this->db->update(self::INVTBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    public function ajaxActiveInvoiceTech($where_arr='', $whereArrExclude, $whereArrExclude2, $orWhere = '') {

        $this->db->select('invoice_tbl.invoice_id');

        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('refund_invoice_logs','refund_invoice_logs.invoice_id = invoice_tbl.invoice_id','left');

        // $this->db->join('technician_job_assign', 'technician_job_assign.customer_id = invoice_tbl.customer_id AND technician_job_assign.job_id = invoice_tbl.job_id AND technician_job_assign.program_id = invoice_tbl.program_id AND technician_job_assign.property_id = invoice_tbl.property_id', 'left');
        $this->db->join('technician_job_assign', 'technician_job_assign.invoice_id = invoice_tbl.invoice_id', 'left');

        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.customer_id = invoice_tbl.customer_id AND property_program_job_invoice.job_id = invoice_tbl.job_id AND property_program_job_invoice.program_id = invoice_tbl.program_id AND property_program_job_invoice.property_id = invoice_tbl.property_id', 'left');
        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.invoice_id = invoice_tbl.invoice_id', 'left');
        $this->db->join("( SELECT *, (LENGTH(csv_report_ids) - LENGTH(REPLACE(csv_report_ids, ',', '')) + 1) AS csv_item_num FROM ( SELECT *, GROUP_CONCAT(report_id) AS csv_report_ids, COUNT(invoice_id) AS invoice_id_count FROM property_program_job_invoice GROUP BY invoice_id ) AS T ) AS property_program_job_invoice2", 'property_program_job_invoice2.invoice_id = invoice_tbl.invoice_id', 'left');


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (is_array($orWhere) && !empty($orWhere)) {
            if(isset($orWhere['payment_status'])){
                $this->db->where_in('payment_status', $orWhere['payment_status']);
            }

        }

        if (is_array($whereArrExclude)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude);
            $this->db->group_end();
        }

        if (is_array($whereArrExclude2)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude2);
            $this->db->group_end();
        }

        $this->db->group_by('invoice_id');


        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        // print_r($this->db->last_query());
        return $data;

    }

    public function ajaxActiveInvoicesTech($where_arr='', $whereArrExclude, $whereArrExclude2, $orWhere = '') {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price AS programs_program_price, technician_job_assign.is_complete AS tech_job_assign_complete, refund_invoice_logs.refund_datetime', false);
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('refund_invoice_logs','refund_invoice_logs.invoice_id = invoice_tbl.invoice_id','left');

        // $this->db->join('technician_job_assign', 'technician_job_assign.customer_id = invoice_tbl.customer_id AND technician_job_assign.job_id = invoice_tbl.job_id AND technician_job_assign.program_id = invoice_tbl.program_id AND technician_job_assign.property_id = invoice_tbl.property_id', 'left');
        $this->db->join('technician_job_assign', 'technician_job_assign.invoice_id = invoice_tbl.invoice_id', 'left');

        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.customer_id = invoice_tbl.customer_id AND property_program_job_invoice.job_id = invoice_tbl.job_id AND property_program_job_invoice.program_id = invoice_tbl.program_id AND property_program_job_invoice.property_id = invoice_tbl.property_id', 'left');
        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.invoice_id = invoice_tbl.invoice_id', 'left');
        $this->db->join("( SELECT *, (LENGTH(csv_report_ids) - LENGTH(REPLACE(csv_report_ids, ',', '')) + 1) AS csv_item_num FROM ( SELECT *, GROUP_CONCAT(report_id) AS csv_report_ids, COUNT(invoice_id) AS invoice_id_count FROM property_program_job_invoice GROUP BY invoice_id ) AS T ) AS property_program_job_invoice2", 'property_program_job_invoice2.invoice_id = invoice_tbl.invoice_id', 'left');



        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (is_array($orWhere) && !empty($orWhere)) {
            if(isset($orWhere['payment_status'])){
                $this->db->where_in('payment_status', $orWhere['payment_status']);
            }

        }

        if (is_array($whereArrExclude)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude);
            $this->db->group_end();
        }

        if (is_array($whereArrExclude2)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude2);
            $this->db->group_end();
        }

        $this->db->group_by('invoice_id');

        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        // print_r($this->db->last_query());
        return $data;

    }




     public function getOneRepots($where_arr=''){
        $this->db->select('*');
        $this->db->from(self::RPT);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }          
       return  $this->db->get()->row();
 
    }
     
   public function getAllInvoiceJobDetails($where_arr = '') {
           
        $this->db->select('*,invoice_job.job_id,invoice_job.report_id');
        
        $this->db->from('invoice_job');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->join('invoice_tbl','invoice_tbl.invoice_id =invoice_job.invoice_id','inner');
        $this->db->join('jobs','jobs.job_id =invoice_job.job_id','left');

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }

    /**
     * Returns list of invoice records for provided date criteria.
     */
    public function getCompletedJobCustomerDetail($where_arr) {
        $this->db->select('customers.customer_id,email,invoice_tbl.company_id,invoice_id,report_id, invoice_date');
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id','inner');        
        $this->db->where($where_arr);        
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
    }

    public function updateInvoiceForInvoices($invoice_id_list, $update_arr) {
        $this->db->where_in("invoice_id",$invoice_id_list);
        $this->db->update(self::INVTBL, $update_arr);
        $this->db->affected_rows();
    }

    public function getInvoiceMiniListFromHashString($where_arr) {
        $this->db->select('invoice_id,company_id');
        $this->db->from('invoice_tbl');
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->result_array();
        if(count($data) == 0) {
            $this->db->select('invoice_id,company_id');
            $this->db->from('invoice_hash_tbl');
            $this->db->where($where_arr);
            $result = $this->db->get();
            $data = $result->result_array();
            if(count($data) == 0) {
                return array(
                    "invoice_ids" => '',
                    "company_id" => ''
                );
            } else {
                return array(
                    "invoice_ids" => implode(',', array_column($data, 'invoice_id')),
                    "company_id" => $data[0]["company_id"]
                );
            }            
        }        
        return array(
            "invoice_ids" => implode(',',array_column($data, 'invoice_id')),
            "company_id" => $data[0]["company_id"]
        );  
    }
	public function checkPastDue($where_arr){
		$this->db->select('invoice_tbl.invoice_id, invoice_tbl.status, invoice_tbl.payment_status, invoice_tbl.invoice_date, invoice_tbl.first_sent_date, t_company.payment_terms');  
     	$this->db->from('invoice_tbl');
		$this->db->join('t_company','t_company.company_id = invoice_tbl.company_id ','inner');
	 	$this->db->where($where_arr);
		$result = $this->db->get();
        $data = $result->result_array();
        return $data;
	}

    public function ajaxActiveInvoicesTechWithSalesTax($where_arr='', $limit, $start, $col, $dir, $whereArrExclude, $whereArrExclude2, $orWhere = '', $is_for_count) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price AS programs_program_price, technician_job_assign.is_complete AS tech_job_assign_complete, invoice_sales_tax.tax_amount AS tax_amm', false);
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		
        // $this->db->join('technician_job_assign', 'technician_job_assign.customer_id = invoice_tbl.customer_id AND technician_job_assign.job_id = invoice_tbl.job_id AND technician_job_assign.program_id = invoice_tbl.program_id AND technician_job_assign.property_id = invoice_tbl.property_id', 'left');
        $this->db->join('technician_job_assign', 'technician_job_assign.invoice_id = invoice_tbl.invoice_id', 'left');

        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.customer_id = invoice_tbl.customer_id AND property_program_job_invoice.job_id = invoice_tbl.job_id AND property_program_job_invoice.program_id = invoice_tbl.program_id AND property_program_job_invoice.property_id = invoice_tbl.property_id', 'left');
        // $this->db->join('property_program_job_invoice', 'property_program_job_invoice.invoice_id = invoice_tbl.invoice_id', 'left');
        $this->db->join("( SELECT *, (LENGTH(csv_report_ids) - LENGTH(REPLACE(csv_report_ids, ',', '')) + 1) AS csv_item_num FROM ( SELECT *, GROUP_CONCAT(report_id) AS csv_report_ids, COUNT(invoice_id) AS invoice_id_count FROM property_program_job_invoice GROUP BY invoice_id ) AS T ) AS property_program_job_invoice2", 'property_program_job_invoice2.invoice_id = invoice_tbl.invoice_id', 'left');

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		if (is_array($orWhere) && !empty($orWhere)) {
			if(isset($orWhere['payment_status'])){
				$this->db->where_in('payment_status', $orWhere['payment_status']);
			}
            
        }

        if (is_array($whereArrExclude)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude);
            $this->db->group_end();
        }

        if (is_array($whereArrExclude2)) {
            $this->db->group_start('!');
            $this->db->where($whereArrExclude2);
            $this->db->group_end();
        }

        $this->db->group_by('invoice_id');
        $this->db->order_by($col,$dir);

        $result = $this->db->get();
        $data = $result->result();
		//return	print_r($this->db->last_query());
        return $data;

    }
    public function getLateFee($invoice_id){
        $sql = "SELECT 
                    IF(apply_late_fee
                            AND auto_late_fee_multiplier
                            AND is_recurring,
                        auto_late_fee_multiplier * late_fee_amount,
                        IF(apply_late_fee, late_fee_amount, 0)) late_fee_amount
                FROM
                    (SELECT 
                        IF(DATEDIFF(NOW(), a.first_sent_date) > b.late_fee_due, 1, 0) apply_late_fee,
                            IF(b.late_fee_is_recurring, 1, 0) is_recurring,
                            TIMESTAMPDIFF(MONTH, DATE_ADD(a.first_sent_date, INTERVAL 10 DAY), NOW()) auto_late_fee_multiplier,
                            ROUND(IF(b.late_fee_flat > 0, b.late_fee_flat, (b.late_fee_percent * a.cost * .01)), 2) late_fee_amount
                    FROM
                        invoice_tbl a
                    JOIN t_company b ON a.company_id = b.company_id
                        AND a.is_late_fee = 1
                    WHERE
                        a.invoice_id = {$invoice_id}) t";

        $query = $this->db->query($sql);
        $result =   $query->row();
        
        return !empty($result) ? $result->late_fee_amount : 0 ;

    }
    public function sendMonthlyInvoice($customer,$email)
    {

        $file = fopen("MonthlyStatementResult.txt","a");
        fwrite($file, 'Customer_id: '.$customer.  PHP_EOL);

        $customer_id = $customer;



        // WHERE:
        $whereArr = array(
            'is_archived' => 0,
            'invoice_tbl.customer_id' => $customer_id,
            'invoice_tbl.status !=' => 0
            // 'payment_status !=' => 2
        );

        $start_date = date("Y-m-d", strtotime("first day of previous month"));
        $end_date = date('Y-m-01');

        $whereArr['invoice_tbl.invoice_date >='] = $start_date; //$post_data['start_date'];
        $whereArr['invoice_tbl.invoice_date <'] = $end_date; //$post_data['end_date'];


        // WHERE NOT: all of the below true
        $whereArrExclude = array(
            "programs.program_price" => 2,
            // "technician_job_assign.is_complete" => 0,
            "technician_job_assign.is_complete !=" => 1,
            "technician_job_assign.is_complete IS NOT NULL" => null
        );

        // WHERE NOT: all of the below true
        $whereArrExclude2 = array(
            "programs.program_price" => 2,
            "technician_job_assign.invoice_id IS NULL" => null,
            "invoice_tbl.report_id" => 0,
            "property_program_job_invoice2.report_id IS NULL" => null,
        );

        $invoice_total_cost = 0;
        $previous_total = 0;




        if (isset($start_date) && !empty($end_date)) {

            $whereArrBefore = array(
                'is_archived' => 0,
                'invoice_tbl.customer_id' => $customer_id,
                // 'payment_status !=' => 2,
                'invoice_tbl.invoice_date >' => $end_date,
            );

            $data_before_period = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArrBefore, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);
            if (!empty($data_before_period)) {
                foreach ($data_before_period as $invoice_details) {

                    ////////////////////////////////////
                    // START INVOICE CALCULATION COST //

                    // vars
                    $tmp_invoice_id = $invoice_details->invoice_id;

                    // cost of all services (with price overrides) - service coupons
                    $job_cost_total = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
                    );
                    $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);

                    if (!empty($proprojobinv)) {
                        foreach ($proprojobinv as $job) {

                            $job_cost = $job['job_cost'];

                            $job_where = array(
                                'job_id' => $job['job_id'],
                                'customer_id' => $job['customer_id'],
                                'property_id' => $job['property_id'],
                                'program_id' => $job['program_id'],
                            );
                            $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                            if (!empty($coupon_job_details)) {

                                foreach ($coupon_job_details as $coupon) {
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
                        //die(print_r("Inside Conditional: " . $invoice_total_cost));
                    } else {
                        $invoice_total_cost = $invoice_details->cost;

                    }

                    // check price override -- any that are not stored in just that ^^.

                    // - invoice coupons
                    $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
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
                    $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
                    if (!empty($invoice_sales_tax_details)) {
                        foreach ($invoice_sales_tax_details as $tax) {
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

                    // $total = $invoice_details->cost - $invoice_details->partial_payment + $invoice_details->tax_amm;
                    $total = $invoice_total_cost - $invoice_details->partial_payment;
                    $total = number_format($total, 2);
                    $total = (float) $total;
                    //var_dump($total);
                    $previous_total += $total;
                    //die(print_r($previous_total));
                }
            }
        }

        $data['past_invoice_total'] = $previous_total;
        $data['statement_start_date'] = $start_date;
        $data['statement_end_date'] = $end_date;
        $data['invoice_details'] = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArr, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);


        $data['customer_details'] = $this->Customer->getCustomerDetail($customer_id);
        $companyID =  $data['customer_details']['company_id'];
        $where_company = array('company_id' => $companyID);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where = array('user_id' => $data['customer_details']['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);
        $count = 0;
        //echo print_r($data['invoice_details']);
        foreach ($data['invoice_details'] as $index => $inv_deets) {
            //echo 'Invoice id: '.$inv_deets->invoice_id.'<br>';
//            fwrite($file, 'Invoice id: '.$inv_deets->invoice_id. PHP_EOL);

            $property_details = $this->PropertyModel->getOneProperty(array('property_id'=>$inv_deets->property_id));

            $data['invoice_details'][$index]->property_address = $property_details->property_address;
            $data['invoice_details'][$index]->property_city = $property_details->property_city;
            $data['invoice_details'][$index]->property_state = $property_details->property_state;
            $data['invoice_details'][$index]->property_zip = $property_details->property_zip;
            $data['invoice_details'][$index]->late_fee = $this->INV->getLateFee($inv_deets->invoice_id);
            $data['invoice_details'][$index]->partial_payment = $inv_deets->partial_payment;
            // $tax_arr = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $inv_deets->invoice_id));
            // if (!empty($tax_arr)) {
            //     foreach ($tax_arr as $tax) {
            //         if (array_key_exists("tax_value", $tax)) {
            //             $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $inv_deets->partial_payment;
            //             $data['invoice_details'][$index]->partial_payment += $tax_amm_to_add;
            //         }
            //     }
            // }


            ##### WHERE FOR GETTING ALL PARTIALS AND REFUNDS PAYMENTS FOR INVOICE ID #####
            $where = array(
                'customer_id' => $customer_id,
                'invoice_id' => $inv_deets->invoice_id,
            );

            ##### GETTING ALL PARTIALS FOR INVOICE ID #####
            $inv_deets->partial_payments_logs = $this->PartialPaymentModel->getAllPartialPayment($where);
            ####
            ##### GETTING ALL REFUNDS FOR INVOICE ID #####
            $inv_deets->refund_payments_logs = $this->RefundPaymentModel->getAllPartialRefund($where);
            ####
            //die(print_r($inv_deets));
            // die(print_r($this->db->last_query()));
            //die(print_r($partial_payments));

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // vars
            $tmp_invoice_id = $inv_deets->invoice_id;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
            );
            $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);

            if (!empty($proprojobinv)) {
                foreach ($proprojobinv as $job) {

                    $job_cost = $job['job_cost'];

                    $job_where = array(
                        'job_id' => $job['job_id'],
                        'customer_id' => $job['customer_id'],
                        'property_id' => $job['property_id'],
                        'program_id' => $job['program_id'],
                    );
                    $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                    if (!empty($coupon_job_details)) {

                        foreach ($coupon_job_details as $coupon) {
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
                $invoice_total_cost = (float) $job_cost_total;
            } else {
                $invoice_total_cost = (float) $inv_deets->cost;
            }

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
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
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
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

            // $data['invoice_details'][$count]->coupon_invoice = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $inv_deets->invoice_id ));
            $data['invoice_details'][$count]->final_cost = $invoice_total_cost;
            $count += 1;
        }

        //if (count($data['invoice_details']) != 0){

            $this->output->set_output('');
            $this->load->view('admin/invoice/customer_all_pdf_invoice', $data);

            $html = $this->output->get_output();

            //  // Load pdf library
            $this->load->library('pdf');
            $dompdf = new Dompdf();

            // Load HTML content
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrate');

            // Render the HTML as PDF
            $dompdf->render();
            $output = $dompdf->output();
            unset($dompdf);

            if (strcmp($email, 'test') == 0){

                $email = 'alvaro.mho2@gmail.com';
                //echo $email;
            } else if (strcmp($email, 'prd') == 0){
                //echo '[Prd]';
                //echo print_r($data['customer_details']['email']);
                if (strcmp($data['customer_details']['email'], '') == 0 ){
                    $email = $data['setting_details']->company_email;
                } else {
                    $email = $data['customer_details']['email'];
                }

                //echo $email;
            } else {
                $email = '';
            }
            fwrite($file, 'Sent Email To: '.$email.  PHP_EOL);
            if($data['customer_details']['email'] != ''){
                //echo 'Sending email...';
                $body = ' ';
                $companyID =  $data['customer_details']['company_id'];
                $data['customer_details'] = $this->Customer->getOneCustomerDetail($customer_id);
                $where_company = array('company_id' => $companyID);
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }
                $file = [
                    'file' =>base64_encode($output),
                    'file_name' => 'Statement.pdf',
                    'encoding' => 'base64',
                    'type' => 'application/pdf'
                ];
                //echo print_r($data);
                $res = Send_Mail_dynamic(
                    $company_email_details,
                    $email,
                    array(
                        "name" => $data['setting_details']->company_name,//$this->session->userdata['compny_details']->company_name,;
                        "email" => $email
                    ),
                    'Your monthly account statement is attached. Please remit payment at your earliest convenience. If you have already sent your payment, you can disregard this message.',
                    $data['setting_details']->company_name.' - Monthly Statement',
                    $data['customer_details']->secondary_email,
                    $file
                );
                fwrite($file, 'Resp: '.print_r($res). PHP_EOL);


            }
        //}
        fclose($file);
    }

    public function getAllInvoicesReport($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.tax_name,invoice_sales_tax.tax_value,invoice_sales_tax.tax_amount,property_tbl.property_area');

        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
        $this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();

        //print_r($this->db->last_query());
        return $data;
    }

    public function getAllSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area');

        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
        $this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();


        return $data;
    }
}