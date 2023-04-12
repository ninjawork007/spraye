<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Cardconnect_model extends CI_Model{
    const TBL="t_cardconnect";

    public function CreateOneCardConnect($post) {
        $query = $this->db->insert(self::TBL, $post);
        return $this->db->insert_id();
    }

    public function getOneCardConnect($where_arr = '') {

        $this->db->select('*');

        $this->db->from(self::TBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllCardConnect($where_arr = '') {

        $this->db->select('*');

        $this->db->from(self::TBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }



    public function updateCardConnect($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::TBL, $updatearr);
        $a = $this->db->affected_rows();
        return $a;

    }


    public function deleteCardConnect($wherearr) {

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
