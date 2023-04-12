<?php
/**
 * @author
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_update_table_tcompany_and_users
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Update_table_company_and_users extends CI_Migration {


	protected $user_table = 'users';
	protected $company_table = 't_company';


	public function up()
	{
		$user_field = array(
			'deleted_at' => [
				'type' => 'DATETIME',
				'after' => 'updated_at',
                'null' => true
			],
		);
		$this->dbforge->add_column($this->user_table, $user_field);

		$company_field = array(
			'deleted_at' => [
				'type' => 'DATETIME',
                'after' => 'updated_at',
                'null' => true
			],
		);
		$this->dbforge->add_column($this->company_table, $company_field);
	}


	public function down()
	{
		if ($this->db->table_exists($this->user_table))
		{
			$this->dbforge->drop_column($this->user_table, 'deleted_at');
		}
		if ($this->db->table_exists($this->company_table))
		{
			$this->dbforge->drop_column($this->company_table, 'deleted_at');
		}
	}

}
