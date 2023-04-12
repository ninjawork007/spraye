<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HooksModel
 *
 * @author satanand
 */
class HooksModel extends CI_Model{
    const USERAUTH="tbl_user_auth";
    const USERTBL="tbl_user";
    const SMILEYTBL="tbl_smiley";
    const PUSHNOTITBL="tbl_push_notification";

    public function __construct() {
        parent::__construct();
    }
    
    public function getOneUserDetails($where) {
        return $this->db->where($where)->get(self::USERTBL)->row();
    }
    public function getAllUserAuth($where=array(1=>1)) {
        return $this->db->where($where)->order_by('auth_id', 'DESC')->get(self::USERAUTH)->result();
    }
    
    public function getOneSmilyData($where=array(1=>1)) {
         return $this->db->where($where)->get(self::SMILEYTBL)->row();
    }
    public function getAllSmilyData($where=array(1=>1)) {
         return $this->db->where($where)->get(self::SMILEYTBL)->num_rows();
    }
    
    public function saveNotification($param) {
        $this->db->insert(self::PUSHNOTITBL,$param);
        return $this->db->insert_id();
    }
    
  
}
