<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Save_sales_pipeline_filter_model extends CI_Model{

    public function updateSaveReport($where, $post_data) {
        $this->db->where($where);
        return $this->db->update('save_sales_pipeline_filter',$post_data);
    }

    public function getTechSavedReport($where){
        $this->db->where($where);
        $q=$this->db->get('save_sales_pipeline_filter');
        return $q->row_array();  
    }

    public function createSaveReport($post) {
        $query = $this->db->insert("save_sales_pipeline_filter", $post);
        return $this->db->insert_id();
    }

}