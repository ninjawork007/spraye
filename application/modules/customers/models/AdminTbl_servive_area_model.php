<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_servive_area_model extends CI_Model{
      const SRATBL="category_property_area";

   public function CreateOneServiceArea($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }

    public function getOneServiceArea($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::SRATBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllServiceArea($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::SRATBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('property_area_cat_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    

    public function updateServiceArea($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::SRATBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteServiceArea($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::SRATBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

 

}
 