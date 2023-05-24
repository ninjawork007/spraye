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

  public function updateAdminTblZap($property_id, $post_data) {  
      
    $this->db->where('property_id', $property_id); 
    $result = $this->db->get('property_tbl');
    $row = $result->row_array();
    //return $row['tags'];
    
    //check if tag exists already. if so skip. else insert.  
    if($row['tags'] != null){
        $tagsArray = explode(",", $row['tags']);
        if ( in_array($post_data['tag'], $tagsArray) ) 
        {
            //skip
        } else {
            array_push($tagsArray, $post_data['tag']);
            $newTags = implode(",", $tagsArray);

            $data = array(
                'tags' => $newTags
            );
                        
            $this->db->where('property_id', $property_id);
            return $this->db->update('property_tbl', $data);
        }

    }else{
        $this->db->where('property_id', $property_id);
        return $this->db->update('property_tbl', $post_data);
    }

    return false;
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

 public function getTagsList($where_arr=''){
    $this->db->select('id,tags_title');
    $this->db->from('tags');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
      $this->db->or_where('company_id',0);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
        $data = $result->result();
        return $data;
    }
    else {
        return $result->num_rows();
    }
  }


  public function getTagsListZap($where_arr=''){
    $this->db->select('id,tags_title');
    $this->db->from('tags');
    if (is_array($where_arr)) {
            $this->db->where($where_arr);
    }
    //$this->db->or_where('company_id',0);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
    }
    else {
            return $result->num_rows();
    }
}




  public function getLatestProperty($where_arr = []) {
    $this->db->select('*');
    $this->db->from(self::PMTBL);
    if (count($where_arr) > 0) {
        $this->db->where($where_arr);
    }
    $this->db->order_by('property_id', 'desc');
    $result = $this->db->get();
    $property_obj = $result->row();
    return $property_obj;
  }



  public function autoStatusCheck($company_id=0, $PropId=0){
    // If a property has an estimate that has been marked "Sent" and they are not currently "Active", then they should be changed to a status of "Estimate Sent”
    $sql = "SELECT 
                a.property_status, a.property_id, GROUP_CONCAT(b.status) as e_status
            FROM
                property_tbl a
                    INNER JOIN
                t_estimate b ON b.property_id = a.property_id
            WHERE
                a.property_status != 1
            Group by
                a.property_id
            ";
    $query = $this->db->query($sql);
    $result =   $query->result();
    if($result){
        foreach($result as $row){
            $all_estimate_statuses = explode(",", $row->e_status);
            // now if we array unique this and we are only left with 5 then EVERY estimate for this property is declined and we need to move the status if the old status was Estimate sent
            $all_estimate_statuses = array_unique($all_estimate_statuses);
            if(count($all_estimate_statuses) == 1 && $all_estimate_statuses[0] == "5" && $row->property_status == "5") {
                $this->db->update(self::PMTBL,['property_status' => 6], ['property_id' => $row->property_id]);
            } elseif($row->property_status != '1' && in_array('1',$all_estimate_statuses) == true) {
                $this->db->update(self::PMTBL,['property_status' => 5], ['property_id' => $row->property_id]);
            }
        }
    }
    // now we need to handle the next line of logic
    // If a property has a “Sales Call” currently scheduled to be completed, then the property status should change to “Sales Call Scheduled”
    $this->db->select("is_complete,job_name");
    $this->db->from('technician_job_assign');
    $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
    $this->db->where('technician_job_assign.is_job_mode = 1 and technician_job_assign.is_complete = 0');
    
    $result2 = $this->db->get();
    $data2 = $result2->result();
    foreach($data2 as $d2) {
        if(strpos($d2->job_name, 'Sales Visit') !== false) {
            $this->db->update(self::PMTBL,['property_status' => 5], ['property_id' => $row->property_id]);
        }
    }



    // check for if property is set to active after a program is assigned
    if($PropId != 0){
        $this->db->select("property_status");
        $this->db->from('property_tbl');
        $this->db->where('property_id', $PropId);
        
        $result3 = $this->db->get();
        $propertyStatus = $result3->result();


        $this->db->select("program_id");
        $this->db->from('property_program_assign');
        $this->db->where('property_id', $PropId);
        $programAssigned = $this->db->count_all_results();

                   
        if($propertyStatus != 1 && $programAssigned > 0){
            $data3= array(
                'property_status' => 1                
        );
            //$this->db->replace('property_tbl', $data3);
            $this->db->where('property_id', $PropId);
            $this->db->update('property_tbl', $data3);
        }
    }



    
}
 

}
