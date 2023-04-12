<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
  function quickBookDebugReport($post){
    
    $CI =& get_instance();
    $query = $CI->db->insert('quickbook_debug', $post);
    return $CI->db->insert_id();

  }

