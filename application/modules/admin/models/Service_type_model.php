<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Service_type_model extends CI_Model{
  const TBL="service_type_tbl";

  public function CreateOneServiceType($post) {
    $query = $this->db->insert(self::TBL, $post);
    return $this->db->insert_id();
  }
  public function getOneServiceType($where_arr = '') {
    $this->db->select('*');
    $this->db->from(self::TBL);
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $result = $this->db->get();
    $data = $result->row();
    return $data;
  }
  public function getAllServiceType($where_arr = '') {
    $this->db->select('*');
    $this->db->from(self::TBL);
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $this->db->order_by('service_type_id','desc');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function updateServiceType($wherearr, $updatearr) {
    $this->db->where($wherearr);
    $this->db->update(self::TBL, $updatearr);
    return $a = $this->db->affected_rows();        
  }    
  public function deleteServiceType($wherearr) {
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
  /**
	 * Updates record in service type table based on provided argument data and filter criteria.
	 * @param array $data
	 * @param array $where	 
	 *  */	
  public function updateServiceTypeData($data, $where) { 
    $this->db->where($where);
    $this->db->update(self::TBL);
  }
}
 