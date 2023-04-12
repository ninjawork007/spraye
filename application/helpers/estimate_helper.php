<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 

  function GetOneEstimateDetails($where_arr){
 
        $CI =& get_instance();


         $CI->db->select('*');

        
        $CI->db->from('t_estimate');

        if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }                    

        $result = $CI->db->get();

        $data = $result->row();
        return $data;
  }



 
  function GetOneEstimatAllJobPrice($where_arr){
 
        $CI =& get_instance();


         $CI->db->select('*');

        
        $CI->db->from('t_estimate_price_override');

        if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }                    

        $CI->db->join('jobs','jobs.job_id = t_estimate_price_override.job_id','inner');
       
        $result = $CI->db->get();

        $data = $result->result_array();
        return $data;
  }



  function GetOneEstimateJobPriceOverride($where_arr){
 
        $CI =& get_instance();


        $CI->db->select('*');

        
        $CI->db->from('t_estimate_price_override');

        if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }                    
       
        $result = $CI->db->get();

        $data = $result->row();
        return $data;
  }


  function getEstimateAmount($where_arr=''){

    $CI =& get_instance();
        $CI->db->select('t_estimate_price_override.*,job_price,yard_square_feet,t_estimate.property_id,t_estimate.program_id,difficulty_level,base_fee_override,min_fee_override');

    
    $CI->db->from('t_estimate');

    if (is_array($where_arr)) {
        $CI->db->where($where_arr);
    }                    

    $CI->db->join('t_estimate_price_override','t_estimate_price_override.estimate_id = t_estimate.estimate_id','inner');
    $CI->db->join('jobs','jobs.job_id = t_estimate_price_override.job_id','inner');

    $CI->db->join('property_tbl','property_tbl.property_id = t_estimate.property_id','inner');
    
    $result = $CI->db->get();

    $data = $result->result_array();
    
    if ($data) {
    return $data;   
    } else {
    
        return false;
    
    }
  }

  function getOnePriceOverrideProgramProperty($where_arr = '') {
           
        $CI =& get_instance();


        $CI->db->select('*');
        
        $CI->db->from('property_program_assign');
        if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }

        $result = $CI->db->get();

        $data = $result->row();
        return $data;
    }



 function formatPhoneNum_old($phone){

  $phone = preg_replace("/[^0-9]*/",'',$phone);
  if(strlen($phone) != 10) return(false);
  $sArea = substr($phone,0,3);
  $sPrefix = substr($phone,3,3);
  $sNumber = substr($phone,6,4);
  $phone = $sArea."-".$sPrefix."-".$sNumber;
  return($phone);
}

function formatPhoneNum($phone){
    return(preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $phone));
}
function formatPhoneNum2($phone){
    return(preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $phone));
}
