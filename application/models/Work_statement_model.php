<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Work_statement_model extends CI_Model{   
           const STATETBL="completed_work_statements";



    public function getOneStatement($where_arr='') {       
        $this->db->select('*');  
        $this->db->from('completed_work_statements');
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }

          $this->db->order_by('work_statement_id','desc');
          $result = $this->db->get();

         $data = $result->row();
         return $data;    
    }

    public function getOneWorkStatement($where_arr='') {        
        $this->db->select('*,completed_work_statements.company_id');  
        $this->db->from('completed_work_statements');
        $this->db->join('customers','customers.customer_id = completed_work_statements.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = completed_work_statements.property_id','inner');
        $this->db->join('programs','programs.program_id = completed_work_statements.program_id','inner');     
        $this->db->join('jobs','jobs.job_id = completed_work_statements.job_id','left');     
             
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }
           $result = $this->db->get();
           

        $data = $result->row();        
        return $data;    
    }

    public function getOneAssociatedStatement($where_arr='') {       
        $this->db->select('*');  
        $this->db->from('completed_work_statements');
        $this->db->join('customers','customers.customer_id = completed_work_statements.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = completed_work_statements.property_id','inner');          
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }

            $this->db->order_by('work_statement_id','desc');
           $result = $this->db->get();

        $data = $result->row();
        return $data;    
    }






    public function createOneStatement($post) {
          $query = $this->db->insert(self::STATETBL, $post);
        return $this->db->insert_id();
    }

    public function getOneStatementComplete($where_arr){
            
        $this->db->select('*');  
        $this->db->from(self::STATETBL);
        $this->db->join('customers','customers.customer_id = completed_work_statements.customer_id ','inner');
        $this->db->join('property_tbl','property_tbl.property_id = completed_work_statements.property_id','inner');     
           
            if (is_array($where_arr)) {
                  $this->db->where($where_arr);
            }
            $this->db->order_by('work_statement_id','desc');
           $result = $this->db->get();

        $data = $result->row();
        return $data;    

    }


       public function updateStatement($wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update(self::STATETBL, $updatearr);
        return $a = $this->db->affected_rows();
        
    }

	    public function deleteStatement($wherearr) {
        $updatearr  = array(
            'is_archived' => 1
        );
        $this->db->where($wherearr);
        $this->db->update(self::STATETBL, $updatearr);
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getStatementMiniListFromHashString($where_arr) {
        $this->db->select('work_statement_id,company_id');
        $this->db->from('completed_work_statements');
        $this->db->where($where_arr);
        $result = $this->db->get();
        $data = $result->result_array();
        if(count($data) == 0) {
            $this->db->select('work_statement_id,company_id');
            $this->db->from('statement_hash_tbl');
            $this->db->where($where_arr);
            $result = $this->db->get();
            $data = $result->result_array();
            if(count($data) == 0) {
                return array(
                    "statement_ids" => '',
                    "company_id" => ''
                );
            } else {
                return array(
                    "statement_ids" => implode(',', array_column($data, 'work_statement_id')),
                    "company_id" => $data[0]["company_id"]
                );
            }            
        }        
        return array(
            "statement_ids" => implode(',',array_column($data, 'work_statement_id')),
            "company_id" => $data[0]["company_id"]
        );  
    }


}
 
