<?php
/**
 * @author
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_Notify_Column_In_E_Notes_Table
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_Notify_Column_In_E_Notes_Table extends CI_Migration {

	protected $notes_table = 'e_notes';


	public function up()
	{
		$notify_me_field = array(
			'notify_me' => [
                'type'    => 'TINYINT(1)',
                'default' => 1,
                'after'   => 'note_user_id'
			],
		);

		$is_enable_notifications_field = array(
			'is_enable_notifications' => [
                'type'    => 'TINYINT(1)',
                'default' => 0
			],
		);

		$notification_to_field = array(
			'notification_to' => [
                'type'    => 'TEXT'
			],
		);

		$this->dbforge->add_column($this->notes_table, $notify_me_field);
		$this->dbforge->add_column($this->notes_table, $is_enable_notifications_field);
		$this->dbforge->add_column($this->notes_table, $notification_to_field);
	}

	public function down()
	{

	}
}
