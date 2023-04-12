<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 

  function getHold_Customer_Couts($value=''){

    $CI =& get_instance();
    $company_id = $CI->session->userdata['company_id'];          
    $query = $CI->db->query("SELECT count(`customer_id`) as count FROM `customers` WHERE `company_id`=$company_id and `customer_status`=2");
    
    $result =   $query->row();
    return $result->count;
  }
 
   

