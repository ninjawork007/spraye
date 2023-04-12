<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Company_email_model extends CI_Model{
	
    const CMPNYEML="t_company_email_setting";

     public function createCompanyEmail($param) {

     $query = $this->db->insert(self::CMPNYEML, $param);
     return $this->db->insert_id();

    }


    public function getOneCompanyEmail($where){
        return $this->db->where($where)->get(self::CMPNYEML)->row();
    }

    public function updateCompanyEmail($where,$post_data) {
    	$this->db->where($where);
      $this->db->update(self::CMPNYEML,$post_data);

      return $this->db->affected_rows();

    }
}
 