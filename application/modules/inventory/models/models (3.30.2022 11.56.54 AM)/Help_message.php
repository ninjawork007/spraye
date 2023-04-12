<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Help_message extends CI_Model{
      const HLPMSG="help_message";

   public function CreateOneHelpMessage($post) {
        $query = $this->db->insert(self::HLPMSG, $post);
        return $this->db->insert_id();
    }

    public function getOneHelpMessage($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::HLPMSG);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllHelpMessage($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::HLPMSG);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    

    public function updateAdminTbl($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::HLPMSG, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    
    public function deleteHelpMessage($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::HLPMSG);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    //  public function getAssignUsers() {
    //        $sql = "select * from `users` where `company_id` = '".$this->session->userdata['company_id']."' and where ( `role_id` = 1 or `role_id` = 4) order by `id` desc ";
           
    //       $data  = $this->db->query($sql)->get()->result();

    //       print_r($data);

        

    //     $this->db->order_by('id','desc');
    //     $result = $this->db->get();

    //     $data = $result->result();
    //     return $data;
    // }

 

}
 