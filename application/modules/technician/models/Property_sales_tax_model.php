<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Property_sales_tax_model extends CI_Model{
      const PRTX="property_sales_tax";

   public function CreateOnePropertySalesTax($post) {
        $query = $this->db->insert(self::PRTX, $post);
        return $this->db->insert_id();
    }

    public function getAllPropertySalesTax($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::PRTX);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->join('sale_tax_area','sale_tax_area.sale_tax_area_id = property_sales_tax.sale_tax_area_id','inner');
        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }

        
    public function deletePropertySalesTax($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::PRTX);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}
 