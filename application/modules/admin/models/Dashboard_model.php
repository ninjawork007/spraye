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
    public function getAssignTechnicianDisplay($where_arr = '',$where_in=array()) {
        $this->db->select("technician_job_assign_id,invoice_id,first_name,last_name,program_name,customers.customer_id,category_area_name,is_job_mode,job_name,user_first_name,user_last_name,job_assign_date,property_address,property_title,technician_id");
        $this->db->from('technician_job_assign');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (!empty($where_in)) {
            $this->db->where_in('technician_id',$where_in);
        }
        $this->db->order_by('job_assign_date DESC, technician_id ASC');
        //var_dump($this->db->get_compiled_select());
		//$this->db->limit(100);
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
        $this->db->limit(10000);
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

    public function getMapDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '', $or_where = '', $northEastLng = '', $northEastLat = '', $northWestLng = '', $northWestLat = '', $southWestLng = '', $southWestLat = '', $southEastLng = '', $southEastLat = '',$property_outstanding_services = '' ) {
//       die(var_dump([$where_arr, $where_like, $limit, $start, $col, $dir, $is_for_count, $where_in, $or_where, $northEastLng, $northEastLat, $northWestLng, $northWestLat, $southWestLng, $southWestLat, $southEastLng, $southEastLat,$property_outstanding_services]));
        // $file = fopen("test.txt","w");
        // echo fwrite($file,"We are inside getTableDataAjax function");
        // fclose($file);
        //print_r($where_in);
        //print_r($where_arr);
        //die(print_r($where_like));
        //die(print_r($or_where));
        //die($distance);
//		$file = fopen("test.txt","w");
//		fwrite($file,"We are inside getMapDataAjax function WITH filters");
//		fclose($file);
        $program_services_search = array();

        // Old/Original Not sure if incoming commit statement will work or be compatible with new statement so leaving this here for easy reference if need to revert
        // $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");
        if ($is_for_count){

            $select = "jobs.job_id";
            $this->db->select($select);
        } else {
            // New incoming commit statement
            $this->db->select("
            customers.first_name,
            customers.last_name,
            billing_street,
            billing_street_2,
            jobs.job_id,
            jobs.job_name,
            program_name,
            CASE WHEN datediff(now(),completed_date_property_program) >= (IFNULL(programs.program_schedule_window,30)+ 5) THEN 'Overdue' WHEN datediff(now(),completed_date_property_program) < (IFNULL(programs.program_schedule_window,30) - 5)  THEN 'Not Due' ELSE 'Due' END as service_due,
            customers.customer_id,
            programs.program_id,
            `property_tbl`.`property_id`,
            `property_tbl`.`property_notes`,
            `property_tbl`.`yard_square_feet`,
            `property_tbl`.`property_latitude`,
            `property_tbl`.`property_longitude`,
            `category_area_name`,
            property_address,
            program_job_assign. priority,
            property_type,
            property_title,
            completed_date_property,
            completed_date_property_program,
            technician_job_assign.is_job_mode,
            unassigned_Job_delete.unassigned_Job_delete_id,
            `property_tbl`.`property_state`,
            `property_tbl`.`property_city`,
            `property_tbl`.`property_zip`,
            `property_tbl`.`available_days`,
            customers.pre_service_notification,
            `property_tbl`.`tags`,
            `jobs`.`service_note`,
            `jobs`.`job_notes`,
            customers.customer_status,
            technician_job_assign.reschedule_message,
            (
                SELECT
                    MAX(job_completed_date) AS completed_date_last_service_by_type
                FROM
                    technician_job_assign tja 		
				JOIN
					jobs j
				ON
					j.job_id = tja.job_id
                WHERE
					tja.is_complete = 1
                    and j.service_type_id = jobs.service_type_id
                    and tja.property_id = property_program_assign.property_id
            ) as completed_date_last_service_by_type,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN 1 
                    ELSE 0
                END asap,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN program_job_assigned_customer_property.reason 
                ELSE '' 
            END as asap_reason 
        ", FALSE);
        }

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
        $this->db->join('program_job_assigned_customer_property', 'jobs.job_id = program_job_assigned_customer_property.job_id AND customers.customer_id = program_job_assigned_customer_property.customer_id AND programs.program_id = program_job_assigned_customer_property.program_id AND property_tbl.property_id = program_job_assigned_customer_property.property_id', 'left');
        // Filtering job name and program services

        if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        if (is_array($where_in) && array_key_exists( 'jobs.job_name', $where_in )) {
            $this->db->where_in( 'jobs.job_name', $where_in['jobs.job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }


        if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
            $program_services_search = $where_like['program_services'];
            unset($where_like['program_services']);
        }
        if (is_array($where_arr) && array_key_exists( 'program_services', $where_arr)) {
            $program_services_search = array($where_arr['program_services']);
            unset($where_arr['program_services']);
        }
        if (is_array($where_in) && array_key_exists( 'program_services', $where_in )) {
            if (is_array($where_like) && array_key_exists( 'jobs.job_name', $where_like )) {
                unset($where_in['program_services']);
            }
        }
        if (is_array($where_arr) && array_key_exists( 'program_job_assigned_customer_property', $where_arr)) {
            $val = $where_arr['program_job_assigned_customer_property'];
            if ($val == 1)
                $this->db->where('program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL');
            else
                $this->db->where('program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NULL');
            unset($where_arr['program_job_assigned_customer_property']);
        }
		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}

        if (is_array($where_in) && array_key_exists( 'job_name', $where_in )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }

        if (is_array($where_in) && array_key_exists( 'category_area_name', $where_in )) {
            $this->db->where_in('category_area_name',$where_in['category_area_name']);
        }
        // Available days
        if (is_array($where_in) && array_key_exists( 'available_days', $where_in)) {
            foreach($where_in['available_days'] as $day)
            {
                $this->db->where('JSON_VALUE(`property_tbl`.`available_days`, "$.'.$day.'" RETURNING UNSIGNED) IS TRUE');
            }
        }
        /*
        if (!empty($or_where)) {
            $this->db->group_start();
            $whereI = 0;
            foreach($or_where as $whereKey => $whereValue)
            {
                foreach($whereValue as $whereinsert)
                {
                $whereI++;
                if($whereI == 1)
                $this->db->where($whereKey,$whereinsert);
                else
                $this->db->or_where($whereKey,$whereinsert);
                }
            }
            $this->db->group_end();
        }
        */
        if (is_array($where_like)) {
            // We need to catch the service overdue flag and add to query, and unset the where_like['service_due'] elem,
            //@TODO check if CI has a better way of handling
            if(!empty($where_like['service_due'])){
                $this->db->group_start();
                $servicesDue = $where_like['service_due'];
                foreach($servicesDue as $due) {

                    switch ($due) {
                        case '2':
                            $this->db->or_where("datediff(now(),completed_date_property_program) >= (programs.program_schedule_window + 5)");
                            break;

                        case '3':
                            $this->db->or_where("datediff(now(),completed_date_property_program) < (programs.program_schedule_window -5)");
                            break;

                        case '1':
                            $this->db->or_where("(datediff(now(),completed_date_property_program) IS NULL OR programs.program_schedule_window IS NULL)");
                            break;

                        default:
                            break;
                    }
                }
                $this->db->group_end();
//                switch ($where_like['service_due']) {
//                    case '2':
//                        $this->db->where("datediff(now(),completed_date_property_program) >= (programs.program_schedule_window + 5)");
//                        break;
//
//                    case '3':
//                        $this->db->where("datediff(now(),completed_date_property_program) < (programs.program_schedule_window -5)");
//                        break;
//
//                    case '1':
//                        $this->db->where("(datediff(now(),completed_date_property_program) IS NULL OR programs.program_schedule_window IS NULL)");
//                        break;
//
//                    default:
//                        break;
//                }
                unset($where_like['service_due']);
            }
            if(!empty($where_like['pre_service_notification'])){
                switch($where_like['pre_service_notification']){
                    case '1':
                        $this->db->where("(customers.pre_service_notification LIKE '%1%')");
                        break;

                    case '2':
                        $this->db->where("(customers.pre_service_notification LIKE '%4%')");
                        break;

                    case '3':
                        $this->db->where("(customers.pre_service_notification LIKE '%2%' OR customers.pre_service_notification LIKE '%3%')");
                        break;

                    default:
                        break;

                }
                //unset($where_like['pre_service_notification']);
            }
            $this->db->like($where_like);
        }

        if($northEastLng != "") {
            // we only need to check if one of these is set - if 1 is set they all will be and we need to make sure the points we return are in the view
            $this->db->where('
                ST_CONTAINS(
                    ST_GEOMFROMTEXT(
                        "POLYGON((
                            '.$northEastLng.' '.$northEastLat.',
                            '.$northWestLng.' '.$northWestLat.',
                            '.$southWestLng.' '.$southWestLat.',
                            '.$southEastLng.' '.$southEastLat.',
                            '.$northEastLng.' '.$northEastLat.'
                        ))"
                    ),
                    POINT(property_longitude, property_latitude)
                )
            ');
        }
        if ($is_for_count){
            $this->db->group_by('');
        } else {
            $this->db->group_by('`customers`.`first_name`, `customers`.`last_name`, `billing_street`, `billing_street_2`, `jobs`.`job_id`, `jobs`.`job_name`, programs.`program_name`,
            service_due,
           `customers`.`customer_id`, `jobs`.`job_id`, `programs`.`program_id`, `property_tbl`.`property_id`, `property_tbl`.`property_notes`,
           `property_tbl`.`yard_square_feet`, `property_tbl`.`property_latitude`, `property_tbl`.`property_longitude`, `category_area_name`,
           `property_address`, program_job_assign.`priority`, `property_type`, `property_title`, `completed_date_property`, `completed_date_property_program`,
           `technician_job_assign`.`is_job_mode`, `unassigned_Job_delete`.`unassigned_Job_delete_id`, `property_tbl`.`property_state`,
           `property_tbl`.`property_city`, `property_tbl`.`property_zip`, `customers`.`pre_service_notification`, `property_tbl`.`tags`,
           `jobs`.`service_note`, `jobs`.`job_notes`, `customers`.`customer_status`,technician_job_assign.technician_id, completed_date_last_service_by_type');
        }
//        $this->db->order_by($col,$dir);
        if ($is_for_count == false || $limit == 50) {
            $this->db->limit($limit, $start);
            $this->db->order_by($col,$dir);
        }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');
        $result = $this->db->get();
        $data = $result->result();
//         die( print_r($this->db->last_query()));
        $final_data = array();
        // Check for programs service filter
        if (!empty($program_services_search) && !empty($property_outstanding_services)){

            foreach ($data as $d){
                $check_program_services = true;
                $property_outstanding_services_list = '';

                foreach ($property_outstanding_services as $pos){
                    if ($pos->property_id == $d->property_id && $property_outstanding_services_list == ''){
                        $property_outstanding_services_list =$pos->outstanding;
                    }
                }
                foreach ($program_services_search as $pss){

                    if (strpos($property_outstanding_services_list, $pss) !== false ){
                        //For some reason, for this specific condition it does not work with other condition, won't worrk with ===, == or !=.
                    } else {
                        $check_program_services = false;
                    }
                }
                if ($check_program_services){
                    array_push($final_data,$d);
                }
            }
            $data = $final_data;
        }

        // die( print_r($this->db->last_query()));
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        // die(print_r($this->db->last_query()));
        if ($is_for_count == false) {
            return $data;
        } else {
            return count($data);
        }

    }

    public function getTableDataAjaxCustomer($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '') {

        if ($is_for_count == false) {
            $this->db->select('*, CONCAT(first_name, " ", last_name) as customer_name');
        } else {
            $this->db->select('customer_id, CONCAT(first_name, " ", last_name) as customer_name');
        }
        $this->db->from('customers');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();

        if (!empty($data) && $is_for_count == false) {
            foreach ($data as $key => $value) {
                $data[$key]->property_id =  $this->CustomerModel->getAllproperty(array('customer_id' => $value->customer_id, 'property_status' => 1));
            }
        }
// die( print_r($this->db->last_query()));  
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        // die(print_r($this->db->last_query()));
        return $data;
    }

    public function getTableDataAjaxSearchCustomer($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count, $where_in = '') {

        if ($is_for_count == false) {
            $this->db->select('*, CONCAT(first_name, " ", last_name) as customer_name');
        } else {
            $this->db->select('customer_id, CONCAT(first_name, " ", last_name) as customer_name');
        }
        $this->db->from('customers');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->group_start();

        $this->db->or_like('CONCAT(customers.first_name, " ", customers.last_name)',$search, false);
        $this->db->or_like('customer_id',$search);
        $this->db->or_like('phone',$search);
        $this->db->or_like('email',$search);
        $this->db->or_like('billing_street',$search);
        $this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        if (!empty($data) && $is_for_count == false) {
            foreach ($data as $key => $value) {
                $data[$key]->property_id =  $this->CustomerModel->getAllproperty(array('customer_id' => $value->customer_id, 'property_status' => 1));
            }
        }

        return $data;
    }

    public function getTableDataAjaxProperty($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '') {

        if ($is_for_count == false) {
            $this->db->select('*, category_property_area.category_area_name, customer_property_assign.customer_id, CONCAT(customers.first_name, " ",customers.last_name) as customer_name');
        } else {
            $this->db->select('property_tbl.property_id, CONCAT(customers.first_name, " ",customers.last_name) as customer_name');
        }
        $this->db->from('property_tbl');
        $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
        $this->db->join('customer_property_assign', "customer_property_assign.property_id = property_tbl.property_id", "inner");
        $this->db->join('customers', "customers.customer_id = customer_property_assign.customer_id", "inner");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();

        //foreach ($data as $key => $value) {
        //$data[$key]->customer_id =  $this->PropertyModel->getAllcustomerDisplay(array('property_id' => $value->property_id));
        /*
        $data[$key]->program_id =  $this->PropertyModel->getAllprogramDisplay(array('property_id' => $value->property_id));
        $pid = array();
        foreach ($data[$key]->program_id as $value2) {
            $active = true;
            $ad_hoc = false;
            if(strstr($value2->program_name, '-Standalone Service')){
                $ad_hoc = true;
            } else if (strstr($value2->program_name, '- One Time Project Invoicing') && strstr($value2->program_name, '+')){
                $ad_hoc = true;
            } else if (strstr($value2->program_name, '- Invoiced at Job Completion') && strstr($value2->program_name, '+')){
                $ad_hoc = true;
            } else if (strstr($value2->program_name, '- Manual Billing') && strstr($value2->program_name, '+')){
                $ad_hoc = true;
            } else {
                $ad_hoc = false;
            }

            if ($value2->ad_hoc == 1){
                $ad_hoc = true;
            }
            if($value2->program_active == 0){
                $active = false;
            }
            if($active && !$ad_hoc){
                $pid[] =  $value2->program_name;
            }
        }
        $data2 = array(
                'program_text_for_display' => implode(' , ',$pid)
        );

        $this->db->where('property_id', $data[$key]->property_id);
        $this->db->update('property_tbl', $data2);
        */
        //}

        return $data;
    }

    public function getTableDataAjaxSearchProperty($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count, $where_in = '') {

        if ($is_for_count == false) {
            $this->db->select('*, category_property_area.category_area_name, customer_property_assign.customer_id, CONCAT(customers.first_name, " ",customers.last_name) as customer_name');
        } else {
            $this->db->select('property_tbl.property_id, CONCAT(customers.first_name, " ",customers.last_name) as customer_name');
        }
        $this->db->from('property_tbl');
        $this->db->join('category_property_area', "category_property_area.property_area_cat_id = property_tbl.property_area", "left");
        $this->db->join('customer_property_assign', "customer_property_assign.property_id = property_tbl.property_id", "inner");
        $this->db->join('customers', "customers.customer_id = customer_property_assign.customer_id", "inner");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->group_start();

        $this->db->or_like('property_title',$search);
        $this->db->or_like('property_address',$search);
        $this->db->or_like('program_text_for_display',$search);
        $this->db->or_like('customers.first_name',$search);
        $this->db->or_like('customers.last_name',$search);
        $this->db->or_like('property_status',$search);
        $this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function getUnassignedServiceList($where_arr)
    {

        $this->db->select("jobs.job_id,job_name");

        // $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

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

        // to filter out deleted & non-reschedule & un-cancelled
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        $this->db->join("cancelled_services_tbl", "unassigned_Job_delete.customer_id = cancelled_services_tbl.customer_id AND unassigned_Job_delete.job_id = cancelled_services_tbl.job_id AND unassigned_Job_delete.program_id = cancelled_services_tbl.program_id AND unassigned_Job_delete.property_id = cancelled_services_tbl.property_id", "left");

        $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND (unassigned_Job_delete_id IS NULL OR cancelled_services_tbl.cancelled_service_id IS NULL OR cancelled_services_tbl.is_cancelled = 0)");
        ##### ADDED TO ALLOW PROSPECT PROPERTY TO SHOW IN UNASSIGNED SERVICE TABLE (RG)3/3/22 #####
        $this->db->where("(property_status = 2 OR property_status = 1) ");
        ####

        $this->db->distinct();


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();
//        die(print_r($this->db->last_query()));
        $data = $result->result_array();

        return $data;
    }

    public function getTableDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count, $where_in = '', $or_where = '',$property_outstanding_services = '') {
//        die(var_dump([$where_arr, $where_like, $limit, $start, $col, $dir, $search, $is_for_count, $where_in, $or_where,$property_outstanding_services]));
//        $file = fopen("test.txt","w");
//        fwrite($file,"We are inside getTableDataAjaxSearch function");
//        fclose($file);

        if ($is_for_count){
            $this->db->select(" COUNT(*) AS count ");
        } else {
            $this->db->select("
                customers.first_name,
                customers.last_name,
                billing_street,
                billing_street_2,
                jobs.job_id,
                jobs.job_name as job_name,
                program_name,CASE WHEN datediff(now(),
                completed_date_property_program) >= (IFNULL(programs.program_schedule_window,30) + 5) THEN 'Overdue' WHEN datediff(now(),completed_date_property_program) < (IFNULL(programs.program_schedule_window,30) - 5) THEN 'Not Due' ELSE 'Due' END as service_due,
                customers.customer_id,
                jobs.job_id,programs.program_id,
                `property_tbl`.`property_id`,
                `property_tbl`.`property_notes`,
                `property_tbl`.`yard_square_feet`,
                `property_tbl`.`property_latitude`,
                `property_tbl`.`property_longitude`,
                `category_area_name`,
                property_address,
                program_job_assign.priority,
                property_type,
                property_title,
                completed_date_property,
                completed_date_property_program,
                technician_job_assign.is_job_mode,
                unassigned_Job_delete.unassigned_Job_delete_id,
                technician_job_assign.reschedule_message,
                `property_tbl`.`property_state`,
                `property_tbl`.`property_city`,
                `property_tbl`.`property_zip`,
                `property_tbl`.`available_days`, 
                customers.pre_service_notification,
                customers.customer_status,
                `property_tbl`.`tags`,
                `jobs`.`service_note`,
                 property_program_assign.property_program_date,
                 technician.user_first_name,
                 technician.user_last_name,
                 (
                        SELECT
                            MAX(job_completed_date) AS completed_date_last_service_by_type
                        FROM
                            technician_job_assign tja 		
                        JOIN
                            jobs j
                        ON
                            j.job_id = tja.job_id
                        WHERE
                            tja.is_complete = 1
                            and j.service_type_id = jobs.service_type_id
                            and tja.property_id = property_program_assign.property_id
                 ) as completed_date_last_service_by_type,
                 (
                    SELECT 
                        reschedule_message 
                    FROM 
                        technician_job_assign 
                    WHERE 
                        customer_id = customers.customer_id AND 
                        job_id = jobs.job_id AND 
                        program_id = programs.program_id AND 
                        property_id = property_tbl.property_id
                 ) assign_reschedule_message,
                 EXISTS(
                    SELECT 
                        * 
                    FROM 
                        technician_job_assign 
                    WHERE 
                        is_job_mode = 2 AND 
                        customer_id = customers.customer_id AND
                        job_id = jobs.job_id AND 
                        program_id = programs.program_id 
                        AND property_id = property_tbl.property_id
                 ) assign_table_data,
                 CASE 
                    WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                        THEN 1 
                        ELSE 0
                    END asap,
                 CASE 
                    WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                        THEN program_job_assigned_customer_property.reason 
                    ELSE '' 
                 END as asap_reason 
                ", FALSE);
        }


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
        $this->db->join('users technician', 'technician.user_id = technician_job_assign.technician_id', 'left');
        $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        $this->db->join('program_job_assigned_customer_property', 'jobs.job_id = program_job_assigned_customer_property.job_id AND customers.customer_id = program_job_assigned_customer_property.customer_id AND programs.program_id = program_job_assigned_customer_property.program_id AND property_tbl.property_id = program_job_assigned_customer_property.property_id', 'left');

        if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        if (is_array($where_in) && array_key_exists( 'jobs.job_name', $where_in )) {
            $this->db->where_in( 'jobs.job_name', $where_in['jobs.job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }

        if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
            $program_services_search = $where_like['program_services'];
            unset($where_like['program_services']);
        }
        if (is_array($where_arr) && array_key_exists( 'program_services', $where_arr)) {
            $program_services_search = array($where_arr['program_services']);
            unset($where_arr['program_services']);
        }
        // die(print_r($program_services_search));
        if (is_array($where_in) && array_key_exists( 'program_services', $where_in )) {
            if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
                unset($where_in['program_services']);
            }
        }

        // Available days
        if (is_array($where_in) && array_key_exists( 'available_days', $where_in)) {
            foreach($where_in['available_days'] as $day)
            {
                $this->db->where('JSON_VALUE(`property_tbl`.`available_days`, "$.'.$day.'" RETURNING UNSIGNED) IS TRUE');
            }
        }

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        //     $whereI = 0;

//        if (is_array($where_in)) {
//            $this->db->where_in($where_in);
//        } elseif (is_array($where_like)) {
//            $this->db->like($where_like);
//        }


        if (is_array($where_like)) {
            // We need to catch the service overdue flag and add to query, and unset the where_like['service_due'] elem,
            //@TODO check if CI has a better way of handling
            if(!empty($where_like['service_due'])){
                $this->db->group_start();
                $servicesDue = explode(",", $where_like['service_due']);
                foreach($servicesDue as $due) {
                    switch ($due) {
                        case '2':
                            $this->db->or_where("datediff(now(),
                            (
	                            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) >= (programs.program_schedule_window + 5)");
                            break;

                        case '3':
                            $this->db->or_where("datediff(now(),
                            (
                                SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) < (programs.program_schedule_window -5)");
                            break;

                        case '1':
                            $this->db->or_where("(datediff(now(),
                            (
                                SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) IS NULL OR programs.program_schedule_window IS NULL)");
                            break;

                        default:
                            break;
                    }
                }
                $this->db->group_end();
//                switch ($where_like['service_due']) {
//                    case '2':
//                        $this->db->where("datediff(now(),completed_date_property_program) >= (programs.program_schedule_window + 5)");
//                        break;
//
//                    case '3':
//                        $this->db->where("datediff(now(),completed_date_property_program) < (programs.program_schedule_window -5)");
//                        break;
//
//                    case '1':
//                        $this->db->where("(datediff(now(),completed_date_property_program) IS NULL OR programs.program_schedule_window IS NULL)");
//                        break;
//
//                    default:
//                        break;
//                }
                unset($where_like['service_due']);
            }
            if(!empty($where_like['pre_service_notification'])){
                switch($where_like['pre_service_notification']){
                    case '1':
                        $this->db->where("(customers.pre_service_notification LIKE '%1%')");
                        break;

                    case '2':
                        $this->db->where("(customers.pre_service_notification LIKE '%4%')");
                        break;

                    case '3':
                        $this->db->where("(customers.pre_service_notification LIKE '%2%' OR customers.pre_service_notification LIKE '%3%')");
                        break;

                    default:
                        break;

                }
                unset($where_like['pre_service_notification']);
            }
            $this->db->like($where_like);
        }


        $this->db->group_start();
        $this->db->like('program_job_assign.priority',$search);

        $this->db->or_like('CONCAT(customers.first_name, " ", customers.last_name)',$search, false);
        $this->db->or_like('property_title',$search);
        $this->db->or_like('`property_tbl`.`yard_square_feet`',$search);
        $this->db->or_like('completed_date_property',$search);
        $this->db->or_like('completed_date_property_program',$search);
        $this->db->or_like('property_address',$search);
        $this->db->or_like('property_type',$search);
        $this->db->or_like('property_notes',$search);
        $this->db->or_like('category_area_name',$search);
        $this->db->or_like('program_name',$search);
        $this->db->or_like('reschedule_message',$search);
        $this->db->or_like('jobs.job_name',$search);
       // $this->db->or_like('program_schedule_window',$search);
        $this->db->group_end();
        if (!empty($or_where)) {
            $this->db->group_start();
            $whereI = 0;
            foreach($or_where as $whereKey => $whereValue)
            {
                foreach($whereValue as $whereinsert)
                {
                    $whereI++;
                    if($whereI == 1)
                        $this->db->where($whereKey,$whereinsert);
                    else
                        $this->db->or_where($whereKey,$whereinsert);
                }
            }
            $this->db->group_end();
        }
        if ($is_for_count){

            $this->db->group_by('');
        } else {
            $this->db->group_by('`customers`.`first_name`, `customers`.`last_name`, `billing_street`, `billing_street_2`, `jobs`.`job_id`, `jobs`.`job_name`, programs.`program_name`,
            service_due,
           `customers`.`customer_id`, `jobs`.`job_id`, `programs`.`program_id`, `property_tbl`.`property_id`, `property_tbl`.`property_notes`,
           `property_tbl`.`yard_square_feet`, `property_tbl`.`property_latitude`, `property_tbl`.`property_longitude`, `category_area_name`,
           `property_address`, program_job_assign.`priority`, `property_type`, `property_title`, `completed_date_property`, `completed_date_property_program`,
           `technician_job_assign`.`is_job_mode`, `unassigned_Job_delete`.`unassigned_Job_delete_id`, `property_tbl`.`property_state`,
           `property_tbl`.`property_city`, `property_tbl`.`property_zip`, `customers`.`pre_service_notification`, `property_tbl`.`tags`,
           `jobs`.`service_note`, `jobs`.`job_notes`, `customers`.`customer_status`,`technician_job_assign`.`technician_id`,`property_program_assign`.`property_program_date`, 
           `technician`.`user_first_name`, `technician`.`user_last_name`, completed_date_last_service_by_type, assign_reschedule_message, assign_table_data,'
            );
            $this->db->order_by($col,$dir);
        }


       // $this->db->group_by('1,2,5,6,7,8,9');
//        $this->db->order_by($col,$dir);
        if ($is_for_count == false || $limit == 50) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        $final_data = array();
        // Check for programs service filter
//        die(print_r($property_outstanding_services));
        if (!empty($program_services_search) && !empty($property_outstanding_services)){

            foreach ($data as $d){
                $check_program_services = true;
                $property_outstanding_services_list = '';

                foreach ($property_outstanding_services as $pos){
                    if ($pos->property_id == $d->property_id && $property_outstanding_services_list == ''){
                        $property_outstanding_services_list =$pos->outstanding;
                    }
                }
                foreach ($program_services_search as $pss){

                    if (strpos($property_outstanding_services_list, $pss) !== false ){
                        //For some reason, for this specific condition it does not work with other condition, won't worrk with ===, == or !=.
                    } else {
                        $check_program_services = false;
                    }
                }
                if ($check_program_services){
                    array_push($final_data,$d);
                }
            }
            $data = $final_data;
        }
        if ($is_for_count == false) {
            return $data;
        } else {
            if (isset($data[0]) && isset($data[0]->count))
                return $data[0]->count;
            return count($data);
        }

//        return $data;
    }

// Commented Code is old version before commit merge
    // public function getCustomerAllServices($where_arr = '') {
    //  $this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name, user_first_name, user_last_name, technician_job_assign.job_assign_date, property_address, category_area_name, is_job_mode, coupon_code_csv");
    //        $this->db->from('jobs');
    //  $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
    //  $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
    //  $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
    //  $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
    //  $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
    //  $this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
    //        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');
    //        $this->db->join('category_property_area', 'category_property_area.property_area_cat_id = property_tbl.property_area');
    //        $this->db->join('(SELECT *, GROUP_CONCAT(coupon_code SEPARATOR ", ") AS coupon_code_csv FROM coupon_job GROUP BY job_id, program_id, property_id, customer_id) AS coupon_job', 'jobs.job_id = coupon_job.job_id AND customers.customer_id = coupon_job.customer_id AND programs.program_id = coupon_job.program_id AND property_tbl.property_id = coupon_job.property_id', 'left');
    //        $this->db->join('users', 'users.user_id = technician_job_assign.technician_id', 'left');


    //  if (is_array($where_arr)) {
    //            $this->db->where($where_arr);
    //        }
    //  $this->db->order_by('technician_job_assign.job_assign_date','desc');
    //  $result = $this->db->get();
    //        $data = $result->result();
    //  return $data;
    // }
//Old code end

    public function getCustomerAllServices($where_arr = '') {
        $this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name, programs.program_active, user_first_name, user_last_name, technician_job_assign.job_assign_date, property_address, category_area_name, is_job_mode, coupon_code_csv");
        $this->db->from('jobs');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');
        $this->db->join('category_property_area', 'category_property_area.property_area_cat_id = property_tbl.property_area', 'left');
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
    public function getCustomerAllServicesWithSalesRep($where_arr = '') {
        $this->db->select("
            technician_job_assign.technician_job_assign_id,
            jobs.job_id,
            `property_program_job_invoice`.`job_cost`,
            job_name,
            customer_property_assign.customer_id,
            jobs.job_id,
            `property_tbl`.`property_id`,
            `property_tbl`.`yard_square_feet`,
            `property_tbl`.`difficulty_level`,
            property_program_assign.program_id,
            property_title,
            programs.program_name,
            technician.user_first_name,
            technician.user_last_name,
            technician_job_assign.job_assign_date,
            property_address,
            category_area_name,
            is_job_mode,
            coupon_code_csv,
            t_estimate.sales_rep,
            CONCAT(sales_rep.user_first_name,' ', sales_rep.user_last_name )as sales_rep_name,
            jobs.job_price,
            coupon_amount,
            jobs.base_fee_override,
            jobs.min_fee_override,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN 1 
                    ELSE 0
                END asap,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN program_job_assigned_customer_property.reason 
                ELSE '' 
            END as asap_reason 
        ", FALSE);
        $this->db->from('jobs');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
        $this->db->join('property_program_job_invoice', 'property_program_job_invoice.customer_id = customers.customer_id AND property_program_job_invoice.program_id = programs.program_id and property_tbl.property_id = property_program_job_invoice.property_id AND property_program_job_invoice.job_id = jobs.job_id', 'left');
        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');
        $this->db->join('category_property_area', 'category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('(SELECT *, GROUP_CONCAT(coupon_code SEPARATOR ", ") AS coupon_code_csv FROM coupon_job GROUP BY job_id, program_id, property_id, customer_id) AS coupon_job', 'jobs.job_id = coupon_job.job_id AND customers.customer_id = coupon_job.customer_id AND programs.program_id = coupon_job.program_id AND property_tbl.property_id = coupon_job.property_id', 'left');
        $this->db->join('users technician', 'technician.user_id = technician_job_assign.technician_id', 'left');
        $this->db->join('t_estimate ', 'customer_property_assign.customer_id =  t_estimate.customer_id AND property_program_assign.program_id = t_estimate.program_id AND property_tbl.property_id = t_estimate.property_id', 'left');
        $this->db->join('users sales_rep', 'sales_rep.id = t_estimate.sales_rep', 'left');
        //$this->db->join('program_service_property_price_overrides psp', 'psp.property_id = property_tbl.property_id and psp.program_id = programs.program_id and psp.job_id = jobs.job_id' , 'left');
        $this->db->join('program_job_assigned_customer_property', 'jobs.job_id = program_job_assigned_customer_property.job_id AND customers.customer_id = program_job_assigned_customer_property.customer_id AND programs.program_id = program_job_assigned_customer_property.program_id AND property_tbl.property_id = program_job_assigned_customer_property.property_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('technician_job_assign.job_assign_date','desc');
        $result = $this->db->get();
        $data = $result->result();
        //print_r($this->db->last_query());
        return $data;
    }
    public function getCustomerAllServicesWOARea($where_arr = '') {
        $this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name, programs.program_active, user_first_name, user_last_name, technician_job_assign.job_assign_date, property_address, category_area_name, is_job_mode, coupon_code_csv");
        $this->db->from('jobs');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');

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
        $this->db->select("jobs.job_id,job_name,customer_property_assign.customer_id,jobs.job_id,`property_tbl`.`property_id`,property_program_assign.program_id,property_title, programs.program_name, jobs.ad_hoc");
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
    public function deleteAssignJob($wherearr) {
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

        // $this->db->select("technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, property_tbl.*");
        if($is_for_count == true) {
            $this->db->select("technician_job_assign.technician_job_assign_id");
        } else {
            $this->db->select("technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, programs.program_name, programs.program_schedule_window, property_tbl.*,customers.pre_service_notification");
        }

        $this->db->from('technician_job_assign');

        $this->db->join('route','route.route_id = technician_job_assign.route_id','left');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','left');
        $this->db->join('jobs','jobs.job_id = technician_job_assign.job_id','left');
        $this->db->join('users','users.user_id = technician_job_assign.technician_id','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');

        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL)");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->order_by($col,$dir);
        $this->db->group_by('technician_job_assign.technician_job_assign_id');
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
    public function getTableRouteDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $search = null) {
        $this->db->select("technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, property_tbl.*, customers.pre_service_notification");

        $this->db->from('technician_job_assign');

        $this->db->join('route','route.route_id = technician_job_assign.route_id','left');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','left');
        $this->db->join('users','users.user_id = technician_job_assign.technician_id','inner');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');

        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        //$this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL)");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->order_by($col,$dir);
        $this->db->group_by('technician_job_assign.technician_job_assign_id');
        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        // search query
        $this->db->group_start();
        $this->db->like('CONCAT(users.user_first_name, " ", users.user_last_name)',$search, false);
        $this->db->group_end();

        $result = $this->db->get();
        $data = $result->result();
        // print_r($this->db->last_query());
        //    die(print_r($data));
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    public function getWorkReportOutstanding($where_arr = '', $where_in = '') {

        // die(print_r(json_encode($where_in),true));

        $this->db->select("
            DISTINCT(jobs.job_id),
            job_name,
            category_property_area.category_area_name,
            property_program_assign.program_id,
            customer_property_assign.customer_id,
            property_program_assign.property_id,
            property_tbl.yard_square_feet
        ");

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

        if (is_array($where_in) && array_key_exists( 'job_id', $where_in )) {
            $this->db->where_in( 'jobs.job_id', $where_in['job_id'] );
        }

        if (is_array($where_in) && array_key_exists( 'program_id', $where_in )) {
            $this->db->where_in( 'programs.program_id', $where_in['program_id'] );
        }

        $result = $this->db->get();
        //die(print_r($this->db->last_query()));
        $data = $result->result();

        return $data;
    }
    public function getTableProspectDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`, `property_tbl`.`prospect_status`");
        // $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

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

        // $this->db->where("(is_job_mode = 1 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        // $this->db->where("prospect_status = 1");

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

        //     print_r($this->db->last_query());
        //    die(print_r($data));

        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    public function getTableProspectDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count) {
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


    public function getUnassignJobsWhere($jobId, $grassType) {
        $this->db->select("technician_job_assign_id,technician_id,technician_job_assign_id,invoice_id,program_name,programs.program_id,is_job_mode,is_complete,job_name,job_assign_date,job_assign_updated_date,jobs.job_id,property_tbl.*,products.*");

        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');

        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');

        // latest property job date
        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");


        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", " unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        $this->db->join("job_product_assign", "job_product_assign.job_id = jobs.job_id", "inner");
        $this->db->join("products", "products.product_id = job_product_assign.product_id", "inner");

        $this->db->where("(is_job_mode = 2 OR is_job_mode = 0 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

        // if (is_array($where_arr)) {
        //     $this->db->where($where_arr);
        // }

        $this->db->where('jobs.job_id',$jobId);


        // $this->db->order_by('job_assign_date ASC, technician_id ASC');
        $result = $this->db->get();
        $data = $result->result();
        $new_data = array();
        if(!empty($data)){
            foreach($data as $d){
                if($grassType != ''){
                    if($d->front_yard_grass == $grassType || $d->back_yard_grass == $grassType){
                        array_push($new_data, $d);
                    } else if($d->total_yard_grass == $grassType){
                        array_push($new_data, $d);
                    }
                } else {
                    array_push($new_data, $d);
                }
            }
        }
        return $new_data;
    }

    public function getTableDataAjaxAll($where_arr = '') {
        $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,
         job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,
         `property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,
         `property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,
         property_type,property_notes,property_title, completed_date_property, completed_date_property_program,
          technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,
          `property_tbl`.`property_city`,`property_tbl`.`tags`,`property_tbl`.`property_zip`");

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


        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();

        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);

        return $data;
    }

    public function getTableDataAssigned($where_arr) {

        $this->db->select("property_tbl.property_id,technician_job_assign.technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, CONCAT(users.user_first_name, ' ', users.user_last_name) AS 'tech_name',  route.route_id, route.route_name, technician_job_assign.job_assign_date, technician_job_assign.customer_id, technician_job_assign.job_id, technician_job_assign.program_id, property_tbl.*");

        $this->db->from('technician_job_assign');
        $this->db->join('route','route.route_id = technician_job_assign.route_id','left');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','left');
        $this->db->join('users','users.user_id = technician_job_assign.technician_id','inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $tech_name="";
        // search query
        $this->db->group_start();
        $this->db->like('CONCAT(users.user_first_name, " ", users.user_last_name)',$tech_name, false);
        $this->db->group_end();


        $result = $this->db->get();
        $data = $result->result();

        return $data;

    }

    public function getTableDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '', $or_where = '') {
        // $file = fopen("test.txt","w");
        // echo fwrite($file,"We are inside getTableDataAjax function");
        // fclose($file);
        //print_r($where_in);
		//print_r($where_arr);
         $program_services_search = array();
			
        $file = fopen("test.txt","w");
        fwrite($file,"We are inside getTableDataAjax function WITH filters");
        fclose($file);

        // Old/Original Not sure if incoming commit statement will work or be compatible with new statement so leaving this here for easy reference if need to revert
        // $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

        // New incoming commit statement
        $this->db->select("
        customers.first_name,
        customers.last_name,
        billing_street,
        billing_street_2,
        jobs.job_id,
        jobs.job_name,
        program_name, 
        CASE WHEN datediff(now(),completed_date_property_program) >= (IFNULL(programs.program_schedule_window,30)+ 5) THEN 'Overdue' WHEN datediff(now(),completed_date_property_program) < (IFNULL(programs.program_schedule_window,30) - 5)  THEN 'Not Due' ELSE 'Due' END as service_due,
         customers.customer_id,
         jobs.job_id,
         programs.program_id,
         `property_tbl`.`property_id`,
         `property_tbl`.`property_notes`,
         `property_tbl`.`yard_square_feet`,
         `property_tbl`.`property_latitude`,
         `property_tbl`.`property_longitude`,
         `category_area_name`,
         property_address,
         program_job_assign.priority,
         property_type,
         property_title, 
         completed_date_property, 
         completed_date_property_program, 
         technician_job_assign.is_job_mode, 
         unassigned_Job_delete.unassigned_Job_delete_id,
         `property_tbl`.`property_state`,
         `property_tbl`.`property_city`,
         `property_tbl`.`property_zip`,
         customers.pre_service_notification,
         `property_tbl`.`tags`,
         `property_tbl`.`available_days`,
         `jobs`.`service_note`,
         `jobs`.`job_notes`,
         customers.customer_status,
         group_concat(distinct j2.job_name SEPARATOR ',') as program_services
         ");
        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
         $this->db->join('program_job_assign p2','p2.program_id = program_job_assign.program_id ','inner');
         $this->db->join('jobs j2','jobs j2 ON j2.job_id =p2.job_id ','inner');
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



        if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
         if (is_array($where_in) && array_key_exists( 'jobs.job_name', $where_in )) {
             $this->db->where_in( 'jobs.job_name', $where_in['jobs.job_name'] );
             if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                 unset($where_like['job_name']);
             }
         }



         if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
             $program_services_search = $where_like['program_services'];
             unset($where_like['program_services']);
         }
         if (is_array($where_arr) && array_key_exists( 'program_services', $where_arr)) {
             $program_services_search = array($where_arr['program_services']);
             unset($where_arr['program_services']);
         }

         if (is_array($where_in) && array_key_exists( 'program_services', $where_in )) {
             if (is_array($where_like) && array_key_exists( 'jobs.job_name', $where_like )) {
                 unset($where_in['program_services']);
             }
         }

         if (is_array($where_arr)) {
             $this->db->where($where_arr);
         }

        if (!empty($or_where)) {	
            $this->db->group_start();	
            $whereI = 0;	
            foreach($or_where as $whereKey => $whereValue)	
            {	
                foreach($whereValue as $whereinsert)	
                {	
                $whereI++;	
                if($whereI == 1)	
                $this->db->where($whereKey,$whereinsert);	
                else	
                $this->db->or_where($whereKey,$whereinsert);	
                }	
            }	
            $this->db->group_end();	
        }
        //die(print_r($where_in));
        if (is_array($where_like)) {
            // We need to catch the service overdue flag and add to query, and unset the where_like['service_due'] elem,
            //@TODO check if CI has a better way of handling
            if(!empty($where_like['service_due'])){
                switch ($where_like['service_due']) {
                    case '2':
                        $this->db->where("datediff(now(),completed_date_property_program) >= (programs.program_schedule_window + 5)");
                        break;

                    case '3':
                        $this->db->where("datediff(now(),completed_date_property_program) < (programs.program_schedule_window -5)");
                        break;

                    case '1':
                        $this->db->where("(datediff(now(),completed_date_property_program) IS NULL OR programs.program_schedule_window IS NULL)");
                        break;

                    default:
                        break;
                }
                unset($where_like['service_due']);
            }
            if(!empty($where_like['pre_service_notification'])){
                switch($where_like['pre_service_notification']){
                    case '1':
                        $this->db->where("(customers.pre_service_notification LIKE '%1%')");
                        break;

                    case '2':
                        $this->db->where("(customers.pre_service_notification LIKE '%4%')");
                        break;

                    case '3':
                        $this->db->where("(customers.pre_service_notification LIKE '%2%' OR customers.pre_service_notification LIKE '%3%')");
                        break;

                    default:
                        break;

                }
                unset($where_like['pre_service_notification']);
            }
            $this->db->like($where_like);
        }

        $this->db->group_by('`customers`.`first_name`, `customers`.`last_name`, `billing_street`, `billing_street_2`, `jobs`.`job_id`, `jobs`.`job_name`, programs.`program_name`,
        service_due,
       `customers`.`customer_id`, `jobs`.`job_id`, `programs`.`program_id`, `property_tbl`.`property_id`, `property_tbl`.`property_notes`,
       `property_tbl`.`yard_square_feet`, `property_tbl`.`property_latitude`, `property_tbl`.`property_longitude`, `category_area_name`,
       `property_address`, program_job_assign.`priority`, `property_type`, `property_title`, `completed_date_property`, `completed_date_property_program`,
       `technician_job_assign`.`is_job_mode`, `unassigned_Job_delete`.`unassigned_Job_delete_id`, `property_tbl`.`property_state`,
       `property_tbl`.`property_city`, `property_tbl`.`property_zip`, `customers`.`pre_service_notification`, `property_tbl`.`tags`,
       `jobs`.`service_note`, `jobs`.`job_notes`, `customers`.`customer_status`,technician_job_assign.technician_id');
        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();
        $final_data = array();
        // Check for programs service filter
         if (!empty($program_services_search) ){
            foreach ($data as $d){
                $check_program_services = true;
                foreach ($program_services_search as $pss){
                    if (strpos($d->program_services, $pss) !== false ){
                        //echo 'no coincide';
                    } else {
                        $check_program_services = false;
                    }
                }
                if ($check_program_services){
                    array_push($final_data,$d);
                }
            }
            $data = $final_data;
         }

// die( print_r($this->db->last_query()));  
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
        // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        // die(print_r($this->db->last_query()));
        return $data;
    }

    public function getTableDataAjax_new($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '', $or_where = '',$property_outstanding_services = '') {
//        die(var_dump([
//            $where_arr, $where_like, $limit, $start, $col, $dir, $is_for_count, $where_in, $or_where,$property_outstanding_services
//        ]));
        $program_services_search = array();
        $select = "";
        if ($is_for_count){

            $select = "
            jobs.job_id,
            customers.customer_id,
            `property_tbl`.`property_id`,
            CASE WHEN datediff(now(),(
	            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
            )) >= (IFNULL(programs.program_schedule_window,30)+ 5) THEN 'Overdue' WHEN datediff(now(),(
	            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
            )) < (IFNULL(programs.program_schedule_window,30) - 5)  THEN 'Not Due' ELSE 'Due' END as service_due,
            (
                SELECT MAX(job_completed_date) AS completed_date_property FROM technician_job_assign where property_id = property_program_assign.property_id GROUP BY property_id
            ) as completed_date_property,
            (
	            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
            ) as completed_date_property_program,
            (
                SELECT
                    MAX(job_completed_date) AS completed_date_last_service_by_type
                FROM
                    technician_job_assign tja 		
				JOIN
					jobs j
				ON
					j.job_id = tja.job_id
                WHERE
					tja.is_complete = 1
                    and j.service_type_id = jobs.service_type_id
                    and tja.property_id = property_program_assign.property_id
            ) as completed_date_last_service_by_type
            ";
            $select = " COUNT(*) as count";
        } else {

            $select = "
            customers.first_name,
            customers.last_name,
            billing_street,
            billing_street_2,
            jobs.job_id,
            jobs.job_name,
            program_name, 
             customers.customer_id,
             jobs.job_id,
             programs.program_id,
             `property_tbl`.`property_id`,
             `property_tbl`.`property_notes`,
             `property_tbl`.`yard_square_feet`,
             `property_tbl`.`property_latitude`,
             `property_tbl`.`property_longitude`,
             `category_area_name`,
             property_address,
             program_job_assign.priority,
             property_type,
             property_title, 
             technician_job_assign.is_job_mode, 
             unassigned_Job_delete.unassigned_Job_delete_id,
             `property_tbl`.`property_state`,
             `property_tbl`.`property_city`,
             `property_tbl`.`property_zip`,
             customers.pre_service_notification,
             `property_tbl`.`tags`,
             `property_tbl`.`available_days`,	     
             `jobs`.`service_note`,
             `jobs`.`job_notes`,
             customers.customer_status,
             (
                SELECT 
                    reschedule_message 
                FROM 
                    technician_job_assign 
                WHERE 
                    customer_id = customers.customer_id AND 
                    job_id = jobs.job_id AND 
                    program_id = programs.program_id AND 
                    property_id = property_tbl.property_id
             ) assign_reschedule_message,
             EXISTS(
                SELECT 
                    * 
                FROM 
                    technician_job_assign 
                WHERE 
                    is_job_mode = 2 AND 
                    customer_id = customers.customer_id AND
                    job_id = jobs.job_id AND 
                    program_id = programs.program_id 
                    AND property_id = property_tbl.property_id
             ) assign_table_data,   
             property_program_assign.property_program_date,
             technician.user_first_name,
             technician.user_last_name,
             (
                SELECT
                    MAX(job_completed_date) AS completed_date_last_service_by_type
                FROM
                    technician_job_assign tja 		
				JOIN
					jobs j
				ON
					j.job_id = tja.job_id
                WHERE
					tja.is_complete = 1
                    and j.service_type_id = jobs.service_type_id
                    and tja.property_id = property_program_assign.property_id
            ) as completed_date_last_service_by_type,
            (
                SELECT MAX(job_completed_date) AS completed_date_property FROM technician_job_assign where property_id = property_program_assign.property_id GROUP BY property_id
            ) as completed_date_property,
            (
	            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
            ) as completed_date_property_program,
            CASE WHEN datediff(now(), (
	            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
            )) >= (IFNULL(programs.program_schedule_window, 30)+ 5) THEN 'Overdue' WHEN datediff(now(), 
            ( SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id)) < (IFNULL(programs.program_schedule_window, 30) - 5)  THEN 'Not Due' ELSE 'Due' END as service_due,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN 1 
                    ELSE 0
                END asap,
            CASE 
                WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL 
                    THEN program_job_assigned_customer_property.reason 
                ELSE '' 
            END as asap_reason 
            ";


        }

        $this->db->select($select, FALSE);
        $this->db->from('jobs');

        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customers.customer_id = customer_property_assign.customer_id ','inner');

        // latest property job date
//        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");

        // latest property program job date
//        $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property_program, property_id, program_id FROM technician_job_assign GROUP BY property_id, program_id ) AS technician_job_assign_property_program", "property_program_assign.property_id = technician_job_assign_property_program.property_id AND programs.program_id = technician_job_assign_property_program.program_id", "left");


        // to filter out deleted & non-reschedule
        $this->db->join("technician_job_assign", "technician_job_assign.customer_id = customers.customer_id AND technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");
        $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
        $this->db->join('users technician', 'technician.user_id = technician_job_assign.technician_id', 'left');
        $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");
        $this->db->join('program_job_assigned_customer_property', 'jobs.job_id = program_job_assigned_customer_property.job_id AND customers.customer_id = program_job_assigned_customer_property.customer_id AND programs.program_id = program_job_assigned_customer_property.program_id AND property_tbl.property_id = program_job_assigned_customer_property.property_id', 'left');


        if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
            $this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        if (is_array($where_in) && array_key_exists( 'jobs.job_name', $where_in )) {
            $this->db->where_in( 'jobs.job_name', $where_in['jobs.job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }

        if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
            $program_services_search = $where_like['program_services'];
            unset($where_like['program_services']);
        }
        if (is_array($where_arr) && array_key_exists( 'program_services', $where_arr)) {
            $program_services_search = array($where_arr['program_services']);
            unset($where_arr['program_services']);
        }
       // die(print_r($program_services_search));
        if (is_array($where_in) && array_key_exists( 'program_services', $where_in )) {
            if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
                unset($where_in['program_services']);
            }
        }
        if (is_array($where_in) && array_key_exists( 'category_area_name', $where_in )) {
            $this->db->where_in('category_area_name',$where_in['category_area_name']);
        }
        if (is_array($where_arr) && array_key_exists( 'program_job_assigned_customer_property', $where_arr)) {
            $val = $where_arr['program_job_assigned_customer_property'];
            if ($val == 1)
                $this->db->where('program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL');
            else
                $this->db->where('program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NULL');
            unset($where_arr['program_job_assigned_customer_property']);
        }

        // Available days
        if (is_array($where_in) && array_key_exists( 'available_days', $where_in)) {
            foreach($where_in['available_days'] as $day)
            {
                $this->db->where('JSON_VALUE(`property_tbl`.`available_days`, "$.'.$day.'" RETURNING UNSIGNED) IS TRUE');
            }
        }


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (!empty($or_where)) {
            $this->db->group_start();
            $whereI = 0;
            foreach($or_where as $whereKey => $whereValue)
            {
                foreach($whereValue as $whereinsert)
                {
                    $whereI++;
                    if($whereI == 1)
                        $this->db->where($whereKey,$whereinsert);
                    else
                        $this->db->or_where($whereKey,$whereinsert);
                }
            }
            $this->db->group_end();
        }
        //die(print_r($where_in));
        if (is_array($where_like)) {
            // We need to catch the service overdue flag and add to query, and unset the where_like['service_due'] elem,
            //@TODO check if CI has a better way of handling
            if(!empty($where_like['service_due'])){
                $this->db->group_start();
                $servicesDue = explode(",", $where_like['service_due']);
                foreach($servicesDue as $due) {
                    switch ($due) {
                        case '2':
                            $this->db->or_where("datediff(now(),
                            (
	                            SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) >= (programs.program_schedule_window + 5)");
                            break;

                        case '3':
                            $this->db->or_where("datediff(now(),
                            (
                                SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) < (programs.program_schedule_window -5)");
                            break;

                        case '1':
                            $this->db->or_where("(datediff(now(),
                            (
                                SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign  where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id
                            )
                        ) IS NULL OR programs.program_schedule_window IS NULL)");
                            break;

                        default:
                            break;
                    }
                }
                $this->db->group_end();
                unset($where_like['service_due']);
            }
            if(!empty($where_like['pre_service_notification'])){
                switch($where_like['pre_service_notification']){
                    case '1':
                        $this->db->where("(customers.pre_service_notification LIKE '%1%')");
                        break;

                    case '2':
                        $this->db->where("(customers.pre_service_notification LIKE '%4%')");
                        break;

                    case '3':
                        $this->db->where("(customers.pre_service_notification LIKE '%2%' OR customers.pre_service_notification LIKE '%3%')");
                        break;

                    default:
                        break;

                }
                unset($where_like['pre_service_notification']);
            }
            $this->db->like($where_like);
        }
        if ($is_for_count){

            $this->db->group_by('');
        } else {
            $this->db->group_by('`customers`.`first_name`, `customers`.`last_name`, `billing_street`, `billing_street_2`, `jobs`.`job_id`, `jobs`.`job_name`, programs.`program_name`,
            service_due,
           `customers`.`customer_id`, `jobs`.`job_id`, `programs`.`program_id`, `property_tbl`.`property_id`, `property_tbl`.`property_notes`,
           `property_tbl`.`yard_square_feet`, `property_tbl`.`property_latitude`, `property_tbl`.`property_longitude`, `category_area_name`,
           `property_address`, program_job_assign.`priority`, `property_type`, `property_title`, `completed_date_property`, `completed_date_property_program`,
           `technician_job_assign`.`is_job_mode`, `unassigned_Job_delete`.`unassigned_Job_delete_id`, `property_tbl`.`property_state`,
           `property_tbl`.`property_city`, `property_tbl`.`property_zip`, `customers`.`pre_service_notification`, `property_tbl`.`tags`,
           `jobs`.`service_note`, `jobs`.`job_notes`, `customers`.`customer_status`, assign_reschedule_message, assign_table_data,  `technician_job_assign`.`technician_id`,`property_program_assign`.`property_program_date`,
           `technician`.`user_first_name`, `technician`.`user_last_name`, `completed_date_last_service_by_type`');
            $this->db->order_by($col,$dir);
        }


        if ($is_for_count == false) {
            $this->db->limit( $limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();
        //die(print_r($this->db->last_query()));
//        $this->benchmark->mark('code_inside_end');
//        echo $this->benchmark->elapsed_time('code_inside_start', 'code_inside_end');
//        echo '<br>';
        $final_data = array();
        $total = 0;
//        die(var_dump($time_elapsed_secs));
        // Check for programs service filter
        if (!empty($program_services_search) && !empty($property_outstanding_services)) {

            foreach ($data as $d) {
                $check_program_services = true;
                $property_outstanding_services_list = '';

                foreach ($property_outstanding_services as $pos) {
                    if (isset($d->property_id) && $pos->property_id == $d->property_id && $property_outstanding_services_list == '') {
                        $property_outstanding_services_list = $pos->outstanding;
                    }
                }
                foreach ($program_services_search as $pss) {

                    if (strpos($property_outstanding_services_list, $pss) !== false) {
                        //For some reason, for this specific condition it does not work with other condition, won't worrk with ===, == or !=.
                    } else {
                        $check_program_services = false;
                    }
                }
                if ($check_program_services) {
                    array_push($final_data, $d);
                }
            }
            //$total = count($final_data);
            if ($is_for_count == false) {
                return $final_data;
            } else {
                if (isset($data[0]) && isset($data[0]->count))
                    return $data[0]->count;
                return count($final_data);
            }
        }
            //$data = $final_data;

        //die('total '.$total);

//        $this->benchmark->mark('code_inside_end_end');
//        echo $this->benchmark->elapsed_time('code_inside_start', 'code_inside_end_end');
//        echo '<br>';
//        die(var_dump($data));
        if ($is_for_count == false) {
            return $data;
        } else {
            if (isset($data[0]) && isset($data[0]->count))
                return $data[0]->count;
            return count($data);
        }


    }

    public function getOutstandingServicesFromProperty_forTable($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '', $or_where = '') {
        // $file = fopen("test.txt","w");
        // echo fwrite($file,"We are inside getTableDataAjax function");
        // fclose($file);
        //print_r($where_in);
        //print_r($where_arr);
        $program_services_search = array();

//        $file = fopen("test.txt","w");
//        fwrite($file,"We are inside getTableDataAjax function WITH filters");
//        fclose($file);

        // Old/Original Not sure if incoming commit statement will work or be compatible with new statement so leaving this here for easy reference if need to revert
        // $this->db->select("customers.first_name,customers.last_name,billing_street,billing_street_2,jobs.job_id,job_name,program_name,customers.customer_id,jobs.job_id,programs.program_id,`property_tbl`.`property_id`,`property_tbl`.`yard_square_feet`,`property_tbl`.`property_latitude`,`property_tbl`.`property_longitude`,`category_area_name`,property_address,priority,property_type,property_title, completed_date_property, completed_date_property_program, technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id,`property_tbl`.`property_state`,`property_tbl`.`property_city`,`property_tbl`.`property_zip`");

        // New incoming commit statement
        $this->db->select("
            customers.customer_id,
            `property_tbl`.`property_id`,
            group_concat(DISTINCT job_name SEPARATOR ', ') as outstanding
         ");
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



        if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
            //$this->db->where_in( 'job_name', $where_in['job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        if (is_array($where_in) && array_key_exists( 'jobs.job_name', $where_in )) {
            //$this->db->where_in( 'jobs.job_name', $where_in['jobs.job_name'] );
            if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
                unset($where_like['job_name']);
            }
        }
        if (is_array($where_arr) && array_key_exists( 'jobs.job_name', $where_arr )) {
            unset($where_arr['jobs.job_name']);

        }



        if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
            $program_services_search = $where_like['program_services'];
            unset($where_like['program_services']);
        }
        if (is_array($where_arr) && array_key_exists( 'program_services', $where_arr)) {
            $program_services_search = array($where_arr['program_services']);
            unset($where_arr['program_services']);
        }

        if (is_array($where_in) && array_key_exists( 'program_services', $where_in )) {
            if (is_array($where_like) && array_key_exists( 'program_services', $where_like )) {
                unset($where_in['program_services']);
            }
        }

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (!empty($or_where)) {
            $this->db->group_start();
            $whereI = 0;
            foreach($or_where as $whereKey => $whereValue)
            {
                foreach($whereValue as $whereinsert)
                {
                    $whereI++;
                    if($whereI == 1)
                        $this->db->where($whereKey,$whereinsert);
                    else
                        $this->db->or_where($whereKey,$whereinsert);
                }
            }
            $this->db->group_end();
        }
        //die(print_r($where_in));
        if (is_array($where_like)) {
            // We need to catch the service overdue flag and add to query, and unset the where_like['service_due'] elem,
            //@TODO check if CI has a better way of handling
            if(!empty($where_like['service_due'])){
                switch ($where_like['service_due']) {
                    case '2':
                        $this->db->where("datediff(now(),completed_date_property_program) >= (programs.program_schedule_window + 5)");
                        break;

                    case '3':
                        $this->db->where("datediff(now(),completed_date_property_program) < (programs.program_schedule_window -5)");
                        break;

                    case '1':
                        $this->db->where("(datediff(now(),completed_date_property_program) IS NULL OR programs.program_schedule_window IS NULL)");
                        break;

                    default:
                        break;
                }
                unset($where_like['service_due']);
            }
            if(!empty($where_like['pre_service_notification'])){
                switch($where_like['pre_service_notification']){
                    case '1':
                        $this->db->where("(customers.pre_service_notification LIKE '%1%')");
                        break;

                    case '2':
                        $this->db->where("(customers.pre_service_notification LIKE '%4%')");
                        break;

                    case '3':
                        $this->db->where("(customers.pre_service_notification LIKE '%2%' OR customers.pre_service_notification LIKE '%3%')");
                        break;

                    default:
                        break;

                }
                unset($where_like['pre_service_notification']);
            }
            $this->db->like($where_like);
        }

        $this->db->group_by('property_tbl.property_id');
//        $this->db->order_by($col,$dir);
        $this->db->order_by('property_tbl.property_id',$dir);


        $result = $this->db->get();
//        die(print_r($this->db->last_query()));
        $data = $result->result();
        $final_data = array();
        // Check for programs service filter
//        if (!empty($program_services_search) ){
//            foreach ($data as $d){
//                $check_program_services = true;
//                foreach ($program_services_search as $pss){
//                    if (strpos($d->program_services, $pss) !== false ){
//                        //echo 'no coincide';
//                    } else {
//                        $check_program_services = false;
//                    }
//                }
//                if ($check_program_services){
//                    array_push($final_data,$d);
//                }
//            }
//            $data = $final_data;
//        }


        // die(print_r($this->db->last_query()));
        return $data;
    }





    public function getCustomerAllServicesForReport($where_arr = '', $String = "") {
        $this->db->select("
            jobs.job_id,
            programs.program_id,
            programs.program_name,
            is_job_mode
        ", FALSE);
        $this->db->from('jobs');
        $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
        $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
        $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');
        $this->db->join('customer_property_assign','customer_property_assign.property_id = property_program_assign.property_id ','inner');
        $this->db->join('customers','customer_property_assign.customer_id = customers.customer_id ','inner');
        $this->db->join('technician_job_assign', 'jobs.job_id = technician_job_assign.job_id AND customers.customer_id = technician_job_assign.customer_id AND programs.program_id = technician_job_assign.program_id AND property_tbl.property_id = technician_job_assign.property_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if($String != ""){
            $this->db->where($String);
        }

        $result = $this->db->get();
        $data = $result->result();
        //die(print_r($this->db->last_query()));
        return $data;
    }

}
