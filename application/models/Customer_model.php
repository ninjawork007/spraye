<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Customer_model extends CI_Model{
      const PMTBL="customers";

 

      public function insert_customer($post) {
        $query = $this->db->insert(self::PMTBL, $post);
        return $this->db->insert_id();
      }

    public function updateCustomerTbl($where, $post_data) {

         //$data = array('skills' => $post_data['skills']);
        
        $this->db->where($where);
        return $this->db->update('customers',$post_data);
        
    }

       public function getCustomerDetail($customerID){
 
        $this->db->where('customer_id',$customerID);
        $q=$this->db->get('customers');
        return $q->row_array();  

    }
	
	 public function getOneCustomerDetail($customerID){
 		return $this->db->where('customer_id',$customerID)->get('customers')->row();
 	}

     public function assignProperty($post) {
        $this->db->insert('customer_property_assign', $post);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
      } 
      
	public function getOneCustomer($where_arr = []) {
      $this->db->select('*');
      $this->db->from("customers");
      if (count($where_arr) > 0) {
          $this->db->where($where_arr);
      }
      $result = $this->db->get();
      $customer_obj = $result->row();
      return $customer_obj;
    }
	
 	public function getOnecustomerPropert($where_arr = '') {           
      $this->db->select('*');
      $this->db->from('customer_property_assign');
      if (is_array($where_arr)) {
          $this->db->where($where_arr);
      }
      $result = $this->db->get();
      $data = $result->row();
      return $data;
    }

    public function getCustomerList($where_arr=''){
        $this->db->select('customer_id,first_name,last_name');
        $this->db->from('customers');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        }
        else {
            return $result->num_rows();
        }
    }


    public function getLatestCustomer($where_arr = []) {
        $this->db->select('*');
        $this->db->from("customers");
        if (count($where_arr) > 0) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('customer_id', 'desc');
        $result = $this->db->get();
        $customer_obj = $result->row();
        return $customer_obj;
      }


}
 