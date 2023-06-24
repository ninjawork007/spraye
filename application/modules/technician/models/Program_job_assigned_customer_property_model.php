<?php
class Program_job_assigned_customer_property_model extends CI_Model
{

    const PJACPM = 'program_job_assigned_customer_property';

    public function createOrUpdateProgramJobAssignedCustomerProperty($post)
    {
        $isExistedData = $post;
        unset($isExistedData['reason']);
        unset($isExistedData['hold_until_date']);
        $result = $this->db->select('*')
            ->from('program_job_assigned_customer_property')
            ->where($isExistedData)
            ->get();
        $data = $result->result();
        if ($data) {
            $this->db->where($isExistedData);
            $this->db->update(self::PJACPM, $post);
            return $this->db->affected_rows();
        } else {
            $this->db->insert(self::PJACPM, $post);
            return $this->db->insert_id();
        }
    }

    public function getIsAsap($where)
    {
        $this->db->select('*');
        $this->db->from('program_job_assigned_customer_property');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if ($data && !empty($data['reason'])) {
            return 1;
        }
        return 0;
    }

}