<?php
/**
 * @author
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_Cron_Jobs_Table
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_Cron_Jobs_Table extends CI_Migration {

	protected $table = 'cron_jobs';


	public function up()
	{

        $fields = array(
            'id'         => [
                'type'           => 'INT(11)',
                'auto_increment' => TRUE,
                'unsigned'       => TRUE,
            ],
            'job'      => [
                'type'   => 'VARCHAR(255)',
                'unique' => TRUE,
            ],
            'schedule'   => [
                'type' => 'VARCHAR(255)',
            ],
            'last_run'  => [
                'type' => 'VARCHAR(255)',
            ]
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->table, TRUE);
	}

	public function down()
	{

	}
}
