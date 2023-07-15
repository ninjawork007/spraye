<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminTbl_customer_model extends CI_Model{
	const PMTBL="customers";
    const PRPTBL="property_tbl";
	const SETBL="";

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

	public function formatStatus($status)
	{
		switch($status)
		{
			case 1:
				return "Active";
			case 2:
				return "Hold";
			case 3:
				return "Estimate";
			case 4:
				return "Sales Call Scheduled";
			case 5:
				return "Estimate Sent";
			case 6:
				return "Estimate Declined";
			case 7:
				return "Prospect";
			default:
				return "Non-Active";
		}
	}
	public function get_all_customer_ID_customerList($where_arr = '') {           
		$this->db->select('customer_id, first_name, last_name, phone, home_phone, work_phone, email, billing_street, customer_status');
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
	
    public function get_all_customer_marketing($where_arr = '') {           
		$this->db->select('customer_id, phone, work_phone, first_name, last_name, email, secondary_email, billing_street, billing_city, billing_state, billing_zipcode');

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
		$this->db->select('customer_id');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		return $result->num_rows();
	}

	public function getNumberOfCustomersByStatus($where_arr = '') {
		$this->db->select('COUNT(customer_id) as total_customer,
		COUNT(DISTINCT CASE WHEN customer_status = 1 THEN customer_id END) active_customer,
		COUNT(DISTINCT CASE WHEN customer_status = 2 THEN customer_id END) hold_customer,
		COUNT(DISTINCT CASE WHEN customer_status = 4 THEN customer_id END) sales_call_scheduled,
		COUNT(DISTINCT CASE WHEN customer_status = 5 THEN customer_id END) estimate_sent,
		COUNT(DISTINCT CASE WHEN customer_status = 6 THEN customer_id END) estimate_declined,
		COUNT(DISTINCT CASE WHEN customer_status = 7 THEN customer_id END) prospect,
		COUNT(DISTINCT CASE WHEN customer_status = 0 THEN customer_id END) non_active_customer');
		$this->db->from(self::PMTBL);
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$result = $this->db->get();
		return $result->result();
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
		$this->db->select('property_id,property_title,property_address, property_status');
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
	public function getOneCustomer($where_arr = []) {
		$this->db->select('*');
		$this->db->from("customers");
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




	public function getAllproperty_ID_customerList($where_arr = '') {
		$this->db->select('customer_property_assign.property_id, property_tbl.property_title');
		$this->db->from('customer_property_assign');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$this->db->join('property_tbl','property_tbl.property_id=customer_property_assign.property_id','inner');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}





	public function getAllpropertyExt($where_arr = '', $customer_id = '') {
            $this->db->select('property_tbl.*, customers.*, category_property_area.*, programs.*, GROUP_CONCAT(programs.program_name SEPARATOR "; ") as program_assigned');
		$this->db->from('property_tbl');
		if (is_array($where_arr)) {
				$this->db->where($where_arr);
		}
		$this->db->join('customer_property_assign','property_tbl.property_id=customer_property_assign.property_id','inner');
		$this->db->join('customers','customer_property_assign.customer_id=customers.customer_id','inner');
		$this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id', 'left');
		$this->db->join('property_program_assign', 'property_program_assign.property_id=property_tbl.property_id', 'left');
		$this->db->join('programs', 'programs.program_id=property_program_assign.program_id', 'left');
        if (!empty($customer_id)) {
            $this->db->order_by("FIELD(customers.customer_id, '$customer_id') DESC");
        }
        $this->db->group_by('property_tbl.property_id');
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
  /** Notes - Customer Specific **/
  public function addNote($data)
  {
    $query = $this->db->insert('enhanced_customer_notes', $data);
    return $this->db->insert_id();
  }
  public function getNotes($where)
  {
    // $propertyId = $where['property_id'];
    // $companyId = $where['company_id'];
    $this->db->select('*');
    $this->db->from('enhanced_customer_notes');
    $this->db->where($where);
    $this->db->join('users', 'users.id=enhanced_customer_notes.note_user_id','inner');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function closeNoteStatus($noteId) 
  {
    $this->db->set('note_status', 2);
    $this->db->where('note_id', $noteId);
    $result = $this->db->update('enhanced_customer_notes');
    return $result;
  }
  public function deleteNote($noteId) 
  {
		$where = array(
			'note_id' => $noteId,
			'note_type' => 1
		);
		$this->db->where($where);
		$this->db->delete(array('enhanced_note_comments','enhanced_customer_notes'));
		$result = $this->db->affected_rows();
		return $result;
  }

  public function updateNoteData($data, $where)
  {
    $this->db->where($where);
    return $this->db->update('enhanced_customer_notes', $data);
  }

  /** Note Comments **/
  public function getNoteComments($noteId, $note_type = 1)
  {
    $where = array(
      'note_id' => $noteId,
      'note_type' => $note_type
    );
    $this->db->from('enhanced_note_comments');
    $this->db->where($where);
    $this->db->join('users', 'users.id=enhanced_note_comments.user_id');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function addNoteComment($data)
  {
    $this->db->insert('enhanced_note_comments', $data);
    return $this->db->insert_id();
  }
  /* Note Task Types */
  	public function getNoteTaskTypes() {
		$this->db->from('enhanced_note_task_types');
		$result = $this->db->get();
		$data = $result->result();
		return $data;
  	}

	/* Get All Notes for CSR View */
	public function getAllCompanyNotes($where)
	{
		$this->db->from('enhanced_customer_notes');
		$this->db->join('users', 'users.id=enhanced_customer_notes.note_user_id','inner');
		$result = $this->db->get();
		$data1 = $result->result();

		$this->db->from('enhanced_property_notes');
		$this->db->join('users', 'users.id=enhanced_property_notes.note_user_id','inner');
		$result = $this->db->get();
		$data2 = $result->result();

		// $this->db->from('enhanced_technician_notes');
		// $this->db->join('users', 'users.id=enhanced_technician_notes.note_user_id','inner');
		// $result = $this->db->get();
		// $data3 = $result->result();		

		$data = array_merge($data1,$data2);
		return $data;
	}
	public function checkGroupBilling($customerID){
		$this->db->where(array('customer_id'=>$customerID,'billing_type'=>1));
		$result = $this->db->get('customers');
		if($result->num_rows()>0) {
			$data = $result->result();
			return true;
		}else{
			return false;
		}
	}

    public function autoStatusCheck($customer_id=0, $company_id=0){
    	$where = $customer_id ?  "a.customer_id={$customer_id}" : "a.company_id={$company_id}";
        $sql = "SELECT 
				    a.customer_id,
					GROUP_CONCAT(a.customer_status) customer_status,
					GROUP_CONCAT(c.property_status) property_status,
					GROUP_CONCAT(d.status) estimate_status
					-- ,GROUP_CONCAT(c.property_id) property_id
				FROM
				    customers a
				        INNER JOIN
				    customer_property_assign b ON b.customer_id = a.customer_id
				        INNER JOIN
				    property_tbl c ON b.property_id = c.property_id
				    	LEFT JOIN
					t_estimate d ON d.property_id=c.property_id
				WHERE {$where}
				GROUP BY a.customer_id
                ";
        $query = $this->db->query($sql);
        $result =   $query->result();
        if($result){
        	// print_r($result);
        	foreach($result as $row){
        		// IF THE CUSTOMER IS SET TO HOLD WE DO NOT WANT TO CHANGE THE CUSTOMER STATUS AT ALL
                // ALSO NEED TO IGNORE THIS LOGIC IF THE CUSTOMER IS NON ACTIVE
                if(!in_array("2", explode(",",$row->customer_status)) && !in_array("0", explode(",",$row->customer_status))) {
                    // we need to go through all the properties they have and check for the following rules
                    /*
                        - If a customer has all “Non-Active” properties, the customer should show non-active.
                        - If a customer has any properties with an "Active" status, the customer should automatically be active.
                        - If a customer has no active properties but has at least one property with an "Estimate Sent" status, the customer status should be "Estimate Sent"
                        - If a customer has no “sales call scheduled”, “estimate sent” or “active” properties but has at least 1 property with a "Prospect" status, then the customer status should be "Prospect”
                        - If a customer only has “Estimate Declined” properties, then it’s status should change to “Estimate Declined”
                    */
                    
                    $all_property_status_array = explode(",",$row->property_status);
                    if(in_array("5",$all_property_status_array) && !in_array("1",$all_property_status_array)){
                        // print "Customer Status for {$customer_id} = Estimate Sent";
                        $this->db->update(self::PMTBL,['customer_status' => 5], ['customer_id' => $row->customer_id]);
                    }
                    if(in_array("2",$all_property_status_array) && !in_array("1",$all_property_status_array) && !in_array("4",$all_property_status_array) && !in_array("5",$all_property_status_array) && !in_array("6",$all_property_status_array)){
                        // print "Customer Status for {$customer_id} = Propect";
                        $this->db->update(self::PMTBL,['customer_status' => 7], ['customer_id' => $row->customer_id]);
                    }
                    if(in_array("4",$all_property_status_array) && !in_array("1",$all_property_status_array) && !in_array("2",$all_property_status_array) && !in_array("5",$all_property_status_array) && !in_array("6",$all_property_status_array)){
                        // print "Customer Status for {$customer_id} = Sales Call Schedule";
                        $this->db->update(self::PMTBL,['customer_status' => 4], ['customer_id' => $row->customer_id]);
                    }
                    $all_nonactive = true;
                    $all_esimate_declined = true;
                    foreach($all_property_status_array as $apsa) {
                        if($apsa != 0) {
                            $all_nonactive = false;
                        }
                        if($apsa != 6) {
                            $all_esimate_declined = false;
                        }
                    }
                    if($all_nonactive == true) {
                        // set this customer to non active
                        $this->db->update(self::PMTBL,['customer_status' => 0], ['customer_id' => $row->customer_id]);
                    }
                    if(in_array("1",explode(",",$row->property_status))){
                        // print "Customer Status for {$customer_id} = Active";
                        $this->db->update(self::PMTBL,['customer_status' => 1], ['customer_id' => $row->customer_id]);
                    }
                    if($all_esimate_declined == true) {
                        $this->db->update(self::PMTBL,['customer_status' => 6], ['customer_id' => $row->customer_id]);
                    }
                }
        	}
            return true;
        }else{
            return false;
        }
    }

	public function addCancelReasons($param) {
		$query = $this->db->insert("cancel_reasons", $param);
		return $this->db->insert_id();
	}

	public function editCancelReason($data, $where)
   {
      $this->db->where($where);
      return $this->db->update("cancel_reasons", $data);
   }

   public function deleteCancelReason($where)
   {
      $this->db->where($where);
      return $this->db->delete("cancel_reasons", $where);
   }
   /** Rescheduled reasons functions*/
    public function addRescheduleReasons($param) {
        $query = $this->db->insert("reschedule_reasons", $param);
        return $this->db->insert_id();
    }

    public function addSkipReasons($param) {
        $query = $this->db->insert("skip_reasons", $param);
        return $this->db->insert_id();
    }

    public function editRescheduleReason($data, $where)
    {
        $this->db->where($where);
        return $this->db->update("reschedule_reasons", $data);
    }

    public function editSkipReason($data, $where)
    {
        $this->db->where($where);
        return $this->db->update("skip_reasons", $data);
    }

    public function deleteRescheduleReason($where)
    {
        $this->db->where($where);
        return $this->db->delete("reschedule_reasons", $where);
    }

    public function deleteSkipReason($where)
    {
        $this->db->where($where);
        return $this->db->delete("skip_reasons", $where);
    }

    public function getCancelReasons ($company_id) {
	  $this->db->from("cancel_reasons");
      $this->db->where('company_id', $company_id);
      return $this->db->get()->result();
	}
    public function getCancelReasonsMarketing ($company_id) {
        $this->db->select('cancel_name');
        $this->db->from("cancel_reasons");
        $this->db->where('company_id', $company_id);
        return $this->db->get()->result();
      }
    public function getRescheduleReasonsList($company_id) {
        $this->db->from("reschedule_reasons");
        $this->db->where('company_id', $company_id);
        //die($this->db->last_query());
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSkipReasonsList($company_id) {
        $this->db->from("skip_reasons");
        $this->db->where('company_id', $company_id);
        //die($this->db->last_query());
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updateCustomerStatus($customerID,$status) {
        $this->db->where('customer_id', $customerID );
        return $this->db->update(self::PMTBL,array('property_status'=> $status));
    }


    public function getCustomerListFromAutoComplete($companyID, $textSample) {
        $this->db->select('customer_id,first_name,last_name');
        $this->db->from('customers');

        $this->db->where('company_id', $companyID);
        $this->db->where('(first_name like "%'.$textSample.'%" or last_name like "%'.$textSample.'%")');
        
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        } else {
            return $result->num_rows();
        }
    }



    public function getPropertyListFromAutoComplete($companyID, $textSample) {
       
        $this->db->select('property_id,property_title,property_address, property_status');
		$this->db->from('property_tbl');


        $this->db->where('company_id', $companyID);
        $this->db->like('property_title', $textSample, 'after');
        
        
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        } else {
            return $result->num_rows();
        }
    }




	public function getSelectedPropertyDetails($propertyId){
		$this->db->select('property_title,property_address, property_status');
		$this->db->from('property_tbl');

		$this->db->where('property_id', $propertyId);

		$result = $this->db->get();

		if ($result->num_rows() > 0) {
				$data = $result->result();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}




    public function getCompanyForCustomer($custId){
		$this->db->select('company_id');
		$this->db->from('customers');

		$this->db->where('customer_id', $custId);

		$result = $this->db->get();

		if ($result->num_rows() > 0) {
				$data = $result->result();
				return $data;
		}
		else {
				return $result->num_rows();
		}
	}


}
 
