<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Invoice_model extends CI_Model{   
           const INVTBL="invoice_tbl";
           const SRATBL="payment_invoice_logs";



    public function getOneInvoice($where_arr='') {       
        $this->db->select('*');  
        $this->db->from('invoice_tbl');
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }

          $this->db->order_by('invoice_id','desc');
          $result = $this->db->get();


         
         $data = $result->row();
         
         return $data;    
    }



    public function getOneInvoive($where_arr='') {       
        $this->db->select('*');
        $this->db->from('invoice_tbl');
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','left');
        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','left');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
        $this->db->join('jobs','jobs.job_id = invoice_tbl.job_id','left');     
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }

            $this->db->order_by('invoice_id','desc');
           $result = $this->db->get();

        $data = $result->row();
        // die(print_r($this->db->last_query()));
        return $data;    
    }






    public function createOneInvoice($post) {
          $query = $this->db->insert(self::INVTBL, $post);
        return $this->db->insert_id();
    }

    public function getOneInvoiceComplete($where_arr){
            
        $this->db->select('*');  
        $this->db->from(self::INVTBL);
        $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = invoice_tbl.property_id','inner');     
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }
            $this->db->order_by('invoice_id','desc');
           $result = $this->db->get();

        $data = $result->row();
        return $data;    

    }


       public function updateInvoive($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::INVTBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }       
    public function updateInvovice($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::INVTBL, $updatearr);
        return $a = $this->db->affected_rows();

    }
	    public function deleteInvoice($wherearr) {
        $updatearr  = array(
            'is_archived' => 1
        );
        $this->db->where($wherearr);
        $this->db->update(self::INVTBL, $updatearr);
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function addCreditPayment($customer_id=0, $credit_amount=0, $payment_type="check"){
        
        if($customer_id){   
         $row = $this->db->select('credit_amount')->from('customers')->where(['customer_id' => $customer_id])->get()->row();
         $new_credit_amount = $row->credit_amount + $credit_amount;
         
         $result = $this->db->update('customers',['credit_amount' => $new_credit_amount, 'payment_type' => $payment_type],['customer_id' => $customer_id]);
        }
        return !empty($result) ? $result : false;
    }
    public function createOnePartialPayment($post) {
        $query = $this->db->insert(self::SRATBL, $post);
        return $this->db->insert_id();
    }
    public function getUnpaidInvoices($customer_id){
        $this->db->select('invoice_id as unpaid_invoice, cost, partial_payment as paid_already');

        $this->db->from('invoice_tbl');

        $this->db->where(array('customer_id' => $customer_id, 'status !=' => 0, 'payment_status !=' => 2, 'is_archived' => 0));

        $data = $this->db->get();
        
        $result = $data->result();
        
        // die(print_r($result));

        if(!empty($result)){            
            
            foreach($result as $res){

                $this->db->select('coupon_amount_calculation, coupon_amount');
                $this->db->from('coupon_invoice');
                $this->db->where(array('invoice_id' => $res->unpaid_invoice));
                $coup_data = $this->db->get();
                $coupons = $coup_data->result();

                $this->db->select('tax_value');
                $this->db->from('invoice_sales_tax');
                $this->db->where(array('invoice_id' => $res->unpaid_invoice));
                $tax_data = $this->db->get();
                $taxes = $tax_data->result();

                $tax_value = 0;

                $res->unpaid_amount = $res->cost;

                if(!empty($coupons)){
                    foreach($coupons as $coupon){
                        if($coupon->coupon_amount){
                            if($coupon->coupon_amount_calculation){
                                $coupon_value = $res->unpaid_amount * ($coupon->coupon_amount * .01);
                                $res->unpaid_amount -= $coupon_value; 
                            } else {
                                $coupon_value = $coupon->coupon_amount;
                                $res->unpaid_amount -= $coupon_value; 
                            }
                        }
                    }

                    
                }

                if(!empty($taxes)){
                    foreach($taxes as $tax){
                        $tax_value += $res->unpaid_amount * ($tax->tax_value * .01);
                    }

                    $res->unpaid_amount += $tax_value;
                }

                $res->unpaid_amount -= $res->paid_already;

                if ($res->unpaid_amount <= 0){
                    $res->unpaid_amount = 0;
                }

                $res->unpaid_amount = number_format($res->unpaid_amount, 2, '.', '');
            }
        }
        
        return $result;

    }

    public function getUnpaidInvoiceById($invoice_id){

        $this->db->select('invoice_id as unpaid_invoice, cost, partial_payment as paid_already');

        $this->db->from('invoice_tbl');

        $this->db->where(array('invoice_id' => $invoice_id, 'is_archived' => 0 ));

        $data = $this->db->get();
        
        $result = $data->row();
        
        // die(print_r($result));


        $this->db->select('tax_value');
        $this->db->from('invoice_sales_tax');
        $this->db->where('invoice_id', $result->unpaid_invoice);
        $tax_data = $this->db->get();
        $taxes = $tax_data->result();

        $this->db->select('coupon_amount_calculation, coupon_amount');
        $this->db->from('coupon_invoice');
        $this->db->where('invoice_id', $result->unpaid_invoice);
        $coup_data = $this->db->get();
        $coupons = $coup_data->result();

        $tax_value = 0;
        $result->unpaid_amount = $result->cost;

        

        if(!empty($coupons)){
            foreach($coupons as $coupon){
                if($coupon->coupon_amount){
                    if($coupon->coupon_amount_calculation){
                        $coupon_value = $result->unpaid_amount * ($coupon->coupon_amount * .01);
                        $result->unpaid_amount -= $coupon_value; 
                    } else {
                        $coupon_value = $coupon->coupon_amount;
                        $result->unpaid_amount -= $coupon_value; 
                    }
                }
            }

            
        }
        
        if(!empty($taxes)){
            foreach($taxes as $tax){
                $tax_value += $result->unpaid_amount * ($tax->tax_value * .01);
            }

            $result->unpaid_amount += $tax_value;
        }

        $result->unpaid_amount -= $result->paid_already;

        if ($result->unpaid_amount <= 0){
            $result->unpaid_amount = 0;
        }

        $result->unpaid_amount = number_format($result->unpaid_amount, 2, '.', '');

        
        // die(print_r($result));
        return $result;

    }

    public function adjustCreditPayment($customer_id=0, $credit_amount=0, $payment_type="check"){
        
        if($customer_id){            
         
         $result = $this->db->update('customers',['credit_amount' => $credit_amount, 'payment_type' => $payment_type],['customer_id' => $customer_id]);
        }
        return !empty($result) ? $result : false;
    }


}


