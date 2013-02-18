<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  * jrCron Session & Log Updates
  *
  * @author David A Conway Jr.
  * @desc Added fields for session data, logs, and jobs.
  */
class Migration_Session_log_updates extends Migration {

	public function up() 
	{
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Load DBForge
		$this->load->dbforge();

		// Add Session Column
		$this->dbforge->add_column('jrcron', array(
			'cron_sess' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 100,
				'null'			=> true
			)
		), 'cron_job');

		// Add Session Column
		$this->dbforge->add_column('jrcron', array(
			'cron_result' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 250,
				'null'			=> true
			)
		), 'cron_sess');

		// Add Session Column
		$this->dbforge->add_column('jrcron', array(
			'cron_error' => array(
				'type'			=> 'TINYINT',
				'constraint'	=> 1,
				'null'          => false,
				'default'		=> 0
			)
		), 'cron_result');



		// Create jrCron_Logs Table
		$this->dbforge->add_field('log_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field('log_job VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('log_sess VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('log_msg VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('log_error TINYINT(1) NOT NULL DEFAULT 0');
		$this->dbforge->add_field('export_name VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('created_on DATETIME NOT NULL');
		$this->dbforge->add_field('runtime INT NOT NULL DEFAULT 0');
		$this->dbforge->add_field('deleted TINYINT(1) NOT NULL DEFAULT 0');
		$this->dbforge->add_key('log_id', TRUE);
		$this->dbforge->add_key('export_name', FALSE);
		$this->dbforge->create_table('jrcron_logs');



		// Create jrCron_Jobs Table
		$this->dbforge->add_field('job_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field('job_name VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('job_enabled VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('created_on DATETIME NOT NULL');
		$this->dbforge->add_field('modified_on DATETIME NULL');
		$this->dbforge->add_field('deleted TINYINT(1) NOT NULL DEFAULT 0');
		$this->dbforge->add_key('job_id', TRUE);
		$this->dbforge->add_key('job_name', FALSE);
		$this->dbforge->create_table('jrcron_jobs');



		// Add Manage Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.Manage',
			'description' => 'Manage settings for cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Cron Permissions to Administrator Role
		if(is_array($permissions) && count($permissions)) {
			foreach($permissions as $permission_id) {
				$role_perm = array(
					'role_id' => 1,
					'permission_id' => $permission_id
				);
				$this->db->insert("{$prefix}role_permissions", $role_perm);
			}
		}


		// Remove Edit Cron Settings Permissions
		$this->db->or_where('name', 'jrCron.Settings.Edit');
		$query = $this->db->get("{$prefix}permissions");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->delete("{$prefix}role_permissions", array("permission_id" => $row->permission_id));
			}
		}
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.Edit'));
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Load DBForge
		$this->load->dbforge();

		// Drop Session Column
		$this->dbforge->drop_column('jrcron', 'cron_sess');
		$this->dbforge->drop_column('jrcron', 'cron_result');
		$this->dbforge->drop_column('jrcron', 'cron_error');

		// Drop jrCron_Logs Table
		$this->dbforge->drop_table('jrcron_logs');

		// Drop jrCron_Jobs Table
		$this->dbforge->drop_table('jrcron_jobs');



		// Remove Manage Cron Settings Permissions
		$this->db->or_where('name', 'jrCron.Settings.Manage');
		$query = $this->db->get("{$prefix}permissions");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->delete("{$prefix}role_permissions", array("permission_id" => $row->permission_id));
			}
		}
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.Manage'));

		// Re-add Edit Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.Edit',
			'description' => 'Edit settings for cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Cron Permissions to Administrator Role
		if(is_array($permissions) && count($permissions)) {
			foreach($permissions as $permission_id) {
				$role_perm = array(
					'role_id' => 1,
					'permission_id' => $permission_id
				);
				$this->db->insert("{$prefix}role_permissions", $role_perm);
			}
		}
	}
	
	//--------------------------------------------------------------------
	
}