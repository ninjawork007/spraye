<?php

class Notes_default_filters_model extends CI_Model
{
    const TABLE = "notes_default_filters";

    public function get_notes_default_filter_by_user_id($user_id)
    {
        return $this->db->where('user_id', $user_id)->get(self::TABLE)->row();
    }

    public function create_notes_default_filter($user_id, $filter) {
        $this->db->insert(self::TABLE, ['user_id' => $user_id, 'filter_json' => $filter]);
        return $this->db->insert_id();
    }

    public function update_notes_default_filter($user_id, $filter) {
        $this->db->where('user_id', $user_id);
        $this->db->update(self::TABLE, ['filter_json' => $filter]);

        return $this->db->affected_rows();
    }
}
 