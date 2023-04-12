<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Superadmin_model extends CI_Model{
      const ADMINTBL="t_superadmin";

   public function CreateOneAdmin($post) {
        $query = $this->db->insert(self::ADMINTBL, $post);
        return $this->db->insert_id();
    }

    public function getOneSuperAdmin($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::ADMINTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row_array();
        return $data;
    }


 

}
 