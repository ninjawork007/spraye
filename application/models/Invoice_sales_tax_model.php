<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Invoice_sales_tax_model extends CI_Model{
      const INTX="invoice_sales_tax";

   public function CreateOneInvoiceSalesTax($post) {
        $query = $this->db->insert(self::INTX, $post);
        return $this->db->insert_id();
    }

    public function getAllInvoiceSalesTax($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::INTX);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }

     public function getAllInvoiceSalesTaxDetails($where_arr = '') {
           
        $this->db->select('*,sum(tax_amount) as total_tax, sum(cost) as total_cost, (CASE 
		WHEN max(job_completed_date) > max(payment_created) THEN max(job_completed_date)
		WHEN max(payment_created) > max(job_completed_date) THEN max(payment_created)
		END )
		as real_date',FALSE);
        
        $this->db->from(self::INTX);

        if (is_array($where_arr)) {
			//die(print_r($where_arr));
            $this->db->where($where_arr,null,FALSE);
        }

        $this->db->join('invoice_tbl','invoice_tbl.invoice_id = invoice_sales_tax.invoice_id','inner');
		 // $this->db->join('jobs','jobs.job_id = invoice_sales_tax.job_id','inner');
		 
		//$this->db->join('property_tbl','property_tbl.property_id =invoice_tbl.property_id','inner');
		// $this->db->join('invoice_job','invoice_job.invoice_id = invoice_tbl.invoice_id','left outer');
		 
		 
		 
		$this->db->join('technician_job_assign','invoice_tbl.job_id = technician_job_assign.job_id and technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','inner');
		 
		$this->db->order_by('real_date', 'DESC'); 
		 
		
		 
		$this->db->group_by("tax_name");

        $result = $this->db->get();
		 
		 // die('last query '.$this->db->last_query());

        $data = $result->result();
		 //die(print_r($data));
        return $data;
    }


        
    public function deleteInvoiceSalesTax($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::INTX);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 