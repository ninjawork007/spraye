<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminTbl_property_model extends CI_Model
{
  const PMTBL = "property_tbl";
  public function insert_property($post) {
    $query = $this->db->insert(self::PMTBL, $post);
    return $this->db->insert_id();
  }
  public function assignCustomer($post) {
    $this->db->insert('customer_property_assign', $post);
    $insert_id = $this->db->insert_id();
    return $insert_id;
  }
  public function assignProgram($post) {
    $this->db->insert('property_program_assign', $post);
    $insert_id = $this->db->insert_id();
    return $insert_id;
  }
  public function getOnePropertyProgram($where) {
    return $this->db->where($where)->get('property_program_assign')->row();
  }
  public function get_all_property($where_arr = '') {
    $this->db->select('*');
    $this->db->from(self::PMTBL);
    $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
        $data = $result->result();
        return $data;
    } else {
        return $result->num_rows();
    }
  }
  public function updateAdminTbl($property_id, $post_data) {
    $this->db->where('property_id', $property_id);
    return $this->db->update('property_tbl', $post_data);
  }
  public function deleteProperty($wherearr) {
    if (is_array($wherearr)) {
        $this->db->where($wherearr);
    }
    $this->db->delete(self::PMTBL);
    $a = $this->db->affected_rows();
    if ($a) {
        return true;
    } else {
        return false;
    }
  }
  public function getPropertyAreaList($where_arr = '') {
    $this->db->select('property_area_cat_id,category_area_name');
    $this->db->from('category_property_area');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
        $data = $result->result();
        return $data;
    } else {
        return $result->num_rows();
    }
  }

  public function getProgramList($where_arr = '') {
    $this->db->select('program_id,program_name');
    $this->db->from('programs');
    if (is_array($where_arr)) {
      $this->db->where($where_arr);
    }
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $data = $result->result();
      return $data;
    } else {
      return $result->num_rows();
    }
  }

  public function getCustomerList($where_arr = '') {
    $this->db->select('customer_id,first_name,last_name,billing_street');
    $this->db->from('customers');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
        $data = $result->result();
        return $data;
    } else {
        return $result->num_rows();
    }
  }
public function getAllCustomerProperties($customer_id) {
    $this->db->select('*');
    $this->db->from('customer_property_assign');
    $this->db->join('property_tbl', 'property_tbl.property_id=customer_property_assign.property_id', 'inner');
    $this->db->where('customer_id',$customer_id);
    
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getAllcustomer($where_arr = '') {
    $this->db->select('*');
    $this->db->from('customer_property_assign');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $this->db->join('customers', 'customers.customer_id=customer_property_assign.customer_id', 'inner');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getAllprogram($where_arr = '') {
    $this->db->select('*');
    $this->db->from('property_program_assign');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $this->db->join('programs', 'programs.program_id=property_program_assign.program_id', 'inner');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }    
  public function checkProperty($param, $property_id = '') {
    $this->db->where('property_title', $param['property_title']);
    $this->db->where('property_address', $param['property_address']);
    $this->db->where('property_city', $param['property_city']);
    $this->db->where('property_state', $param['property_state']);
    $this->db->where('property_area', $param['property_area']);
    $this->db->where('property_type', $param['property_type']);
    $this->db->where('property_zip', $param['property_zip']);
    if ($property_id != '') {
      $this->db->where('property_id !=', $property_id);
    }
    $result = $this->db->get('property_tbl');
    if ($result->num_rows() > 0) {
      $data = $result->result();
      return "true";
    } else {
      return "false";
    }
  }
  public function getOneProperty($where_arr = '') {
    $this->db->select('*');
    $this->db->from('property_tbl');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $result = $this->db->get();
    $data = $result->row();
    return $data;
  }
  public function getPropertyDetail($propertyID) {
    $this->db->where('property_id', $propertyID);
    $q = $this->db->get('property_tbl');
    if ($q->num_rows() > 0) {
        return $q->result_array()[0];
    }
  }
  public function getOnePropertyDetail($propertyID) {
    $this->db->where('property_id', $propertyID);
    $q = $this->db->get('property_tbl');
    return $q->row();
  }
  public function getSelectedProgram($propertyID) {
    $this->db->select('*');
    $this->db->from('property_program_assign');
    $this->db->join('programs', 'programs.program_id=property_program_assign.program_id', 'inner');
    $this->db->where('property_id', $propertyID);
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getSelectedCustomer($propertyID) {
    $this->db->select('customer_id');
    $this->db->from('customer_property_assign');
    $this->db->where('property_id', $propertyID);
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function deleteAssignCustomer($wherearr) {
    if (is_array($wherearr)) {
        $this->db->where($wherearr);
    }
    $this->db->delete('customer_property_assign');
    $a = $this->db->affected_rows();
    if ($a) {
        return true;
    } else {
        return false;
    }
  }
  public function deleteAssignProgram($wherearr) {
    if (is_array($wherearr)) {
        $this->db->where($wherearr);
    }
    $this->db->delete('property_program_assign');
    $a = $this->db->affected_rows();
    if ($a) {
        return true;
    } else {
        return false;
    }
  }
  /**
  * Updates record in Properties table based on provided argument data and filter criteria.
  * @param array $data
  * @param array $where
  *  */
  public function updatePropertyData($data, $where) {
    $this->db->where($where);
    $this->db->update(self::PMTBL,$data);
  }
	public function updatePropertyPropgramData($data, $where) {
    $this->db->where($where);
    return $this->db->update('property_program_assign', $data);
  }
  public function updateGroupBilling($group_billing_id, $post_data) {
    $this->db->where('group_billing_id', $group_billing_id);
    return $this->db->update('group_billing', $post_data);
  }
  public function getGroupBillingByProperty($property_id){
    $this->db->select('*');
    $this->db->from('group_billing');
	$this->db->where('property_id', $property_id);
	
	$result = $this->db->get();
	if ($result->num_rows() > 0) {
        return $result->result_array()[0];
    }
 }
}
