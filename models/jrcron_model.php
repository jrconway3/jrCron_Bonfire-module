<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Activities
 *
 * Provides a simple and consistent way to record and display user-related activities
 * in both core- and custom-modules.
 *
 * @package    Bonfire
 * @subpackage Modules_Jrcron
 * @category   Models
 * @author     jrConway
 * @link       https://github.com/jrconway3
 *
 */
class Jrcron_model extends MY_Model {

	protected $table		= 'jrcron';
	protected $key			= 'cron_id';
	protected $set_created	= true;
	protected $set_modified	= true;
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';

	// Cron Defaults
	protected $limit_memory = 128;
	protected $limit_time   = 240;
	protected $cron_jobs    = array(
		"hello_world" => array(
			"job_title" => "Hello World!",
			"job_desc"  => "Displays some hello world text.",
			"job_file"  => "hello_world_%day%-%mon%-%year%"
		)
	);

	//--------------------------------------------------------------------

	/**
	  * Get Cron Object
	  *
	  * @author David A Conway Jr.
	  * @desc Convert cron array from the array above to an object.
	  * @param string $job : the job name of the job to convert to object
	  * @return object of cron job data
	  */
	function get_cron_object($job) {
		// Is Valid Job?
		$cron = $this->cron_jobs[$job];
		if(empty($cron)) {
			return null;
		}

		// Convert to Object
		$obj = new stdclass();
		foreach($this->cron_jobs[$job] as $k => $v) {
			$obj->$k = $v;
		}

		// Add Extra Values
		$obj->job_id       = 0;
		$obj->job_name     = $job;
		$obj->job_enabled  = 1;
		$obj->limit_memory = $this->limit_memory;
		$obj->limit_time   = $this->limit_time;
		$obj->created_on   = date("Y-m-d H:i:s");
		$obj->modified_on  = $obj->created_on;
		$obj->deleted      = 0;

		// Return Object
		return $obj;
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Filename
	  *
	  * @author David A Conway Jr.
	  * @desc Replace codes in cron filename.
	  * @param string $file : the filename to get cron data for
	  * @param object $job  : the job to replace info for 
	  * @return string of updated cron filename
	  */
	function get_cron_file($file, $job) {
		// Get Current Date
		$time = time();
		$day  = date("n");
		$mon  = date("j");
		$year = date("Y");

		// Replace Date
		$file = str_replace("%day%", $day, $file);
		$file = str_replace("%mon%", $mon, $file);
		$file = str_replace("%year%", $year, $file);

		// Return Object
		return $file;
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Jobs
	  *
	  * @author David A Conway Jr.
	  * @desc Get cron job settings from both the preset array above and the database.
	  * @param $opts : options to get cron jobs by
	  * @return array of cron job objects on success, empty array on failure
	  */
	function get_cron_jobs($opts = array()) {
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Loop Cron Jobs
		$results = array();
		foreach($this->cron_jobs as $k => $v) {
			// Convert to Object
			$job = $this->get_cron_object($k);

			// Get Active/Inactive?
			if(isset($opts['job_enabled']) && ($opts['job_enabled'] != $job->job_enabled)) {
				continue;
			}

			// Add to Results
			$results[$k] = $job;
		}

		// Check Database
		$query = $this->db->get_where($prefix . 'jrcron_jobs', $opts);

		// DB Results Found?
		if($query->num_rows() > 0) {
			// Get Result
			$result = $query->result();

			// Loop Results
			foreach($result as $res) {
				$results[$res->job_name] = $res;
			}
		}

		// Return Results
		return $results;
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Jobs w/Latest
	  *
	  * @author David A Conway Jr.
	  * @desc Get cron job settings with latest job run.
	  * @param $opts : options to get cron jobs by
	  * @return array of cron job objects on success, empty array on failure
	  */
	function get_cron_jobs_w_latest($opts = array()) {
		// Get Cron Jobs
		$jobs = $this->get_cron_jobs($opts);

		// Loop Cron Jobs
		$results = array();
		foreach($jobs as $result) {
			// Get Latest Result
			$latest = parent::select('cron_job, finished_on, runtime, export_name')
					->where('runtime >', 0)
					->where('cron_job', $result->job_name)
					->order_by('finished_on', 'DESC')
					->limit(1)
					->find_all();

			// Is Array?
			if(!empty($latest) && is_array($latest)) {
				// Set Latest
				$result->latest = reset($latest);	
			}

			// Add to Results
			$results[] = $result;
		}

		// Return Results
		return $results;
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Jobs w/File
	  *
	  * @author David A Conway Jr.
	  * @desc Get cron job settings with latest job run's filename.
	  * @param $opts : options to get cron jobs by
	  * @return array of cron job objects on success, empty array on failure
	  */
	function get_cron_jobs_w_file($opts = array()) {
		// Get Cron Jobs
		$jobs = $this->get_cron_jobs($opts);

		// Loop Cron Jobs
		$results = array();
		foreach($jobs as $result) {
			// Get Latest Result
			$latest = parent::select('cron_job, finished_on, runtime, export_name')
					->where('runtime >', 0)
					->where('cron_job', $result->job_name)
					->order_by('finished_on', 'DESC')
					->limit(1)
					->find_all();

			// Is Array?
			$export = new stdclass;
			if(!empty($latest) && is_array($latest)) {
				// Set Latest
				$data = reset($latest);
				$export->export_name = $data->export_name;
				$export->job_name    = $data->cron_job;
			}
			else {
				continue;
			}

			// Add to Results
			$results[] = $export;
		}

		// Return Results
		return $results;
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Jobs Count
	  *
	  * @author David A Conway Jr.
	  * @desc Get cron jobs count from both the preset array above and the database.
	  * @param $opts : options to get cron jobs by
	  * @return count of cron jobs
	  */
	function get_cron_jobs_count($opts = array()) {
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Loop Cron Jobs
		$results = array();
		foreach($this->cron_jobs as $k => $v) {
			// Convert to Object
			$job = $this->get_cron_object($k);

			// Get Cron Enabled?
			if(isset($opts['job_enabled']) && ($opts['job_enabled'] != $job->job_enabled)) {
				continue;
			}

			// Add to Results
			$results[$k] = $job;
		}

		// Check DB
		$query = $this->db->get_where($prefix . 'jrcron_jobs', $opts);

		// DB Results Found?
		if($query->num_rows() > 0) {
			// Get Result
			$result = $query->result();

			// Loop Results
			foreach($result as $res) {
				$results[$res->job_name] = $res;
			}
		}

		// Return Result Count
		return count($results);
	}

	//--------------------------------------------------------------------

	/**
	  * Get Cron Job
	  *
	  * @author David A Conway Jr.
	  * @desc Search database for cron job settings, otherwise get from preset array above.
	  * @param string $job : name of the cron job to get settings for
	  * @return object of job data on success, false on failure
	  */
	function get_cron_job($job) {
		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Check DB
		$query = $this->db->get_where($prefix . 'jrcron_jobs', array("job_name" => $job));

		// DB Results Found?
		if($query->num_rows() > 0) {
			// Get Result
			$result = reset($query->result());

			// Return Result
			return $result;
		}

		// Internal Settings Found?
		if(!empty($this->cron_jobs[$job])) {
			// Get Result
			$result = $this->get_cron_object($job);

			// Return Result.
			return $result;
		}

		// Return False
		return false;
	}

	//--------------------------------------------------------------------

	/**
	  * Edit Cron Job
	  *
	  * @author David A Conway Jr.
	  * @desc Search database for cron job settings, otherwise get from preset array above.
	  * @param string $job  : name of the cron job to get settings for
	  * @param array  $data : array of job data to edit
	  * @return true on successful update, false when invalid data provided
	  */
	function edit_cron_job($job, $data = array()) {
		// Data Empty?
		if(empty($data)) {
			return false;
		}

		// Get Prefix
		$prefix = $this->db->dbprefix;

		// Check DB
		$query = $this->db->get_where($prefix . 'jrcron_jobs', array("job_name" => $job));

		// Field in DB?
		if($query->num_rows() > 0) {
			// Update Database
			if($this->db->update($prefix . 'jrcron_jobs', $data, array("job_name" => $job))) {
				// Successfully Updated
				return true;
			}
		}
		else {
			// Get Full Array
			$full = $this->get_cron_object($job);

			// Loop Replacement Data
			foreach($data as $k => $v) {
				// Replace Item
				$full->$k = $v;
			}

			// Insert Job Into Database
			if($this->db->insert($prefix . 'jrcron_jobs', $full)) {
				// Successfully Updated
				return true;
			}
		}

		// Failed to Update
		return false;
	}

	//--------------------------------------------------------------------

	/**
	  * Start Cron Session
	  *
	  * @author David A Conway Jr.
	  * @desc Start a cron session.
	  * @param string $job  : name of the cron job to log message for
	  * @param string $sess : the cron session to add log for
	  */
	function start_session($job, $sess, $export) {
		// Add Cron Log
		$this->add_cron_log($job, 'Cron session has started.', $sess, false, $export);

		// Insert Cron Job
		$cron = array(
			'cron_job'     => $job,
			'cron_sess'    => $sess,
			'export_name'  => $export
		);
		return parent::insert($cron);
	}

	//--------------------------------------------------------------------

	/**
	  * Start Cron Session
	  *
	  * @author David A Conway Jr.
	  * @desc Start a cron session.
	  * @param int     $id     : id of the cron session to end
	  * @param string  $job    : name of the cron job to end session for
	  * @param string  $sess   : the cron session to end
	  * @param string  $time   : the runtime in seconds of the cron session
	  * @param string  $msg    : message to submit with the cron log
	  * @param boolean $error  : true if logging an error, false otherwise; default: false
	  * @param string  $export : the name of the exported file
	  */
	function end_session($id, $job, $sess, $time, $msg = 'Cron session has ended.', $error = false, $export = '') {
		// Add Cron Log
		$this->add_cron_log($job, $msg, $sess, $error, $export, $time);

		// Insert Cron Job
		$cron = array(
			'cron_result'  => $msg,
			'cron_error'   => $error,
			'export_name'  => $export,
			'runtime'      => $time,
			'finished_on'  => date("Y-m-d H:m:s")
		);
		parent::update($id, $cron);
	}

	//--------------------------------------------------------------------

	/**
	  * Add Cron Log
	  *
	  * @author David A Conway Jr.
	  * @desc Function to log a cron message to the databse.
	  * @param string  $job    : name of the cron job to log message for
	  * @param string  $msg    : message to submit with the cron log
	  * @param string  $sess   : the cron session to add log for
	  * @param boolean $error  : true if logging an error, false otherwise; default: false
	  * @param string  $export : the export filename that was updated if applicable; default: ''
	  * @param string  $time   : the runtime of the job so far in seconds; default: 0
	  * @return id of the cron log added
	  */
	function add_cron_log($job, $msg, $sess, $error = false, $export = '', $time = 0) {
		// Insert Cron Log
		$cron_log = array(
			'log_job'     => $job,
			'log_sess'    => $sess,
			'log_msg'     => $msg,
			'log_error'   => (!empty($error) ? '1' : '0'),
			'created_on'  => date("Y-m-d H:m:s"),
			'export_name' => $export,
			'runtime'     => $time,
			'deleted'     => 0
		);
		$this->db->insert("jrcron_logs", $cron_log);
	}

	//--------------------------------------------------------------------

}//end class
