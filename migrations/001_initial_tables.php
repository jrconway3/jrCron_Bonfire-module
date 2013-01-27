<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Initial_tables extends Migration {

	public function up() 
	{
		// Add View Cron Content Permission
		$permissions = array();
		$perms = array(
			'name'        => 'jrCron.Content.View',
			'description' => 'View and download cron exported files.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Delete Cron Content Permission
		$perms = array(
			'name'        => 'jrCron.Content.Delete',
			'description' => 'Delete cron exported files.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add View Cron Reports Permission
		$perms = array(
			'name'        => 'jrCron.Reports.View',
			'description' => 'View reports on currently running cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Delete Cron Reports Permission
		$perms = array(
			'name'        => 'jrCron.Reports.Delete',
			'description' => 'Delete reports on cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add View Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.View',
			'description' => 'View setttings for cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Create Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.Add',
			'description' => 'Add settings for cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Edit Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.Edit',
			'description' => 'Edit settings for cron jobs.'
		);
		$this->db->insert("{$prefix}permissions", $perms);
		$permissions[] = $this->db->insert_id();

		// Add Delete Cron Settings Permission
		$perms = array(
			'name'        => 'jrCron.Settings.Delete',
			'description' => 'Delete settings for cron jobs.'
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


		// Add Run Job Setting
		$settings = array(
			'name'   => 'jrCron.run_job',
			'module' => 'jrCron',
			'value'  => '0'
		);
		$this->db->insert("{$prefix}settings", $settings);


		// Load DBForge
		$this->load->dbforge();

		// Create jrCron Table
		$this->dbforge->add_field('cron_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field('cron_job VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('export_name VARCHAR(255) NOT NULL');
		$this->dbforge->add_field('created_on DATETIME NOT NULL');
		$this->dbforge->add_field('modified_on DATETIME NULL');
		$this->dbforge->add_field('finished_on DATETIME NULL');
		$this->dbforge->add_field('runtime INT NOT NULL DEFAULT 0');
		$this->dbforge->add_field('deleted TINYINT(1) NOT NULL DEFAULT 0');
		$this->dbforge->add_key('cron_id', TRUE);
		$this->dbforge->add_key('export_name', FALSE);
		$this->dbforge->create_table('jrcron');
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		// Get Permissions
		$this->db->or_where('name', 'jrCron.Content.View');
		$this->db->or_where('name', 'jrCron.Content.Delete');
		$this->db->or_where('name', 'jrCron.Reports.View');
		$this->db->or_where('name', 'jrCron.Reports.Delete');
		$this->db->or_where('name', 'jrCron.Settings.View');
		$this->db->or_where('name', 'jrCron.Settings.Add');
		$this->db->or_where('name', 'jrCron.Settings.Edit');
		$this->db->or_where('name', 'jrCron.Settings.Delete');
		$query = $this->db->get("{$prefix}permissions");

		// Delete Cron Role Permissions
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->delete("{$prefix}role_permissions", array("permission_id" => $row->permission_id));
			}
		}

		// Delete Cron Permissions
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Content.View'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Content.Delete'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Reports.View'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Reports.Delete'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.View'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.Add'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.Edit'));
		$this->db->delete("{$prefix}permissions", array('name' => 'jrCron.Settings.Delete'));


		// Delete Run Job Setting
		$this->db->delete("{$prefix}settings", array('module' => 'jrCron'));


		// Load DBForge
		$this->load->dbforge();

		// Drop jrCron Table
		$this->dbforge->drop_table('jrcron');
	}
	
	//--------------------------------------------------------------------
	
}