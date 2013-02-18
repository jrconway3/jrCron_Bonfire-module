<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  * jrCron Settings Updates
  *
  * @author David A Conway Jr.
  * @desc Added fields for cron job settings.
  */
class Migration_Settings_updates extends Migration {

	public function up() 
	{
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Load DBForge
		$this->load->dbforge();

		// Add Description Column
		$this->dbforge->add_column('jrcron_jobs', array(
			'job_title' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 100,
				'null'			=> true
			)
		), 'job_name');

		// Add Description Column
		$this->dbforge->add_column('jrcron_jobs', array(
			'job_desc' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 255,
				'null'			=> true
			)
		), 'job_title');

		// Add Filename Column
		$this->dbforge->add_column('jrcron_jobs', array(
			'job_file' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 255,
				'null'			=> true
			)
		), 'job_enabled');

		// Add Disable Memory Limit Column
		$this->dbforge->add_column('jrcron_jobs', array(
			'limit_memory' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 10,
				'null'			=> false,
				'default'       => '32M'
			)
		), 'job_file');

		// Add Disable Memory Limit Column
		$this->dbforge->add_column('jrcron_jobs', array(
			'limit_time' => array(
				'type'			=> 'SMALLINT',
				'constraint'	=> 6,
				'null'			=> false,
				'default'       => 30
			)
		), 'limit_memory');
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Load DBForge
		$this->load->dbforge();

		// Drop Session Column
		$this->dbforge->drop_column('jrcron_jobs', 'job_title');
		$this->dbforge->drop_column('jrcron_jobs', 'job_desc');
		$this->dbforge->drop_column('jrcron_jobs', 'job_file');
		$this->dbforge->drop_column('jrcron_jobs', 'limit_memory');
		$this->dbforge->drop_column('jrcron_jobs', 'limit_time');
	}
	
	//--------------------------------------------------------------------
	
}