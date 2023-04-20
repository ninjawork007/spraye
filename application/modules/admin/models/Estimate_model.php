<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Estimate_model extends CI_Model{
      const EST="t_estimate";
      const ESTPR="t_estimate_price_override";

   public function CreateOneEstimate($post) {
        $query = $this->db->insert(self::EST, $post);
        return $this->db->insert_id();
    }

    public function getOneEstimate($where_arr = '') {
           
        $this->db->select('t_estimate.*,first_name,last_name,email,customer_company_name,billing_street,billing_city,billing_state,billing_zipcode,phone,program_name,program_price,yard_square_feet,property_address,difficulty_level,notes');
   
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');

        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getEstimatePropertiesById($program_id)
    {
        $this->db->select('property_id');
        $this->db->from(self::EST);
        $this->db->where('program_id',$program_id);
        $result = $this->db->get();
        $data = $result->result();
        $arr = array_column($data, 'property_id');
        return $arr;
    }

    public function getJustOneEstimate($where_arr = '') {
           
        $this->db->select('*');
   
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllEstimate($where_arr = '') {

        $this->db->select('`t_estimate`.*  ,first_name,last_name,customers.email,program_name,program_price,yard_square_feet,property_address,property_tbl.property_status,difficulty_level,user_first_name,user_last_name,program_job_assign.job_id,jobs.job_name,count(distinct `coupon_estimate`.coupon_id) as coupon');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }         
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('program_job_assign','program_job_assign.program_id = programs.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
       $this->db->join('users','users.id = t_estimate.sales_rep','left');
       $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
       $this->db->join('coupon_estimate','t_estimate.estimate_id = coupon_estimate.estimate_id','left');

       $this->db->group_by('estimate_id','desc');
       $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();

        $data = $result->result();
      //   die(print_r($this->db->last_query()));
        return $data;
    }

    public function getAllEstimate_for_table($where_arr = '') {

        $this->db->select('`t_estimate`.estimate_id,`t_estimate`.estimate_created_date,`t_estimate`.status, `t_estimate`.property_id, `t_estimate`.program_id, `t_estimate`.customer_id, first_name,last_name,customers.email,program_name,program_price,yard_square_feet,property_address,property_tbl.property_status,difficulty_level,user_first_name,user_last_name,program_job_assign.job_id,jobs.job_name,count(distinct `coupon_estimate`.coupon_id) as coupon, `t_estimate`.signwell_id, `t_estimate`.signwell_status,`t_estimate`.signwell_completed, `t_estimate`.signwell_url'  );

        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }         
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('program_job_assign','program_job_assign.program_id = programs.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
       $this->db->join('users','users.id = t_estimate.sales_rep','left');
       $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
       $this->db->join('coupon_estimate','t_estimate.estimate_id = coupon_estimate.estimate_id','left');


       $this->db->group_by('estimate_id','desc');
       $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();

        $data = $result->result();
      //   die(print_r($this->db->last_query()));
        return $data;
    }

    public function getAllEstimate_for_table_new($where_arr = '', $where_in= '',$where_like= '', $start,$limit,$order,$dir, $is_for_count) {
        if ($is_for_count == false) {
            $this->db->select('`t_estimate`.estimate_id,`t_estimate`.estimate_created_date,`t_estimate`.status, `t_estimate`.property_id, `t_estimate`.program_id, `t_estimate`.customer_id, first_name,last_name,customers.email,program_name,program_price,yard_square_feet,property_address,property_tbl.property_status,difficulty_level,user_first_name, user_last_name,program_job_assign.job_id,jobs.job_name,count(distinct `coupon_estimate`.coupon_id) as coupon,group_concat(distinct `coupon_estimate`.coupon_code,", ") as coupon_name, `t_estimate`.signwell_id, `t_estimate`.signwell_status,`t_estimate`.signwell_completed, `t_estimate`.signwell_url' );
        } else {
            $this->db->select('`t_estimate`.estimate_id');
        }

        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (is_array($where_in)) {
            $this->db->where_in=($where_in);
        }

        if (count($where_like)!==0) {
            //die(count($where_like));
            $this->db->group_start();
            $this->db->or_like($where_like);
            $this->db->group_end();
        }


        $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('program_job_assign','program_job_assign.program_id = programs.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
       $this->db->join('users','users.id = t_estimate.sales_rep','left');
       $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
       $this->db->join('coupon_estimate','t_estimate.estimate_id = coupon_estimate.estimate_id','left');

        if ($is_for_count == false) {
            $this->db->limit( $limit,$start);

        }
        $this->db->group_by('estimate_id','desc');
        //$this->db->order_by('estimate_id','desc');
        //die(print_r($order));
        if ($order == 'customer_name'){
            $this->db->order_by('customers.first_name',$dir);
            $this->db->order_by('customers.last_name',$dir);
        } else if ($order == 'total_cost'){
        } else if ($order == 'coupon'){
            $this->db->order_by("coupon_estimate.coupon_code",$dir);
        } else if ($order == 'user_complete_name'){
            $this->db->order_by('user_first_name',$dir);
            $this->db->order_by('user_last_name',$dir);
        } else {
            $this->db->order_by($order,$dir);
        }

        $result = $this->db->get();


        $data = $result->result();
        if ($is_for_count == false) {
            //die($this->db->last_query());
            return $data;
        } else {
            return count($data);
        }


    }

    public function updateEstimate($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::EST, $updatearr);
        return $a = $this->db->affected_rows();
        
    }
    
    public function deleteEstimate($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::EST);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


     public function CreateOneEstimatePriceOverRide($post) {
        $query = $this->db->insert(self::ESTPR, $post);
        return $this->db->insert_id();
    }

      public function getOneEstimatePriceOverRide($where_arr = '') {
           
        $this->db->select('*');
   
        
        $this->db->from(self::ESTPR);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    
    
        $this->db->join('jobs','jobs.job_id = t_estimate_price_override.job_id','inner');
       
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllEstimatePriceOveride($where_arr = '') {
        $this->db->select('*');
        $this->db->from("t_estimate_price_override");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllEstimatePriceOveridewJob($where_arr = '') {
        $this->db->select('t_estimate_price_override.*, jobs.job_price, jobs.job_price_per, jobs.base_fee_override, jobs.min_fee_override');
        $this->db->from("t_estimate_price_override");
        $this->db->join("jobs", "t_estimate_price_override.job_id = jobs.job_id", "inner");
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }


       public function deleteEstimatePriceOverRide($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::ESTPR);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    function getProgramPropertyJobPriceOverrides($where = '')
    {
        // $where = array('property_id' => $property_id, 'program_id' = $program_id) 
        // Raw SQL: SELECT * FROM `t_estimate_price_override` WHERE `property_id` = $property_id 
        //                  AND `program_id` = $program_id AND `customer_id` = $customer_id;
        $this->db->select('*');
        $this->db->from(self::ESTPR);
        if(is_array($where)) 
        {
            $this->db->where($where);
        }
        $result = $this->db->get();
        $data = $result->result();
        // die(print_r($this->db->last_query()));
        return $data;
    }


    public function assignProgramProperty($post) {
        $this->db->insert('property_program_assign', $post);
          $insert_id = $this->db->insert_id();

       return  $insert_id;
    }

    public function getOneProgramProperty($where){
       return $this->db->where($where)->get('property_program_assign')->row();
        
    }

    public function updateProgramProperty($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update('property_program_assign', $updatearr);
        return $a = $this->db->affected_rows();        
    }

    public function getAllSalesRepEstimate($where_arr = '') {
           
        $this->db->select('t_estimate.*,user_first_name,user_last_name');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }         
       $this->db->join('users','users.id = t_estimate.sales_rep','inner');
    //    $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
    //    $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');

        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllEstimateSearch($params = array()){
        $this->db->select('t_estimate.*,first_name,last_name,customers.email,program_name,program_price,yard_square_feet,property_address,property_tbl.property_status,difficulty_level,user_first_name,user_last_name,program_job_assign.job_id,program_job_assign.program_job_id,jobs.job_name');
        $this->db->from(self::EST);

        // if (is_array($where_arr)) {
        //     $this->db->where($where_arr);
        // }         
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('programs','programs.program_id = t_estimate.program_id','inner');
       $this->db->join('program_job_assign','program_job_assign.program_id = programs.program_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
       $this->db->join('users','users.id = t_estimate.sales_rep','inner');
       $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
        // $this->db->select('*, report.report_id as thereportid');
        // $this->db->from(self::RPT);
        // $this->db->join("report_product","report_product.report_id = report.report_id","left");
		// $this->db->join('technician_job_assign','technician_job_assign.technician_job_assign_id = report.technician_job_assign_id','inner');
		
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

        // if(!empty($params['search']['customer_name'])) {
        //     $this->db->where("(`first_name` LIKE '%".$params['search']['customer_name']."%' OR `last_name` LIKE '%".$params['search']['customer_name']."%')");
        // } 
       
        if(!empty($params['search']['sales_rep'])){    
           $this->db->where("(`user_first_name` LIKE '%".$params['search']['sales_rep']."%' OR `user_last_name` LIKE '%".$params['search']['sales_rep']."%')");
        }
        
        if(!empty($params['search']['sales_rep_id'])){    
            $SaleRpID = explode(",", $params['search']['sales_rep_id']);

            $IdString = "sales_rep IN (";
            foreach($SaleRpID as $TcID){
                $IdString .= "'".$TcID."',";
            }
            $IdString = substr($IdString, 0, -1);
            $IdString .= ")";

            $this->db->where($IdString);
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

        $this->db->group_by('t_estimate.estimate_id');
        $this->db->order_by('t_estimate.estimate_id','desc');
      
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

    public function getAllEstimateCompleted($where_arr = '') {
           
        $this->db->select('t_estimate.estimate_id,t_estimate.program_id,t_estimate.estimate_date,t_estimate.status as estimate status,t_estimate.sales_rep,t_estimate.estimate_created_date,t_estimate.source,jobs.job_id,jobs.job_name,jobs.service_type_id,jobs.commission_type,jobs.bonus_type,technician_job_assign.technician_job_assign_id,technician_job_assign.user_id,technician_job_assign.technician_id,technician_job_assign.job_completed_date');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }         
       $this->db->join('program_job_assign','program_job_assign.program_id = t_estimate.program_id','inner');
       $this->db->join('technician_job_assign','technician_job_assign.program_id = t_estimate.program_id','inner');
       $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
    //    $this->db->join('users','users.id = t_estimate.sales_rep','inner');

        $this->db->group_by('estimate_id','desc');
        $this->db->order_by('estimate_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAllEstimateDetails($where_arr = array()){
        $this->db->select('* ');
        $this->db->from(self::EST);

        $this->db->join('program_job_assign','program_job_assign.program_id = t_estimate.program_id','inner');
        $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
        
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->result();
        // die(print($this->db->last_query()));
        return $data;        
 
       
    }
    public function getAllEstimateDetailsSearch($params = array()){
        $this->db->select('* ');
        $this->db->from(self::EST);
        $this->db->join('program_job_assign','program_job_assign.program_id = t_estimate.program_id','inner');
        $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
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

        // $this->db->group_by('t_estimate.estimate_id');
        $this->db->order_by('t_estimate.estimate_id','desc');
      
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

    public function getAllEstimateGroupByID($where_arr = array()){
      $this->db->select('* ');
      $this->db->from(self::EST);

      $this->db->join('program_job_assign','program_job_assign.program_id = t_estimate.program_id','inner');
      $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
      
      $this->db->where($where_arr);
      $this->db->group_by('estimate_id','desc');
      $this->db->order_by('estimate_id','desc');
      $result = $this->db->get();
      $data = $result->result();
      // die(print($this->db->last_query()));
      return $data;        
     
  }

  public function getAllEstimateDetailsSearchGroupByID($params = array()){
   $this->db->select('* ');
   $this->db->from(self::EST);
   $this->db->join('program_job_assign','program_job_assign.program_id = t_estimate.program_id','inner');
   $this->db->join('jobs','jobs.job_id = program_job_assign.job_id','inner');
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
   if(!empty($params['search']['status'])){    
      $this->db->where('status =',$params['search']['status']);
      // $this->db->where("(`status` LIKE '%".$params['search']['status']."%' )");
   }

   $this->db->group_by('t_estimate.estimate_id');
   $this->db->order_by('t_estimate.estimate_id','desc');
 
   //set start and limit
   if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
       $this->db->limit($params['limit'],$params['start']);
   }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
       $this->db->limit($params['limit']);
   }
    //get records
      $query = $this->db->get();
//  die($this->db->last_query());
       //return fetched data
    return ($query->num_rows() > 0)?$query->result():FALSE;        


  
}

public function getSource($property_id) {
    $this->db->select("*");
    $this->db->from('property_tbl');
    $this->db->where('property_id',$property_id);
    $result = $this->db->get();
    $data = $result->result();
    return $data;
}

public function updateEstimateSignWellID($estimate_id, $signwell_id) {
    $this->db->where('estimate_id', $estimate_id);
    $this->db->update(self::EST, array('signwell_id'=>$signwell_id, 'status'=>1));
    return $a = $this->db->affected_rows();
}

public function getTableDataAjaxEstimateProp($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count, $where_in = '') {

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

    if($where_like != "") {
        if(is_array($where_like) && !empty($where_like)) {
            $this->db->group_start();
            foreach($where_like as $key=>$value) {
                $this->db->or_like($key, $value);
            }
            $this->db->group_end();
        }
    }

    $this->db->order_by($col,$dir);

    if ($is_for_count == false) {
        $this->db->limit($limit, $start);
    }


    $result = $this->db->get();
    $data = $result->result();

    return $data;
}

public function getTableDataAjaxSearchEstimateProp($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count, $where_in = '') {
    
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
    if($where_like != "") {
        if(is_array($where_like) && !empty($where_like)) {
            foreach($where_like as $key=>$value) {
                $this->db->or_like($key, $value);
            }
        }
    }

    $this->db->or_like('property_title',$search);
    $this->db->or_like('property_address',$search);
    $this->db->or_like('first_name',$search);
    $this->db->or_like('last_name',$search);
    $this->db->or_like('category_area_name',$search);
    $this->db->or_like('property_type',$search);
    $this->db->or_like('property_zip',$search);
    $this->db->or_like('property_city',$search);
    $this->db->or_like('property_status',$search);
    $this->db->or_like('customers.customer_id',$search);
    $this->db->or_like('email',$search);
    $this->db->group_end();

    $this->db->order_by($col,$dir);

    if ($is_for_count == false) {
        $this->db->limit($limit, $start);
    }

    $result = $this->db->get();
    $data = $result->result();

    return $data;
}

}
 