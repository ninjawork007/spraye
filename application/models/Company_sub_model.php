<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Company_sub_model extends CI_Model{
      const PMTBL="customers";

 

    public function updateCompanySub($where, $post_data) {

         //$data = array('skills' => $post_data['skills']);
        
        $this->db->where($where);
        return $this->db->update('t_company_subscription',$post_data);
        
    }

 

}
 