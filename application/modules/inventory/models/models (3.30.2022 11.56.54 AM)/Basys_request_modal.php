<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Basys_request_modal extends CI_Model{
    const TBL="t_basys_request";

   public function CreateOneBasysRequest($post) {
        $query = $this->db->insert(self::TBL, $post);
        return $this->db->insert_id();
    }

    public function getOneBasysRequest($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllBasysRequest($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('sale_tax_area_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    

    public function updateBasysRequest($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::TBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteBasysRequest($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::TBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 