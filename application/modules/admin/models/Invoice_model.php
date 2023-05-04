<?php



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



use Dompdf\Dompdf;

class Invoice_model extends CI_Model{

    const INVTBL="invoice_tbl";
    const SRATBL="payment_invoice_logs";

    public function getinvoicedata($where_arr = '') {

        $this->db->select("technician_job_assign_id,first_name,last_name,customers.email,jobs.job_id,job_price,yard_square_feet,job_assign_date,job_assign_updated_date, ");

        $this->db->from('technician_job_assign');

        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');

        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');

        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');

        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');

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

    public function ajaxActiveInvoices($where_arr='', $limit, $start, $col, $dir, $invoice_id_list=0) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price, t_company_email_setting.late_fee_email,t_company.company_logo, t_company.company_name', false);

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');

        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('t_company_email_setting','t_company_email_setting.company_id = invoice_tbl.company_id','left');
        $this->db->join('t_company','t_company.company_id = invoice_tbl.company_id','left');

        $this->db->limit($limit, $start);


        if($invoice_id_list){
             $this->db->where_in("invoice_tbl.invoice_id",$invoice_id_list);
        }

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $this->db->order_by($col,$dir);

        $result = $this->db->get();



        $data = $result->result();

        return $data;



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

    public function ajaxActiveInvoicesTech($where_arr='', $limit, $start, $col, $dir, $whereArrExclude, $whereArrExclude2, $orWhere = '', $is_for_count) {

        if($is_for_count == true) {
            $this->db->select('invoice_tbl.invoice_id');
        } else {
            $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price AS programs_program_price, technician_job_assign.is_complete AS tech_job_assign_complete, refund_invoice_logs.refund_datetime', false);
        }
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('refund_invoice_logs','refund_invoice_logs.invoice_id = invoice_tbl.invoice_id','left');

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
        if ($col !== 'balance_due' && $col !== 'payment_info'){
            if ( $col === 'refund_date') {
                $this->db->order_by('refund_datetime', $dir);

            } else {
                $this->db->order_by($col,$dir);

            }
        }
        //$this->db->order_by($col,$dir);

        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
			// print_r($this->db->last_query());
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
			// print_r($this->db->last_query());
        return $data;

    }

    public function ajaxActiveInvoicesSearchTech($where_arr='', $limit, $start, $search, $col, $dir, $whereArrExclude, $whereArrExclude2, $orWhere = '', $is_for_count) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, customers.email, programs.program_price AS programs_program_price, technician_job_assign.is_complete AS tech_job_assign_complete, refund_invoice_logs.refund_datetime', false);
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('refund_invoice_logs','refund_invoice_logs.invoice_id = invoice_tbl.invoice_id','left');

        $this->db->join('technician_job_assign', 'technician_job_assign.invoice_id = invoice_tbl.invoice_id', 'left');
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


        // search query
        $this->db->group_start();
        $this->db->like('invoice_tbl.invoice_id',$search);
        $this->db->or_like('CONCAT(customers.first_name, " ", customers.last_name)',$search, false);
        $this->db->or_like('customers.email',$search);

        $this->db->or_like('IF (invoice_tbl.payment_method = 1, "Cash",IF (invoice_tbl.payment_method = 2, "Check",IF (invoice_tbl.payment_method = 3, "Credit Card","Other")))',$search);
        $this->db->or_like('invoice_tbl.check_number',$search);
        $this->db->or_like('invoice_tbl.cc_number',$search);
        $this->db->or_like('invoice_tbl.other_note',$search);

        $this->db->group_end();

        $this->db->group_by('invoice_id');
//        die($col);
        if ($col !== 'balance_due' && $col !== 'payment_info'){
            if ( $col === 'refund_date') {
                $this->db->order_by('refund_datetime', $dir);

            } else {
                $this->db->order_by($col,$dir);

            }
        }

        $result = $this->db->get();
        $data = $result->result();
		//print_r($this->db->last_query());
        return $data;

    }

    public function ajaxActiveInvoicesSearch($where_arr='', $limit, $start, $search, $col, $dir) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price', false);

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id','inner');

        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        $this->db->limit($limit, $start);

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $this->db->group_start();

        $this->db->like('invoice_id',$search);

        $this->db->or_like('CONCAT(customers.first_name, " ", customers.last_name)',$search, false);

        $this->db->or_like('email',$search);

        $this->db->group_end();

        $this->db->order_by($col,$dir);

        $result = $this->db->get();

        $data = $result->result();

		

        //die(print_r($this->db->last_query()));   

        //die(print_r($data)); 

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

        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $this->db->order_by('invoice_id','desc');

        $result = $this->db->get();

        // die(print_r($this->db->last_query()));

        $data = $result->result();

         //die(print_r($data));

        return $data;

    }

    public function getProgramInvoiceMethod($where_arr='') {

        $this->db->select('program_price');

        $this->db->from('programs');

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

         $result = $this->db->get();

        $data = $result->result_array();

        return $data;

    }



    public function getAllInvoiveForQuick($where_arr='') {

        $this->db->select('*,invoice_tbl.company_id');

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');

        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');

        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $result = $this->db->get();

        $data = $result->result_array();

        return $data;

    }



    public function getOneInvoive($where_arr='') {

        $this->db->select('*,invoice_tbl.invoice_id,invoice_tbl.company_id');

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');

        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');

        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','inner');

        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');

        //$this->db->join('property_program_job_invoice','property_program_job_invoice.invoice_id = invoice_tbl.invoice_id','left');

       // $this->db->join('jobs','(jobs.job_id = property_program_job_invoice.job_id OR jobs.job_id = invoice_tbl.job_id)','left');

        // if (is_array($where_arr)) {

            $this->db->where($where_arr);
            // $this->db->where_in($where_arr);
            // die('admin');
        // }

        $this->db->order_by('invoice_tbl.invoice_id','desc');

        $result = $this->db->get();



        $data = $result->row();

        //die(print_r($data));
        //die(print_r($this->db->last_query()));
        return $data;

    }


    public function getOneRow($where_arr='') {

        $this->db->select('*');

        $this->db->from('invoice_tbl');

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $this->db->order_by('invoice_id','desc');

        $result = $this->db->get();

        $data = $result->row();
        // die(print_r($this->db->last_query()));

        return $data;

    }

    public function updateInvoive($where_arr, $update_arr) {

        // $this->db->where($where_arr);
        // $this->db->update(self::INVTBL, $update_arr);
        // return $a = $this->db->affected_rows();

        $this->db->where($where_arr);
        $this->db->update(self::INVTBL, $update_arr);
        $this->db->where($where_arr);

        $result = $this->db->get(self::INVTBL);

        $data = $result->row('invoice_id');

        // die(print_r($this->db->last_query()));

        return $data;

    }

    public function getSumInvoive($where_arr) {

        $this->db->select(' sum( IFNULL(tax_amount, 0 ) ) + cost as cost , partial_payment , sum( IFNULL(tax_amount, 0 ) ) + cost - partial_payment as remaning_amount , refund_amount_total,  invoice_tbl.invoice_id ');

        $this->db->from('invoice_tbl');
        $this->db->join('invoice_sales_tax','invoice_sales_tax.invoice_id = invoice_tbl.invoice_id','left');
        $this->db->where($where_arr);
        $this->db->group_by('invoice_tbl.invoice_id');

        $result = $this->db->get();

        // die(print_r($this->db->last_query()));

        $data = $result->result_array();

        if ($data) {

            $cost =    array_column($data, 'cost');
            $partial_payment =   array_column($data, 'partial_payment');
            $remaning_amount =   array_column($data, 'remaning_amount');
            $refund_amount_total = array_column($data, 'refund_amount_total');
            $return =  array(

                'cost' =>  array_sum($cost),
                'total_partial' => array_sum($partial_payment),
                'remaning_amount' => array_sum($remaning_amount),
                'refund_amount_total' => array_sum($refund_amount_total)
            );

        } else {

            $return =  array(

                'cost' =>  0,
                'total_partial' => 0,
                'remaning_amount' => 0,
                'refund_amount_total' => 0
            );
        }
        return (object)$return;

    }

    public function deleteInvoice($wherearr) {

        $updatearr  = array(

            'is_archived' => 1

        );

        $this->db->where($wherearr);

        $this->db->update(self::INVTBL, $updatearr);

        $a = $this->db->affected_rows();

        if($a){

            return true;

        }

        else{

            return false;

        }

    }

    /**

     * Restore invoice model function.

     */

    public function restoreInvoice($wherearr) {

        $updatearr  = array(

            'is_archived' => 0

        );

        $this->db->where($wherearr);

        $this->db->update(self::INVTBL, $updatearr);

        $a = $this->db->affected_rows();

        if($a){

            return true;

        }

        else{

            return false;

        }

    }
    public function getAllSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area, GREATEST(invoice_tbl.payment_created, technician_job_assign.job_completed_date) as latest_date');

        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');
        $this->db->join('technician_job_assign', 'invoice_tbl.invoice_id = technician_job_assign.invoice_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();
        //var_dump($this->db->last_query());
        $data = $result->result();


        return $data;
    }

    public function getAllHotfixSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area, GREATEST(invoice_tbl.payment_created, technician_job_assign.job_completed_date) as latest_date');
        
        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');
        $this->db->join('technician_job_assign', 'invoice_tbl.invoice_id = technician_job_assign.invoice_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();


        return $data;
    }

   /* public function getAllHotfixSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area');

        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'inner');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();

        return $data;
    }
*/

    public function getAllPartialHotfixSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area, GREATEST(invoice_tbl.payment_created, technician_job_assign.job_completed_date) as latest_date');
        
        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');
        $this->db->join('technician_job_assign', 'invoice_tbl.invoice_id = technician_job_assign.invoice_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();

        return $data;
    }

    public function getAllPartialSalesInvoice($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.*,property_tbl.property_area, GREATEST(invoice_tbl.payment_created, technician_job_assign.job_completed_date) as latest_date');
        
        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'inner');
        $this->db->join('technician_job_assign', 'invoice_tbl.invoice_id = technician_job_assign.invoice_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();

        return $data;
    }

    public function getAllCreditAmountsApplied($cred_arr){
        $this->db->select('*');
        $this->db->from('payment_invoice_logs');
        if (is_array($cred_arr)) {
            $this->db->where($cred_arr);
        }

        $result = $this->db->get();

        $data = $result->result();

        return $data;

    }
	public function getAllInvoicesReport($where_arr = '') {

        $this->db->select('invoice_tbl.*,invoice_sales_tax.tax_name,invoice_sales_tax.tax_value,invoice_sales_tax.tax_amount,property_tbl.property_area, users.user_first_name, users.user_last_name');

        $this->db->from(self::INVTBL);
        $this->db->join('invoice_sales_tax', 'invoice_tbl.invoice_id = invoice_sales_tax.invoice_id', 'left');
		$this->db->join('property_tbl', 'invoice_tbl.property_id = property_tbl.property_id', 'left');
        $this->db->join('users', 'invoice_tbl.credit_given_user = users.id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();

		//print_r($this->db->last_query());
        return $data;
    }

    public function getRefundDate($invoice_id){
        $this->db->select('refund_invoice_logs.refund_datetime');
        $this->db->from('refund_invoice_logs');
        $this->db->where('refund_invoice_logs.invoice_id', $invoice_id);
        $result = $this->db->get();

        $data = $result->result();

        if($data){
            return $data[0];
        } else {
            return;
        }
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
    public function updateInvoiceForInvoices($invoice_id_list, $update_arr) {
        $this->db->where_in("invoice_id",$invoice_id_list);
        $this->db->update(self::INVTBL, $update_arr);
        $this->db->affected_rows();
    }
    public function getInvoices($where_arr='') {
        $this->db->select('*');
        $this->db->from('invoice_tbl');          
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('invoice_id','desc');
        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function sendMonthlyInvoice($customer,$email)
    {
        //$all_customers  = $this->CompanyModel->getAllCustomersToSendMonthlyStatement();
        //die(print_r($all_customers));
        //$post_data = $this->input->post();
        //foreach ($all_customers as $value){
            $customer_id = $customer;
            //echo $customer_id;
            if (!isset($email))
            $email = 'alvaro.mho2@gmail.com';
            //die(print_r($post_data));

            // WHERE:
            $whereArr = array(
                'is_archived' => 0,
                'invoice_tbl.customer_id' => $customer_id,
                'invoice_tbl.status !=' => 0
                // 'payment_status !=' => 2
            );
            if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {
                $whereArr['invoice_tbl.invoice_date >='] = $post_data['start_date'];
            }
            if (isset($post_data['end_date']) && !empty($post_data['end_date'])) {
                $whereArr['invoice_tbl.invoice_date <='] = $post_data['end_date'];
            }

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
            $start_date = 0;
            $end_date = 0;

            if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {
                $start_date = $post_data['start_date'];
            }
            if (isset($post_data['end_date']) && !empty($post_data['end_date'])) {
                $end_date = $post_data['end_date'];
            }

            if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {

                $whereArrBefore = array(
                    'is_archived' => 0,
                    'invoice_tbl.customer_id' => $customer_id,
                    // 'payment_status !=' => 2,
                    'invoice_tbl.invoice_date <' => $post_data['start_date'],
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
            // print_r($previous_total);
            // echo "<br>";
            // print_r(number_format($previous_total, 2));
            // die();
            $data['past_invoice_total'] = $previous_total;
            $data['statement_start_date'] = $start_date;
            $data['statement_end_date'] = $end_date;
            $data['invoice_details'] = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArr, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);



            $data['customer_details'] = $this->CustomerModel->getCustomerDetail($customer_id);
            $companyID =  $data['customer_details']['company_id'];
            $where_company = array('company_id' => $companyID);
            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

            $where = array('user_id' => $data['customer_details']['user_id']);
            $data['user_details'] = $this->Administrator->getOneAdmin($where);

            // die(print_r($data['invoice_details']));

            $count = 0;
            foreach ($data['invoice_details'] as $index => $inv_deets) {



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
            if (count($data['invoice_details']) != 0){
                /*
             * $dompdf= new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($name);*/
                $this->output->set_output('');
                $this->load->view('admin/invoice/customer_all_pdf_invoice', $data);

                $html = $this->output->get_output();

                //echo(print_r($this->output));
                //reset($this->output);
                //  // Load pdf library
                $this->load->library('pdf');
                //$this->dompdf = new pdf();
                $dompdf = new Dompdf();
//            $dompdf->loadHtml($html);
//            $dompdf->setPaper('A4', 'portrate');
//            $dompdf->render();


                // Load HTML content
                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrate');
                //echo $companyID.' ';

                // Render the HTML as PDF
                $dompdf->render();
                $output = $dompdf->output();
                unset($dompdf);
                //unset($this->dompdf);
                //echo $companyID.' ';
                if($email){
                    $body = ' ';
                    //echo  $data['customer_details']->company_id;
                    $companyID =  $data['customer_details']['company_id'];
                    //echo $companyID.' ';

                    $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
                    //die(print_r($data['customer_details']));

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

                    $res = Send_Mail_dynamic(
                        $company_email_details,
                        $email,
                        array(
                            "name" => $this->session->userdata['compny_details']->company_name,
                            "email" => $email
                        ),
                        $body,
                        'Customer Statement',
                        $data['customer_details']->secondary_email,
                        $file
                    );
                    //die(print_r($res));

//                if ($data['customer_details']->secondary_email !== ''){
//                    $secondary_email_list = explode(',',$data['customer_details']->secondary_email);
//                    foreach($secondary_email_list as $sel){
//                        Send_Mail_dynamic(
//                            $company_email_details,
//                            $sel,
//                            array(
//                                "name" => $this->session->userdata['compny_details']->company_name,
//                                "email" => $this->session->userdata['compny_details']->company_email
//                            ),
//                            $body,
//                            'Customer Statement',
//                            '',
//                            $file
//                        );
//                    }
//                }
//                if($res){
//                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Success! </strong> Statement sent successfully</div>');
//                    //redirect('admin/editCustomer/' . $customer_id);
//
//                }

                }
            }





        }

        //  // Output the generated PDF (1 = download and 0 = preview)
        //$this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
    //}

    public function createOnePartialPayment($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }

    public function addCreditPayment($customer_id=0, $credit_amount=0, $payment_type="check"){
        
        if($customer_id && $credit_amount){   
         $row = $this->db->select('credit_amount')->from('customers')->where(['customer_id' => $customer_id])->get()->row();
         $new_credit_amount = $row->credit_amount + $credit_amount;
         
         $result = $this->db->update('customers',['credit_amount' => $new_credit_amount, 'payment_type' => $payment_type],['customer_id' => $customer_id]);
        }
        return !empty($result) ? $result : false;
    }
    public function adjustCreditPayment($customer_id=0, $credit_amount=0, $payment_type="check"){
        
        if($customer_id){            
         
         $result = $this->db->update('customers',['credit_amount' => $credit_amount, 'payment_type' => $payment_type],['customer_id' => $customer_id]);
        }
        return !empty($result) ? $result : false;
    }

    public function getUnpaidInvoices($customer_id=0){
        $this->db->select('invoice_id as unpaid_invoice, cost, partial_payment as paid_already');

        $this->db->from('invoice_tbl');

        $this->db->where(array('customer_id' => $customer_id, 'status !=' => 0, 'payment_status !=' => 2, 'is_archived' => 0));

        $data = $this->db->get();
        
        $result = $data->result();        

        if(!empty($result)){            
            
            foreach($result as $res){

                $this->db->select('coupon_amount_calculation, coupon_amount');
                $this->db->from('coupon_invoice');
                $this->db->where('invoice_id', $res->unpaid_invoice);
                $coup_data = $this->db->get();
                $coupons = $coup_data->result();

                $this->db->select('tax_value');
                $this->db->from('invoice_sales_tax');
                $this->db->where('invoice_id', $res->unpaid_invoice);
                $tax_data = $this->db->get();
                $taxes = $tax_data->result();

                $tax_value = 0;

                $res->unpaid_amount = $res->cost;

                if(!empty($coupons)){
                    foreach($coupons as $coupon){
                        if($coupon->coupon_amount){
                            if($coupon->coupon_amount_calculation){
                                $coupon_value = $res->unpaid_amount * ($coupon->coupon_amount * .01);
                                $res->unpaid_amount -= $coupon_value;
                            } else {
                                $coupon_value = $coupon->coupon_amount;
                                $res->unpaid_amount -= $coupon_value;
                            }
                        }
                    }

                     
                }

                if(!empty($taxes)){
                    foreach($taxes as $tax){
                        $tax_value += $res->unpaid_amount * ($tax->tax_value * .01);
                    }

                    $res->unpaid_amount += $tax_value;
                }

                $res->unpaid_amount = number_format($res->unpaid_amount, 2, '.', '');
            }
        }

        
        // die(print_r($result));
        return $result;

    }

    public function getUnpaidInvoiceById($invoice_id){

        $this->db->select('invoice_id as unpaid_invoice, cost, partial_payment as paid_already');

        $this->db->from('invoice_tbl');

        $this->db->where(array('invoice_id' => $invoice_id, 'is_archived' => 0 ));

        $data = $this->db->get();
        
        $result = $data->row();
        
        // die(print_r($result));

        $this->db->select('tax_value');
        $this->db->from('invoice_sales_tax');
        $this->db->where('invoice_id', $result->unpaid_invoice);
        $tax_data = $this->db->get();
        $taxes = $tax_data->result();

        $this->db->select('coupon_amount_calculation, coupon_amount');
        $this->db->from('coupon_invoice');
        $this->db->where('invoice_id', $result->unpaid_invoice);
        $coup_data = $this->db->get();
        $coupons = $coup_data->result();

        $tax_value = 0;

        $result->unpaid_amount = $result->cost;

        if(!empty($coupons)){
            foreach($coupons as $coupon){
                if($coupon->coupon_amount){
                    if($coupon->coupon_amount_calculation){
                        $coupon_value = $result->unpaid_amount * ($coupon->coupon_amount * .01);
                        $result->unpaid_amount -= $coupon_value; 
                    } else {
                        $coupon_value = $coupon->coupon_amount;
                        $result->unpaid_amount -= $coupon_value; 
                    }
                }
            }

            
        }
        
        if(!empty($taxes)){
            foreach($taxes as $tax){
                $tax_value += $result->unpaid_amount * ($tax->tax_value * .01);
            }

            $result->unpaid_amount += $tax_value;
        }

        $result->unpaid_amount = number_format($result->unpaid_amount, 2, '.', '');
        
        // die(print_r($result));
        return $result;

    }
}

 

