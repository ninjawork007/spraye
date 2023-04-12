<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_company_model extends CI_Model{
    const ST="t_company";
    const FV = 'fleet_vehicles';
    const VN = 'vehicle_notes';
    const VNA = 'vehicle_note_attachments';
    const FM = 'fleet_maintenance';
  
    public function getOneCompany($where){
        return $this->db->where($where)->get(self::ST)->row();
    }
    public function getOneCompanyUser($where){
        return $this->db->where($where)->get('users')->row();
    }
    public function updateCompany($where,$post_data) {
      $this->db->where($where);
      $this->db->update(self::ST,$post_data);
	//die(print_r($this->db->last_query())); 
	  return $this->db->affected_rows();
    }

    // start KT and EE delete or addSlug //
    public function updateSlug($where,$post_data) {
      $this->db->where($where);
      $this->db->update(self::ST,$post_data);
	//die(print_r($this->db->last_query())); 
	  return $this->db->affected_rows();
    }

    // end KT and EE delete or addSlug //

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
	public function getOneCompanyEmail($where){
        return $this->db->where($where)->get('t_company_email_setting')->row();
    }
	 public function getOneCompanyEmailArray($where){
        return $this->db->where($where)->get('t_company_email_setting')->row_array();
    }

    public function getOneDefaultEmailArray(){
            return $this->db->get('t_superadmin')->row_array();
    }

   /* Enhanced Notes */
	const ENOTES="e_notes";
	const NTYPES="e_note_types";
    const NFILES="e_note_files";
    const ECMMTS="e_note_comments";

   public function addNote($data)
   {
      $query = $this->db->insert(self::ENOTES, $data);
      return $this->db->insert_id();
   }   

   public function getCompanyNotes($companyId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_company_id',$companyId);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
	  $this->db->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left');
      return $this->db->get()->result();
   }

   public function getNote($noteId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_id',$noteId);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      return $this->db->get()->result();
   }

   public function getUserNotes($userId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_user_id',$userId);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      return $this->db->get()->result();
   }

   public function getCustomerNotes($customerId) 
   {
      $this->db->from(self::ENOTES);
      $where = array(
         'note_customer_id' => $customerId,
         'note_category' => 1
      );
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
      return $this->db->get()->result();
   }

   public function getPropertyNotes($propertyId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_property_id',$propertyId);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
	  $this->db->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left');
      return $this->db->get()->result();
   }

   public function getCustomerPropertyNotes($where)
   {
      $this->db->from(self::ENOTES);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
	  $this->db->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left');
      return $this->db->get()->result();
   }

   public function getPropertyNotesByCompanyAndCategory($where)
   {
      $this->db->from(self::ENOTES);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id');
      return $this->db->get()->result();
   }

   public function getPropertyNotesByCustomerAndCategory($where)
   {
      $this->db->from(self::ENOTES);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
      return $this->db->get()->result();
   }   

   public function getNotesWhere($where)
   {
      if(is_array($where))
      {
         $this->db->from(self::ENOTES);
         $this->db->where($where);
         return $this->db->get()->result();
      } else
      {
         return array(
            'message' => 'Warning: You must provide an array of column "where" arguments.'
         );
      }
   }

   public function getOneNote($where)
   {
      $this->db->from(self::ENOTES);
      $this->db->where($where);
      return $this->db->get()->row();
   }

   public function updateNoteData($data, $where)
   {
      $this->db->where($where);
      return $this->db->update(self::ENOTES, $data);
   }   

   public function closeNoteStatus($noteId)
   {
      $this->db->set('note_status', 2);
      $this->db->where('note_id', $noteId);
      $result = $this->db->update(self::ENOTES);
      return $result;
   }

   public function deleteNote($noteId) 
   {
      $this->db->where('note_id', $noteId);
      $this->db->delete(array('e_note_comments','e_notes'));
      $result = $this->db->affected_rows();
      return $result;
   }

   public function getNoteTypes($companyId)
   {
      $this->db->from(self::NTYPES);
      $this->db->where('type_company_id',$companyId);
	  $this->db->or_where('type_company_id',0);
      return $this->db->get()->result();
   }

   public function getOneNoteTypeName($typeId)
   {
      $this->db->from(self::NTYPES);
      $this->db->where('type_id',$typeId);
      return $this->db->get()->row();
   }

   public function createNoteType($data)
   {
      $query = $this->db->insert(self::NTYPES, $data);
      return $this->db->insert_id();
   }

   public function editNoteType($data, $where)
   {
      $this->db->where($where);
      return $this->db->update(self::NTYPES, $data);
   }

   public function deleteNoteType($where)
   {
      $this->db->where($where);
      return $this->db->delete(self::NTYPES, $where);
   }   

   public function noteAddFiles($data)
   {
      $result = $this->db->insert_batch(self::NFILES, $data);
      return $result ? true : false;
   }

   public function addNoteComment($data)
   {
      $query = $this->db->insert(self::ECMMTS, $data);
      return $this->db->insert_id();
   }

   public function getNoteComments($noteId)
   {
      $where = array(
         'note_id' => $noteId,
      );
      $this->db->from(self::ECMMTS);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_note_comments.comment_user_id');
      $result = $this->db->get();
      $data = $result->result();
      return $data;
   }   

   public function getSingleNoteComment($comment_id) 
   {
      $this->db->from(self::ECMMTS);
      $this->db->where('comment_id', $comment_id);
      $result = $this->db->get()->row();
      return $result;
   }

   public function getNoteCommentCount($noteId)
   {
      $where = array(
         'note_id' => $noteId,
      );
      $this->db->from(self::ECMMTS);
      $this->db->where($where);
      // $this->db->join('users', 'users.id=e_note_comments.comment_user_id');
      $result = $this->db->get();
      $data = $result->num_rows();
      return $data;
   }   

   public function getNoteFiles($noteId)
   {
      $this->db->from(self::NFILES);
      $this->db->where('note_id', $noteId);
      $result = $this->db->get();
      $data = $result->result();
      return $data;         
   }

   public function updateNoteTechView($int, $id)
   {
      $this->db->set('include_in_tech_view', $int);
      $this->db->where('note_id', $id);
      $result = $this->db->update(self::ENOTES);
      return $result;
   }

   public function getTechCompletionNotes($where)
   {
      $this->db->from(self::ENOTES);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
      $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
      $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
      return $this->db->get()->result();
   }    

   // Fleet Vehicles
    public function getAllFleetVehicles($company_id)
    {
      $this->db->from(self::FV);
      $this->db->where('v_company_id', $company_id);
      $this->db->join('users', 'users.id=fleet_vehicles.v_assigned_user', 'left');
      $result = $this->db->get();
      $data = $result->result();
      return $data;
    }

    public function getOneFleetVehicle($fleet_id)
    {
        $this->db->from(self::FV);
    	$this->db->where('fleet_id', $fleet_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getOneFleetVehicleAssigned($user_id)
    {
        $this->db->from(self::FV);
    	  $this->db->where('v_assigned_user', $user_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function setLastLogin($login_date, $user_id)
    {
        $this->db->set('last_login_date', $login_date);
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->update('users');
        return true;

    }
    

    public function addVehicle($post_data)
    {
        $query = $this->db->insert(self::FV, $post_data);
        return $this->db->insert_id();
    }

    public function updateFleetVehicle($id, $post_data)
    {
         $this->db->from(self::FV);
         $this->db->where('fleet_id', $id);
         $this->db->update(self::FV,$post_data);
         return $this->db->affected_rows();
    }    

    public function deleteFleetVehicle($id)
    {
        $this->db->where('fleet_id', $id);
        $this->db->delete(self::FV);
        $count = $this->db->affected_rows();
        return $count;
    }

    /**
     * @param array $where_arr
     * Contains: company_id, note_truck_id 
     */
    public function getSingleVehicleNotes($where_arr)
    {
        $this->db->from(self::ENOTES);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSingleVehicleNotesAttachments($v_note_id)
    {
        $this->db->from(self::VNA);
        $this->db->where('v_note_id', $v_note_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function addVehicleNote($post_data)
    {
        $query = $this->db->insert(self::VN, $post_data);
        return $this->db->insert_id();
    }

    public function addVehicleNoteAttachment($post_data)
    {
        $query = $this->db->insert(self::VNA, $post_data);
        return $this->db->insert_id();
    }

    // Fleet Maintenance

    public function getFleetMaintenanceCount($where_arr)
    {
      $this->db->from(self::FM);
      $this->db->where($where_arr);
      $result = $this->db->count_all_results();
      return $result;
    }

    public function addMaintenanceEntry($post_data)
    {
        $query = $this->db->insert(self::FM, $post_data);
        return $this->db->insert_id();
    }

   public function getNoteMaintenanceEntry($note_id)
    {
        $this->db->from(self::FM);
        $this->db->where('mnt_note_id', $note_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

   //  Fleet Inspection Report

    public function getNoteInspectionId($note_id)
    {
      $this->db->from('vehicle_inspection_reports');
      $this->db->where('vehicle_note_id', $note_id);
      $result = $this->db->get();
      $data = $result->row();
      return $data ?? NULL;
    }

    public function getOneVehicleInspection($insp_id)
    {
      $this->db->from('vehicle_inspection_reports');
      $this->db->where('v_insp_id', $insp_id);
      $this->db->join('users', 'users.id=vehicle_inspection_reports.driver_id','inner');
      $result = $this->db->get();
      $data = $result->row();
      return $data;
    }
    function getMaintenanceManegers($where_arr)
    {
        $this->db->from('users');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;       
    }
    function checkDriverAvailability($where_arr)
    {
      $this->db->from(self::FV);
      if(is_array($where_arr))
      {
         $this->db->where($where_arr);
         $result = $this->db->get();
         $data = $result->num_rows();
         return $data;
      }
    }

   public function getAllCompanySlugDuplicates($wherearr='') {
      $this->db->select('*');
      $this->db->from(self::ST);
      if (is_array($wherearr)) {
          $this->db->where($wherearr);
      }
      $result = $this->db->get();
      $data = $result->result();
      return $data;
  }

    public function getAllCustomersToSendMonthlyStatement(){
//        $query = $this->db->query("call getSuscriptedCompaniesToMonthlyInvoiceStatement()");
//        //$result = $query->result();
//        $query->next_result(); //Needed because stored proceedure was excecuted.
//        $query->free_result();//Needed because stored proceedure was excecuted.

        $query = $this->db->query("SELECT customers.customer_id, customers.email
                                    FROM `invoice_tbl`
                                        INNER JOIN `customers` ON `customers`.`customer_id` = `invoice_tbl`.`customer_id`
                                        LEFT JOIN `programs` ON `programs`.`program_id` = `invoice_tbl`.`program_id`
                                        LEFT JOIN `refund_invoice_logs` ON `refund_invoice_logs`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                        LEFT JOIN `technician_job_assign` ON `technician_job_assign`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                        LEFT JOIN ( SELECT *, (LENGTH(csv_report_ids) - LENGTH(REPLACE(csv_report_ids, ',', '')) + 1) AS csv_item_num
                                                    FROM ( SELECT *, GROUP_CONCAT(report_id) AS csv_report_ids, COUNT(invoice_id) AS invoice_id_count
                                                           FROM property_program_job_invoice GROUP BY invoice_id
                                                                                             ) AS T ) AS property_program_job_invoice2 ON `property_program_job_invoice2`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                    WHERE `invoice_tbl`.`company_id` in (select t.company_id from t_company t
                                                                            where t.send_monthly_invoice_statement = 1) AND `is_archived` =0
                                      AND ! ( `programs`.`program_price` = 2 AND `technician_job_assign`.`is_complete` != 1 AND `technician_job_assign`.`is_complete` IS NOT NULL )
                                      AND ! ( `programs`.`program_price` = 2 AND `technician_job_assign`.`invoice_id` IS NULL AND `invoice_tbl`.`report_id` =0 AND `property_program_job_invoice2`.`report_id` IS NULL )
                                    GROUP BY customers.customer_id
                                    ORDER BY customers.customer_id DESC;");
        $result = $query->result();
        //die(print_r($result));
        if ($result !== NULL) {
            //$result->next_result(); //Needed because stored proceedure was excecuted.
            //$result->free_result();//Needed because stored proceedure was excecuted.
            return $result;
        }
        return FALSE;

    }
// these functions are related to layout controller - had to move here to fix broken model controller relationships	
  public function updateAssignJobView($where,$post_data) {
    $this->db->where($where);
    $this->db->update(self::ST,$post_data);
  //die(print_r($this->db->last_query())); 
    return $this->db->affected_rows();
  }

  public function getDefaultAssignJobsView($company_id)
  {
    $this->db->select('*');
    $this->db->from('t_company');
    $this->db->where('company_id', $company_id);
    $result = $this->db->get();
    $data = $result->row();
    if(!empty($data)){
        return $data->assign_job_view;
    }
  }
}