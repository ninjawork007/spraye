<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_customer_model extends CI_Model{
      const PMTBL="customers";

   public function insert_customer($post) {
        $query = $this->db->insert(self::PMTBL, $post);
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




    public function get_all_customer($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::PMTBL);
      
        //$this->db->join('property_tbl', "customer_property_assign.customer_id = customers.customer_id", "inner");


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

    

    public function updateAdminTbl($customerid, $post_data) {

         //$data = array('skills' => $post_data['skills']);
        
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

        $this->db->select('property_id,property_title');
        
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

    public function getCustomerList(){

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
        
        if($q->num_rows()>0)
        {
            return $q->result_array()[0];  
        }

    }

    public function getOneCustomer($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("customers");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
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

        //$this->db->select('property_id','property_title');
       $this->db->where('property_id',$propertyID);
        $q=$this->db->get('property_tbl');
        
        if($q->num_rows()>0)
        {
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

    public function getSelectedProperty($customerID){

        $this->db->select('property_id');
        
        $this->db->from('customer_property_assign');
        $this->db->where('customer_id',$customerID);

        
        $result = $this->db->get();

        $data = $result->result();
        return $data;

    }

    public function checkEmail($email){

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
    public function checkEmailonUpdate($email,$customerid){

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

        $this->db->select('*');
        
        $this->db->from("customers");

        $this->db->where('customer_id', $customerID);
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
    
    // Enhanced Notes
    public function getTechViewCustomerNotes($where)
    {
        $this->db->select('enhanced_customer_notes.*,users.id,users.user_first_name,users.user_last_name');
        $this->db->from('enhanced_customer_notes');
        $this->db->where($where);
        $this->db->join('users','users.id=enhanced_customer_notes.note_user_id');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    public function getTechNoteComments($where)
    {
        $this->db->from('enhanced_note_comments');
        $this->db->where($where);
        $this->db->join('users', 'users.id=enhanced_note_comments.user_id');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    /* Note Task Types */
  	public function getNoteTaskTypes() {
		$this->db->from('enhanced_note_task_types');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
  	}
      public function getAllpropertyExt($where_arr = '') {
		$this->db->select('*');
		$this->db->from('property_tbl');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$this->db->join('customer_property_assign','property_tbl.property_id=customer_property_assign.property_id','inner');
		$this->db->join('customers','customer_property_assign.customer_id=customers.customer_id','inner');
		$this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id', 'left');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}

    public function getAllCustomerByProperty($where_arr = '') {
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


 
/*
    $this->db->select('enhanced_property_notes.*,users.id,users.user_first_name,users.user_last_name');
    $this->db->from('enhanced_property_notes');
    $this->db->where($where);
    $this->db->join('users','users.id=enhanced_property_notes.note_user_id');
*/
	
	public function checkGroupBilling($customerID){
		$this->db->where(array('customer_id'=>$customerID,'billing_type'=>1));
		$result = $this->db->get('customers');
		if($result->num_rows()>0) {
			$data = $result->result();
			return "true";
		}else{
			return "false";
		}
	}
    public function getRescheduleReasonsList2($company_id) {
        $this->db->from("reschedule_reasons");
        $this->db->where('company_id', $company_id);
        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }
    public function getRescheduleReasonsList($company_id) {
        try {
//            $this->db->select('*');
//            $this->db->from("reschedule_reasons");
//            $this->db->where('company_id', $company_id);
//            $result = $this->db->get();
//            //echo $this->db->last_query();
//
//            //$data = $result->result_array();
//            //echo print_r($data);
//            if($result->num_rows()>0) {
//                $data = array_shift($result->result_array());
//                echo print_r($result->num_rows());
//                echo 3;
//                return "true";
//            }else{
//                return "false";
//            }
            $this->db->select('*');
            $this->db->from('property_tbl');

            $this->db->join('customer_property_assign','property_tbl.property_id=customer_property_assign.property_id','inner');
            $this->db->join('customers','customer_property_assign.customer_id=customers.customer_id','inner');
            $this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id', 'left');
            $result = $this->db->get();
            $data = $result->result();
            return $data;


        } catch (Exception $e) {
            echo 'error';
            throw new Exception('Error: ' . $e->getMessage());
            return false;
        }


    }
}
