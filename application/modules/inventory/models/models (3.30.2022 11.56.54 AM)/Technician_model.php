<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Technician_model extends CI_Model{   
    const TJATBL="technician_job_assign";
    const ROUTE="route";


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
           
        $this->db->select('*');
        
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

}
 