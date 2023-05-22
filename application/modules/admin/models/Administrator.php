<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Administrator extends CI_Model{
      const ADMINTBL="users";

   public function CreateOneAdmin($post) {
        $query = $this->db->insert(self::ADMINTBL, $post);
        return $this->db->insert_id();
    }

    public function getOneAdmin($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::ADMINTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getNumberOFTechnician($where_arr = '') {
           
        $this->db->select(' count(id) as tech_count ');
        
        $this->db->from(self::ADMINTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllAdmin($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::ADMINTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllAdminMarketing($where_arr = '') {
           
        $this->db->select('user_first_name, user_last_name, user_id, id');
        
        $this->db->from(self::ADMINTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllCompanyUsers($where_arr) 
    {
        $this->db->from(self::ADMINTBL);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('id','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }    

    public function updateAdminTbl($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::ADMINTBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteAdmin($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::ADMINTBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    //  public function getAssignUsers() {
    //        $sql = "select * from `users` where `company_id` = '".$this->session->userdata['company_id']."' and where ( `role_id` = 1 or `role_id` = 4) order by `id` desc ";
           
    //       $data  = $this->db->query($sql)->get()->result();

    //       print_r($data);

        

    //     $this->db->order_by('id','desc');
    //     $result = $this->db->get();

    //     $data = $result->result();
    //     return $data;
    // }


    public function getUserById($user_id) {
        $this->db->from(self::ADMINTBL)
            ->where('id', $user_id);

        return $this->db->get()->row_array();
    }
}
 
