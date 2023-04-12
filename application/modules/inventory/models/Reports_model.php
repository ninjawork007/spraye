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
        $this->db->select('*, report.report_id as thereportid,jobs.job_name');
        $this->db->from(self::RPT);
        $this->db->join("report_product","report_product.report_id = report.report_id","left");
		$this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id','inner');
		$this->db->join('jobs','technician_job_assign.job_id = jobs.job_id','left');
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

    public function getTecnicianhDaysWorkedBetweenDates($where_arr)
    {
        $this->db->select('job_completed_date');
        $this->db->from(self::TJATBL);
        $this->db->where('technician_id',$where_arr['technician_id']);
        $this->db->where('is_complete', $where_arr['is_complete']);

         if ($where_arr['date_from'] != '') {

            $this->db->where('job_completed_date >=', $where_arr['date_from']);

         }
         if ($where_arr['date_to'] != '') {

            $this->db->where('job_completed_date <=', $where_arr['date_to']);

         }                 
      //   $this->db->where('job_completed_date <=', $where_arr['date_to']);
        $result = $this->db->get();
        $data = $result->result();
        $data = count(array_unique(array_column($data, 'job_completed_date')));
      //  die($this->db->last_query());
      //   return $this->db->last_query();
        return $data;
    }
     
    // public function getAllCommissionReports($params = array()){
    public function getAllCommissionReports($where_arr=''){
        $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_date,
        jobs.job_id,jobs.service_type_id,jobs.commission_type,jobs.bonus_type,users.user_first_name as sales_f_name, users.user_last_name as sales_l_name ');
        $this->db->from(self::RPT);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }  
		// $this->db->join("t_estimate","t_estimate.company_id = report.company_id","left");
		// $this->db->join('jobs','jobs.company_id = report.company_id ','left');
		$this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id ','inner');
      $this->db->join("t_estimate","t_estimate.program_id = technician_job_assign.program_id","left");
      // $this->db->join("technician_job_assign","technician_job_assign.program_id = t_estimate.program_id","inner");
      $this->db->join("jobs","jobs.job_id = technician_job_assign.job_id","left");
      $this->db->join("users","users.id = t_estimate.sales_rep","left");

        $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);

    //     if (array_key_exists("where_condition",$params)) {

    //         $this->db->where($params['where_condition']);
             
    //      }

    //     if(!empty($params['search']['job_completed_date_to']) && empty($params['search']['job_completed_date_from']) ){
    //        $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
    //     }
    //     else if(empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
    //        $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
    //     }

    //     else if(!empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
    //        $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
    //        $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
    //     }

    //     $this->db->where('t_estimate.company_id',$this->session->userdata['company_id']);

    //     if (array_key_exists("where_condition",$params)) {
    //        $this->db->where($params['where_condition']);
    //     }

    //    if(!empty($params['search']['estimate_created_date_to']) && empty($params['search']['estimate_created_date_from']) ){
    //       $this->db->where('t_estimate.estimate_created_date >=',$params['search']['estimate_created_date_to']);
    //    }
    //    else if(empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
    //       $this->db->where('t_estimate.estimate_created_date <=',$params['search']['estimate_created_date_from']);
    //    }

    //    else if(!empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
    //       $this->db->where('t_estimate.estimate_created_date >=',$params['search']['estimate_created_date_to']);
    //       $this->db->where('t_estimate.estimate_created_date <=',$params['search']['estimate_created_date_from']);
    //    }
		


        $this->db->group_by('report.report_id');
        $this->db->order_by('report.technician_job_assign_id','desc');
      
        
        $result = $this->db->get();

        $data = $result->result();
        
      //   die(print_r($this->db->last_query()));
        return $data;
    }
 
    public function getAllCommissionReportsSearch($params = array()){
      $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_date,
      jobs.job_id,jobs.service_type_id,jobs.commission_type,jobs.bonus_type,users.user_first_name as sales_f_name, users.user_last_name as sales_l_name ');
      $this->db->from(self::RPT);
      // if (is_array($where_arr)) {
      //     $this->db->where($where_arr);
      // }  

      $this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id ','inner');
      $this->db->join("t_estimate","t_estimate.program_id = technician_job_assign.program_id","left");
      // $this->db->join("technician_job_assign","technician_job_assign.program_id = t_estimate.program_id","inner");
      $this->db->join("jobs","jobs.job_id = technician_job_assign.job_id","left");
      $this->db->join("users","users.id = t_estimate.sales_rep","left");

      $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);
      // $this->db->where('t_estimate.status',2);

      if (array_key_exists("where_condition",$params)) {
         $this->db->where($params['where_condition']);
      }
      if(!empty($params['search']['sales_rep'])){    
         $this->db->where("(`user_first_name` LIKE '%".$params['search']['sales_rep']."%' OR `user_last_name` LIKE '%".$params['search']['sales_rep']."%')");
      }
      // if(!empty($params['search']['sales_rep_id'])){    
      //    $this->db->where("(`t_estimate.sales_rep` LIKE '%".$params['search']['sales_rep_id']."%' )");
      // }
      if(!empty($params['search']['sales_rep_id'])){    
         $this->db->where('report.sales_rep' ,$params['search']['sales_rep_id'] );
      }
      if(!empty($params['search']['estimate_status'])){    
         $this->db->where('estimate_status' ,$params['search']['estimate_status'] );
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

        $this->db->where('t_estimate.company_id',$this->session->userdata['company_id']);

        if (array_key_exists("where_condition",$params)) {
           $this->db->where($params['where_condition']);
        }

       if(!empty($params['search']['estimate_created_date_to']) && empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date >=',$params['search']['estimate_created_date_to']);
       }
       else if(empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date <=',$params['search']['estimate_created_date_from']);
       }

       else if(!empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date >=',$params['search']['estimate_created_date_to']);
          $this->db->where('t_estimate.estimate_date <=',$params['search']['estimate_created_date_from']);
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
   // die($this->db->last_query());
         //return fetched data
      return ($query->num_rows() > 0)?$query->result():FALSE; 
    }
 
    // public function getAllCommissionReports($params = array()){
      public function getAllBonusReports($where_arr=''){
         $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_created_date,
         jobs.job_id,jobs.service_type_id,jobs.commission_type,jobs.bonus_type,users.user_first_name as sales_f_name, users.user_last_name as sales_l_name ');
         $this->db->from(self::RPT);
         if (is_array($where_arr)) {
             $this->db->where($where_arr);
         }  
       // $this->db->join("t_estimate","t_estimate.company_id = report.company_id","left");
       // $this->db->join('jobs','jobs.company_id = report.company_id ','left');
       $this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id ','inner');
       $this->db->join("t_estimate","t_estimate.program_id = technician_job_assign.program_id","left");
       // $this->db->join("technician_job_assign","technician_job_assign.program_id = t_estimate.program_id","inner");
       $this->db->join("jobs","jobs.job_id = technician_job_assign.job_id","left");
       $this->db->join("users","users.id = t_estimate.sales_rep","left");
 
         $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);
 
     //     if (array_key_exists("where_condition",$params)) {
 
     //         $this->db->where($params['where_condition']);
              
     //      }
 
     //     if(!empty($params['search']['job_completed_date_to']) && empty($params['search']['job_completed_date_from']) ){
     //        $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
     //     }
     //     else if(empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
     //        $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
     //     }
 
     //     else if(!empty($params['search']['job_completed_date_to']) && !empty($params['search']['job_completed_date_from']) ){
     //        $this->db->where('technician_job_assign.job_completed_date >=',$params['search']['job_completed_date_to']);
     //        $this->db->where('technician_job_assign.job_completed_date <=',$params['search']['job_completed_date_from']);
     //     }
 
     //     $this->db->where('t_estimate.company_id',$this->session->userdata['company_id']);
 
     //     if (array_key_exists("where_condition",$params)) {
     //        $this->db->where($params['where_condition']);
     //     }
 
     //    if(!empty($params['search']['estimate_created_date_to']) && empty($params['search']['estimate_created_date_from']) ){
     //       $this->db->where('t_estimate.estimate_created_date >=',$params['search']['estimate_created_date_to']);
     //    }
     //    else if(empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
     //       $this->db->where('t_estimate.estimate_created_date <=',$params['search']['estimate_created_date_from']);
     //    }
 
     //    else if(!empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
     //       $this->db->where('t_estimate.estimate_created_date >=',$params['search']['estimate_created_date_to']);
     //       $this->db->where('t_estimate.estimate_created_date <=',$params['search']['estimate_created_date_from']);
     //    }
       
 
 
         $this->db->group_by('report.report_id');
         $this->db->order_by('report.technician_job_assign_id','desc');
       
         
         $result = $this->db->get();
 
         $data = $result->result();
         return $data;
     }

   public function getAllBonusReportsSearch($params = array()){
      $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_created_date,
      jobs.job_id,jobs.service_type_id,jobs.commission_type,jobs.bonus_type,users.user_first_name as sales_f_name, users.user_last_name as sales_l_name ');
      $this->db->from(self::RPT);
      // if (is_array($where_arr)) {
      //     $this->db->where($where_arr);
      // }  

      $this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id ','inner');
      $this->db->join("t_estimate","t_estimate.program_id = technician_job_assign.program_id","left");
      // $this->db->join("technician_job_assign","technician_job_assign.program_id = t_estimate.program_id","inner");
      $this->db->join("jobs","jobs.job_id = technician_job_assign.job_id","left");
      $this->db->join("users","users.id = t_estimate.sales_rep","left");

      $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);
      // $this->db->where('t_estimate.status',2);

      if (array_key_exists("where_condition",$params)) {
         $this->db->where($params['where_condition']);
      }
      if(!empty($params['search']['sales_rep'])){    
         $this->db->where("(`user_first_name` LIKE '%".$params['search']['sales_rep']."%' OR `user_last_name` LIKE '%".$params['search']['sales_rep']."%')");
      }
      // if(!empty($params['search']['technician_id'])){    
      //    $this->db->where("(`t_estimate.sales_rep` LIKE '%".$params['search']['technician_id']."%' )");
      // }
      if(!empty($params['search']['technician_id'])){    
         $this->db->where("(`report.sales_rep` LIKE '%".$params['search']['technician_id']."%' )");
      }
      if(!empty($params['search']['estimate_status'])){    
         $this->db->where('estimate_status' ,$params['search']['estimate_status'] );
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

        $this->db->where('t_estimate.company_id',$this->session->userdata['company_id']);

        if (array_key_exists("where_condition",$params)) {
           $this->db->where($params['where_condition']);
        }

       if(!empty($params['search']['estimate_created_date_to']) && empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date >=',$params['search']['estimate_created_date_to']);
       }
       else if(empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date <=',$params['search']['estimate_created_date_from']);
       }

       else if(!empty($params['search']['estimate_created_date_to']) && !empty($params['search']['estimate_created_date_from']) ){
          $this->db->where('t_estimate.estimate_date >=',$params['search']['estimate_created_date_to']);
          $this->db->where('t_estimate.estimate_date <=',$params['search']['estimate_created_date_from']);
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
         // die($this->db->last_query());
         //return fetched data
      return ($query->num_rows() > 0)?$query->result():FALSE; 
   }
 
    public function getTecnicianhJobsCompletedBetweenDates($where_arr)
    {
        $this->db->select('job_completed_date');
        $this->db->from(self::TJATBL);
        $this->db->where('technician_id',$where_arr['technician_id']);
        $this->db->where('is_complete', $where_arr['is_complete']);
         if ($where_arr['date_from'] != '') {

            $this->db->where('job_completed_date >=', $where_arr['date_from']);

         }     
         if ($where_arr['date_to'] != '') {

            $this->db->where('job_completed_date <=', $where_arr['date_to']);

         }         
        $result = $this->db->get();
        $data = $result->num_rows();
        // return $this->db->last_query();
        return $data;
    }

    public function getTecnicianJobAssignIdsBetweenDates($where_arr)
    {
        // $this->db->select('technician_job_assign_id');
        $this->db->from(self::TJATBL);
        $this->db->where('technician_id',$where_arr['technician_id']);
        $this->db->where('is_complete', $where_arr['is_complete']);
         if ($where_arr['date_from'] != '') {

            $this->db->where('job_completed_date >=', $where_arr['date_from']);

         }     
         if ($where_arr['date_to'] != '') {

            $this->db->where('job_completed_date <=', $where_arr['date_to']);

         }         
        $result = $this->db->get();
        $data = array_column($result->result(), 'technician_job_assign_id');
        // $data = $result->num_rows();
        // return $this->db->last_query();
        return $data;
    }

    public function getTechInvoiceIdsBetweenDates($where_arr)
    {
        // $this->db->select('technician_job_assign_id');
        $this->db->from(self::TJATBL);
        $this->db->where('technician_id',$where_arr['technician_id']);
        $this->db->where('is_complete', $where_arr['is_complete']);
         if ($where_arr['date_from'] != '') {

            $this->db->where('job_completed_date >=', $where_arr['date_from']);

         }     
         if ($where_arr['date_to'] != '') {

            $this->db->where('job_completed_date <=', $where_arr['date_to']);

         }         
        $result = $this->db->get();
        $data = array_column($result->result(), 'invoice_id');
        // $data = $result->num_rows();
        // return $this->db->last_query();
        return $data;
    }

    public function getTecnicianhJobTimesBetweenDates($where_arr)
    {
        $this->db->select('job_start_time,job_completed_time');
        $this->db->from(self::TJATBL);
        $this->db->where('technician_id',$where_arr['technician_id']);
        $this->db->where('is_complete', $where_arr['is_complete']);
         if ($where_arr['date_from'] != '') {

            $this->db->where('job_completed_date >=', $where_arr['date_from']);

         }     
         if ($where_arr['date_to'] != '') {

            $this->db->where('job_completed_date <=', $where_arr['date_to']);

         }         
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }    

    public function getTechJobReports($ids_array)
    {
        $this->db->from(self::RPT);
        $this->db->where_in('technician_job_assign_id',$ids_array);
		// $this->db->join('invoice_tbl','invoice_tbl.report_id = report.report_id','left');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }    

    public function getTechInvoices($ids_array)
    {
        $this->db->from('invoice_tbl');
        $this->db->where_in('invoice_id', $ids_array);
        $result = $this->db->get();
        $data = $result->result();
        return $data;        
    }

    public function getJobCompletedCost($where_arr)
    {
        $this->db->from(self::RPT);
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function GetAllRow($where_arr) {
        $this->db->select('*');
        $this->db->from(self::TJATBL);
        $this->db->where($where_arr);
        $this->db->join('property_tbl', 'technician_job_assign.property_id=property_tbl.property_id');
        $this->db->join('category_property_area', 'property_tbl.property_area=category_property_area.property_area_cat_id');
        // $this->db->join('invoice_tbl', 'technician_job_assign.invoice_id=invoice_tbl.invoice_id');
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
    }

    public function getJobInvoiceCost($where_arr)
    {
        $this->db->from('property_program_job_invoice');
        if(is_array($where_arr))
        {
            $this->db->where($where_arr);
        }
        $result = $this->db->get()->row();
        return $result->job_cost ?? 0;
    }

    public function GetAllServices($where_arr, $where_in = '') {
      $this->db->select('*');
      $this->db->from('jobs');
      $this->db->join('job_product_assign','job_product_assign.job_id =jobs.job_id','inner');
      $this->db->join('products','products.product_id =job_product_assign.product_id','inner');
      $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
      $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
      $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
      $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');

      $this->db->join("technician_job_assign", "technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");

      $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");

      $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL"); 

      if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

      if (is_array($where_in) && array_key_exists( 'job_name', $where_in )) {
         $this->db->where_in( 'job_name', $where_in['job_name'] );
         if (is_array($where_like) && array_key_exists( 'job_name', $where_like )) {
               unset($where_like['job_name']);
         }
      }
      $result = $this->db->get();
      $data = $result->result();
      // die( print_r($this->db->last_query()));  
      // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
      // $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
      // die(print_r($this->db->last_query()));
      return $data;
    }

   public function GetAllServicesSearch($params = array()){
      $this->db->select('*');
      $this->db->from('jobs');
      $this->db->join('job_product_assign','job_product_assign.job_id =jobs.job_id','inner');
      $this->db->join('products','products.product_id =job_product_assign.product_id','inner');
      $this->db->join('program_job_assign','program_job_assign.job_id =jobs.job_id','inner');
      $this->db->join('property_program_assign','property_program_assign.program_id = program_job_assign.program_id','inner');
      $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');
      $this->db->join('programs','programs.program_id = property_program_assign.program_id','inner');

      $this->db->join("technician_job_assign", "technician_job_assign.job_id = jobs.job_id AND technician_job_assign.program_id = programs.program_id AND technician_job_assign.property_id = property_tbl.property_id", "left");

      $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.program_id = programs.program_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");

      $this->db->where("(is_job_mode = 2 OR is_job_mode IS NULL) AND unassigned_Job_delete_id IS NULL");

      if (array_key_exists("where_condition",$params)) {
         $this->db->where($params['where_condition']);
      }
      if(!empty($params['search']['job_id'])){ 
         $this->db->where_in('job_id', $params['search']['job_id']);
      }

      $this->db->where('jobs.company_id',$this->session->userdata['company_id']);

      if (array_key_exists("where_condition",$params)) {
         $this->db->where($params['where_condition']);
      }
		
      $this->db->group_by('job.job_id');
      $this->db->order_by('job.job_id','desc');
      
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
         $this->db->limit($params['limit'],$params['start']);
      }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
      }
      //get records
      $query = $this->db->get();
      // die($this->db->last_query());
      //return fetched data
      return ($query->num_rows() > 0)?$query->result():FALSE; 
   }

   public function getAmountOfProductQty($product_id){
      $this->db->select('*');
      $this->db->from('item_product_tbl');
      $this->db->join('quantities','quantities.quantity_item_id =item_product_tbl.item_id','inner');
      $this->db->where('product_id', $product_id);
      $result = $this->db->get();
      $data = $result->result();
      // die( print_r($this->db->last_query()));  
      $qty_ct = 0;

      foreach($data as $qty){
         $qty_ct += $qty->quantity;
      }
     
      return $qty_ct;
   }

   public function getItemUnitType($product_id){
      $this->db->select('*');
      $this->db->from('item_product_tbl');
      $this->db->join('items_tbl','items_tbl.item_id =item_product_tbl.item_id','inner');
      $this->db->where('product_id', $product_id);
      $result = $this->db->get();
      $data = $result->row();
      // die( print_r($this->db->last_query()));  
     if(!empty($data)){

        return $data->unit_type;
     }


   }

   public function getItemUnitAmount($product_id){
    $this->db->select('*');
    $this->db->from('item_product_tbl');
    $this->db->join('items_tbl','items_tbl.item_id =item_product_tbl.item_id','inner');
    $this->db->where('product_id', $product_id);
    $result = $this->db->get();
    $data = $result->row();
    // die( print_r($this->db->last_query()));  
   if(!empty($data)){

      return $data->unit_amount;
   }

   
 }

   public function getItemIdByProductId($product_id){
    $this->db->select('*');
    $this->db->from('item_product_tbl');
    $this->db->where('product_id', $product_id);
    $result = $this->db->get();
    $data = $result->row();
    if(!empty($data)){

       return $data->item_id;
    }
 }

 public function getReceivingQtyByItemId($item_id, $company_id){
    $this->db->select('*');
    $this->db->from('purchase_receiving_tbl');
    $this->db->where(array('company_id' => $company_id, 'is_draft' => 0));
    $ordered_items = 0;
    $result = $this->db->get();

    $data = $result->result();
    if(!empty($data)){
      //  die(print_r($item_id));
      //  die(print_r($data));
       foreach($data as $d){
          foreach(json_decode($d->items) as $i){
             // print('item_id = '.$item_id. ' and data item_id = '.$i->item_id.'. <br/>' );
             if($i->item_id == $item_id){
                // die(print_r($item_id));
                $ordered_items += (number_format($i->quantity) - number_format($i->received_qty));
             }
          }
       }
    }
    return $ordered_items;
 }

    public function getUnitConversionInfoByItemId($item_id)
    {
        $this->db->select('*');
        $this->db->from('items_tbl');
        $this->db->where('item_id', $item_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getUnitConversionInfoByProductId($product_id)
    {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('product_id', $product_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

}
 
