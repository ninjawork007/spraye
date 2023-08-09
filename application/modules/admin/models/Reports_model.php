<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Reports_model extends CI_Model{   

    const TJATBL="technician_job_assign";
    const RPT="report";



    public function getCompletedServices($params = array()){
        $this->db->select('*, report.report_id as thereportid,jobs.job_name,category_area_name');
        $this->db->from('technician_job_assign');
        $this->db->join("report","technician_job_assign.technician_job_assign_id = report.technician_job_assign_id","left");
        $this->db->join("report_product","report_product.report_id = report.report_id","left");
		$this->db->join('jobs','technician_job_assign.job_id = jobs.job_id','left');
		$this->db->join('property_tbl','`technician_job_assign`.`property_id` = `property_tbl`.`property_id`','left');
		$this->db->join('category_property_area','`category_property_area`.`property_area_cat_id` = `property_tbl`.`property_area`','left');


        //$this->db->join("property_program_job_invoice","property_program_job_invoice.report_id = report.report_id","left");
		//$this->db->join('invoice_tbl','technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','inner');
		//$this->db->join('invoice_tbl','invoice_tbl.job_id = technician_job_assign.job_id and technician_job_assign.property_id = invoice_tbl.property_id and technician_job_assign.program_id = invoice_tbl.program_id','left');
	
     	//$this->db->where('report.company_id',$this->session->userdata['company_id']);
         $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);
         $this->db->where('is_complete',1);

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
        // For available workreport
        if(!empty($params['jobs.job_id'])){

            $this->db->where_in('jobs.job_id', $params['jobs.job_id']);
        }
        //die(print_r($params['programs.program_id']));
        if(isset($params['programs.program_id']) && (!empty($params['programs.program_id'] || !is_null($params['programs.program_id'][0] )))){
            $this->db->where_in('technician_job_assign.program_id', $params['programs.program_id']);
        } // end -- For available workreport





        $this->db->group_by('technician_job_assign.technician_job_assign_id');
        $this->db->order_by('report.technician_job_assign_id','desc');
      
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
         //get records
           $query = $this->db->get();
            //return fetched data
         return ($query->num_rows() > 0)?$query->result():FALSE;        
      
    }

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


//        if(!empty($params['search']['technician_name'])){
//
//            $this->db->where("(`user_first_name` LIKE '%".$params['search']['technician_name']."%' OR `user_last_name` LIKE '%".$params['search']['technician_name']."%')");
//        }
        if(!empty($params['search']['technician_name'])){
            $this->db->where_in('technician_job_assign.user_id', $params['search']['technician_name']);
        }


        if(!empty($params['search']['product_name'])){

            $this->db->where(" `product_name` LIKE '%".$params['search']['product_name']."%' ");
        }
        if(!empty($params['jobs.job_id'])){

            $this->db->where_in('jobs.job_id', $params['jobs.job_id']);
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
        $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_date, t_estimate.source,
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
        $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_date, t_estimate.source,
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
        $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_created_date, t_estimate.source,
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
        $this->db->select('report.*,t_estimate.estimate_id,t_estimate.property_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.property_status,t_estimate.sales_rep,t_estimate.estimate_created_date, t_estimate.source,
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
        $this->db->select('technician_job_assign_id, cost, yard_square_feet');
        $this->db->from(self::RPT);
        $this->db->group_start();
        $ids_chunk = array_chunk($ids_array,25);
        foreach($ids_chunk as $ids)
        {
            $this->db->or_where_in('technician_job_assign_id', $ids);
        }
        $this->db->group_end();
        //$this->db->where_in('technician_job_assign_id',$ids_array);
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
        $this->db->join('invoice_tbl', 'technician_job_assign.invoice_id=invoice_tbl.invoice_id');
        $this->db->join('coupon_invoice', 'coupon_invoice.invoice_id=invoice_tbl.invoice_id');
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
        $data = $result->result_array();
        //die($this->db->last_query());
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
    public function getJobInvoiceCostwithCoupon($where_arr)
    {
        /* $where_arr example
         * array(
                'job_id' => $job_id,
                'invoice_id' => $invoice_id
        */
        // Get number of services from invoice
        $job_cost = 0;
        $total_amount_of_services_per_invoice = 0;
        $this->db->select('*');
        $this->db->from('property_program_job_invoice');
        if(is_array($where_arr))
        {
            $this->db->where(array('invoice_id' => $where_arr['invoice_id']));
        }
        $result = $this->db->get()->result();
        foreach ($result as $job){
            if ($job->job_id ==$where_arr['job_id'] ){
                $job_cost = $job->job_cost;
            }
        }
        $total_amount_of_services_per_invoice = count($result);

        $coupon_array = $this->RP->getJobInvoiceCoupons($where_arr['invoice_id'], $job_cost ?? 0);
        // Calculate $ discounts before % discounts
        if ($total_amount_of_services_per_invoice >0) {
            foreach ($coupon_array as $coupon) {
                if ($coupon['coupon_amount_calculation'] == 0) {
                    $job_cost = $job_cost - $coupon['coupon_amount'] / $total_amount_of_services_per_invoice;
                }
            }
        }
        // now check for % discounts
        foreach ($coupon_array as $coupon){
            if ($coupon['coupon_amount_calculation'] == 1){
                $job_cost = $job_cost - $job_cost*$coupon['coupon_amount']/100;
            }
        }
        return $job_cost;

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
    $this->db->where('company_id', $company_id);
    $received_items = 0;
    $result = $this->db->get();

    $data = $result->result();
    if(!empty($data)){
       // die(print_r($data));
       foreach($data as $d){
          foreach(json_decode($d->items) as $i){
             // print('item_id = '.$item_id. ' and data item_id = '.$i->item_id.'. <br/>' );
             if($i->item_id == $item_id){
                // die(print_r($item_id));
                $received_items += (number_format($i->quantity) - number_format($i->received_qty));
             }
          }
       }
    }
    return $received_items;
 }

    public function getReportTech($tech_id){
        $this->db->select('technician_job_assign_id, customer_id, invoice_id, job_id');
        $this->db->from('technician_job_assign');
        $this->db->where('technician_job_assign_id', $tech_id);

        $result = $this->db->get();
        $data = $result->row();

        // print_r($data);

        return $data;
    }

    public function getJobDiscounts($job_id, $customer_id){
        $this->db->select('coupon.amount, coupon.amount_calculation, coupon.type, coupon.expiration_date');
        $this->db->from('coupon_job');
        $this->db->join('coupon', 'coupon.coupon_id = coupon_job.coupon_id');
        $this->db->where('job_id', $job_id);
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();

        // die(print_r($data));
        return $data;
    }

    public function getInvoiceDiscounts($invoice_id){
        $this->db->select('coupon.amount, coupon.amount_calculation, coupon.type, coupon.expiration_date');
        $this->db->from('coupon_invoice');
        $this->db->join('coupon', 'coupon.coupon_id = coupon_invoice.coupon_id');
        $this->db->where('invoice_id', $invoice_id);
        $result = $this->db->get();
        $data = $result->result();

        // die(print_r($data));
        return $data;
    }

    public function getProgramPriceById($program_id){
        $this->db->select('*');
        $this->db->from('programs');
        $this->db->where('program_id', $program_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getNameFromSourceNumber($source_number) {
        $this->db->select('*');
        $this->db->from('source_tbl');
        $this->db->where('source_id', $source_number);
        $result = $this->db->get();
        $data = $result->row();
        if(!empty($data)) {
            return $data->source_name;
        } else {
            return "";
        }
    }

    public function getUserNameFromSourceNumber($source_number) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $source_number);
        $result = $this->db->get();
        $data = $result->row();
        if(!empty($data)) {
            return $data->user_first_name." ".$data->user_last_name;
        }
    }
    public function find_property_from_filter($filters_array){
        if(!empty($filters_array)){
            // die(print_r($filters_array));
            $this->db->select('property_tbl.property_id, property_tbl.yard_square_feet');
            $this->db->select('GROUP_CONCAT(property_program_assign.program_id) as program_ids');
            
            $this->db->from('property_tbl');
            $this->db->join('customer_property_assign', 'customer_property_assign.property_id = property_tbl.property_id');
            $this->db->join('customers', 'customers.customer_id = customer_property_assign.customer_id');
            $this->db->join('property_program_assign','property_program_assign.property_id = property_tbl.property_id','left');

            $this->db->where('property_tbl.property_id', $filters_array['prop_id']);
            if($filters_array['filters']['sources'][0] != 'null' && $filters_array['filters']['sources'][0] != NULL){
                $this->db->where_in('property_tbl.source', $filters_array['filters']['sources']);
            }
            if($filters_array['filters']["all_programs"] == "true" || $filters_array['filters']["all_programs"] == "on") {
                if($filters_array['filters']['programs_multi'][0] != 'null' && $filters_array['filters']['programs_multi'][0] != NULL) {
                    // this means we need all of the programs to be AND together
                    $this->db->where_in('property_program_assign.program_id', $filters_array['filters']['programs_multi']);
                    $this->db->having('COUNT(DISTINCT property_program_assign.program_id) = '.count($filters_array['filters']['programs_multi']));
                }
            } else {
                if($filters_array['filters']['programs_multi'][0] != 'null' && $filters_array['filters']['programs_multi'][0] != NULL) {
                    $this->db->where_in('property_program_assign.program_id', $filters_array['filters']['programs_multi']);
                }
            }
            if($filters_array['filters']["all_tags"] == "true" || $filters_array['filters']["all_tags"] == "on") {
                if($filters_array['filters']['tags_multi'][0] != 'null' && $filters_array['filters']['tags_multi'][0] != NULL) {
                    // this means we need all of the tags to be AND together
                    $like_stmt = "(";
                    foreach($filters_array['filters']['tags_multi'] as $tm) {
                        $like_stmt .= "FIND_IN_SET('".$tm."', property_tbl.tags) AND ";
                    }
                    $like_stmt = substr($like_stmt, 0, -4);
                    $this->db->where($like_stmt.")");
                }
            } else {
                if($filters_array['filters']['tags_multi'][0] != 'null' && $filters_array['filters']['tags_multi'][0] != NULL) {
                    $like_stmt = "(";
                    foreach($filters_array['filters']['tags_multi'] as $tm) {
                        $like_stmt .= "FIND_IN_SET('".$tm."', property_tbl.tags) OR ";
                    }
                    $like_stmt = substr($like_stmt, 0, -3);
                    $this->db->where($like_stmt.")");
                }
            }
            if($filters_array['filters']["all_pre_service"] == "true" || $filters_array['filters']["all_pre_service"] == "on") {
                if($filters_array['filters']['preservice_notifications_multi'][0] != 'null' && $filters_array['filters']['preservice_notifications_multi'][0] != NULL) {
                    // this means we need all of the tags to be AND together
                    $like_stmt = "(";
                    foreach($filters_array['filters']['preservice_notifications_multi'] as $tm) {
                        $like_stmt .= "INSTR(customers.pre_service_notification, '\"".$tm."\"') > 0 AND ";
                    }
                    $like_stmt = substr($like_stmt, 0, -4);
                    $this->db->where($like_stmt.")");
                }
            } else {
                if($filters_array['filters']['preservice_notifications_multi'][0] != 'null' && $filters_array['filters']['preservice_notifications_multi'][0] != NULL) {
                    // so this is stored in the customer table as a json array - so we need to create a like stmt for each one they picked, then add that onto the where
                    $like_stmt = "(";
                    foreach($filters_array['filters']['preservice_notifications_multi'] as $tm) {
                        if($tm == "2") {
                            $like_stmt .= " customers.is_email = 1 OR ";
                        }
                        if($tm == "3") {
                            $like_stmt .= " customers.is_mobile_text = 1 OR ";
                        }
                        $like_stmt .= "INSTR(customers.pre_service_notification, '".$tm."') > 0 OR ";
                    }
                    $like_stmt = substr($like_stmt, 0, -4);
                    $this->db->where($like_stmt.")");

                }
            }
            if($filters_array['filters']['lead_start_date_start'] != "") {
                // this means they set a start date, check for an end date and if not found default to today
                if($filters_array['filters']['lead_start_date_end'] != "") {
                    $this->db->where("customers.created_at BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['lead_start_date_start']))."' and '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['lead_start_date_end']))."'");
                } else {
                    // they did not give us an end date - so we can assume they mean today
                    $this->db->where("customers.created_at BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['lead_start_date_start']))."' and '".date('Y-m-d h:i:s',strtotime('today'))."'");
                }
            }
            if($filters_array['filters']['estimate_accpeted'] != "") {
                $this->db->join('t_estimate', 't_estimate.property_id = property_tbl.property_id');
                $this->db->where_in('t_estimate.status', $filters_array['filters']['estimate_accpeted']);
            }
            if($filters_array['filters']['service_areas_multi'][0] != 'null' && $filters_array['filters']['service_areas_multi'][0] != NULL) {
                $this->db->where_in('property_tbl.property_area', $filters_array['filters']['service_areas_multi']);
            }
            if($filters_array['filters']['zip_codes_multi'][0] != 'null' && $filters_array['filters']['zip_codes_multi'][0] != NULL) {
                $this->db->where_in('property_tbl.property_zip', $filters_array['filters']['zip_codes_multi']);
            }
            if($filters_array['filters']['res_or_com'] != '') {
                $this->db->where_in('property_tbl.property_type', $filters_array['filters']['res_or_com']);
            }
            if($filters_array['filters']['cancel_reasons_multi'][0] != 'null' && $filters_array['filters']['cancel_reasons_multi'][0] != NULL) {
                $this->db->join('cancelled_services_tbl','cancelled_services_tbl.property_id = property_tbl.property_id','inner');
                $this->db->where_in('cancelled_services_tbl.cancel_reason', $filters_array['filters']['cancel_reasons_multi']);
            }
            if($filters_array['filters']['last_date_program_start'] != "") {
                // this means they set a start date, check for an end date and if not found default to today
                if($filters_array['filters']['last_date_program_end'] != "") {
                    $this->db->where("property_program_assign.property_program_date BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['last_date_program_start']))."' and '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['last_date_program_end']))."'");
                } else {
                    // they did not give us an end date - so we can assume they mean today
                    $this->db->where("property_program_assign.property_program_date BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['last_date_program_start']))."' and '".date('Y-m-d h:i:s',strtotime('today'))."'");
                }
            }
            if($filters_array['filters']['cancelation_date_start'] != "") {
                // this means they set a start date, check for an end date and if not found default to today
                $this->db->join('cancelled_services_tbl','cancelled_services_tbl.property_id = property_tbl.property_id','inner');
                if($filters_array['filters']['cancelation_date_end'] != "") {
                    $this->db->where("cancelled_services_tbl.created_at BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['cancelation_date_start']))."' and '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['cancelation_date_end']))."'");
                } else {
                    // they did not give us an end date - so we can assume they mean today
                    $this->db->where("cancelled_services_tbl.created_at BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['cancelation_date_start']))."' and '".date('Y-m-d h:i:s',strtotime('today'))."'");
                }
            }

            if($filters_array['filters']['sale_start_date_start'] != "") {
                // this means they set a start date, check for an end date and if not found default to today
                if($filters_array['filters']['sale_start_date_end'] != "") {
                    $this->db->having("MIN(property_program_assign.property_program_date) BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['sale_start_date_start']))."' and '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['sale_start_date_end']))."'");
                } else {
                    // they did not give us an end date - so we can assume they mean today
                    $this->db->having("MIN(property_program_assign.property_program_date) BETWEEN '".date('Y-m-d h:i:s', strtotime($filters_array['filters']['sale_start_date_start']))."' and '".date('Y-m-d h:i:s',strtotime('today'))."'");
                }
            }
            /* not sure if I will need this again or something so save it
            if($filters_array['filters']['outstanding_services_multi'][0] != 'null') {
                $this->db->join('program_job_assign','program_job_assign.program_id = property_program_assign.program_id','left');
                $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','left');
                $this->db->join('technician_job_assign', 'technician_job_assign.job_id = jobs.job_id', 'left');
                $this->db->join('programs','programs.program_id = property_program_assign.program_id','left');
                $this->db->join("unassigned_Job_delete", "unassigned_Job_delete.customer_id = customers.customer_id AND unassigned_Job_delete.job_id = jobs.job_id AND unassigned_Job_delete.property_id = property_tbl.property_id", "left");
                // latest property job date
                $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property, property_id FROM technician_job_assign GROUP BY property_id) AS technician_job_assign_property", "property_program_assign.property_id = technician_job_assign_property.property_id", "left");
                // latest property program job date
                $this->db->join("(SELECT MAX(job_completed_date) AS completed_date_property_program, property_id, program_id FROM technician_job_assign GROUP BY property_id, program_id ) AS technician_job_assign_property_program", "property_program_assign.property_id = technician_job_assign_property_program.property_id AND programs.program_id = technician_job_assign_property_program.program_id", "left");
                $this->db->where("is_complete = 0 AND (is_job_mode = 2 OR is_job_mode = 0) AND unassigned_Job_delete_id IS NULL");
                $this->db->where_in('program_job_assign.job_id', $filters_array['filters']['outstanding_services_multi']);
            }
            */
            if($filters_array['filters']['customer_status'] != '') {
                $this->db->where_in('customers.customer_status', $filters_array['filters']['customer_status']);
            }

            $this->db->group_by('property_tbl.property_id');
            $result = $this->db->get();
            $data = $result->result();
            //var_dump($this->db->last_query());
            //var_dump("<br /><br />");
            return $data;
        } 
        
    }

    public function get_company_created_at_date($company_id) {
        $this->db->select('created_at');
        $this->db->from('t_company');
        $this->db->where('company_id', $company_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function get_job_ids_from_property($program_id) {
        $this->db->select('job_id');
        $this->db->from('program_job_assign');
        $this->db->where('program_id', $program_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function get_invoice_number_from_programs($program_id, $property_id) {
        $this->db->select('GROUP_CONCAT(invoice_id) as invoice_ids');
        $this->db->from('property_program_job_invoice');
        $this->db->where('program_id', $program_id);
        $this->db->where('property_id', $property_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function get_all_invoice_info($invoice_id, $customer_id) {
        $this->db->select('invoice_id, partial_payment, refund_amount_total, invoice_date');
        $this->db->from('invoice_tbl');
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function get_projected_annual($customer_id) {
        $today = date('Y-m-d', strtotime('today'));
        $today_last_year = date('Y-m-d', strtotime('today last year'));
        $this->db->select('partial_payment, refund_amount_total');
        $this->db->from('invoice_tbl');
        $this->db->where("invoice_tbl.invoice_date BETWEEN '".date('Y-m-d h:i:s', strtotime($today_last_year))."' and '".date('Y-m-d h:i:s', strtotime($today))."'");
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function get_outstanding_service_properties($filters_array) {
        $this->db->select('property_id');
        $this->db->from('property_program_job_invoice');
        if($filters_array['all_outstanding'] == "true" || $filters_array["all_outstanding"] == "on") {
            $this->db->where_in('job_id', $filters_array['outstanding_services_multi']);
            $this->db->group_by('property_id');
            $this->db->having('COUNT(DISTINCT job_id) = '.count($filters_array['outstanding_services_multi']));
        } else {
            $this->db->where_in('job_id', $filters_array['outstanding_services_multi']);
        }
        $result = $this->db->get();
        $data = $result->result();
        //var_dump($this->db->last_query());
        //var_dump("<br /><br />");
        return $data;
    }
// Added by Alvaro M

    public function getJobInvoiceCoupons($invoice_id, $job_cost)
    {
        $where_arr = array( 'invoice_id' => $invoice_id);
        $this->db->select('*');
        $this->db->from('coupon_invoice');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
    }

    public function calculateJobCost(  $customer_id, $property_id, $program_id,$invoice_id = 0,$job_id, $yard_square_feet )
    {
        $job_cost = 0;
        // Meant to work in all cases. for pricing types.
        $where_arr = array(
            'customer_id' => $customer_id,
            'property_id' => $property_id,
            'program_id' => $program_id,
            'job_id' => $job_id
        );
        // check estimate price
        $estimate_price_override = GetOneEstimateJobPriceOverride( $where_arr );
        if( $estimate_price_override && !empty( $estimate_price_override->is_price_override_set ))
        {
            $job_cost = $estimate_price_override->price_override;
        } else if ( $invoice_id != 0) {
            $invoice_cost = $this->getJobInvoiceCostwithCoupon(array(
                'job_id' => $job_id,
                'invoice_id' => $invoice_id
            ));
            //die($invoice_cost);
            // include coupons
            $job_cost = $invoice_cost;
        } else {

            $where_arr = array(
                'property_id' => $property_id,
                'program_id' => $program_id
            );
            // Get program price override
            $priceOverrideData = $this->Tech->getOnePriceOverride( $where_arr );

            if( isset($priceOverrideData->is_price_override_set) && $priceOverrideData->is_price_override_set == 1 )
            {
                $job_cost = $priceOverrideData->price_override;
            } else {
                //else no price overrides, then calculate job cost
                $job = $this->JobModel->getOneJob(array( 'job_id' => $job_id ));
                $property = $this->PropertyModel->getOneProperty( array( 'property_id' => $property_id ));
                $lawn_sqf = $yard_square_feet;
                $job_price = $job->job_price;

                //get property difficulty level
                $setting_details = $this->CompanyModel->getOneCompany( array( 'company_id' => $this->session->userdata['company_id'] ));

                if( isset( $property->difficulty_level ) && $property->difficulty_level == 2 )
                {
                    $difficulty_multiplier = $setting_details->dlmult_2;
                } elseif( isset( $property->difficulty_level ) && $property->difficulty_level == 3 )
                {
                    $difficulty_multiplier = $setting_details->dlmult_3;
                } else {
                    $difficulty_multiplier = $setting_details->dlmult_1;
                }

                //get base fee
                if( isset( $job->base_fee_override ))
                {
                    $base_fee = $job->base_fee_override;
                } else
                {
                    $base_fee = $setting_details->base_service_fee;
                }

                $cost_per_sqf = $base_fee + ( $job_price * $lawn_sqf * $difficulty_multiplier ) / 1000;

                //get min. service fee
                if( isset( $job->min_fee_override ))
                {
                    $min_fee = $job->min_fee_override;
                } else {
                    $min_fee = $setting_details->minimum_service_fee;
                }

                // Compare cost per sf with min service fee
                if ($cost_per_sqf > $min_fee) {
                    $job_cost = $cost_per_sqf;
                } else {
                    $job_cost = $min_fee;
                }
            }
        }

        return $job_cost;

    }

}