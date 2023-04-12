<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Invoice_sales_tax_model extends CI_Model{
      const INTX="invoice_sales_tax";

    public function CreateOneInvoiceSalesTax($post) {
        $query = $this->db->insert(self::INTX, $post);
        return $this->db->insert_id();
    }

    public function getAllInvoiceSalesTax($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from(self::INTX);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }
    public function getAllInvoiceSalesTaxWhereIn($search_column, $where_arr='') {
           
        $this->db->select('*');
        
        $this->db->from(self::INTX);

        // if (is_array($where_arr)) {
            $this->db->where_in($search_column, $where_arr);
        // }
        

        $result = $this->db->get();

        $data = $result->result_array();
        return $data;
    }

    // will mostly pull where all plus if theres a tech_job_assign match
    public function getAllInvoiceSalesTaxDetails($where = '', $addition_where = '') {

        // getting sales tax & order total details from invoices where the associated job was assigned and is completed
        // 3 test cases to connect invoices to tech_job_assign:
        // 1. via invoice_id, via 4-id match, via pull from program_job_assign to get all job_ids and match
        // tried in that order

        // too complex of a query to justify putting into code igniter format
        $insertQuery = <<<EOD
        SELECT sum(tax_amount) as total_tax, sum(cost) as total_cost, tax_name, tax_value, GREATEST(IFNULL(greatest_date, one_real_date), IFNULL(one_real_date, greatest_date)) as real_date FROM (

            SELECT tax_amount as total_tax, cost as total_cost, (CASE 
            WHEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) > max(payment_created) THEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date))
            WHEN max(payment_created) > max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) THEN max(payment_created)
            END ) as one_real_date, IFNULL(complete_one_csv, IFNULL(tja2.is_complete, GROUP_CONCAT(DISTINCT tja3.is_complete ORDER BY tja3.is_complete SEPARATOR ','))) AS is_complete, GROUP_CONCAT(tja3.job_id) AS job_csv, invoice_sales_tax.*, complete_one_csv, tja2.is_complete as is_complete_two, GROUP_CONCAT(DISTINCT tja3.is_complete ORDER BY tja3.is_complete SEPARATOR ',') as complete_three_csv, IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)) as greatest_date, cost
            
                    FROM invoice_sales_tax
                    INNER JOIN invoice_tbl ON invoice_tbl.invoice_id = invoice_sales_tax.invoice_id
                    
                    LEFT JOIN (SELECT *, GROUP_CONCAT(DISTINCT is_complete ORDER BY is_complete) AS complete_one_csv FROM technician_job_assign GROUP BY invoice_id) AS tja1 ON (invoice_tbl.invoice_id = tja1.invoice_id)
                
                    LEFT JOIN technician_job_assign AS tja2 ON (tja2.customer_id = invoice_tbl.customer_id AND tja2.program_id = invoice_tbl.program_id AND tja2.property_id = invoice_tbl.property_id AND invoice_tbl.job_id = tja2.job_id)
                    
                    LEFT JOIN program_job_assign ON program_job_assign.program_id = invoice_tbl.program_id
                    
                    LEFT JOIN technician_job_assign AS tja3 ON (tja3.customer_id = invoice_tbl.customer_id AND tja3.program_id = invoice_tbl.program_id AND tja3.property_id = invoice_tbl.property_id AND program_job_assign.job_id = tja3.job_id)
                    
                    $where

                    $addition_where
                    
                    GROUP BY invoice_tbl.invoice_id
                    
                    ORDER BY `invoice_sales_tax`.`invoice_id` DESC
        ) full_tbl

        WHERE is_complete = 1
        GROUP BY tax_name
EOD;

        $result = $this->db->query($insertQuery);
        $data = $result->result();
        return $data;

        // GREATEST(IFNULL(IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date)), (CASE 
        //     WHEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) > max(payment_created) THEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date))
        //     WHEN max(payment_created) > max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) THEN max(payment_created)
        //     END )), IFNULL((CASE 
        //     WHEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) > max(payment_created) THEN max(IFNULL(tja1.job_completed_date, tja2.job_completed_date))
        //     WHEN max(payment_created) > max(IFNULL(tja1.job_completed_date, tja2.job_completed_date)) THEN max(payment_created)
        //     END ), IFNULL(tja1.job_completed_date, IFNULL(tja2.job_completed_date, tja3.job_completed_date))))

        // GREATEST(
        //     IFNULL(MAX(tja1.job_completed_date), IFNULL(MAX(tja2.job_completed_date), MAX(tja3.job_completed_date))),
        //     IFNULL(MAX(tja2.job_completed_date), IFNULL(MAX(tja1.job_completed_date), MAX(tja3.job_completed_date))),
        //     IFNULL(MAX(tja3.job_completed_date), IFNULL(MAX(tja2.job_completed_date), MAX(tja1.job_completed_date))),
        //     payment_created
        // ) as greatest_date_here

    }


        
    public function deleteInvoiceSalesTax($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::INTX);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function matchInvTechByInvId($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('invoice_tbl');
        $this->db->join('technician_job_assign', 'invoice_tbl.invoice_id = technician_job_assign.invoice_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function matchInvTechByAllFour($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('invoice_tbl');
        $this->db->join('technician_job_assign', 'technician_job_assign.customer_id = invoice_tbl.customer_id AND technician_job_assign.program_id = invoice_tbl.program_id AND technician_job_assign.property_id = invoice_tbl.property_id AND invoice_tbl.job_id = technician_job_assign.job_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
    public function matchInvTechByTbl($where_arr = '') {
           
        $this->db->select('*');
        
        $this->db->from('invoice_tbl');
        $this->db->join('program_job_assign', 'program_job_assign.program_id = invoice_tbl.program_id', 'left');
        $this->db->join('technician_job_assign', 'technician_job_assign.customer_id = invoice_tbl.customer_id AND technician_job_assign.program_id = invoice_tbl.program_id AND technician_job_assign.property_id = invoice_tbl.property_id AND program_job_assign.job_id = technician_job_assign.job_id', 'inner');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        
        $this->db->order_by('invoice_tbl.invoice_id','asc');
        $result = $this->db->get();

        $data = $result->result();
        return $data;
    }
}
 
