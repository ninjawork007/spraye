<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_product_model extends CI_Model{
    const PMTBL="products";

    public function insert_product($post) {
        $query = $this->db->insert(self::PMTBL, $post);
        return $this->db->insert_id();
    }

    public function insertActiveIngredient($post) {
        $query = $this->db->insert("product_active_ingredient", $post);
        return $this->db->insert_id();
    }

    public function assignJobs($post) {
        $this->db->insert('job_product_assign', $post);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    public function get_all_product($where_arr = '') {

        $this->db->select('*');

        $this->db->from(self::PMTBL);

        //$this->db->join('jobs', "jobs.job_id = products.assign_job", "inner");


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        }
        else {
            return $result->num_rows();
        }
    }


    public function getAllIngredient($where_arr = '') {

        $this->db->select('*');

        $this->db->from("product_active_ingredient");

        //$this->db->join('jobs', "jobs.job_id = products.assign_job", "inner");


        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        }
        else {
            return $result->num_rows();
        }
    }





    public function updateAdminTbl($product_id, $post_data) {

        $this->db->where('product_id',$product_id);
        $this->db->update('products', $post_data);
        return $a = $this->db->affected_rows();

    }


    public function deleteProduct($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }

        $this->db->delete(self::PMTBL);

        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getJobList($where_arr=''){

        $this->db->select('job_id,job_name');

        $this->db->from('jobs');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data;
        }
        else {
            return $result->num_rows();
        }
    }

    public function getAssignJobs($where_arr = '') {

        $this->db->select('*');

        $this->db->from('job_product_assign');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('jobs','jobs.job_id=job_product_assign.job_id','inner');

        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }





    public function getAssignProducts($where_arr = '') {

        $this->db->select('*');

        $this->db->from('job_product_assign');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->join('products','products.product_id=job_product_assign.product_id','inner');

        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAssignProductsByInvoice($invoice_id) {

        $this->db->select('*');

        $this->db->from('property_program_job_invoice');

        $this->db->where('invoice_id', $invoice_id);

        $this->db->join('job_product_assign','property_program_job_invoice.job_id=job_product_assign.job_id','inner');
        $this->db->join('products','products.product_id=job_product_assign.product_id','inner');

        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function getAssignProductsNyJobs($where_arr_in) {

        $this->db->select('*');

        $this->db->from('job_product_assign');

        if (is_array($where_arr_in)) {
            $this->db->where_in('job_id',$where_arr_in);
        }
        $this->db->join('products','products.product_id=job_product_assign.product_id','inner');

        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }




    public function checkProduct($param){

        $this->db->where('user_id',$param['user_id']);
        $this->db->where('product_name',$param['product_name']);
//        $this->db->where('epa_reg_nunber',$param['epa_reg_nunber']);
        // $this->db->where('product_cost',$param['product_cost']);
        // $this->db->where('formulation',$param['formulation']);
        // $this->db->where('application_rate',$param['application_rate']);
        // $this->db->where('temperature_information',$param['temperature_information']);

        $result=$this->db->get('products');

        if ($result->num_rows() > 0) {
            $data = $result->result();
            return "true";
        }
        else {
            return "false";
        }
    }

    public function getProductDetail($productID){

        $this->db->where('product_id',$productID);
        $q=$this->db->get('products');

        if($q->num_rows()>0)
        {
            return $q->result_array()[0];
        }

    }

    public function getSelectedJobs($product_id){

        $this->db->select('job_id');

        $this->db->from('job_product_assign');
        $this->db->where('product_id',$product_id);


        $result = $this->db->get();

        $data = $result->result();
        return $data;

    }

    public function deleteAssignJobs($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }

        $this->db->delete('job_product_assign');

        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


    public function deleteActiveIngredient($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }

        $this->db->delete('product_active_ingredient');

        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getOneProduct($where_arr = '') {

        $this->db->select('*');

        $this->db->from(self::PMTBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }



}

