<?php
/**
 * @author
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_Is_Urgent_Column_In_E_Notes_Table
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_Is_Urgent_Column_In_E_Notes_Table extends CI_Migration {


	protected $notes_table = 'e_notes';


	public function up()
	{
		$is_urgent_field = array(
			'is_urgent' => [
                'type'    => 'TINYINT(1)',
                'default' => 0
			],
		);
		$this->dbforge->add_column($this->notes_table, $is_urgent_field);
	}

	public function down()
	{

	}
}
