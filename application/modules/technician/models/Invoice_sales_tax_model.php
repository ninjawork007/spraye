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
 