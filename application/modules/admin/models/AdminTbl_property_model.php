<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminTbl_property_model extends CI_Model
{
    const PMTBL = "property_tbl";
    const GBTBL = "group_billing";

    public function insert_property($post)
    {
        $query = $this->db->insert(self::PMTBL, $post);
        return $this->db->insert_id();
    }

    public function assignCustomer($post)
    {
        $this->db->insert('customer_property_assign', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function assignProgram($post)
    {
        $this->db->insert('property_program_assign', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getOnePropertyProgram($where)
    {
        return $this->db->where($where)->get('property_program_assign')->row();
    }

    public function get_all_property($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from(self::PMTBL);
        $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $data = $result->result();
            // die(print_r($this->db->last_query()));
            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function get_all_list_properties($where_arr = '')
    {
        $this->db->select('*, category_property_area.category_area_name');
        $this->db->from(self::PMTBL);
        $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $data = $result->result();
            // die(print_r($this->db->last_query()));
            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function updateAdminTbl($property_id, $post_data)
    {
        $this->db->where('property_id', $property_id);
        return $this->db->update('property_tbl', $post_data);
    }


    public function deleteProperty($wherearr)
    {
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

    public function getPropertyAreaList($where_arr = '')
    {
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

    public function getProgramList($where_arr = '')
    {
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

    public function getCustomerList($where_arr = '')
    {
        $this->db->select('customer_id,first_name,last_name,billing_street,billing_type');
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


    public function getCustomerListFromAutoComplete($companyID, $textSample)
    {
        $this->db->select('customer_id,first_name,last_name,billing_street,billing_type');
        $this->db->from('customers');

        $this->db->where('company_id', $companyID);
        $this->db->where('(first_name like "%' . $textSample . '%" or last_name like "%' . $textSample . '%")');
        //$this->db->or_like('last_name', $textSample, 'after');

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        } else {
            return $result->num_rows();
        }
    }


    public function getTagsList($where_arr = '')
    {
        $this->db->select('id,tags_title');
        $this->db->from('tags');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->or_where('company_id', 0);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function getAllCustomerProperties($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_property_assign');
        $this->db->join('property_tbl', 'property_tbl.property_id=customer_property_assign.property_id', 'inner');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllCustomerPropertiesMarketing($customer_id)
    {
        $this->db->select('property_id');
        $this->db->from('customer_property_assign');
        //$this->db->join('property_tbl', 'property_tbl.property_id=customer_property_assign.property_id', 'inner');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllcustomer($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('customer_property_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('customers', 'customers.customer_id=customer_property_assign.customer_id', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        return $data;
    }

    public function getAllcustomerDisplay($where_arr = '')
    {
        $this->db->select('customer_property_assign.customer_id, customers.first_name, customers.last_name');
        $this->db->from('customer_property_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('customers', 'customers.customer_id=customer_property_assign.customer_id', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        return $data;
    }

    public function getAllprogram($where_arr = '')
    {
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

    public function getAllprogramDisplay($where_arr = '')
    {
        $this->db->select('program_name, ad_hoc, program_active');
        $this->db->from('property_program_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('programs', 'programs.program_id=property_program_assign.program_id', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    /**
     * GEt data for assign programs table  on quickview
     * @param $where_arr
     * @return mixed
     */
    public function getAssignedPrograms($where_arr = '')
    {

        $this->db->select("*, CONCAT(sales_rep.user_first_name,' ', sales_rep.user_last_name )as sales_rep_name, sales_rep.id as id_sales_rep");
        $this->db->from('property_program_assign');
        $this->db->join('programs', 'programs.program_id=property_program_assign.program_id', 'inner');
        $this->db->join('property_tbl', 'property_tbl.property_id = property_program_assign.property_id', 'inner');
        $this->db->join('customer_property_assign', 'customer_property_assign.property_id = property_program_assign.property_id ', 'inner');
        $this->db->join('customers', 'customer_property_assign.customer_id = customers.customer_id ', 'inner');
        $this->db->join('t_estimate ', 'customer_property_assign.customer_id =  t_estimate.customer_id AND property_program_assign.program_id = t_estimate.program_id AND property_tbl.property_id = t_estimate.property_id', 'left');
        $this->db->join('users sales_rep', 'sales_rep.id = t_estimate.sales_rep', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function checkProperty($param, $property_id = '')
    {
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

    public function getOneProperty($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_tbl');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getAllProspectsProperty($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_tbl');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
        // $this->db->select('*', 'category_property_area.category_area_name');
        // $this->db->from(self::PMTBL);
        // $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
        // if (is_array($where_arr)) {
        //     $this->db->where($where_arr);
        // }
        // $result = $this->db->get();
        // if ($result->num_rows() > 0) {
        //     $data = $result->result();
        //     // die(print_r($this->db->last_query()));
        //     return $data;
        // } else {
        //     return $result->num_rows();
        // }
    }

    public function getPropertyDetail($propertyID)
    {
        $this->db->where('property_id', $propertyID);
        $q = $this->db->get('property_tbl');
        if ($q->num_rows() > 0) {
            return $q->result_array()[0];
        }
    }

    public function getOnePropertyDetail($propertyID)
    {
        $this->db->where('property_id', $propertyID);
        $q = $this->db->get('property_tbl');
        return $q->row();
    }

    public function getSelectedProgram($propertyID)
    {
        $this->db->select('*');
        $this->db->from('property_program_assign');
        $this->db->join('programs', 'programs.program_id=property_program_assign.program_id AND programs.program_active = 1', 'inner');
        $this->db->where(array('property_id' => $propertyID));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSelectedCustomer($propertyID)
    {
        $this->db->select('customer_id');
        $this->db->from('customer_property_assign');
        $this->db->where('property_id', $propertyID);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function deleteAssignCustomer($wherearr)
    {
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

    public function deleteAssignProgram($wherearr)
    {
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

    public function getBulkPropertyData($postData = null)
    {
        $this->db->select('*');
        $this->db->from(self::PMTBL);
        $this->db->join('customers', 'customers.user_id = property_tbl.user_id');
        return $this->db->get();
    }

    /**
     * Updates record in Properties table based on provided argument data and filter criteria.
     * @param array $data
     * @param array $where
     *  */
    public function updatePropertyData($data, $where)
    {
        $this->db->where($where);
        $this->db->update(self::PMTBL, $data);
    }

    public function updatePropertyPropgramData($data, $where)
    {
        $this->db->where($where);
        return $this->db->update('property_program_assign', $data);
    }

    public function getPropAreaName($area_id)
    {
        $this->db->select('category_area_name');
        $this->db->from('category_property_area');
        $this->db->where('property_area_cat_id', $area_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data->category_area_name ?? NULL;
    }

    /** Notes **/
    public function addNote($data)
    {
        $query = $this->db->insert('enhanced_property_notes', $data);
        return $this->db->insert_id();
    }

    public function getNotes($where)
    {
        // $propertyId = $where['property_id'];
        // $companyId = $where['company_id'];
        // $this->db->select('enhanced_property_notes.*','users.id','users.user_first_name','users.user_last_name','property_tbl.*');
        $this->db->from('enhanced_property_notes');
        $this->db->where($where);
        $this->db->join('users', 'users.id=enhanced_property_notes.note_user_id', 'inner');
        $this->db->join('property_tbl', 'property_tbl.property_id=enhanced_property_notes.note_property_id');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function closeNoteStatus($noteId)
    {
        // $this->db->select('*');
        // $this->db->from('enhanced_property_notes');
        $this->db->set('note_status', 2);
        $this->db->where('note_id', $noteId);
        $result = $this->db->update('enhanced_property_notes');
        return $result;
    }

    public function deleteNote($noteId)
    {
        $this->db->where('note_id', $noteId);
        $this->db->delete(array('enhanced_note_comments', 'enhanced_property_notes'));
        $result = $this->db->affected_rows();
        return $result;
    }

    public function updateNoteData($data, $where)
    {
        $this->db->where($where);
        return $this->db->update('enhanced_property_notes', $data);
    }

    /** Note Comments **/
    public function getNoteComments($noteId, $note_type = 0)
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

    /* Note File Attachments */
    public function noteAddFiles($data)
    {
        $result = $this->db->insert_batch('enhanced_note_files', $data);
        return $result ? true : false;
    }

    public function getNoteFiles($where)
    {
        $this->db->from('enhanced_note_files');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    /* Add Additional Files to Existing Note */
    public function addToNoteFiles($data)
    {
        $result = $this->db->insert_batch('enhanced_note_files', $data);
        return $result ? true : false;
    }

    public function getAllActiveCustomerProperties($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_property_assign');
        $this->db->join('property_tbl', 'property_tbl.property_id=customer_property_assign.property_id', 'inner');
        $this->db->where('customer_id', $customer_id);
        $this->db->where_in('property_status', array(1, 2));

        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updatePropertyStatus($propertyID, $property_status)
    {
        $this->db->where('property_id', $propertyID);
        return $this->db->update('property_tbl', array('property_status' => $property_status));
    }

    public function assignGroupBilling($post)
    {
        $this->db->insert('group_billing', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getGroupBillingByProperty($property_id)
    {
        $this->db->select('*');
        $this->db->from('group_billing');
        $this->db->where('property_id', $property_id);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        }
    }

    public function updateGroupBilling($group_billing_id, $post_data)
    {
        $this->db->where('group_billing_id', $group_billing_id);
        return $this->db->update('group_billing', $post_data);
    }

    public function addPropertyCondition($post)
    {
        $this->db->insert('property_conditions', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getCompanyPropertyConditions($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_conditions');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updatePropertyCondition($where_arr = '', $post_data)
    {
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        return $this->db->update('property_conditions', $post_data);
    }

    public function getPropertyConditions($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_conditions');
        $this->db->join('property_condition_assign ', 'property_condition_assign.property_condition_id=property_conditions.property_condition_id', 'right');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getOnePropertyCondition($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_conditions');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function deletePropertyCondition($where_arr = '')
    {
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->delete('property_conditions');
        $result = $this->db->affected_rows();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function assignPropertyCondition($post)
    {
        $this->db->insert('property_condition_assign', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getAssignedPropertyConditions($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_condition_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function deleteAssignedPropertyConditions($where_arr = '')
    {
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->delete('property_condition_assign');
        $result = $this->db->affected_rows();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function autoStatusCheck($company_id = 0, $PropId = 0)
    {
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
        $result = $query->result();
        if ($result) {
            foreach ($result as $row) {
                $all_estimate_statuses = explode(",", $row->e_status);
                // now if we array unique this and we are only left with 5 then EVERY estimate for this property is declined and we need to move the status if the old status was Estimate sent
                $all_estimate_statuses = array_unique($all_estimate_statuses);
                if($row->property_status != 0 && $row->property_status != 7 && $row->property_status != 8 && $row->property_status != 9)
                if(count($all_estimate_statuses) == 1 && $all_estimate_statuses[0] == "5" && $row->property_status == "5") {
                    $this->db->update(self::PMTBL,['property_status' => 6], ['property_id' => $row->property_id]);
                } elseif($row->property_status != '1' && in_array('1',$all_estimate_statuses) == true) {
                    $this->db->update(self::PMTBL,['property_status' => 5], ['property_id' => $row->property_id]);
                }
            }
        }

        // check for if property is set to active after a program is assigned
        if ($PropId != 0) {
            $this->db->select("property_status");
            $this->db->from('property_tbl');
            $this->db->where('property_id', $PropId);

            $result3 = $this->db->get();
            $propertyStatus = $result3->result();


            $this->db->select("program_id");
            $this->db->from('property_program_assign');
            $this->db->where('property_id', $PropId);
            $programAssigned = $this->db->count_all_results();


            if ($propertyStatus != 1 && $programAssigned > 0) {
                $data3 = array(
                    'property_status' => 1
                );
                $this->db->replace('property_tbl', $data3);
            }
        }

        // If a property has a “Sales Call” currently scheduled to be completed, then the property status should change to “Sales Call Scheduled” ONLY FROM PROSPECT!

//        $this->db->select("is_complete,job_name,technician_job_assign.property_id, property_status");
//        $this->db->from('technician_job_assign');
//        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
//        $this->db->join('property_tbl','property_tbl.property_id=technician_job_assign.property_id','inner');
//        $this->db->where('technician_job_assign.is_job_mode = 0');
//
//        $result2 = $this->db->get();
//        $data2 = $result2->result();
        $sql = "
            UPDATE
                technician_job_assign
            INNER JOIN
                jobs ON jobs.job_id = technician_job_assign.job_id
            INNER JOIN
                property_tbl ON property_tbl.property_id = technician_job_assign.property_id
            SET
                property_status = 4
            WHERE
                technician_job_assign.is_job_mode = 0
            AND
                job_name = 'Sales Visit'
            AND
                property_status = 2;";
        $this->db->query($sql);
//        die(print_r($this->db->last_query()));
//        foreach($data2 as $d2) {
//            if(strpos($d2->job_name, 'Sales Visit') !== false && $d2->property_status == "2") {
//                $this->db->update(self::PMTBL,['property_status' => 4], ['property_id' => $d2->property_id]);
//            }
//        }
//        $time_elapsed_secs = microtime(true) - $start;
//        die(var_dump($time_elapsed_secs));
    }

    public function getUnassignJobsByProperty($property_id)
    {
        $this->db->select('property_program_assign.property_id,property_program_assign.program_id,programs.program_name,customers.customer_id,jobs.job_id,jobs.job_name,technician_job_assign.technician_job_assign_id');
        $this->db->from('property_program_assign');
        $this->db->join('customer_property_assign', 'customer_property_assign.property_id = property_program_assign.property_id ', 'inner');
        $this->db->join('customers', 'customers.customer_id = customer_property_assign.customer_id ', 'inner');
        $this->db->join('program_job_assign', 'program_job_assign.program_id =property_program_assign.program_id', 'inner');
        $this->db->join('programs', 'program_job_assign.program_id = programs.program_id', 'inner');
        $this->db->join('jobs', 'program_job_assign.job_id =jobs.job_id', 'inner');
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = property_program_assign.program_id AND technician_job_assign.property_id = property_program_assign.property_id", "left");
        $this->db->where('property_program_assign.property_id= ' . $property_id . ' AND (technician_job_assign.is_complete=0 OR is_complete IS NULL)');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getPropertyProgramJobs($property_id)
    {
        $this->db->select('program_job_assign.program_id,program_job_assign.job_id');
        $this->db->from('property_program_assign');
        $this->db->join('program_job_assign', 'program_job_assign.program_id = property_program_assign.program_id', 'inner');
        $this->db->where('property_program_assign.property_id', $property_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getPropertyByDateRange($where, $from = '', $to = '')
    {
        $this->db->select('*');
        $this->db->from('property_tbl');
        $this->db->join('property_program_assign','property_program_assign.property_id = property_tbl.property_id ','inner');
        $this->db->join('program_job_assign','program_job_assign.program_id = property_program_assign.program_id','inner');

        if(isset($where["assignProgram"]) && $where["assignProgram"] != "null" && $where["assignProgram"] != null){
            $SaleRpID = explode(",", $where["assignProgram"]);

            $IdString = "property_program_assign.program_id IN (";
            foreach($SaleRpID as $TcID){
                $IdString .= "'".$TcID."',";
            }
            $IdString = substr($IdString, 0, -1);
            $IdString .= ")";
            $this->db->where($IdString);
            unset($where["assignProgram"]);
        }

        if(isset($where["assignService"]) && $where["assignService"] != "null" && $where["assignService"] != null){
            $SaleRpID = explode(",", $where["assignService"]);

            $IdString = "program_job_assign.job_id IN (";
            foreach($SaleRpID as $TcID){
                $IdString .= "'".$TcID."',";
            }
            $IdString = substr($IdString, 0, -1);
            $IdString .= ")";
            $this->db->where($IdString);
            unset($where["assignService"]);
        }

        if(isset($where["property_area"]) && $where["property_area"] != "null"){
            $SaleRpID = explode(",", $where["property_area"]);

            $IdString = "property_tbl.property_area IN (";
            foreach($SaleRpID as $TcID){
                $IdString .= "'".$TcID."',";
            }
            $IdString = substr($IdString, 0, -1);
            $IdString .= ")";
            $this->db->where($IdString);
            unset($where["property_area"]);
        }

        $this->db->where($where);
        if ($from != '') {
            $this->db->where('property_tbl.property_created >=', $from);
        }
        if ($to != '') {
            $to_date = explode(' ', $to)[0];
            $now_date = explode(' ', strtotime('now'))[0];
            if ($to_date == $now_date) {
                $to = strtotime('now');
            }
            $this->db->where('property_tbl.property_created <=', $to);
        }
        $this->db->group_by('property_tbl.property_created');
        $this->db->order_by('property_tbl.property_created ASC');
        $result = $this->db->get();
        $data = $result->result();
        //die(print_r($this->db->last_query()));
        return $data;
    }

    public function getCancelledPropertyByDateRange($where, $from = '', $to = '')
    {
        $this->db->select('*');
        $this->db->from('property_tbl');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_tbl.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');
        $this->db->join('users','users.id = property_tbl.cancelled_by ','inner');
        $this->db->join('t_estimate','t_estimate.property_id = property_tbl.property_id ','left');
        $this->db->where($where);
        $this->db->where('property_tbl.property_cancelled IS NOT NULL');
        if ($from != '') {
            $this->db->where('property_tbl.property_cancelled >=', $from);
        }
        if ($to != '') {
            $this->db->where('property_tbl.property_cancelled <=', $to);
        }
        $this->db->group_by('property_tbl.property_cancelled');
        $this->db->order_by('property_tbl.property_cancelled ASC');
        $result = $this->db->get();
        $data = $result->result();
        //die(print_r($this->db->last_query()));
        return $data;
    }

    public function checkPropertyCancelled($property_id)
    {
        $this->db->where('property_status', 0);
        if (!empty($property_id)) {
            $this->db->where('property_id', $property_id);
        }
        $result = $this->db->get('property_tbl');
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    #tags
    public function assignTags($post)
    {
        $this->db->insert('tags', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /*
 * Customers edit page last update.
 * 1- Get selected property
 */

    public function getPropertySelected($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('property_tbl');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    public function getAllZipCodes($where_arr)
    {
        $this->db->distinct();
        $this->db->select('property_zip');
        $this->db->from('property_tbl');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by("property_zip ASC");
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function setAllPropertiesNonActive($where_arr)
    {
        $this->db->set('property_status', '0');
        $this->db->where_in('property_id', $where_arr);
        $result = $this->db->update('property_tbl');
        return $result;
    }
}
