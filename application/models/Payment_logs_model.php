<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Payment_logs_model extends CI_Model{
      const TBLOG="payment_logs";

   public function createLogRecord($post) {
        $query = $this->db->insert(self::TBLOG, $post);
        return $this->db->insert_id();
    }

    public function getAllPaymentLogs($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TBLOG);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('created_at','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
}
 