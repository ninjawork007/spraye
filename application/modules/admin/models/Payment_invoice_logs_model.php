<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Payment_invoice_logs_model extends CI_Model{
      const SRATBL="payment_invoice_logs";

   public function createOnePartialPayment($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }

    public function getAllPartialPayment($where_arr = '') {
        $this->db->select('*');
        $this->db->from(self::SRATBL);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('payment_datetime','asc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllPartialPaymentWhereIn($search_column, $where_arr='') {
           
        $this->db->select('*');
        
        $this->db->from(self::SRATBL);

        // if (is_array($where_arr)) {
        //     $this->db->where($where_arr);
            $this->db->where_in($search_column, $where_arr);
        // }
        
        $this->db->order_by('payment_datetime','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function udpatePartialPayment($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::SRATBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    public function deletePartialPayment($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::SRATBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 