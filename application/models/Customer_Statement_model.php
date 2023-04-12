<?php 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 class Customer_statement_model extends CI_Model{
     const CUSTATE = "customer_statements_tbl";

     public function CreateOneCustomerStatement($post){
         $query = $this->db->insert(self::CUSTATE, $post);
         return $this->db->insert_id();
     }

     public function getOneCustomerStatement($where_arr = ''){
         $this->db->select('*');

         $this->db->from(self::CUSTATE);

         if (is_array($where_arr)){
             $this->db->where($where_arr);
         }

         $result = $this->db->get();

         $data = $result->row();

         return $data;
     }

     public function updateCustomerStatement($where_arr, $update_arr) {

        $this->db->where($where_arr);

        $this->db->update(self::CUSTATE, $update_arr);

        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


     public function deleteCustomerStatement($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::CUSTATE);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }
 }