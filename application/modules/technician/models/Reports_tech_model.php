<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Reports_tech_model extends CI_Model{   

    const RPT="report";
    const VINSP = "vehicle_inspection_reports";



    public function createOneReport($post) {
     
        $query = $this->db->insert(self::RPT, $post);
        return $this->db->insert_id();
    }
 
    public function updateReport($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::RPT, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function getOneRepots($where_arr=''){
        $this->db->select('*');
        $this->db->from(self::RPT);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }          
       return  $this->db->get()->row();
 
    }
     
   /* Vehicle Inspection Reports */
   public function addInspectionReport($data)
   {
        $query = $this->db->insert(self::VINSP, $data);
        return $this->db->insert_id();
   }

   public function updateInspectionReport($where, $data)
   {
        $this->db->where($where);
        $this->db->update(self::VINSP,$data);

        return $this->db->affected_rows();
   }

   public function getOneInspectionReport($where)
   {
       $this->db->from(self::VINSP);
       $this->db->where($where);
       return $this->db->get()->result();
   }

   public function getAllInspectionReport($where)
   {
       $this->db->from(self::VINSP);
       $this->db->where($where);
       return $this->db->get()->result();
   }   

   public function getTechAssignedVehicle($tech_id)
   {
        $this->db->from('fleet_vehicles');
        $this->db->where('v_assigned_user', $tech_id);
        return $this->db->get()->row()->fleet_id ?? NULL;
   }

    public function getAllRepots($property_id){
        $this->db->select('*, report.report_id as thereportid,jobs.job_name');
        $this->db->from(self::RPT);
        $this->db->join("report_product","report_product.report_id = report.report_id","left");
        $this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id','inner');
        $this->db->join('jobs','technician_job_assign.job_id = jobs.job_id','left');
        //$this->db->join("property_program_job_invoice","property_program_job_invoice.report_id = report.report_id","left");
        //$this->db->join('invoice_tbl','technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','inner');
        //$this->db->join('invoice_tbl','invoice_tbl.job_id = technician_job_assign.job_id and technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','left');

        //$this->db->where('report.company_id',$this->session->userdata['company_id']);
        $this->db->where('technician_job_assign.company_id',$this->session->userdata['spraye_technician_login']->company_id);
        $this->db->where('technician_job_assign.property_id',$property_id);


        $this->db->group_by('report.report_id');
        $this->db->order_by('report.technician_job_assign_id','desc');


        //get records
        $query = $this->db->get();
        //die($this->db->last_query());
        //return fetched data
        return ($query->num_rows() > 0)?$query->result():FALSE;

    }
}
 
