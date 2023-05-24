<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_coupon_model extends CI_Model{
      const SRATBL="coupon";

   public function CreateOneCoupon($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }

    public function getOneCoupon($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::SRATBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllCoupon($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::SRATBL);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('coupon_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

    public function updateCoupon($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::SRATBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

    public function deleteCoupon($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::SRATBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    // COUPON_CUSTOMER
    public function CreateOneCouponCustomer($post) {
        $query = $this->db->insert("coupon_customer", $post);
        return $this->db->insert_id();
    }

    public function getAllCouponCustomer($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_customer");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('coupon_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function getAllCouponCustomerCouponDetails($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_customer");
        $this->db->join("coupon", "coupon_customer.coupon_id = coupon.coupon_id");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $this->db->order_by('coupon_customer.coupon_id','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function getOneCouponCustomer($where_arr = '') {
        $this->db->select('*');
        
        $this->db->from("coupon_customer");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
    public function deleteAllCouponCustomer($customerID) {

        $this->db->where('customer_id',$customerID);
        
        $this->db->delete("coupon_customer");
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }
    public function getCouponCustomers($where_arr = '') {
		$this->db->select('coupon_id');
		$this->db->from('coupon_customer');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
    public function getCouponSelectedCustomers($where_arr = '') {
        $this->db->select('*');
        $this->db->from('coupon_customer cc');
        $this->db->join('coupon c', 'c.coupon_id = cc.coupon_id', 'left');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    // COUPON_JOB
    public function getAllCouponJob($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_job");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $this->db->order_by('coupon_amount_calculation','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function getOneCouponJob($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('coupon_job');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
    public function getCouponJob($where_arr = '') {
		$this->db->select('coupon_id');
		$this->db->from('coupon_job');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
    public function CreateOneCouponJob($post) {
        $query = $this->db->insert("coupon_job", $post);
        return $this->db->insert_id();
    }
    public function DeleteCouponJob($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete("coupon_job");
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }


    // COUPON_INVOICE
    public function getAllCouponInvoice($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_invoice");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('coupon_type','desc');
        $this->db->order_by('coupon_amount_calculation','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    
    public function getOneCouponInvoice($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_invoice");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }
    public function getCouponInvoiceIDs($where_arr = '') {
		$this->db->select('coupon_id');
		$this->db->from('coupon_invoice');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
    public function CreateOneCouponInvoice($post) {
        $query = $this->db->insert("coupon_invoice", $post);
        return $this->db->insert_id();
    }
    public function updateCouponInvoice($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update("coupon_invoice", $updatearr);
        return $a = $this->db->affected_rows();
        
    }
    public function DeleteCouponInvoice($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete("coupon_invoice");
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    // COUPON_ESTIMATE
    public function CreateOneCouponEstimate($post) {
        $query = $this->db->insert("coupon_estimate", $post);
        return $this->db->insert_id();
    }
    public function getCouponEstimateIDs($where_arr = '') {
		$this->db->select('coupon_id');
		$this->db->from('coupon_estimate');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}
    public function getOneCouponEstimate($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_estimate");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getAllCouponEstimate($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from("coupon_estimate");

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('coupon_type','desc');
        $this->db->order_by('coupon_amount_calculation','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function DeleteCouponEstimate($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete("coupon_estimate");
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function UpdateCouponEstimate($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update("coupon_estimate", $updatearr);
        return $a = $this->db->affected_rows();
    }

    public function getAllCouponInvoiceWhereIn($search_column, $where_arr='') {
           
        $this->db->select('coupon_invoice.*, invoice_tbl.cost, invoice_tbl.partial_payment');
        
        $this->db->from("coupon_invoice");
        $this->db->join('invoice_tbl','invoice_tbl.invoice_id = coupon_invoice.invoice_id','left');
        // if (is_array($where_arr)) {
             $this->db->where_in($search_column, $where_arr);
        // }
        
        $this->db->order_by('coupon_type','desc');
        $this->db->order_by('coupon_amount_calculation','desc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }

}
 
