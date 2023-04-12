<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_company_model extends CI_Model{
    const ST="t_company";

    public function getOneCompany($where){
        return $this->db->where($where)->get(self::ST)->row();
    }
    public function updateCompany($where,$post_data) {
      $this->db->where($where);
      $this->db->update(self::ST,$post_data);
	//die(print_r($this->db->last_query())); 
	  return $this->db->affected_rows();
      

    }
	public function getPaymentTerms($where) {
		$this->db->select('payment_terms');
        $this->db->from('t_company');
    	$this->db->where($where);
     	$result = $this->db->get();
        $data = $result->row();
        return $data;

    } 
	public function getOneBasysRequest($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('t_basys_request');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
	public function getOneAdminUser($where_arr = '') {       
        $this->db->select('*');
        $this->db->from('users');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
	 public function getOneCompanyEmailArray($where){
        return $this->db->where($where)->get('t_company_email_setting')->row_array();
    }

    public function getOneDefaultEmailArray(){
            return $this->db->get('t_superadmin')->row_array();
   }

   public function getOneCompanyEmail($where){
    return $this->db->where($where)->get('t_company_email_setting')->row();
}
}
