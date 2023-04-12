<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Dashboard_model extends CI_Model{

    public function getUnAssignJobsGroup($where_arr,$where_in=array()) {
        $this->db->select('job_assign_date');
        $this->db->from('technician_job_assign');
        $this->db->where($where_arr);
        if (!empty($where_in)) {
            $this->db->where_in('technician_id',$where_in);
        }
        $this->db->group_by('job_assign_date');
        $this->db->order_by('job_assign_date ASC');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    public function getTechnicianScoreboard($where_arr=''){
        $this->db->select("`technician_id`,`job_assign_date`,`user_first_name`, `user_last_name`, `property_title`, sum(`yard_square_feet`) as total");
        $this->db->from('technician_job_assign');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->group_by('technician_id');
        $result = $this->db->get();
        $data = $result->result();
        return $data;         
    }
    public function getAssignTechnician($where_arr = '',$where_in=array()) {
        $this->db->select("technician_job_assign_id,technician_id,technician_job_assign_id,invoice_id,first_name,last_name,billing_street,billing_street_2,program_name,customers.customer_id,programs.program_id,programs.program_price,category_area_name,is_job_mode,is_complete,job_name,user_first_name,user_last_name,job_assign_date,property_address,job_assign_updated_date,jobs.job_id,job_price,property_title,property_tbl.property_id, customers.first_name as customer_first_name ,customers.last_name as customer_last_name, coupon_code_csv");        
        $this->db->from('technician_job_assign');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        $this->db->join('(SELECT *, GROUP_CONCAT(coupon_code SEPARATOR ", ") AS coupon_code_csv FROM coupon_job GROUP BY job_id, program_id, property_id, customer_id) AS coupon_job', 'technician_job_assign.job_id = coupon_job.job_id AND technician_job_assign.customer_id = coupon_job.customer_id AND technician_job_assign.program_id = coupon_job.program_id AND technician_job_assign.property_id = coupon_job.property_id', 'left');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (!empty($where_in)) {
            $this->db->where_in('technician_id',$where_in);
        }
        $this->db->order_by('job_assign_date ASC, technician_id ASC');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    public function getUnassignJobs($where_arr = '',$where_in=array()) {
        $this->db->select("technician_job_assign_id,technician_id,technician_job_assign_id,invoice_id,first_name,last_name,billing_street,billing_street_2,program_name,customers.customer_id,programs.program_id,programs.program_price,category_area_name,is_job_mode,is_complete,job_name,user_first_name,user_last_name,job_assign_date,property_address,job_assign_updated_date,jobs.job_id,job_price,property_title,property_tbl.property_id, customers.first_name as customer_first_name ,customers.last_name as customer_last_name");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');

        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        // $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");

        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        
        // $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (!empty($where_in)) {
            $this->db->where_in('technician_id',$where_in);
        }
        $this->db->order_by('job_assign_date ASC, technician_id ASC');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }
    public function getOneAssignTechnician($where_arr = '') {           
        $this->db->select("technician_job_assign_id,first_name,last_name,billing_street,billing_street_2,program_name,customers.customer_id,programs.program_id,technician_job_assign.property_id,yard_square_feet,invoice_id,category_area_name,is_job_mode,job_name,user_first_name,user_last_name,technician_job_assign.job_assign_date,property_address,job_assign_updated_date,jobs.job_id,job_price,base_fee_override,min_fee_override,difficulty_level,technician_job_assign.technician_id,job_assign_notes,technician_job_assign.route_id,route_name,is_time_check,TIME_FORMAT (`specific_time`,'%H:%i') as  specific_time,property_title");
        $this->db->from('technician_job_assign');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');
        $this->db->join('route','route.route_id = technician_job_assign.route_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }
	
	public function getAllTechAssignJobs($where_arr = ''){
		$this->db->select("technician_job_assign_id,first_name,last_name,billing_street,billing_street_2,program_name,customers.customer_id,programs.program_id,technician_job_assign.property_id,yard_square_feet,invoice_id,category_area_name,is_job_mode,job_name,user_first_name,user_last_name,technician_job_assign.job_assign_date,property_address,job_assign_updated_date,jobs.job_id,job_price,technician_job_assign.technician_id,job_assign_notes,technician_job_assign.route_id,route_name,is_time_check,TIME_FORMAT (`specific_time`,'%H:%i') as  specific_time,property_title");
        $this->db->from('technician_job_assign');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');
        $this->db->join('route','route.route_id = technician_job_assign.route_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$this->db->order_by('technician_job_assign.job_assign_date ASC');
        $result = $this->db->get();
		//die(print_r($this->db->last_query()));    
        $data = $result->result_array();
        return $data;
	}
    public function getAssignTechnicianJson($where_arr = '') {            
        $this->db->select("technician_job_assign_id as id,CONCAT(job_name,' - ',first_name,' ', last_name) as title,job_assign_date as start,technician_id,is_time_check, TIME_FORMAT (`specific_time`,'%H:%i') as  specific_time");
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->from('technician_job_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('job_assign_date ASC, technician_id ASC');
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
    }
    public function getTableData($where_arr = '') {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`category_area_name`,property_address,priority,property_type,property_title");
        $this->db->from('jobs');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('programs.program_id','asc');
        $this->db->order_by('jobs.job_id','asc');
        $result = $this->db->get();
        $data = $result->result();
        $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		$data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    public function getTableDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

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
        
        $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

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

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();

        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		// $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    public function getTableDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id, technician_job_assign.reschedule_message,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

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

        $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

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

	public function getCustomerAllServices($where_arr = '') {
		$this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name, user_first_name, user_last_name, technician_job_assign.job_assign_date, property_address, category_area_name, is_job_mode, coupon_code_csv");
        $this->db->from('jobs');
		$this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
		$this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
		$this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
		$this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
		$this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
		$this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');
        $this->db->join('category_property_area', 'category_property_area.property_area_cat_id = property_tbl.property_area');
        $this->db->join('(SELECT *, GROUP_CONCAT(coupon_code SEPARATOR ", ") AS coupon_code_csv FROM coupon_job GROUP BY job_id, program_id, property_id, customer_id) AS coupon_job', 'jobs.job_id = coupon_job.job_id AND customers.customer_id = coupon_job.customer_id AND programs.program_id = coupon_job.program_id AND property_tbl.property_id = coupon_job.property_id', 'left');
        $this->db->join('users', 'users.user_id = technician_job_assign.technician_id', 'left');


		if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$this->db->order_by('technician_job_assign.job_assign_date','desc');
		$result = $this->db->get();
        $data = $result->result();
		return $data;
	}
	public function getCustomerUnschedServ($where_arr = '') {
		$this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name");
        $this->db->from('jobs');
		$this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
		$this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
		$this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
		$this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
		$this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
		if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$this->db->order_by('property_title','asc');
		$result = $this->db->get();
        $data = $result->result();
		return $data;
	}
	
	public function GetLastCompletedServiceDateProgram($data) {
        $this->db->select("job_completed_date as program_completed_date");
        $this->db->from('technician_job_assign');
        $where_arr = array(
            "is_complete" => 1,
            "property_id" => $data->property_id,
			"program_id" => $data->program_id
        );
        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $row = $this->db->get()->row(); 
		//die(print_r($row));
        $data->program_completed_date = ($row) ? $row->program_completed_date : '';
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
    public function CreateOneTecnicianJob($post) {
        $query = $this->db->insert('technician_job_assign', $post);
        return $this->db->insert_id();
    }
    public function updateAssignJob($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update('technician_job_assign', $updatearr);
        return $a = $this->db->affected_rows();        
    }
    Public function deleteAssignJob($wherearr) {
        $this->db->where($wherearr);
        $this->db->delete('technician_job_assign');
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }
    public function getTableRouteDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $tech_name = '') {
        $this->db->select("technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, property_tbl.*");

        $this->db->from('technician_job_assign');

        $this->db->join('route','route.route_id = technician_job_assign.route_id','left');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','left');
        $this->db->join('users','users.user_id = technician_job_assign.technician_id','inner');
        
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL)");

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

         // search query
         $this->db->group_start();
         $this->db->like('CONCAT(users.user_first_name, " ", users.user_last_name)',$tech_name, false);
         $this->db->group_end();

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');
        
        $result = $this->db->get();
        $data = $result->result();
        // print_r($this->db->last_query());
    //    die(print_r($data));
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		// $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }
    public function getTableRouteDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, property_tbl.*");

        $this->db->from('technician_job_assign');

        $this->db->join('route','route.route_id = technician_job_assign.route_id','left');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','left');
        $this->db->join('users','users.user_id = technician_job_assign.technician_id','inner');
        
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL)");

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

         // search query
         $this->db->group_start();
         $this->db->like('CONCAT(users.user_first_name, " ", users.user_last_name)',$search, false);
         $this->db->group_end();
 
        
        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');
        
        $result = $this->db->get();
        $data = $result->result();
        // print_r($this->db->last_query());
    //    die(print_r($data));
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		// $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    
}