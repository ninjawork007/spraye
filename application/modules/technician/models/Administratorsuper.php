<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Administratorsuper extends CI_Model{
      const ADMINTBL="t_superadmin";
    
      public function getOneDefaultEmailArray(){
            return $this->db->get(self::ADMINTBL)->row_array();
      }

}
 