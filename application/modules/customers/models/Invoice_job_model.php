<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Invoice_job_model extends CI_Model{
      const INJOB="invoice_job";

   public function CreateOneInvoiceJob($post) {
        $query = $this->db->insert(self::INJOB, $post);
        return $this->db->insert_id();
    }

     public function getOneInvoiceJobDetails($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::INJOB);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }


        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }


 

     public function getAllInvoiceJobDetails($where_arr = '') {
           
        $this->db->select('*,invoice_job.job_id,invoice_job.report_id');
        
        $this->db->from(self::INJOB);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->join('invoice_tbl','invoice_tbl.invoice_id =invoice_job.invoice_id','inner');
        $this->db->join('jobs','jobs.job_id =invoice_job.job_id','left');

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }


        
    public function deleteInvoiceJob($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::INJOB);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 