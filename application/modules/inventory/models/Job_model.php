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
        $this->db->select('jobs.*, service_type_name');
        
        $this->db->from(self::JBRBL);

        $this->db->join('service_type_tbl','service_type_tbl.service_type_id=jobs.service_type_id','left');
        // $this->db->join('service_commissions_tbl','service_commissions_tbl.commission_type=jobs.commission_type','left');
        // $this->db->join('service_bonuses_tbl','service_bonuses_tbl.bonus_type=jobs.bonus_type','left');

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

    public function GetAllServices($where_arr = '',$where_in = '') {

        $this->db->select('*');
        $this->db->select('jobs.*');
        $this->db->from(self::JBRBL);
        $this->db->join('job_product_assign','job_product_assign.job_id =jobs.job_id','inner');
        $this->db->join('products','products.product_id =job_product_assign.product_id','inner');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');


        // $this->db->join("technician_job_assign", "technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");

        // $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");

        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");


        $this->db->where("(is_job_mode != 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL"); 

        if (is_array($where_arr)) {
                $this->db->where($where_arr);
            }

        if (is_array($where_in) && array_key_exists( 'job_name', $where_in )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        
        $this->db->order_by('jobs.job_id','asc');
        
        $result = $this->db->get();
        $data = $result->result();
        //  die( print_r($this->db->last_query()));  

        return $data;
    }

    public function getUnassignJobsWhere($jobId, $grassType) {
        $this->db->select("technician_job_assign_id,technician_id,technician_job_assign_id,invoice_id,program_name,programs.program_id,is_job_mode,is_complete,job_name,job_assign_date,job_assign_updated_date,jobs.job_id,property_tbl.*,products.*");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');

        // latest property job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");

      
        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", " unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        $this->db->join("job_product_assign", "job_product_assign.job_id = jobs.job_id", "inner");
        $this->db->join("products", "products.product_id = job_product_assign.product_id", "inner");
        
        $this->db->where("(is_job_mode = 2 OR is_job_mode = 0 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

        // if (is_array($where_arr)) {
        //     $this->db->where($where_arr);
        // }
     
        $this->db->where('jobs.job_id',$jobId);
       

        // $this->db->order_by('job_assign_date ASC, technician_id ASC');
        $result = $this->db->get();
        $data = $result->result();
        $new_data = array();
        if(!empty($data)){
            foreach($data as $d){
                if($grassType != ''){
                    if($d->front_yard_grass == $grassType || $d->back_yard_grass == $grassType){
                        array_push($new_data, $d);
                    } else if($d->total_yard_grass == $grassType){
                        array_push($new_data, $d);
                    }
                } else {
                    array_push($new_data, $d);
                }
            }
        }
        return $new_data;
    }

    

}
 