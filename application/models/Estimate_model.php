<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Estimate_model extends CI_Model{
      const EST="t_estimate";
      const ESTPR="t_estimate_price_override";
      const EST_PRGMS="estimate_programs";
      const EST_JBS="estimate_jobs";


      public function CreateOneEstimate($post) {
        $query = $this->db->insert(self::EST, $post);
        return $this->db->insert_id();
    }
    
  
    public function getOneEstimate($where_arr = '') {
           
        $this->db->select('
            t_estimate.*,
            customers.user_id,
            first_name,
            last_name,
            email,
            customer_company_name,
            billing_street,
            billing_city,
            billing_state,
            billing_zipcode,
            phone,
            yard_square_feet,
            property_address,
            difficulty_level,
            notes,
            programs.program_price,
            programs.program_name as old_program_name
        ');
        
        $this->db->from(self::EST);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }                    
       $this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
       $this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
       $this->db->join('programs', 'programs.program_id = t_estimate.program_id', 'left');

        
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }  

    public function CreateOneEstimatePriceOverRide($post) {
        $query = $this->db->insert(self::ESTPR, $post);
        return $this->db->insert_id();
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

    public function updateEstimate($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update(self::EST, $updatearr);
        return $a = $this->db->affected_rows();        
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

	public function getOneProperty($where_arr = '') {
      $this->db->select('*');
      $this->db->from('property_tbl');
      if (is_array($where_arr)) {
          $this->db->where($where_arr);
      }
      $result = $this->db->get();
      $data = $result->row();
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

    public function getAllEstimate($where_arr = '') {
         
        //new code:
//-----------------------------------------------
$this->db->select('
`t_estimate`.estimate_id,
`t_estimate`.estimate_created_date,
`t_estimate`.status,
`t_estimate`.property_id,
`t_estimate`.program_id,
`t_estimate`.customer_id,
first_name,
last_name,
customers.email,
yard_square_feet,
property_address,
programs.program_name,
property_tbl.property_status,
difficulty_level,
user_first_name,
user_last_name,
jobs.job_name,
count(distinct `coupon_estimate`.coupon_id) as coupon,
group_concat(distinct `coupon_estimate`.coupon_code,", ") as coupon_name,
`t_estimate`.signwell_id,
`t_estimate`.signwell_status,
`t_estimate`.signwell_completed,
`t_estimate`.signwell_url' );

$this->db->from(self::EST);


$this->db->join('customers','customers.customer_id = t_estimate.customer_id','inner');
$this->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
$this->db->join('users','users.id = t_estimate.sales_rep','left');
$this->db->join('programs','programs.program_id = t_estimate.program_id','left');
$this->db->join('estimate_programs','estimate_programs.estimate_id = t_estimate.estimate_id','left');
$this->db->join('jobs','jobs.job_id = estimate_programs.service_id','left');
$this->db->join('coupon_estimate','t_estimate.estimate_id = coupon_estimate.estimate_id','left');
if (is_array($where_arr)) {
$this->db->where($where_arr);
}

/* if ($is_for_count == false) {
$this->db->limit( $limit,$start);
} */
$this->db->group_by('estimate_id','desc');
$this->db->order_by('estimate_id','desc');


$result = $this->db->get();
$data = $result->result();

return $data;

//-----------------------------------------------
/* 
// original code: 
//-----------------------------------------------
$this->db->select('t_estimate.*,first_name,last_name,customers.email,program_name,program_price,yard_square_feet,property_address,property_tbl.property_status,difficulty_level,user_first_name,user_last_name,program_job_assign.job_id,jobs.job_name');

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

$this->db->group_by('estimate_id','desc');
$this->db->order_by('estimate_id','desc');
$result = $this->db->get();
$data = $result->result();
return  $data;
*/
//-----------------------------------------------
    }
  

    public function getAllJoinedPrograms($where_arr = '') {
        $this->db->select('estimate_programs.*, programs.program_name');
        $this->db->from(self::EST_PRGMS);
        $this->db->join('programs','programs.program_id = estimate_programs.program_id','inner');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        //$this->db->group_by('estimate_programs.estimate_id');
        $result = $this->db->get();


        $data = $result->result();
      //   die(print_r($this->db->last_query()));
        return $data;
    }


    //moved from other estimate model:

    public function getAllNotAcceptedEstimateIdsByCustomer($where_arr) {
        $this->db->select("estimate_id");
        $this->db->from('t_estimate');
        $this->db->where('customer_id',$where_arr["customer_id"]);
        $this->db->where_in('status',array('0', '1', '2'));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getAllJoinedProgramsForAllEstimates($where_arr = '') {
        $this->db->select('estimate_programs.*, programs.program_name');
        $this->db->from(self::EST_PRGMS);
        $this->db->join('programs','programs.program_id = estimate_programs.program_id','inner');
        $this->db->where('estimate_programs.ad_hoc', 0);
        if (is_array($where_arr)) {
            $this->db->where_in('estimate_id', $where_arr);
        }
        //$this->db->group_by('estimate_programs.estimate_id');
        $result = $this->db->get();

        $data = $result->result();
      //   die(print_r($this->db->last_query()));
        return $data;
    
    }

    public function updateEstimateSignWellID($estimate_id, $signwell_id) {
        $this->db->where('estimate_id', $estimate_id);
        $this->db->update(self::EST, array('signwell_id'=>$signwell_id, 'status'=>1));
        return $a = $this->db->affected_rows();
    }
    

    public function CreateEstimatePrograms($program_list=0, $estimate_id=0, $job_list=0) {
        //delete any previous data for same estimate id
        //$this->db->delete(self::EST_PRGMS, ['estimate_id' => $estimate_id]);
        $program_list = json_decode(json_encode($program_list), true);
        if(is_array($program_list) && !empty($program_list) && $estimate_id){
            foreach ($program_list as $program_id=>$ad_hoc) {
                $query = $this->db->insert(self::EST_PRGMS, ['estimate_id' => $estimate_id, 'program_id' => $program_id, 'ad_hoc' => $ad_hoc, 'service_id' => (isset($job_list[$program_id])?$job_list[$program_id]:0)]);
            }
           return true; 
        } else {
           return false;
        }
    }

    
}
 