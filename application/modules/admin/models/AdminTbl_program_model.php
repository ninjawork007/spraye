<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_program_model extends CI_Model
{
    const PMTBL = "programs";
    const PPOTBL = "program_service_property_price_overrides";

    public function insert_program($post)
    {
        $query = $this->db->insert(self::PMTBL, $post);
        return $this->db->insert_id();
    }

    public function assignProgramJobs($post)
    {
        $this->db->insert('program_job_assign', $post);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function checkPriority($where_arr)
    {
        $this->db->select('priority');
        $this->db->from('program_job_assign');
        $this->db->where($where_arr);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // Job-Program mapping exist.
            return array('priorityExist' => true);
        } else {
            // Job-Progam mapping not exist.
            $programID = $where_arr['program_id'];
            $this->db->select_max('priority');
            $this->db->from('program_job_assign');
            $this->db->where(array('program_id' => $programID));
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                if (!empty($result->row()->priority)) {
                    return array('priorityExist' => false, 'priority' => $result->row()->priority + 1);
                } else {
                    return array('priorityExist' => false, 'priority' => 1);
                }
            } else {
                return array('priorityExist' => false, 'priority' => $result->num_rows());
            }
        }
    }

    public function get_all_program($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from(self::PMTBL);
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

    public function get_all_program_marketing($where_arr = '')
    {
        $this->db->select('program_id, program_name');
        $this->db->from(self::PMTBL);
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

    public function updateAdminTbl($program_id, $post_data)
    {
        $this->db->where(array('program_id' => $program_id));
        return $this->db->update('programs', $post_data);
    }

    public function deleteProgram($wherearr)
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

    public function getJobList($where_arr = '')
    {
        $this->db->select('job_id,job_name');
        $this->db->from('jobs');
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

    public function getProgramAssignJobs($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('program_job_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('jobs', 'jobs.job_id=program_job_assign.job_id', 'inner');
        $this->db->join('service_type_tbl', 'service_type_tbl.service_type_id=jobs.service_type_id', 'left');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getJobAssignPrograms($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('program_job_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('programs', 'programs.program_id=program_job_assign.program_id AND programs.ad_hoc = 0', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getJobAssignProgramsByPriority($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('program_job_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('programs', 'programs.program_id=program_job_assign.program_id', 'inner');
        $this->db->order_by('priority', 'ASC');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updatePriority($program_job_id, $data)
    {
        $this->db->where('program_job_id', $program_job_id);
        return $this->db->update('program_job_assign', $data);
    }

    public function checkProgram($param)
    {
        $this->db->where(array('program_name' => $param['program_name']));
        $this->db->where(array('program_price' => $param['program_price']));
        $result = $this->db->get('programs');
        if ($result->num_rows() > 0) {
            $data = $result->result();
            return "true";
        } else {
            return "false";
        }
    }

    public function getProgramDetail($programID)
    {
        $this->db->where('program_id', $programID);
        $q = $this->db->get('programs');
        if ($q->num_rows() > 0) {
            return $q->result_array()[0];
        }
    }

    public function getSelectedJobs($programID)
    {
        $this->db->select('job_id');
        $this->db->from('program_job_assign');
        $this->db->where(array('program_id' => $programID));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSelectedJobsAnother($programID)
    {
        $this->db->select('program_job_assign.job_id,job_name');
        $this->db->from('program_job_assign');
        $this->db->join('jobs', 'jobs.job_id=program_job_assign.job_id', 'inner');
        $this->db->where(array('program_id' => $programID));
        $this->db->order_by('priority', 'ASC');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSelectedProgram($job_id)
    {
        $this->db->select('program_id');
        $this->db->from('program_job_assign');
        $this->db->where(array('job_id' => $job_id));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function deleteAssignJobs($wherearr)
    {
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $this->db->delete('program_job_assign');
        $a = $this->db->affected_rows();
        if ($a) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllproperty($where_arr = '')
    {
        $this->db->select('property_tbl.property_title');
        $this->db->from('property_program_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('property_tbl', 'property_tbl.property_id=property_program_assign.property_id', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getSelectedProperty($programID)
    {
        $this->db->select('*');
        $this->db->from('property_program_assign');
        $this->db->join('property_tbl', 'property_tbl.property_id=property_program_assign.property_id', 'inner');
        // This final join statement will return no results on test data that does not have the property_area set or set to 0
        // $this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id');

        $this->db->where(array('program_id' => $programID));
        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function getSelectedPropertyForBulkEstimates($programID)
    {
        $this->db->select('*');
        $this->db->from('property_program_assign');
        $this->db->join('property_tbl', 'property_tbl.property_id=property_program_assign.property_id', 'inner');
        // This final join statement will return no results on test data that does not have the property_area set or set to 0
        $this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id', 'left');

        $this->db->where('property_program_assign.program_id', $programID);
        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function getOneProgramForCheck($wherearr)
    {
        $this->db->select('*');
        $this->db->from('programs');
        $this->db->where($wherearr);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    /**
     * Updates record in Programs table based on provided argument data and filter criteria.
     * @param array $data
     * @param array $where
     *  */
    public function updateProgramData($data, $where)
    {
        $this->db->where($where);
        $this->db->update(self::PMTBL, $data);
    }

    public function getAllJobsOfProgram($programId)
    {
        $this->db->select('*');
        $this->db->from('programs');
        $this->db->join('program_job_assign', 'program_job_assign.program_id=programs.program_id', 'inner');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getProgramJobAssign()
    {
        $this->db->select('*');
        $this->db->from('program_job_assign');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    /**** Program Price Overrides ****/
    public function insert_price_override($arr)
    {
        $query = $this->db->insert(self::PPOTBL, $arr);
        return $this->db->insert_id();
    }

    public function getProgramPropertyJobsOverrides($where_arr)
    {
        $this->db->select('*');
        $this->db->from(self::PPOTBL);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updateProgramPropertyJobOverrides($data, $where_arr)
    {
        $this->db->update(self::PPOTBL, $data, $where_arr);
    }

    public function getJobListWhereIn($search_column, $where_arr = '')
    {
        $this->db->select('job_id,job_name');
        $this->db->from('jobs');
        $this->db->where('jobs.company_id', $this->session->userdata['company_id']);

        if (is_array($where_arr)) {
            $this->db->where_in($search_column, $where_arr);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function getAllEstimateJoinedPrograms($where_arr)
    {
        $this->db->select('*');
        $this->db->from('estimate_programs');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

}
 
