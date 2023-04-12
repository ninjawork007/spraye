<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Company_subscription_model extends CI_Model{
	
    const CMSB="t_company_subscription";

     public function createCompanySubscription($param) {

     $query = $this->db->insert(self::CMSB, $param);
     return $this->db->insert_id();

    }


    public function getOneCompanySubscription($where){
        return $this->db->select("*,t_company_subscription.subscription_id as subscription_id")->where($where)->join('t_subscription','t_subscription.subscription_unique_id=t_company_subscription.subscription_unique_id','inner')->get(self::CMSB)->row();
    }

    public function updateCompanySubscription($where,$post_data) {
    	$this->db->where($where);
      $this->db->update(self::CMSB,$post_data);

      return $this->db->affected_rows();

    }
}
 