<?php



class Logs_model extends CI_Model
{
    const GLOG = "t_logs"; // creation pending
    const ELOG = "t_log_email"; // creation pending
    const PRTONSCREEN = 1;
    const SAVEONCSV = 0;
    const SAVEONDB = 1;

    public function saveEmailLog($process_name = '',$company_id = '',$customer_id = '', $email = '', $secondary_email = '', $subject = '', $body = '', $status = '', $resp = '', $extra = '' )
    {
        if (self::SAVEONDB == 1){
            $post = array(
                'process_name' => $process_name,
                'company_id' => $company_id,
                'customer_id' => $customer_id,
                'email' => $email,
                'secondary_email' => $secondary_email,
                'resp'=> $resp,
                'status'=> $status,
                'extra'=> $extra);
             return $this->createEmailLog($post);
        }
    }

    public function createEmailLog($post) {
        //die(print_r($post));
        $this->db->insert(self::ELOG, $post);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    public function getOneEmailLog($where_arr = '') {
        $this->db->select('*');
        $this->db->from(self::ELOG);
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }
}