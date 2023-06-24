<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Program_job_assigned_customer_property_model extends CI_Model{

    const PJACPM = 'program_job_assigned_customer_property';

    public function createOrUpdateProgramJobAssignedCustomerProperty($post)
    {
        $isExistedData = $post;
        unset($isExistedData['reason']);
        unset($isExistedData['hold_until_date']);
        $result = $this->db->select('*')
                 ->from('program_job_assigned_customer_property')
                 ->where($isExistedData)
                 ->get();
        $data = $result->result();
        if ($data) {
            $this->db->where($isExistedData);
            $this->db->update(self::PJACPM, $post);
            return $this->db->affected_rows();
        } else {
            $this->db->insert(self::PJACPM, $post);
            return $this->db->insert_id();
        }
    }

    public function getIsAsap($where){
        $this->db->select('*');
        $this->db->from('program_job_assigned_customer_property');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if ($data && isset($data[0]) && !empty($data[0]->reason)) {
            return 1;
        }
        return 0;
    }

    public function update($where, $update)
    {
        $this->db->where($where);
        $this->db->update(self::PJACPM, $update);
        return $this->db->affected_rows();
    }

    public function delete($where)
    {
        if (is_array($where)) {
            $this->db->where($where);
            $this->db->delete(self::PJACPM);

            return $this->db->affected_rows();
        }
        return false;
    }



}