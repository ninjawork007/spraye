<?php



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */





class Invoice_model extends CI_Model{   

    const INVTBL="invoice_tbl";

    public function getinvoicedata($where_arr = '') {

        $this->db->select("technician_job_assign_id,first_name,last_name,customers.email,jobs.job_id,job_price,yard_square_feet,job_assign_date,job_assign_updated_date");

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
	
    public function ajaxActiveInvoices($where_arr='', $limit, $start, $col, $dir) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price', false);

        $this->db->from('invoice_tbl');

        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');

        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        $this->db->limit($limit, $start);

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        $this->db->order_by($col,$dir);

        $result = $this->db->get();

	

        $data = $result->result();

        return $data;

		

    }

    public function ajaxActiveInvoicesTech($where_arr='', $limit, $start, $col, $dir, $whereArrExclude, $whereArrExclude2, $orWhere = '', $is_for_count) {

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
		//	print_r($this->db->last_query());
        return $data;

    }

    public function ajaxActiveInvoicesSearchTech($where_arr='', $limit, $start, $search, $col, $dir, $whereArrExclude, $whereArrExclude2, $orWhere = '', $is_for_count) {

        $this->db->select('invoice_tbl.*, CONCAT(customers.first_name, " ", customers.last_name) AS customer_name, email, programs.program_price AS programs_program_price, technician_job_assign.is_complete AS tech_job_assign_complete', false);
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
		
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
        $this->db->or_like('email',$search);
        $this->db->group_end();

        $this->db->group_by('invoice_id');
        $this->db->order_by($col,$dir);
        
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

        //die(print_r($this->db->last_query())); 

        $data = $result->result();

        // die(print_r($data));

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

        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');

        //$this->db->join('property_program_job_invoice','property_program_job_invoice.invoice_id = invoice_tbl.invoice_id','left'); 

       // $this->db->join('jobs','(jobs.job_id = property_program_job_invoice.job_id OR jobs.job_id = invoice_tbl.job_id)','left');           

        if (is_array($where_arr)) {

                $this->db->where($where_arr);

        }

        $this->db->order_by('invoice_tbl.invoice_id','desc');

        $result = $this->db->get();

		

        $data = $result->row();

        //die(print_r($data));

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

    public function getAllHotfixSalesInvoice($where_arr = '') {
           
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

    public function getAllPartialSalesInvoice($where_arr = '') {
           
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
}

 

