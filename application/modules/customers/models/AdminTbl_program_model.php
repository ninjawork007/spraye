<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_program_model extends CI_Model{
  const PMTBL="programs";
  public function insert_program($post) {
    $query = $this->db->insert(self::PMTBL, $post);
    return $this->db->insert_id();
  }
  public function assignProgramJobs($post) {
    $this->db->insert('program_job_assign', $post);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
  }
  public function checkPriority($where_arr) {
    $this->db->select('priority');
    $this->db->from('program_job_assign');
    $this->db->where($where_arr);
    $result = $this->db->get();
    if($result->num_rows() > 0) {
      // Job-Program mapping exist.
      return array('priorityExist' =>true);
    } else {
      // Job-Progam mapping not exist.
      $programID = $where_arr['program_id'];
      $this->db->select_max('priority');
      $this->db->from('program_job_assign');
      $this->db->where('program_id',$programID);
      $result = $this->db->get();
      if($result->num_rows() > 0) {
        if(!empty($result->row()->priority)) {
          return array('priorityExist'=> false,'priority'=>$result->row()->priority + 1);
        } else {
          return array('priorityExist'=> false,'priority'=>1);
        }
      } else {
        return array('priorityExist' =>false,'priority' => $result->num_rows());
      }
    }
  }
  public function get_all_program($where_arr = '') {           
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
  public function updateAdminTbl($program_id, $post_data) {
    $this->db->where('program_id',$program_id);
    return $this->db->update('programs',$post_data);
  }    
  public function deleteProgram($wherearr) {
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
  public function getJobList($where_arr=''){
    $this->db->select('job_id,job_name');
    $this->db->from('jobs');
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
  public function getProgramAssignJobs($where_arr = '') {
    $this->db->select('*');        
    $this->db->from('program_job_assign');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $this->db->join('jobs','jobs.job_id=program_job_assign.job_id','inner');        
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getJobAssignPrograms($where_arr = '') {
    $this->db->select('*');
    $this->db->from('program_job_assign');
    if (is_array($where_arr)) {
      $this->db->where($where_arr);
    }
    $this->db->join('programs','programs.program_id=program_job_assign.program_id','inner');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getJobAssignProgramsByPriority($where_arr = '') {
    $this->db->select('*');        
    $this->db->from('program_job_assign');
    if (is_array($where_arr)) {
      $this->db->where($where_arr);
    }
    $this->db->join('programs','programs.program_id=program_job_assign.program_id','inner');
    $this->db->order_by('priority','ASC');        
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function updatePriority($program_job_id, $data) {
    $this->db->where('program_job_id',$program_job_id);
    return $this->db->update('program_job_assign',$data);
  }
  public function checkProgram($param){
    $this->db->where('program_name',$param['program_name']);
    $this->db->where('program_price',$param['program_price']);
    $result=$this->db->get('programs');
    if ($result->num_rows() > 0) {
      $data = $result->result();
      return "true";
    } else {
      return "false";
    }
  }
  public function getProgramDetail($programID){
    $this->db->where('program_id',$programID);
    $q=$this->db->get('programs');
    if($q->num_rows()>0) {
      return $q->result_array()[0];  
    }
  }
  public function getSelectedJobs($programID){
    $this->db->select('job_id');        
    $this->db->from('program_job_assign');
    $this->db->where('program_id',$programID);        
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getSelectedJobsAnother($programID){
    $this->db->select('program_job_assign.job_id,job_name');        
    $this->db->from('program_job_assign');
    $this->db->join('jobs','jobs.job_id=program_job_assign.job_id','inner');  
    $this->db->where('program_id',$programID);
    $this->db->order_by('priority','ASC');
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getSelectedProgram($job_id){
    $this->db->select('program_id');        
    $this->db->from('program_job_assign');
    $this->db->where('job_id',$job_id);        
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function deleteAssignJobs($wherearr) {
    if (is_array($wherearr)) {
      $this->db->where($wherearr);
    }
    $this->db->delete('program_job_assign');
    $a = $this->db->affected_rows();
    if($a){
        return true;
    }
    else{
        return false;
    }
  }
  public function getAllproperty($where_arr = '') {
    $this->db->select('*');        
    $this->db->from('property_program_assign');
    if (is_array($where_arr)) {
        $this->db->where($where_arr);
    }
    $this->db->join('property_tbl','property_tbl.property_id=property_program_assign.property_id','inner');        
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getSelectedProperty($programID){
    $this->db->select('*');
    $this->db->from('property_program_assign');
    $this->db->join('property_tbl','property_tbl.property_id=property_program_assign.property_id','inner');
    $this->db->where('program_id',$programID);
    $result = $this->db->get();
    $data = $result->result();
    return $data;
  }
  public function getOneProgramForCheck($wherearr) { 
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
  public function updateProgramData($data, $where) {
    $this->db->where($where);
    $this->db->update(self::PMTBL,$data);
  }
}
 
