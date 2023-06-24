<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class AdminTbl_company_model extends CI_Model
{
    const ST = "t_company";

    public function getOneCompany($where)
    {
        return $this->db->where($where)->get(self::ST)->row();
    }

    public function updateCompany($where, $post_data)
    {
        $this->db->where($where);
        $this->db->update(self::ST, $post_data);
        //die(print_r($this->db->last_query()));
        return $this->db->affected_rows();


    }

    public function getPaymentTerms($where)
    {
        $this->db->select('payment_terms');
        $this->db->from('t_company');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->row();
        return $data;

    }

    public function getOneBasysRequest($where_arr = '')
    {

        $this->db->select('*');

        $this->db->from('t_basys_request');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getOneAdminUser($where_arr = '')
    {
        $this->db->select('*');
        $this->db->from('users');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();

        $data = $result->row();
        return $data;
    }

    public function getOneCompanyEmailArray($where)
    {
        return $this->db->where($where)->get('t_company_email_setting')->row_array();
    }

    public function getOneDefaultEmailArray()
    {
        return $this->db->get('t_superadmin')->row_array();
    }

    public function getAllCustomersToSendMonthlyStatement()
    {
//        $query = $this->db->query("call getSuscriptedCompaniesToMonthlyInvoiceStatement()");
//        //$result = $query->result();
//        $query->next_result(); //Needed because stored proceedure was excecuted.
//        $query->free_result();//Needed because stored proceedure was excecuted.

        $query = $this->db->query("SELECT customers.customer_id, customers.email
                                    FROM `invoice_tbl`
                                        INNER JOIN `customers` ON `customers`.`customer_id` = `invoice_tbl`.`customer_id`
                                        LEFT JOIN `programs` ON `programs`.`program_id` = `invoice_tbl`.`program_id`
                                        LEFT JOIN `refund_invoice_logs` ON `refund_invoice_logs`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                        LEFT JOIN `technician_job_assign` ON `technician_job_assign`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                        LEFT JOIN ( SELECT *, (LENGTH(csv_report_ids) - LENGTH(REPLACE(csv_report_ids, ',', '')) + 1) AS csv_item_num
                                                    FROM ( SELECT *, GROUP_CONCAT(report_id) AS csv_report_ids, COUNT(invoice_id) AS invoice_id_count
                                                           FROM property_program_job_invoice GROUP BY invoice_id
                                                                                             ) AS T ) AS property_program_job_invoice2 ON `property_program_job_invoice2`.`invoice_id` = `invoice_tbl`.`invoice_id`
                                    WHERE `invoice_tbl`.`company_id` in (select t.company_id from t_company t
                                                                            where t.send_monthly_invoice_statement = 1) AND `is_archived` =0
                                      AND ! ( `programs`.`program_price` = 2 AND `technician_job_assign`.`is_complete` != 1 AND `technician_job_assign`.`is_complete` IS NOT NULL )
                                      AND ! ( `programs`.`program_price` = 2 AND `technician_job_assign`.`invoice_id` IS NULL AND `invoice_tbl`.`report_id` =0 AND `property_program_job_invoice2`.`report_id` IS NULL )
                                    GROUP BY customers.customer_id
                                    ORDER BY customers.customer_id DESC;");
        $result = $query->result();
        //die(print_r($result));
        if ($result !== NULL) {
            //$result->next_result(); //Needed because stored proceedure was excecuted.
            //$result->free_result();//Needed because stored proceedure was excecuted.
            return $result;
        }
        return FALSE;

    }
}
