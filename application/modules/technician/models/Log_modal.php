<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Log_modal extends CI_Model{
      const LOG="logs";

   public function CreateOneLog($post) {
        $query = $this->db->insert(self::LOG, $post);
        return $this->db->insert_id();
    }


 

}
 