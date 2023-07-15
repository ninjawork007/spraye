<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Property_program_job_invoice_model extends CI_Model{
      const PPJOBINV="property_program_job_invoice";

   public function CreateOnePropertyProgramJobInvoice($post) {
        $query = $this->db->insert(self::PPJOBINV, $post);
        return $this->db->insert_id();
    }

     public function getOnePropertyProgramJobInvoiceDetails($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::PPJOBINV);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
 

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
    public function getPropertyProgramJobInvoiceCoupon($where_arr='', $where_in='', $get_job_count = false, $invoice_id = '') {
        $this->db->select('*');
        $this->db->from('property_program_job_invoice');
        //$this->db->join('technician_job_assign','technician_job_assign.customer_id = property_program_job_invoice.customer_id and technician_job_assign.job_id = property_program_job_invoice.job_id and technician_job_assign.program_id = property_program_job_invoice.program_id and technician_job_assign.property_id = property_program_job_invoice.property_id','inner');
        // $this->db->join('coupon_job', 'property_program_job_invoice.job_id = coupon_job.job_id AND property_program_job_invoice.program_id = coupon_job.program_id AND property_program_job_invoice.property_id = coupon_job.property_id AND property_program_job_invoice.customer_id = coupon_job.customer_id', 'left');
        if (is_array($where_arr)) {
                $this->db->where($where_arr);
        }
        if(isset($where_in) && $where_in != '') {
            $this->db->where($where_in);
        }
        $this->db->order_by('property_program_job_invoice.job_id','desc');
        $result = $this->db->get();
		$data = $result->result_array();
        //var_dump($this->db->last_query());
        //exit();
		 //die(print_r($data));
        if($get_job_count == true) {
            $this->db->select('job_id');
            $this->db->from('technician_job_assign');
            $this->db->where("invoice_id = '".$invoice_id."'");
            $result2 = $this->db->get();
		    $count = count($result2->result_array());
            $data['total_job_count'] = $count;
        }

        return $data;
    }

    public function getPropertyProgramJobInvoiceCouponReporting($where_arr='', $where_in='', $get_job_count = false, $invoice_id = '') {
        $this->db->select('*');
        $this->db->from('property_program_job_invoice');
        $this->db->join('technician_job_assign','technician_job_assign.customer_id = property_program_job_invoice.customer_id and technician_job_assign.job_id = property_program_job_invoice.job_id and technician_job_assign.program_id = property_program_job_invoice.program_id and technician_job_assign.property_id = property_program_job_invoice.property_id','inner');
        // $this->db->join('coupon_job', 'property_program_job_invoice.job_id = coupon_job.job_id AND property_program_job_invoice.program_id = coupon_job.program_id AND property_program_job_invoice.property_id = coupon_job.property_id AND property_program_job_invoice.customer_id = coupon_job.customer_id', 'left');
        if (is_array($where_arr)) {
                $this->db->where($where_arr);
        }
        if(isset($where_in) && $where_in != '') {
            $this->db->where($where_in);
        }
        $this->db->order_by('property_program_job_invoice.job_id','desc');
        $result = $this->db->get();
		$data = $result->result_array();
        //var_dump($this->db->last_query());
        //exit();
		 //die(print_r($data));
        if($get_job_count == true) {
            $this->db->select('job_id');
            $this->db->from('technician_job_assign');
            $this->db->where("invoice_id = '".$invoice_id."'");
            $result2 = $this->db->get();
		    $count = count($result2->result_array());
            $data['total_job_count'] = $count;
        }

        return $data;
    }
	 public function getOneInvoiceByPropertyProgram($where_arr='') {
        $this->db->select('*, property_program_job_invoice.invoice_id');
        $this->db->from('property_program_job_invoice');
		$this->db->join('invoice_tbl','invoice_tbl.invoice_id = property_program_job_invoice.invoice_id','inner');
		$this->db->join('property_program_assign','property_program_assign.property_program_id = property_program_job_invoice.property_program_id','left');
        $this->db->join('customers','customers.customer_id = property_program_job_invoice.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_job_invoice.property_id','inner');
		$this->db->join('programs','programs.program_id = property_program_job_invoice.program_id','inner');
		$this->db->join('report','report.report_id = property_program_job_invoice.report_id','left');
        $this->db->join('jobs','jobs.job_id = property_program_job_invoice.job_id','left');           
        if (is_array($where_arr)) {
                $this->db->where($where_arr);
        }
        $this->db->order_by('property_program_job_invoice.job_id','desc');
        $result = $this->db->get();
		$data = $result->result_array();
		// die(print_r($data));

        return $data;
    }
	   public function getAllRows($where_arr) {
           
        $this->db->select('*');
		$this->db->from('property_program_job_invoice');
        $this->db->where($where_arr);   

        $this->db->order_by('property_program_job_invoice_id','desc');
        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }
    public function updatePropertyProgramJobInvoice($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::PPJOBINV, $updatearr);
        return $a = $this->db->affected_rows();
        
    }
    public function deletePropertyProgramJobInvoice($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::PPJOBINV);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getPropertyProgramJobInvoiceCouponWhereIn($search_column, $where_arr='') {
        $this->db->select('*');
        $this->db->from('property_program_job_invoice');
        // $this->db->join('coupon_job', 'property_program_job_invoice.job_id = coupon_job.job_id AND property_program_job_invoice.program_id = coupon_job.program_id AND property_program_job_invoice.property_id = coupon_job.property_id AND property_program_job_invoice.customer_id = coupon_job.customer_id', 'left');
        // if (is_array($where_arr)) {
            $this->db->where_in($search_column, $where_arr);
            //var_dump($where_arr);
        // }
        $this->db->order_by('property_program_job_invoice.job_id','desc');
        $result = $this->db->get();
		$data = $result->result_array();
        //die(print_r($this->db->last_query()));
		 //die(print_r($data));

        return $data;
    }

}
 