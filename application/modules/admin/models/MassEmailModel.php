<?php
class MassEmailModel extends CI_Model{
    public function saveMassEmailData($post) {
        $query = $this->db->insert("mass_email", $post);
        return $this->db->insert_id();
    }

    public function getMassEmailData($where) {
        $this->db->select('*');
        $this->db->from("mass_email");
        $this->db->where($where);
        $this->db->order_by('created_at','desc');
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function updateEmailData($Param, $where){
        $this->db->where($where);
        $this->db->update("mass_email", $Param);
        return $this->db->affected_rows();
    }

    public function deleteData($where){
        $this->db->where($where);
        $this->db->delete("mass_email");
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }
}