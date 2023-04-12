<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Unassign_job_delete_model extends CI_Model{
    const DTTBL="unassigned_Job_delete";
    public function createDeleteRow($param) {
        $this->db->insert(self::DTTBL,$param);
        return $this->db->insert_id();
    }
    public function removeDeleteRow($where_arr) {
        $this->db->where($where_arr);
        return $this->db->delete(self::DTTBL);
    }
    public function getOneDeletedRow($where){
        return $this->db->where($where)->get(self::DTTBL)->row();
    }
    public function getDeletedRows($where_arr) {        
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`category_area_name`,property_address,property_type,property_title,priority,reschedule_message");
        $this->db->from(self::DTTBL);
        $this->db->join('jobs','jobs.job_id = unassigned_Job_delete.job_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = unassigned_Job_delete.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = unassigned_Job_delete.program_id','inner');
        $this->db->join('program_job_assign','program_job_assign.job_id = unassigned_Job_delete.job_id and program_job_assign.program_id = unassigned_Job_delete.program_id','inner');
        $this->db->join('technician_job_assign','technician_job_assign.job_id = unassigned_Job_delete.job_id and technician_job_assign.program_id = unassigned_Job_delete.program_id and technician_job_assign.property_id = unassigned_Job_delete.property_id and technician_job_assign.customer_id = unassigned_Job_delete.customer_id','left');
        $this->db->join('customers','customers.customer_id = unassigned_Job_delete.customer_id ','inner');
        $this->db->where($where_arr);
        $this->db->order_by('programs.program_id','asc');  
        $this->db->order_by('jobs.job_id','asc');
        $result = $this->db->get();
        $data = $result->result();
        $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);        
        return $data;
    }
    public function GetLastCompletedServiceDate($data) {
        $this->db->select("job_completed_date");
        $this->db->from('technician_job_assign');
        $where_arr = array(
            "is_complete" => 1,
            "property_id" => $data->property_id
        );
        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $row = $this->db->get()->row();
        $data->job_completed_date = ($row) ? $row->job_completed_date : '';
        return $data;
    }
    public function getTableDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id, technician_job_assign.technician_job_assign_id");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');

        // latest property job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");

        // latest property program job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property_program, property_id, program_id FROM technician_job_assign GROUP BY property_id, program_id ) AS technician_job_assign_property_program", "property_program_assign.property_id = technician_job_assign_property_program.property_id AND programs.program_id = technician_job_assign_property_program.program_id", "left");

        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        
        $this->db->where("unassigned_Job_delete_id IS NOT NULL");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }
	public function getPropertyTechViewNotes($where)
    {
        
       $this->db->from('e_notes');
       $this->db->where($where);
       $this->db->join('users', 'users.id=e_notes.note_user_id','inner');
       $this->db->join('customers', 'customers.customer_id=e_notes.note_customer_id','left');
       $this->db->join('property_tbl', 'property_tbl.property_id=e_notes.note_property_id', 'left');
       $this->db->join ('e_note_types','e_note_types.type_id = e_notes.note_type');
     // $this->db->order_by("note_contents","asc");
       return $this->db->get()->result();
      // die($this->db->last_query());      
    }

    public function getTableServiceNoteData($where_arr) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,
        job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`
        ,`property_tbl`.`yard_square_feet`,`category_area_name`,property_address,
        priority,property_type,property_title, completed_date_property, completed_date_property_program,
         technician_job_assign.is_job_mode,`property_tbl`.`service_note`, unassigned_Job_delete.unassigned_Job_delete_id");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');

        // latest property job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");

        // latest property program job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property_program, property_id, program_id FROM technician_job_assign GROUP BY property_id, program_id ) AS technician_job_assign_property_program", "property_program_assign.property_id = technician_job_assign_property_program.property_id AND programs.program_id = technician_job_assign_property_program.program_id", "left");

        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        
        //$this->db->where("unassigned_Job_delete_id IS NOT NULL");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

       

        $result = $this->db->get();
        $data = $result->result();
      //  die($this->db->last_query());
        return $data;
    }
    public function getTableDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id, technician_job_assign.reschedule_message");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');

        // latest property job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");

        // latest property program job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property_program, property_id, program_id FROM technician_job_assign GROUP BY property_id, program_id ) AS technician_job_assign_property_program", "property_program_assign.property_id = technician_job_assign_property_program.property_id AND programs.program_id = technician_job_assign_property_program.program_id", "left");

        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");

        $this->db->where("unassigned_Job_delete_id IS NOT NULL");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
		$this->db->like('priority',$search);
        $this->db->or_like('job_name',$search);
		$this->db->or_like('CONCAT(customers.first_name, " ", customers.last_name)',$search, false);
		$this->db->or_like('property_title',$search);
        $this->db->or_like('`property_tbl`.`yard_square_feet`',$search);
        $this->db->or_like('completed_date_property',$search);
        $this->db->or_like('completed_date_property_program',$search);
        $this->db->or_like('property_address',$search);
        $this->db->or_like('property_type',$search);
        $this->db->or_like('category_area_name',$search);
        $this->db->or_like('program_name',$search);
        $this->db->or_like('reschedule_message',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }
}
 