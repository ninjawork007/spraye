<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_customer_model extends CI_Model{
	const PMTBL="customers";
	const SETBL="";

	public function CreateOneCustomer($post) {
        $query = $this->db->insert(self::PMTBL, $post);
        return $this->db->insert_id();
    }
	public function updateCustomer($customer_id, $post_data) {
		$this->db->where('customer_id',$customer_id);
		return $this->db->update('customers',$post_data);
	}    

	public function updateCustomersTbl($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::PMTBL, $updatearr);
				// die(print_r($this->db->last_query()));
        return $a = $this->db->affected_rows();
        
    }

		// public function getOneCustomer($where_arr = '') {
           
    //     $this->db->select('*');
    //     $this->db->from(self::ADMINTBL);

    //     if (is_array($where_arr)) {
    //         $this->db->where($where_arr);
    //     }
        
    //     $result = $this->db->get();
    //     $data = $result->row();
    //     return $data;
    // }
		
	public function insert_customer($post) {
		$query = $this->db->insert(self::PMTBL, $post);
		die (print_r($this->db->last_query()));
		return $this->db->insert_id();
	}
	public function assignProgramscustomer($post) {
		$this->db->insert('customer_program_assign', $post);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	public function getAssignProgramscustomer($where_arr="") {
		$this->db->select('*');        
		$this->db->from('customer_program_assign');        
		$this->db->join('programs','programs.program_id = customer_program_assign.program_id','inner');
		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}  
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
	public function deleteassignProgramscustomer($wherearr) {
		if (is_array($wherearr)) {
				$this->db->where($wherearr);
		}        
		$this->db->delete('customer_program_assign');        
		$a = $this->db->affected_rows();
		if($a){
				return true;
		}
		else{
				return false;
		}
	}
	public function assignProperty($post) {
		$this->db->insert('customer_property_assign', $post);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}    
	public function getOnecustomerPropert($where_arr = '') {           
		$this->db->select('*');
		$this->db->from('customer_property_assign');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		$data = $result->row();
		return $data;
	}
	##### GET ALL CUSTOMER PROPERTIES IN AN ARRAY #####
	public function get_all_properties_array($where_arr = '') {
		$this->db->select('*');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
				$data = $result->result_array();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}
	#### ADDED BY RG 12/2/21 ####
	public function get_all_customer($where_arr = '') {           
		$this->db->select('*');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
				$data = $result->result();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}
	public function get_all_customer_array($where_arr = '') {
		$this->db->select('*');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
				$data = $result->result_array();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}
	public function getNumberOfCustomers($where_arr = '') {
		$this->db->select('*');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		return $result->num_rows();
	}
	public function updateAdminTbl($customerid, $post_data) {
		$this->db->where('customer_id',$customerid);
		return $this->db->update('customers',$post_data);
	}    
	public function deleteCustomer($wherearr) {
		if (is_array($wherearr)) {
				$this->db->where($wherearr);
		}        
		$this->db->delete(self::PMTBL);        
		$a = $this->db->affected_rows();
		if($a){
			return true;
		}
		else{
			return false;
		}
	}
	public function getPropertyAreaList(){
		$this->db->select('property_area_cat_id,category_area_name');
		$this->db->from('category_property_area');
		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			$data = $result->result();
			return $data;
		}
		else {
			return $result->num_rows();
		}
	}
	public function getPropertyList($where_arr=''){
		$this->db->select('property_id,property_title,property_address');
		$this->db->from('property_tbl');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
				$data = $result->result();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}
	public function getProgramList(){
		$this->db->select('program_id,program_name');
		$this->db->from('programs');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}		
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
				$data = $result->result();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}
	public function getCustomerList($where_arr=''){
		$this->db->select('customer_id,first_name,last_name');
		$this->db->from('customers');
		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			$data = $result->result();
			return $data;
		}
		else {
			return $result->num_rows();
		}
	}
	public function getCustomerDetail($customerID){
		$this->db->where('customer_id',$customerID);
		$q=$this->db->get('customers');
		if($q->num_rows()>0) {
			return $q->result_array()[0];
		}
	}
	/**
	 * Returns customer data based 
	 * @param array $where_arr array
	 * @return object $customer_obj 
	 */
		#### ADDED BY RG 2/11/22 ####
	public function getOneCustomer($where_arr = "") {
		$this->db->select('*');
		$this->db->from("customers");
		if (count($where_arr) > 0) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		$customer_obj = $result->row();
		return $customer_obj;
	}
	public function getOneCustomerSlug($where_arr = "") {
		$this->db->select('*');
		$this->db->from("customers");
		$this->db->join('t_company','t_company.company_id = customers.company_id','left');
		if (count($where_arr) > 0) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		$customer_obj = $result->row();
		return $customer_obj;
	}
	public function getassignedData($customerID){
		$this->db->select('property_id');
		$this->db->where('customer_id',$customerID);
		$result = $this->db->get('customer_property_assign');
		if ($result->num_rows() > 0) {
			$data = $result->result();
			return $data;
		}
		else {
			return $result->num_rows();
		}
	}
	public function getpropertyDetail($propertyID){        
		$this->db->where('property_id',$propertyID);
		$q=$this->db->get('property_tbl');
		if ($q->num_rows()>0) {
				return $q->result();
		}
	}
	public function getAllproperty($where_arr = '') {
		$this->db->select('*');
		$this->db->from('customer_property_assign');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$this->db->join('property_tbl','property_tbl.property_id=customer_property_assign.property_id','inner');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
	public function getAllCustomerByPropert($where_arr = '') {
		$this->db->select('*');
		$this->db->from('customer_property_assign');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$this->db->join('customers','customers.customer_id=customer_property_assign.customer_id','inner');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
	public function getSelectedProperty($customerID) {
		$this->db->select('property_id');
		$this->db->from('customer_property_assign');
		$this->db->where('customer_id',$customerID);
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
	public function checkEmail($email) {
		$this->db->where('email',$email);
		$result=$this->db->get('customers');
		
		if ($result->num_rows() > 0) {
				$data = $result->result();
				return true;
		} 
		else {
				return false;
		}
	}
	public function checkEmailonUpdate($email,$customerid) {
		$this->db->where('email',$email);
		$result=$this->db->get('customers');
		if ($result->num_rows() > 0) {
			$data = $result->result();
			return "true";
		}
		else {
			return "false";
		}
	}
	public function deleteAssignProperty($wherearr) {
		if (is_array($wherearr)) {
			$this->db->where($wherearr);
		}
		$this->db->delete('customer_property_assign');
		$a = $this->db->affected_rows();
		if($a){
			return true;
		}
		else{
			return false;
		}
	}
	public function getOneCustomerDetail($customerID){
		return $this->db->where('customer_id',$customerID)->get('customers')->row();
	}
	/**
	 * Updates record in Customers table based on provided argument data and filter criteria.
	 * @param array $data
	 * @param array $where	 
	 *  */					
	public function updateCustomerData($data, $where) {
		$this->db->where($where);
		$this->db->update(self::PMTBL,$data);
	}
}
 
