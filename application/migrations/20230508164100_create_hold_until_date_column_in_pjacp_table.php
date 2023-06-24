<?php
/**
 * @author
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_Hold_Until_Date_Column_In_Pjacp_Table
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_Hold_Until_Date_Column_In_Pjacp_Table extends CI_Migration {
	protected $table = 'program_job_assigned_customer_property';
	public function up()
	{
		$hold_until_date = array(
			'hold_until_date' => [
                'type'    => 'DATE',
			],
		);

		$this->dbforge->add_column($this->table, $hold_until_date);
	}

	public function down()
	{

	}
}
