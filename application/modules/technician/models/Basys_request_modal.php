<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Basys_request_modal extends CI_Model{
    const TBL="t_basys_request";


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

 

}
 