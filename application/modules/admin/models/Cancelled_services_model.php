<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Cancelled_services_model extends CI_Model{

    const CST = 'cancelled_services_tbl';

    public function createCancelledService($post)
    {
        $query = $this->db->insert(self::CST, $post);
        return $this->db->insert_id();
    }

    public function getIsCancelledService($where){
        $this->db->select('*');
        $this->db->from('cancelled_services_tbl');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if($data){
            return $data[0]->is_cancelled;
        } else return 0;
    }

    public function getCancelledServiceInfo($where){
        $this->db->select('*');
        $this->db->from('cancelled_services_tbl');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        //die(print_r($this->db->last_query()));
        return $data;
    }

    public function updateCancelledServicesTable($up_arr,$post){
        $this->db->where($up_arr);
        $this->db->update('cancelled_services_tbl', $post);
        return $a = $this->db->affected_rows();
    }

    public function getCustomerInfoForEmail($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function getCancelledServiceName($job_id){
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('job_id', $job_id);
        $result = $this->db->get();
        $data = $result->result();
        if(!empty($data)){

            return $data[0];
        }
    }

    public function getCancelledProgramName($program_id){
        $this->db->select('*');
        $this->db->from('programs');
        $this->db->where('program_id', $program_id);
        $result = $this->db->get();
        $data = $result->result();
        if(!empty($data)){
            return $data[0];
        }
        
    }

    public function getCancelledPropertyName($property_id){
        $this->db->select('*');
        $this->db->from('property_tbl');
        $this->db->where('property_id', $property_id);
        $result = $this->db->get();
        $data = $result->result();
        if(!empty($data)){
            return $data[0];
        }
        
    }

    public function getCancelledServicesCountByUser($user_id){
        $this->db->select('*');
        $this->db->from('cancelled_services_tbl');
        $this->db->where('user_id', $user_id);
        $result = $this->db->get();
        $data = $result->result();
        if(!empty($data)){
            return count($data);
        } else {
            return 0;
        }
    }

    public function getCancelledServicesByCustomer($user_id){
        $this->db->select('*');
        $this->db->from('cancelled_services_tbl');
        $this->db->where('customer_id', $user_id);
        $result = $this->db->get();
        return $data = $result->result();
    }

    public function getCancelledServicesByProperty($user_id){
        $this->db->select('*');
        $this->db->from('cancelled_services_tbl');
        $this->db->where('property_id', $user_id);
        $result = $this->db->get();
        return $data = $result->result();
    }

    public function getCancelledServiceInfoDetails($where){
        $this->db->select('cancelled_services_tbl.*, property_tbl.property_created, property_tbl.yard_square_feet, property_tbl.difficulty_level');
        $this->db->from('cancelled_services_tbl');
		$this->db->join('property_tbl','property_tbl.property_id = cancelled_services_tbl.property_id','inner');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
	public function getCancelledServiceInfoDetailsBetween($where,$from='',$to=''){
        $this->db->select('cancelled_services_tbl.*, property_tbl.property_created');
        $this->db->from('cancelled_services_tbl');
		$this->db->join('property_tbl','property_tbl.property_id = cancelled_services_tbl.property_id','inner');
        $this->db->where($where);
		if($from != ''){
           $this->db->where('cancelled_services_tbl.created_at >=', $from);
        }     
        if($to != ''){
           $this->db->where('cancelled_services_tbl.created_at <=', $to);
        }
        $result = $this->db->get();
        $data = $result->result();
		//die(print_r($this->db->last_query()));
        return $data;
    }
}