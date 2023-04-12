<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Assign_job extends CI_Model{   
    const TJATBL="technician_job_assign";
	
	public function getAllJobAssignByInvoice($where_arr = '') {
		$this->db->select('*');
        $this->db->from(self::TJATBL);
		if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$result = $this->db->get();
        $data = $result->result_array();
		
		return $data;
	}

    public function getAllJobAssignByGroup($where_arr = '',$groupby='') {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
 
        $this->db->join('t_company',"t_company.company_id=technician_job_assign.company_id","inner");
        $this->db->join('t_company_email_setting',"t_company_email_setting.company_id=technician_job_assign.company_id","inner");
  
        $this->db->join('users',"users.user_id=technician_job_assign.technician_id","inner");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }



     public function getAllJobAssign($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);

        $this->db->join('t_company',"t_company.company_id=technician_job_assign.company_id","inner");
        $this->db->join('t_company_email_setting',"t_company_email_setting.company_id=technician_job_assign.company_id","inner");
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
	
	
	public function getAllJobAssignGroup($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);

        $this->db->join('t_company',"t_company.company_id=technician_job_assign.company_id","inner");
        $this->db->join('t_company_email_setting',"t_company_email_setting.company_id=technician_job_assign.company_id","inner");
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$this->db->group_by('email'); 
        
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    



}
 