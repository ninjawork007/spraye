<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Technician_model extends CI_Model{   
    const TJATBL="technician_job_assign";


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

    public function GetAllRow($where_arr='') {

        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->result();
        return $data; 

    }

    public function getOneJobAssign($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('technician_job_assign_id','desc');
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

   

     public function getAllJobAssign($where_arr) {
        $this->db->select("technician_job_assign.*, customers.*, jobs.*, programs.*, property_tbl.*, program_job_assigned_customer_property.program_job_assigned_customer_property_id, program_job_assigned_customer_property.reason ,TIME_FORMAT (`specific_time`,'%H:%i') as  specific_time");
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('program_job_assigned_customer_property', 'jobs.job_id = program_job_assigned_customer_property.job_id AND customers.customer_id = program_job_assigned_customer_property.customer_id AND programs.program_id = program_job_assigned_customer_property.program_id AND property_tbl.property_id = program_job_assigned_customer_property.property_id', 'left');

      

        $this->db->where($where_arr);
        

        $this->db->order_by('technician_job_assign_id','desc');
        $result = $this->db->get();
     
        $data = $result->result_array();
        return $data;
    }

 
 //    public function getAllJobAssign($latitude,$longitude) {


 //        $sql='SELECT *, 111.045 * DEGREES(ACOS(COS(RADIANS('.$latitude.'))
 //            * COS(RADIANS(property_latitude))
 //            * COS(RADIANS(property_longitude) - RADIANS('.$longitude.'))
 //            + SIN(RADIANS('.$latitude.'))
 //            * SIN(RADIANS(property_latitude))))
 //            AS distance_in_km
 //            FROM technician_job_assign INNER JOIN `customers` ON `customers`.`customer_id`=`technician_job_assign`.`customer_id` INNER JOIN `jobs` ON `jobs`.`job_id`=`technician_job_assign`.`job_id` INNER JOIN `programs` ON `programs`.`program_id`=`technician_job_assign`.`program_id` INNER JOIN `property_tbl` ON `property_tbl`.`property_id`=`technician_job_assign`.`property_id` 
 // where technician_job_assign.technician_id="'.$this->session->userdata['spraye_technician_login']->user_id.'" AND `job_assign_date` = "'.date("Y-m-d").'" AND `is_job_mode`=0 
 //            ORDER BY distance_in_km ASC';
 //        $record=$this->db->query($sql);

 //        if($record->num_rows()>0){
 //            return $record->result();
 //        }

 //    }



 //    public function getAllJobAssignArray($latitude,$longitude) {


 //        $sql='SELECT *, 111.045 * DEGREES(ACOS(COS(RADIANS('.$latitude.'))
 //            * COS(RADIANS(property_latitude))
 //            * COS(RADIANS(property_longitude) - RADIANS('.$longitude.'))
 //            + SIN(RADIANS('.$latitude.'))
 //            * SIN(RADIANS(property_latitude))))
 //            AS distance_in_km
 //            FROM technician_job_assign INNER JOIN `customers` ON `customers`.`customer_id`=`technician_job_assign`.`customer_id` INNER JOIN `jobs` ON `jobs`.`job_id`=`technician_job_assign`.`job_id` INNER JOIN `programs` ON `programs`.`program_id`=`technician_job_assign`.`program_id` INNER JOIN `property_tbl` ON `property_tbl`.`property_id`=`technician_job_assign`.`property_id` 
 // where technician_job_assign.technician_id="'.$this->session->userdata['spraye_technician_login']->user_id.'" AND `job_assign_date` = "'.date("Y-m-d").'" AND `is_job_mode`=0 
 //            ORDER BY distance_in_km ASC';
 //        $record=$this->db->query($sql);

 //        if($record->num_rows()>0){
 //            return $record->result_array();
 //        }

 //    }



      public function getAllJobAssignbyAjax($property_address) {


        $sql='SELECT * FROM technician_job_assign INNER JOIN `customers` ON `customers`.`customer_id`=`technician_job_assign`.`customer_id` INNER JOIN `jobs` ON `jobs`.`job_id`=`technician_job_assign`.`job_id` INNER JOIN `programs` ON `programs`.`program_id`=`technician_job_assign`.`program_id` INNER JOIN `property_tbl` ON `property_tbl`.`property_id`=`technician_job_assign`.`property_id` 
 where `technician_job_assign`.`technician_id`="'.$this->session->userdata['spraye_technician_login']->user_id.'" AND `job_assign_date` = "'.date("Y-m-d").'" AND `is_job_mode`=0 AND  `property_address` like"%'.$property_address.'%" ';
        $record=$this->db->query($sql);

        if($record->num_rows()>0){
            return $record->result();
        }

    }



    public function getAllJobAssignCheck($where_arr) {


        $this->db->select('*');
        
        $this->db->from(self::TJATBL);
        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");

     
        $this->db->where($where_arr);

        $this->db->order_by('job_completed_time','desc');
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }


    public function getJobDetails($wherearr='') {
 
        $this->db->select('*');
        $this->db->from('jobs');
        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->row();
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

    public function getAllProductDetails_diff($wherearr='') {

        $this->db->select('*');
        $this->db->from('products');


        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        $result = $this->db->get();
        $data = $result->result();
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


    public function getAllProductDetails_new($wherearr='', $extra_products = '') {

        $id = $wherearr['job_id'];

        /*   $this->db->select('*');
           $from = '(select *
               from job_product_assign
                             where job_product_assign.job_id ='.$id.' ';
           if (!empty($extra_products)){
               foreach ($extra_products as $value){
                   $from .= 'union
                             (select 1 as job_product_id,
                             (select max(job_id) from job_product_assign where job_product_assign.job_id = 187) as job_id,
                             '.$value.' as product_id)';
               }

               $from .= ') a';
           }


           $this->db->from($from);

           $this->db->join('products',"products.product_id=a.product_id ","inner");*/

//        if (is_array($wherearr)) {
//            $this->db->where($wherearr);
//        }
        $query = "SELECT * FROM (select * from job_product_assign where job_product_assign.job_id =".$id." ";
        if (!empty($extra_products)) {
            foreach ($extra_products as $value) {
                $query .= "union (select 1 as job_product_id, (select max(job_id) from job_product_assign where job_product_assign.job_id = ".$id.") as job_id, ".$value." as product_id)  ";

            }

        }
        $query .= ") a INNER JOIN `products` ON `products`.`product_id`=`a`.`product_id` and mixture_application_rate != 0 and application_rate !=0";
        $select = $this->db->query($query);
        //$result = $this->db->get();
        $data = $select->result();
        return $data;
    }


    public function getPropertyDetails($wherearr='') {
 
        $this->db->select('*');
        $this->db->from('property_tbl');

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
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


   public function getWindDirection($wherearr) {
 
        $this->db->select('*');
        $this->db->from('t_direction');

        $this->db->where($wherearr);
        
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

   public function getOneProgram($wherearr) {
 
        $this->db->select('*');
        $this->db->from('programs');

        $this->db->where($wherearr);
        
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }


     public function getCurrentRoute()
    {            
        $this->db->select('route');        
        $this->db->from(self::TJATBL);
     
        $where_arr =  array(
            'technician_job_assign.technician_id'=>$this->session->userdata['spraye_technician_login']->user_id,
            'job_assign_date'=>date("Y-m-d"),
            'is_job_mode'=>0,
            );

        $this->db->where($where_arr);

        $this->db->group_by('route');
        $this->db->order_by('route','asc');
        $result = $this->db->get();
     
        $data = $result->row();
        return $data;
    }


      public function getOneJobAllDetails($where_arr = array()){
        $this->db->select('* , users.phone as phone');
        $this->db->from(self::TJATBL);

        $this->db->join('customers',"customers.customer_id=technician_job_assign.customer_id","inner");
        $this->db->join('jobs',"jobs.job_id=technician_job_assign.job_id","inner");
        $this->db->join('programs',"programs.program_id=technician_job_assign.program_id","inner");
        $this->db->join('property_tbl',"property_tbl.property_id=technician_job_assign.property_id","inner");
        $this->db->join('users',"users.user_id=technician_job_assign.technician_id","inner");
        $this->db->join('t_estimate',"t_estimate.program_id=technician_job_assign.program_id","left");
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->row();
        // die($this->db->last_query());
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

    public function getRoutsByJobAssign($where_arr){

        $this->db->select('technician_job_assign.route_id,route_name,is_time_check,specific_time');
        $this->db->from(self::TJATBL);
        $this->db->join('route',"route.route_id=technician_job_assign.route_id","inner");
        $this->db->where($where_arr);
        $this->db->group_by('technician_job_assign.route_id');
        $result = $this->db->get();
        $data = $result->result_array();
        return $data;
       
    }

    public function getAllRouteJobsCount($route_id)
    {
        $this->db->from(self::TJATBL);
        $this->db->where('route_id', $route_id);
        return $this->db->get()->num_rows();
    }

    public function getItemInfoByProductId($product_id)
    {
        $this->db->select('item_product_tbl.*, items_tbl.*, products.*');
        $this->db->from('item_product_tbl');
        $this->db->join('items_tbl', 'items_tbl.item_id = item_product_tbl.item_id', 'left');
        $this->db->join('products', 'products.product_id = item_product_tbl.product_id', 'left');
        $this->db->where('item_product_tbl.product_id', $product_id);
        $result = $this->db->get();
        $data = $result->result();

        // die(print_r($data));
        return $data;
    }

    public function getFleetInfoByAssignedUser($tech_id)
    {
        $this->db->select('*');
        $this->db->from('fleet_vehicles');
        $this->db->where('v_assigned_user', $tech_id);
        $result = $this->db->get();
        $data = $result->row();
        // die(print_r($data));
        return $data;
    }

    public function getFleetSubLocationInfo($fleet_id){
        $this->db->select('*');
        $this->db->from('sub_locations_tbl');
        $this->db->where('sub_location_fleet_id', $fleet_id);
        $result = $this->db->get();
        $data = $result->row();
        // die(print_r(($data)));
        return $data;
    }

    public function getCurrentItemQuantityInSubLocation($item_id, $sub_id){
        $this->db->select('quantity, quantity_id');
        $this->db->from('quantities');
        $this->db->where(array('quantity_item_id' => $item_id, 'quantity_sublocation_id' => $sub_id));
        $result = $this->db->get();
        $data = $result->row();
        // die(print_r(($data)));
         //die($this->db->last_query());

        return $data;
    }
}
 
