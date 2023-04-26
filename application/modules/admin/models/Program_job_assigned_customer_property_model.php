<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Program_job_assigned_customer_property_model extends CI_Model{

    const PJACPM = 'program_job_assigned_customer_property';

    public function createProgramJobAssignedCustomerProperty($post)
    {
        $query = $this->db->insert(self::PJACPM, $post);
        return $this->db->insert_id();
    }

    public function getIsAsap($where){
        $this->db->select('*');
        $this->db->from('program_job_assigned_customer_property');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if($data){
            return 1;
        }
        return 0;
    }

}