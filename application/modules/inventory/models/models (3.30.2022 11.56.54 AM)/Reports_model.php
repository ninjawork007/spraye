<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Reports_model extends CI_Model{   

    const TJATBL="technician_job_assign";
    const RPT="report";



    public function getAllRepots($params = array()){
        $this->db->select('*, report.report_id as thereportid');
        $this->db->from(self::RPT);
        $this->db->join("report_product","report_product.report_id = report.report_id","left");
		$this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id','inner');
		//$this->db->join("property_program_job_invoice","property_program_job_invoice.report_id = report.report_id","left");
		//$this->db->join('invoice_tbl','technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','inner');
		//$this->db->join('invoice_tbl','invoice_tbl.job_id = technician_job_assign.job_id and technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','left');
	
     	//$this->db->where('report.company_id',$this->session->userdata['company_id']);
         $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);

         if (array_key_exists("where_condition",$params)) {

            $this->db->where($params['where_condition']);
             
         }

        if(!empty($params['search']['job_completed_date_to']) && empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
        }
        else if(empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
        }

        else if(!empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
           $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
           $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
        }



        if(!empty($params['search']['customer_name'])) {
   
            $this->db->where("(`first_name` LIKE '%".$params['search']['customer_name']."%' OR `last_name` LIKE '%".$params['search']['customer_name']."%')");
        } 
       
       
        if(!empty($params['search']['technician_name'])){            
          
           $this->db->where("(`user_first_name` LIKE '%".$params['search']['technician_name']."%' OR `user_last_name` LIKE '%".$params['search']['technician_name']."%')");
        }


        if(!empty($params['search']['product_name'])){            
          
           $this->db->where(" `product_name` LIKE '%".$params['search']['product_name']."%' ");
        }

        $this->db->group_by('report.report_id');
        $this->db->order_by('report.technician_job_assign_id','desc');
      
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
         //get records
           $query = $this->db->get();
		//die($this->db->last_query());
            //return fetched data
         return ($query->num_rows() > 0)?$query->result():FALSE;        
      
    }


    public function getOneRepots($where_arr=''){
        $this->db->select('*');
        $this->db->from(self::RPT);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }          
       return  $this->db->get()->row();
 
    }
     
 

}
 
