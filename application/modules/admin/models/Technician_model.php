<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Technician_model extends CI_Model{   
    const TJATBL="technician_job_assign";
    const ROUTE="route";

    public function GetCountOfRescheduledJobs($where_arr){
        $data =  $this->db->query("
            SELECT 
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
                technician_job_assign.is_job_mode, unassigned_Job_delete.unassigned_Job_delete_id, `property_tbl`.`property_state`, `property_tbl`.`property_city`, `property_tbl`.`property_zip`, customers.pre_service_notification, `property_tbl`.`tags`, `property_tbl`.`available_days`, `jobs`.`service_note`, `jobs`.`job_notes`, customers.customer_status, ( SELECT reschedule_message FROM technician_job_assign WHERE customer_id = customers.customer_id AND job_id = jobs.job_id AND program_id = programs.program_id AND property_id = property_tbl.property_id ) assign_reschedule_message, EXISTS( SELECT * FROM technician_job_assign WHERE is_job_mode = 2 AND customer_id = customers.customer_id AND job_id = jobs.job_id AND program_id = programs.program_id AND property_id = property_tbl.property_id ) assign_table_data, property_program_assign.property_program_date, technician.user_first_name, technician.user_last_name, ( SELECT MAX(job_completed_date) AS completed_date_last_service_by_type FROM technician_job_assign tja JOIN jobs j ON j.job_id = tja.job_id WHERE tja.is_complete = 1 and j.service_type_id = jobs.service_type_id and tja.property_id = property_program_assign.property_id ) as completed_date_last_service_by_type, ( SELECT MAX(job_completed_date) AS completed_date_property FROM technician_job_assign where property_id = property_program_assign.property_id GROUP BY property_id ) as completed_date_property, ( SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id ) as completed_date_property_program, CASE WHEN datediff(now(), ( SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id )) >= (IFNULL(programs.program_schedule_window, 30)+ 5) THEN 'Overdue' WHEN datediff(now(), ( SELECT MAX(job_completed_date) AS completed_date_property_program FROM technician_job_assign where property_id = property_program_assign.property_id AND programs.program_id = program_id GROUP BY property_id, program_id)) < (IFNULL(programs.program_schedule_window, 30) - 5) THEN 'Not Due' ELSE 'Due' END as service_due, CASE WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL THEN 1 ELSE 0 END asap, CASE WHEN program_job_assigned_customer_property.program_job_assigned_customer_property_id IS NOT NULL THEN program_job_assigned_customer_property.reason ELSE '' END as asap_reason FROM `jobs` INNER JOIN `program_job_assign` ON `program_job_assign`.`job_id` =`jobs`.`job_id` INNER JOIN `property_program_assign` ON `property_program_assign`.`program_id` = `program_job_assign`.`program_id` INNER JOIN `property_tbl` ON `property_tbl`.`property_id` = `property_program_assign`.`property_id` LEFT JOIN `category_property_area` ON `category_property_area`.`property_area_cat_id` = `property_tbl`.`property_area` INNER JOIN `programs` ON `programs`.`program_id` = `property_program_assign`.`program_id` INNER JOIN `customer_property_assign` ON `customer_property_assign`.`property_id` = `property_program_assign`.`property_id` INNER JOIN `customers` ON `customers`.`customer_id` = `customer_property_assign`.`customer_id` LEFT JOIN `technician_job_assign` ON `technician_job_assign`.`customer_id` = `customers`.`customer_id` AND `technician_job_assign`.`job_id` = `jobs`.`job_id` AND `technician_job_assign`.`program_id` = `programs`.`program_id` AND `technician_job_assign`.`property_id` = `property_tbl`.`property_id` LEFT JOIN `unassigned_Job_delete` ON `unassigned_Job_delete`.`customer_id` = `customers`.`customer_id` AND `unassigned_Job_delete`.`job_id` = `jobs`.`job_id` AND `unassigned_Job_delete`.`program_id` = `programs`.`program_id` AND `unassigned_Job_delete`.`property_id` = `property_tbl`.`property_id` LEFT JOIN `users` `technician` ON `technician`.`user_id` = `technician_job_assign`.`technician_id` LEFT JOIN `program_job_assigned_customer_property` ON `jobs`.`job_id` = `program_job_assigned_customer_property`.`job_id` AND `customers`.`customer_id` = `program_job_assigned_customer_property`.`customer_id` AND `programs`.`program_id` = `program_job_assigned_customer_property`.`program_id` AND `property_tbl`.`property_id` = `program_job_assigned_customer_property`.`property_id` 
                
                WHERE (`is_job_mode` = 2 ) AND `unassigned_Job_delete_id` IS NULL AND `jobs`.`company_id` = '".$this->session->userdata['company_id']."' AND `property_tbl`.`company_id` = '".$this->session->userdata['company_id']."' AND `customer_status` !=0 AND `property_status` !=0 GROUP BY `customers`.`first_name`, `customers`.`last_name`, `billing_street`, `billing_street_2`, `jobs`.`job_id`, `jobs`.`job_name`, `programs`.`program_name`, `service_due`, `customers`.`customer_id`, `jobs`.`job_id`, `programs`.`program_id`, `property_tbl`.`property_id`, `property_tbl`.`property_notes`, `property_tbl`.`yard_square_feet`, `property_tbl`.`property_latitude`, `property_tbl`.`property_longitude`, `category_area_name`, `property_address`, `program_job_assign`.`priority`, `property_type`, `property_title`, `completed_date_property`, `completed_date_property_program`, `technician_job_assign`.`is_job_mode`, `unassigned_Job_delete`.`unassigned_Job_delete_id`, `property_tbl`.`property_state`, `property_tbl`.`property_city`, `property_tbl`.`property_zip`, `customers`.`pre_service_notification`, `property_tbl`.`tags`, `jobs`.`service_note`, `jobs`.`job_notes`, `customers`.`customer_status`, `assign_reschedule_message`, `assign_table_data`, `technician_job_assign`.`technician_id`, `property_program_assign`.`property_program_date`, `technician`.`user_first_name`, `technician`.`user_last_name`, `completed_date_last_service_by_type` ORDER BY `reschedule_message` DESC;");
        return $data->conn_id->affected_rows;
    }
    public function GetOneRow($where_arr='') {

        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data; 

    }

    public function GetAllRow($where_arr) {
        $this->db->select('*');
        $this->db->from(self::TJATBL);
        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
    }
    

     public function getOneJobAssign($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
 
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    //  public function getOneTechJobAssign($where_arr) {           
    //     $this->db->select('*');        
    //     $this->db->from(self::TJATBL);
    //     $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
    //     $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
    //     $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
    //     $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");

    //     $this->db->where($where_arr);
    //     $this->db->order_by('technician_job_assign_id','desc');
    //     $result = $this->db->get();     
    //     $data = $result->row();
    //     return $data;
    // }

    public function getOneTechJobAssign($where_arr) {           
        $this->db->select('*');        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('route', "route.route_id=technician_job_assign.route_id", "inner");
        $this->db->join('job_product_assign', "job_product_assign.job_id=technician_job_assign.job_id", "inner");
        $this->db->join('products', "products.product_id=job_product_assign.product_id", "inner");
        //$this->db->join('report_product', "report_product.product_id=job_product_assign.product_id", "inner");

        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();     
        $data = $result->row();
        // die(print_r($this->db->last_query()));
        return $data;
    }

    // public function getOneTechJobAssignNoProduct($where_arr) {           
    //     $this->db->select('*');        
    //     $this->db->from(self::TJATBL);
    //     $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
    //     $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
    //     $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
    //     $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
    //     $this->db->join('route', "route.route_id=technician_job_assign.route_id", "inner");
    //     //$this->db->join('report_product', "report_product.product_id=job_product_assign.product_id", "inner");

    //     $this->db->where($where_arr);
    //     $this->db->order_by('technician_job_assign_id','desc');
    //     $result = $this->db->get();     
    //     $data = $result->row();
    //     // die(print_r($this->db->last_query()));
    //     return $data;
    // }



    public function getOneTechJobAssignNoProduct($where_arr) {           
        $this->db->select('*');        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('route', "route.route_id=technician_job_assign.route_id", "inner");
        //$this->db->join('report_product', "report_product.product_id=job_product_assign.product_id", "inner");

        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();     
        $data = $result->row();
        // die(print_r($this->db->last_query()));
        return $data;
    }

	  public function checkIfCompleted($where_arr) {
           
        $this->db->select('is_complete');
        
        $this->db->from(self::TJATBL);
      

        $this->db->where($where_arr);

        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }

 
    //   public function getAllJobAssign($where_arr) {
           
    //     $this->db->select('*');
        
    //     $this->db->from(self::TJATBL);
    //     $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
    //     $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
    //     $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
    //     $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
    //     $this->db->join('users', "users.user_id=technician_job_assign.user_id", "left");

    //     $this->db->where($where_arr);
        
    //     $this->db->order_by('technician_job_assign_id','desc');
    //     $result = $this->db->get();
     
    //     $data = $result->result_array();
    //     return $data;
    // }

    ###### Changed to join USER TABLE BY TECHNICIAN_ID ########
    public function getAllJobAssign($where_arr) {
           
        $this->db->select('*, customers.phone as mobile, customers.alerts as customer_alerts, property_tbl.alerts as property_alerts' );
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('users', "users.user_id=technician_job_assign.technician_id", "left");

        $this->db->where($where_arr);
        
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }
    public function getAllJobAssignWhere($where_arr) {
       
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","left");
        $this->db->join('users', "users.user_id=technician_job_assign.technician_id", "left");

        // $this->db->where($where_arr);
        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }

    public function getProductDetails($wherearr='') {
 
        $this->db->select('*');
        $this->db->from('job_product_assign');

        $this->db->join('products',"products.product_id=job_product_assign.product_id","inner");

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }




    public function getAllProductlike($wherearr,$product_name_like='') {
 
        $this->db->select('*');
        $this->db->from('job_product_assign');
        $this->db->join('products',"products.product_id=job_product_assign.product_id","inner");


        $this->db->where($wherearr);

        if (!empty($product_name_like)) {
        
            $this->db->like('product_name',$product_name_like);
            
        }

       
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getPropertyDetails($wherearr='') {
 
        $this->db->select('*');
        $this->db->from('property_program_assign');

       $this->db->join('property_tbl','property_tbl.property_id = property_program_assign.property_id','inner');


        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

      public function getOnePriceOverride($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('property_program_assign');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }




    

    public function updateJobAssign($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::TJATBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteJobAssign($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::TJATBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


    public function getjobTechEmailData($wherearr) {
       $this->db->select('first_name,last_name,job_name,program_name,property_address');
       $this->db->from('customers,jobs,programs,property_tbl');
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;   

    }


     public function getAllJobAssignCount($where_arr) {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
    

        $this->db->where($where_arr);

        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
        
        return $result->num_rows();    

    }

   

     public function getAllJobAssignCheck($technician_id,$job_assign_date) {


        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");

        $where_arr =  array(
            'technician_job_assign.technician_id'=>$technician_id,
            'job_assign_date'=>$job_assign_date,
            'is_job_mode'=>1,
            'is_complete' =>1
            );

        $this->db->where($where_arr);

        $this->db->order_by('job_completed_time','desc');
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }



    public function getNumberOfRoute($where_arr){            
        $this->db->select('route_id,route_name');        
        $this->db->from(self::ROUTE);
        $this->db->where($where_arr);

        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }

    public function createRoute($post){            
        $query = $this->db->insert(self::ROUTE, $post);
        return $this->db->insert_id();
    }


    public function updateRoute($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::ROUTE, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

       public function GetOneRoute($where_arr='') {

        $this->db->select('*');
        
        $this->db->from(self::ROUTE);
        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row_array();
        return $data; 

    }
    


    
    public function getRoutsByJobAssign($where_arr){

        $this->db->select('technician_job_assign.route_id,route_name');
        $this->db->from(self::TJATBL);
        $this->db->join('route',"route.route_id=technician_job_assign.route_id","inner");
        $this->db->where($where_arr);
        $this->db->group_by('technician_job_assign.route_id');
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
       

        
    }

	public function getProgramPropertyEmailData($wherearr) {
       $this->db->select('first_name,last_name,program_name,property_address');
       $this->db->from('customers,programs,property_tbl');
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();

        // die(print_r($this->db->last_query()));
        return $data;   

    }

    public function getPropertyEmailData($wherearr) {
        $this->db->select('first_name,last_name,property_address');
        $this->db->from('customers,property_tbl');
         if (is_array($wherearr)) {
             $this->db->where($wherearr);
         }
         $result = $this->db->get();
         $data = $result->row();
 
         // die(print_r($this->db->last_query()));
         return $data;   
 
     }

    public function getAllProductDetails($wherearr='') {
 
        $this->db->select('*');
        $this->db->from('job_product_assign');

        $this->db->join('products',"products.product_id=job_product_assign.product_id","inner");

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllOptimizedRoutes($where_arr=''){

        $this->db->select('technician_job_assign_id, technician_job_assign.technician_id, technician_job_assign.route_id, route.route_name, technician_job_assign.job_assign_date, property_tbl.*');
        $this->db->from(self::TJATBL);

        $this->db->join('route', "route.route_id=technician_job_assign.route_id", "left");
        $this->db->join('property_tbl', "property_tbl.property_id=technician_job_assign.property_id");

        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign.route_id', 'desc');

        $result = $this->db->get();
        $data = $result->result_array();
        //die(print_r($this->db->last_query()));
        return $data;
    }

    public function getTechUserDetails($where=''){
        $this->db->select('*, technician_job_assign_id, technician_job_assign.technician_id');
        $this->db->from(self::TJATBL);

        $this->db->join('users', "users.user_id=technician_job_assign.technician_id", "left");

        $this->db->where($where);

        $result = $this->db->get();
        $data = $result->result_array();

        return $data[0];
    }

    public function getRouteDetails($route_id)
    {
        $this->db->from(self::ROUTE);
        $this->db->where('route_id', $route_id);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getAllEstimateDetails($where_arr = array()){
        $this->db->select('* , jobs.job_name, users.phone as phone');
        $this->db->from(self::TJATBL);

        $this->db->join('invoice_tbl',"invoice_tbl.invoice_id=technician_job_assign.invoice_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('users',"users.user_id=technician_job_assign.technician_id","inner");
        $this->db->join('t_estimate',"t_estimate.program_id=technician_job_assign.program_id","inner");
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->result();
        // die(print($this->db->last_query()));
        return $data;        
 
       
    }
    public function getAllEstimateDetailsSearch($params = array()){
        $this->db->select('* , jobs.job_name, users.phone as phone');
        $this->db->from(self::TJATBL);

        $this->db->join('invoice_tbl',"invoice_tbl.invoice_id=technician_job_assign.invoice_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('users',"users.user_id=technician_job_assign.technician_id","inner");
        $this->db->join('t_estimate',"t_estimate.program_id=technician_job_assign.program_id","inner");
        $this->db->where('technician_job_assign.company_id',$this->session->userdata['company_id']);
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

        if(!empty($params['search']['date_range_date_to']) && empty($params['search']['date_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date >=',$params['search']['date_range_date_to']);
        }
        else if(empty($params['search']['date_range_date_to']) && !empty($params['search']['date_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date <=',$params['search']['date_range_date_from']);
        }

        else if(!empty($params['search']['date_range_date_to']) && !empty($params['search']['date_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date >=',$params['search']['date_range_date_to']);
           $this->db->where('t_estimate.estimate_date <=',$params['search']['date_range_date_from']);
        }

        if(!empty($params['search']['comparision_range_date_to']) && empty($params['search']['comparision_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date >=',$params['search']['comparision_range_date_to']);
        }
        else if(empty($params['search']['comparision_range_date_to']) && !empty($params['search']['comparision_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date <=',$params['search']['comparision_range_date_from']);
        }

        else if(!empty($params['search']['comparision_range_date_to']) && !empty($params['search']['comparision_range_date_from']) ){
           $this->db->where('t_estimate.estimate_date >=',$params['search']['comparision_range_date_to']);
           $this->db->where('t_estimate.estimate_date <=',$params['search']['comparision_range_date_from']);
        }
       
        if(!empty($params['search']['sales_rep'])){    
           $this->db->where("(`user_first_name` LIKE '%".$params['search']['sales_rep']."%' OR `user_last_name` LIKE '%".$params['search']['sales_rep']."%')");
        }
        
        if(!empty($params['search']['sales_rep_id'])){    
           $this->db->where("(`sales_rep` LIKE '%".$params['search']['sales_rep_id']."%' )");
        }
        if(!empty($params['search']['job_id'])){    
           $this->db->where("(`jobs.job_id` LIKE '%".$params['search']['job_id']."%' )");
        }
        if(!empty($params['search']['job_name'])){    
           $this->db->where("(`jobs.job_name` LIKE '%".$params['search']['job_name']."%' )");
        }
        if(!empty($params['search']['customer_name'])){    
           $this->db->where("(`first_name` LIKE '%".$params['search']['customer_name']."%' OR `last_name` LIKE '%".$params['search']['customer_name']."%')");
        }
        if(!empty($params['search']['property_address'])){    
           $this->db->where("(`property_address` LIKE '%".$params['search']['property_address']."%' )");
        }
        if(!empty($params['search']['program_name'])){    
           $this->db->where("(`program_name` LIKE '%".$params['search']['program_name']."%' )");
        }

        // if(!empty($params['search']['product_name'])){          
        //    $this->db->where(" `product_name` LIKE '%".$params['search']['product_name']."%' ");
        // }

        $this->db->group_by('technician_job_assign.technician_job_assign_id');
        $this->db->order_by('technician_job_assign.technician_job_assign_id','desc');
      
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

    public function GetAllRowOBJ($where_arr) {
        $this->db->select('*');
        $this->db->from(self::TJATBL);
        $this->db->where($where_arr);
        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

}
 
