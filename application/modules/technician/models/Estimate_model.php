<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Estimate_model extends CI_Model{
      const EST="t_estimate";
      const ESTPR="t_estimate_price_override";

   public function CreateOneEstimate($post) {
        $query = $this->db->insert(self::EST, $post);
        return $this->db->insert_id();
    }

    public function getOneEstimate($where_arr = '') {
           
        $this->db->select('t_estimate.*,first_name,last_name,email,customer_company_name,billing_street,billing_city,billing_state,billing_zipcode,phone,program_name,program_price,yard_square_feet,property_address,difficulty_level,notes');
   
        
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

    public function getEstimatePropertiesById($program_id)
    {
        $this->db->select('property_id');
        $this->db->from(self::EST);
        $this->db->where('program_id',$program_id);
        $result = $this->db->get();
        $data = $result->result();
        $arr = array_column($data, 'property_id');
        return $arr;
    }

    public function getJustOneEstimate($where_arr = '') {
           
        $this->db->select('*');
   
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllEstimate($where_arr = '') {
           
        $this->db->select('t_estimate.*,first_name,last_name,email,program_name,program_price,yard_square_feet,property_address,difficulty_level');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }         
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');

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

    
    public function deleteEstimate($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::EST);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


     public function CreateOneEstimatePriceOverRide($post) {
        $query = $this->db->insert(self::ESTPR, $post);
        return $this->db->insert_id();
    }

      public function getOneEstimatePriceOverRide($where_arr = '') {
           
        $this->db->select('*');
   
        
        $this->db->from(self::ESTPR);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    
    
        $this->db->join('jobs','jobs.job_id = t_estimate_price_override.job_id','inner');
       
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllEstimatePriceOveride($where_arr = '') {
        $this->db->select('*');
        $this->db->from("t_estimate_price_override");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();
        $data = $result->result();
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


       public function deleteEstimatePriceOverRide($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::ESTPR);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    function getProgramPropertyJobPriceOverrides($where = '')
    {
        // $where = array('property_id' => $property_id, 'program_id' = $program_id) 
        // Raw SQL: SELECT * FROM `t_estimate_price_override` WHERE `property_id` = $property_id 
        //                  AND `program_id` = $program_id AND `customer_id` = $customer_id;
        $this->db->select('*');
        $this->db->from(self::ESTPR);
        if(is_array($where)) 
        {
            $this->db->where($where);
        }
        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        return $data;
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

    public function updateEstimateSignWellID($estimate_id, $signwell_id) {
        $this->db->where('estimate_id', $estimate_id);
        $this->db->update(self::EST, array('signwell_id'=>$signwell_id, 'status'=>1));
        return $a = $this->db->affected_rows();
    }

}
 