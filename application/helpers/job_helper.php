<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
  function getJobCoutsThisMonth($value=''){

    $CI =& get_instance();
    $company_id = $CI->session->userdata['company_id'];          
    $query = $CI->db->query("SELECT count(*) count FROM `technician_job_assign` WHERE  MONTH(job_assign_date) = ".date('m')." and   YEAR(job_assign_date) = ".date('Y')." and  `company_id` = $company_id and `is_job_mode` = 0 ");
    $result =   $query->row();
    return $result->count;
  
  }
  function getCompleteJobCouts($value=''){

    $CI =& get_instance();
    $company_id = $CI->session->userdata['company_id'];          
    $query = $CI->db->query("SELECT count(*) count FROM `report` WHERE    `company_id` = $company_id and  MONTH(`job_completed_date`) = ".date('m')." and   YEAR(`job_completed_date`) = ".date('Y')."  ");
    $result =   $query->row();
    return $result->count;
  }

    function getThisMonthSalesTax($value=''){

    $CI =& get_instance();
    $company_id = $CI->session->userdata['company_id'];          
    $query = $CI->db->query("SELECT sum(`tax_amount`) as total_tax FROM `invoice_tbl` WHERE  MONTH(`invoice_date`) = ".date('m')." and   YEAR(`invoice_date`) = ".date('Y')." and   `company_id` = $company_id and status = 2 ");
    $result =   $query->row();
    // print_r($result);
    if ($result && $result->total_tax!='') {
        return $result->total_tax;
    } else {
      return 0;
    }
  
  }

