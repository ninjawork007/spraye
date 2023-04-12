<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Refund_invoice_logs_model extends CI_Model{
      const REFTBL="refund_invoice_logs";

   public function createOnePartialRefund($post) {
        $query = $this->db->insert(self::REFTBL, $post);
        return $this->db->insert_id();
    }

    public function getAllPartialRefund($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::REFTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('refund_datetime','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function udpatePartialRefund($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::REFTBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    public function deletePartialRefund($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::REFTBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 
