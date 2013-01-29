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

	//--------------------------------------------------------------------

	/**
	  * Start Cron Session
	  *
	  * @author David A Conway Jr.
	  * @desc Start a cron session.
	  * @param string  $job    : name of the cron job to log message for
	  * @param string  $sess   : the cron session to add log for
	  */
	function start_session($job, $sess) {
		// Add Cron Log
		$this->add_cron_log($job, 'Cron session has started.', $sess);

		// Insert Cron Job
		$cron = array(
			'cron_job'  => $job,
			'cron_sess' => $sess
		);
		parent::insert($cron);
	}

	//--------------------------------------------------------------------

	/**
	  * Start Cron Session
	  *
	  * @author David A Conway Jr.
	  * @desc Start a cron session.
	  * @param string  $job    : name of the cron job to end session for
	  * @param string  $sess   : the cron session to end
	  * @param string  $time   : the runtime in seconds of the cron session
	  * @param string  $msg    : message to submit with the cron log
	  * @param boolean $error  : true if logging an error, false otherwise; default: false
	  * @param string  $export : the name of the exported file
	  */
	function end_session($job, $sess, $time, $msg = 'Cron session has ended.', $error = false, $export = '') {
		// Add Cron Log
		$this->add_cron_log($job, $msg, $sess, $error, $export, $runtime);

		// Insert Cron Job
		$cron = array(
			'cron_result'  => $msg,
			'cron_error'   => $error,
			'runtime'      => $sess,
			'finished_on'  => time()
		);
		parent::update($cron, array('cron_job' => $job, 'cron_sess' => $sess));
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
			'created_on'  => time(),
			'export_name' => $export,
			'runtime'     => $time,
			'deleted'     => 0
		);
		$this->db->insert("jrcron_logs", $cron_log);
	}

	//--------------------------------------------------------------------

}//end class
