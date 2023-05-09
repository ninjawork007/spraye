<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Payment_logs_model extends CI_Model{
      const SRATBL="payment_logs";

   public function createLogRecord($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }

    public function getAllPaymentLogs($where_arr = '') {
        $this->db->select('payment_logs.*, users.user_first_name, users.user_last_name');
        $this->db->join('users','users.id = payment_logs.user_id','left');
        $this->db->from(self::SRATBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('created_at','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
}
 