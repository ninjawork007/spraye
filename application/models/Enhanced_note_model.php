<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Enhanced_note_model extends CI_Model 
{
	const ENOTES="e_notes";
	const NTYPES="e_note_types";
   const NFILES="e_note_files";
   const ECMMTS="e_note_comments";

   public function addNote($data)
   {
      $query = $this->db->insert(self::ENOTES, $data);
      return $this->db->insert_id();
   }   

   public function getCompanyNotes($companyId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_company_id',$companyId);
      return $this->db->get()->results();
   }

   public function getNote($noteId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_id',$companyId);
      return $this->db->get()->results();
   }

   public function getUserNotes($userId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_user_id',$userId);
      return $this->db->get()->results();
   }

   public function getCustomerNotes($customerId) 
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_customer_id',$customerId);
      return $this->db->get()->results();
   }

   public function getPropertyNotes($propertyId)
   {
      $this->db->from(self::ENOTES);
      $this->db->where('note_property_id',$propertyId);
      return $this->db->get()->results();
   }

   public function getNotesWhere($where)
   {
      if(is_array($where))
      {
         $this->db->from(self::ENOTES);
         $this->db->where($where);
         return $this->db->get()->results();
      } else
      {
         return array(
            'message' => 'Warning: You must provide an array of column "where" arguments.'
         );
      }
   }

   public function updateNoteData($data, $where)
   {
      $this->db->where($where);
      return $this->db->update(self::ENOTES, $data);
   }   

   public function closeNoteStatus($noteId)
   {
      $this->db->set('note_status', 2);
      $this->db->where('note_id', $noteId);
      $result = $this->db->update(self::ENOTES);
      return $result;      
   }

   public function deleteNote($noteId) 
   {
      $this->db->where('note_id', $noteId);
      $this->db->delete(array('e_note_comments','e_notes'));
      $result = $this->db->affected_rows();
      return $result;
   }

   public function getNoteTypes($companyId)
   {
      $this->db->from(self::NTYPES);
      $this->db->where('note_company_id',$companyId);
      return $this->db->get()->results();
   }

   public function createNoteType($data)
   {
      $query = $this->db->insert(self::NTYPES, $data);
      return $this->db->insert_id();
   }


   public function noteAddFiles($data)
   {
      $result = $this->db->insert_batch('enhanced_note_files', $data);
      return $result ? true : false;
   }

   public function addNoteComment($data)
   {
      $query = $this->db->insert(self::ECMMTS, $data);
      return $this->db->insert_id();
   }

   public function getNoteComments($noteId)
   {
      $where = array(
         'note_id' => $noteId,
      );
      $this->db->from(self::ECMMTS);
      $this->db->where($where);
      $this->db->join('users', 'users.id=e_note_comments.comment_user_id');
      $result = $this->db->get();
      $data = $result->result();
      return $data;
   }   

}