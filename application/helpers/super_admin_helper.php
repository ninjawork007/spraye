<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


  
  function getSubScriptionCount($where){

    $CI =& get_instance();
    $result = $CI->db->select("count(*) as  count ")->where($where)->get('t_company_subscription')->row();

    return $result->count;  
  } 
function getAllCompanyCount(){

    $CI =& get_instance();
    $result = $CI->db->select("count(*) as  count ")->get('t_company')->row();

    return $result->count;  
  } 


  function getMonthlyRevenue(){

    $CI =& get_instance();

    $where = array(
      'YEAR(subscription_created_at)' => DATE("Y"),
      'MONTH(subscription_created_at)' => DATE("m"),

    );
    $result = $CI->db->select("sum(is_total_price) as total_revenue")->where($where)->get('t_company_subscription')->row();

    return $result->total_revenue;  
  }

  
 