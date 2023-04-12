<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Job_model extends CI_Model{
      const JBRBL="jobs";

   public function CreateOneJob($post) {
        $query = $this->db->insert(self::JBRBL, $post);
        return $this->db->insert_id();
    }

    public function getOneJob($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::JBRBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllJob($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::JBRBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('job_id','asc');
        
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

   public function getJobList($where_arr = '') {
    	$this->db->select('job_id,job_name');
		$this->db->from(self::JBRBL);
		if (is_array($where_arr)) {
		  $this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
		  $data = $result->result();
		  return $data;
		} else {
		  return $result->num_rows();
		}
  }

    public function updateJob($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::JBRBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }
    public function updateJobTbl($job_id, $post_data) {

        $this->db->where('job_id',$job_id);
        $this->db->update('jobs', $post_data);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteJob($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::JBRBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

     public function getSelectedProduct($job_id){

        $this->db->select('product_id');
        
        $this->db->from('job_product_assign');
        $this->db->where('job_id',$job_id);

        
        $result = $this->db->get();

        $data = $result->result();
        return $data;

    }

    public function deleteAssignProduct($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete('job_product_assign');
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

     public function assignProduct($post) {
        $this->db->insert('job_product_assign', $post);
   $insert_id = $this->db->insert_id();

   return  $insert_id;
    }
 

}
 