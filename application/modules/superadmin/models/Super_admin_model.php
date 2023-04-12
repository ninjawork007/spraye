<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Super_admin_model extends CI_Model{   
    const TBLCOMP="t_company";
    const ADMINTBL="users";

    public function createCompany($param) {

     $query = $this->db->insert(self::TBLCOMP, $param);
     return $this->db->insert_id();

    }



    public function getOneCompany($where_arr='') {

        $this->db->select('*');
        
        $this->db->from(self::TBLCOMP);
        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data; 

    }

    public function getAllCompany($wherearr='') {
 
           
        $this->db->select('*');
        $this->db->from(self::TBLCOMP);
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data; 
    }

    public function getAllCompanyUsers() {
 
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('is_active', true); 
        
        $result = $this->db->get();
        $data = $result->result();
        return $data; 
        
    }


    public function getAllCompanyProperties() {
 
        $this->db->select('*');
        $this->db->from('property_tbl');

        $result = $this->db->get();
        $data = $result->result();
        return $data; 
        
    }


    public function getAllCompanyLoginDates() {
 
        $this->db->select('*');
        $this->db->from('users');

        $result = $this->db->get();
        $data = $result->result();


        return $data; 
        
    }

    
    

    public function updateCompanyDetails($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::TBLCOMP, $updatearr);
        return $a = $this->db->affected_rows();
    }
    
    public function softDeleteCompanyDetails($wherearr) {

        //Transaction start
        $this->db->trans_start();

        //soft deleting company details
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $this->db->update(self::TBLCOMP, array("deleted_at"=>date("Y-m-d h:i:sa")));

        //soft deleting user details
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $this->db->update(self::ADMINTBL, array("deleted_at"=>date("Y-m-d h:i:sa")));

        $this->db->trans_complete();
        //Transaction end

        if ($this->db->trans_status() === FALSE){
            return false;
        }
        else{
            return true;
        }
    }

    public function recoverDeleteCompany($wherearr) {

        //Transaction start
        $this->db->trans_start();

        //recover company details
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $this->db->update(self::TBLCOMP, array("deleted_at"=>NULL));

        //recover user details
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $this->db->update(self::ADMINTBL, array("deleted_at"=>NULL));

        $this->db->trans_complete();
        //Transaction end

        if ($this->db->trans_status() === FALSE){
            return false;
        }
        else{
            return true;
        }
    }

    public function deleteCompanyDetails($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }

        $this->db->delete(self::TBLCOMP);

        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getData($where)
    {
        $query = $this->db->where($where)
                          ->get('t_subscription');
        return $query->row_array();
    }

    public function getAllCompanySlugDuplicates($wherearr='') {
        
        $this->db->select('*');
        $this->db->from(self::TBLCOMP);
        if (is_array($wherearr)) {
            // $this->db->group_by('slug')->having('count(slug) > 1');
            $this->db->where($wherearr);
            // $this->db->where('`slug` IN (select slug FROM t_company HAVING COUNT(slug) >1)', NULL, FALSE); 
            // $this->db->select('*');
            // $this->db->from(self::TBLCOMP);
            // $this->db->having('count(slug) > 1'); 
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    //  $this->db->select('`id`');
    // $this->db->from('cars');
    // $this->db->group_by('slug');
    // $this->db->having('count(slug) > 1');
    // $where_clause = $this->db->get_compiled_select();

    // $this->db->get('cars');
    // $this->db->where('`id` IN ($where_clause)', NULL, FALSE); 
 

}
 
