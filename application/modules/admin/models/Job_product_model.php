<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Job_product_model extends CI_Model{
      const JBPTBL="job_product_assign";
     
   public function CreateOneJobProduct($post) {
        $query = $this->db->insert(self::JBPTBL, $post);
        return $this->db->insert_id();
    }

    public function getOneJobProduct($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::JBPTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllJobProduct($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::JBPTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('products','products.product_id=job_product_assign.product_id','inner');
        
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    

    public function updateJobProduct($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::JBPTBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteJobProduct($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::JBPTBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getAllJobProductWhere($jobID, $grassType) {
    
        $this->db->select('*');
        $this->db->from('job_product_assign');
        $this->db->join('products','products.product_id=job_product_assign.product_id');
        $this->db->join('program_job_assign','program_job_assign.job_id=job_product_assign.job_id','left');
        // $this->db->join('property_program_assign','property_program_assign.program_id=program_job_assign.program_id','left');
        // $this->db->join('property_tbl','property_tbl.property_id=property_program_assign.property_id','left');
        
        $this->db->where('job_product_assign.job_id', $jobID);
        if(!empty($grassType)){
            $this->db->group_start();
            $this->db->or_like('`property_tbl`.`total_yard_grass`', $grassType);
            $this->db->group_end();
        }
       
        $result = $this->db->get();

        $data = $result->result();
        return $data;    
    }

    
    public function getSingleProductName($product_id){
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('product_id', $product_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data->product_name;
    }

}
 