<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Data_table_manage_model extends CI_Model{
    const TBLDTMG="t_datatable_manage";

   public function CreateOneDataTable($post) {
        $query = $this->db->insert(self::TBLDTMG, $post);
        // return $this->db->insert_id();
        return true;
    }

    public function getOneOneDataTable($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TBLDTMG);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }


    public function updateOneDataTable($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::TBLDTMG, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteOneDataTable($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::TBLDTMG);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

} 
