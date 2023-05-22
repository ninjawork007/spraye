<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_Table_Notes_Default_Filter
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_Table_Notes_Default_Filter extends CI_Migration {
	protected $table = 'notes_default_filters';

	public function up()
	{
		$fields = array(
			'id'          => [
				'type'           => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned'       => TRUE,
			],
			'user_id'     => [
                'type' => 'INT(11)',
			],
			'filter_json' => [
				'type' => 'TEXT',
			]
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->table, TRUE);
	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
