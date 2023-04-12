<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Estimate_model extends CI_Model{
      const EST="t_estimate";

  
    public function getOneEstimate($where_arr = '') {
           
        $this->db->select('t_estimate.*,first_name,last_name,email,customer_company_name,billing_street,billing_street_2,billing_city,billing_state,billing_zipcode,phone,property_address,program_name,program_price,yard_square_feet,difficulty_level,customers.user_id');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');

        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }  

    public function getAllEstimatePriceOveridewJob($where_arr = '') {
        $this->db->select('t_estimate_price_override.*, jobs.job_price, jobs.job_price_per, jobs.base_fee_override, jobs.min_fee_override');
        $this->db->from("t_estimate_price_override");
        $this->db->join("jobs", "t_estimate_price_override.job_id = jobs.job_id", "inner");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updateEstimate($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update(self::EST, $updatearr);
        return $a = $this->db->affected_rows();        
    }


     public function assignProgramProperty($post) {
        $this->db->insert('property_program_assign', $post);
          $insert_id = $this->db->insert_id();

       return  $insert_id;
    }

    public function getOneProgramProperty($where){
       return $this->db->where($where)->get('property_program_assign')->row();
        
    }

     public function updateProgramProperty($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update('property_program_assign', $updatearr);
        return $a = $this->db->affected_rows();        
    }

	public function getOneProperty($where_arr = '') {
      $this->db->select('*');
      $this->db->from('property_tbl');
      if (is_array($where_arr)) {
          $this->db->where($where_arr);
      }
      $result = $this->db->get();
      $data = $result->row();
      return $data;
    }

	public function getProgramPropertyEmailData($wherearr) {
       $this->db->select('first_name,last_name,program_name,property_address');
       $this->db->from('customers,programs,property_tbl');
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;   

    }

}
 