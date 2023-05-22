<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_company_model extends CI_Model
{
    const ST = "t_company";

    public function getOneCompany($where)
    {
        return $this->db->where($where)->get(self::ST)->row();
    }

    public function updateCompany($where, $post_data)
    {
        $this->db->where($where);
        $this->db->update(self::ST, $post_data);

        return $this->db->affected_rows();

    }

    public function getOneAdminUser($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('users');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getOneCompanyEmailArray($where)
    {
        return $this->db->where($where)->get('t_company_email_setting')->row_array();
    }

    public function getOneDefaultEmailArray()
    {
        return $this->db->get('t_superadmin')->row_array();
    }

    const ENOTES = "e_notes";
    const NTYPES = "e_note_types";
    const NFILES = "e_note_files";
    const ECMMTS = "e_note_comments";
    const FM = 'fleet_maintenance';

    public function addNote($data)
    {
        $query = $this->db->insert(self::ENOTES, $data);
        return $this->db->insert_id();
    }

    public function getCompanyNotes($companyId)
    {
        $this->db->from(self::ENOTES);
        $this->db->where('note_company_id', $companyId);
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
        $this->db->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left');
        return $this->db->get()->result();
    }

    public function getNote($noteId)
    {
        $this->db->from(self::ENOTES);
        $this->db->where('note_id', $noteId);
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
        return $this->db->get()->result();
    }

    public function getUserNotes($userId)
    {
        $this->db->from(self::ENOTES);
        $this->db->where('note_user_id', $userId);
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
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
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
        return $this->db->get()->result();
    }

    public function getPropertyNotes($propertyId)
    {
        $this->db->from(self::ENOTES);
        $this->db->where('note_property_id', $propertyId);
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
        $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id', 'left');
        $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
        $this->db->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left');
        return $this->db->get()->result();
    }

    public function getPropertyTechViewNotes($where)
    {
        $this->db->select('e_notes.*,
                           users.*,
                           CONCAT(user_assigned.user_first_name, " ", user_assigned.user_last_name) as user_assigned_full_name,
                           CONCAT(customers.first_name, " ", customers.last_name) as customer_full_name,
                           property_tbl.*,
                           jobs.*
                        ')
            ->from(self::ENOTES)
            ->where($where)
            ->join('users', 'users.id=e_notes.note_user_id', 'inner')
            ->join('users as user_assigned', 'user_assigned.id=e_notes.note_assigned_user', 'left')
            ->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left')
            ->join('customers', 'customers.customer_id=e_notes.note_customer_id', 'left')
            ->join('jobs', 'e_notes.note_assigned_services=jobs.job_id', 'left')
            ->order_by('is_urgent DESC, note_created_at DESC');
        return $this->db->get()->result();
    }

    public function getPropertyNotesByCompanyAndCategory($where)
    {
        $this->db->from(self::ENOTES);
        $this->db->where($where);
        $this->db->join('users', 'users.id=e_notes.note_user_id', 'inner');
        $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id');
        return $this->db->get()->result();
    }

    public function getNotesWhere($where)
    {
        if (is_array($where)) {
            $this->db->from(self::ENOTES);
            $this->db->where($where);
            return $this->db->get()->result();
        } else {
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
        $this->db->set('note_due_date', NULL);
        $this->db->where('note_id', $noteId);
        $result = $this->db->update(self::ENOTES);
        return $result;
    }

    public function setNoteUrgentById($noteId, $urgent = 0)
    {
        $this->db->set('is_urgent', $urgent);
        $this->db->where('note_id', $noteId);
        $result = $this->db->update(self::ENOTES);
        return $result;
    }

    public function deleteNote($noteId)
    {
        $this->db->where('note_id', $noteId);
        $this->db->delete(array('e_note_comments', 'e_notes'));
        $result = $this->db->affected_rows();
        return $result;
    }

    public function getNoteTypes($companyId)
    {
        $this->db->from(self::NTYPES);
        $this->db->where('type_company_id', $companyId);
        $this->db->or_where('type_company_id', 0);
        return $this->db->get()->result();
    }

    public function getOneNoteTypeName($typeId)
    {
        $this->db->from(self::NTYPES);
        $this->db->where('type_id', $typeId);
        return $this->db->get()->row();
    }

    public function createNoteType($data)
    {
        $query = $this->db->insert(self::NTYPES, $data);
        return $this->db->insert_id();
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

    public function addTechNote($data)
    {
        $query = $this->db->insert(self::ENOTES, $data);
        return $this->db->insert_id();
    }

    public function getOneBasysRequest($where_arr = '')
    {

        $this->db->select('*');

        $this->db->from('t_basys_request');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function addMaintenanceEntry($post_data)
    {
        $query = $this->db->insert(self::FM, $post_data);
        return $this->db->insert_id();
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

    public function getOneFleetVehicle($fleet_id)
    {
        $this->db->from('fleet_vehicles');
        $this->db->where('fleet_id', $fleet_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getAllFleetVehicles($company_id)
    {
        $this->db->from('fleet_vehicles');
        $this->db->where('v_company_id', $company_id);
        $this->db->join('users', 'users.id=fleet_vehicles.v_assigned_user', 'left');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updateFleetVehicle($id, $post_data)
    {
        $this->db->from('fleet_vehicles');
        $this->db->where('fleet_id', $id);
        $this->db->update('fleet_vehicles', $post_data);
        return $this->db->affected_rows();
    }

    public function getOneFleetVehicleAssigned($user_id)
    {
        $this->db->from('fleet_vehicles');
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

    public function getNoteById($noteId)
    {
        $this->db->from(self::ENOTES);
        $this->db->where('note_id', $noteId);
        return $this->db->get()->row_array();
    }
}