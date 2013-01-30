<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * jrCron Controller
 *
 * Provides front-end functions for cron jobs.
 *
 * @package    Bonfire
 * @subpackage Modules_Jrcron
 * @category   Controllers
 * @author     jrConway
 * @link       https://github.com/jrconway3
 *
 */
class Jrcron extends Front_Controller {

	// Define Globals
	protected $sess_start;
	protected $sess_runtime;
	protected $sess_end;
	protected $sess_id;
	protected $sess_res;
	protected $sess_err;
	protected $sess_job;
	protected $sess_export;

	/**
	  * Initialize Jrcron Controller
	  *
	  * @author David A Conway Jr.
	  * @date 1/27/13
	  */
	public function __construct() 
	{ 
		parent::__construct();
		
		$this->load->library('curl');
		$this->load->library('csv');
		$this->load->model('jrcron_model');
	}
	
	//--------------------------------------------------------------------

	/**
	  * Run Cron Job
	  *
	  * @author David A Conway Jr.
	  * @date 1/27/13
	  */
	public function index() {
		// Run Cron
		$this->run();
	}//end index()
	
	//--------------------------------------------------------------------

	/**
	  * Process Cron While Logging Progress to Database
	  *
	  * @author David A Conway Jr.
	  * @date 1/27/13
	  */
	public function run($job = '') {
		// Start Cron Session
		$this->sess_start   = time();
		$this->sess_id      = md5(uniqid());
		$this->sess_err     = false;
		$this->sess_res     = '';
		$this->sess_job     = $job;
		$this->sess_export  = $job . '_' . date("n-j-y", $this->sess_start) . '_' . $this->sess_start;

		// Try Running Jobs
		try {
			// Find Jobs to Run
			$this->job($job);
		}
		catch (Exception $e) {
			// An Error was returned
			$this->sess_res = $e->getMessage();
			$this->sess_err = true;
		}

		// End Cron Session
		$this->sess_end     = time();
		$this->sess_runtime = $this->sess_end - $this->sess_start;
		if(empty($this->sess_err)) {
			$this->sess_res = 'Completed successfully.';
		}
		$this->jrcron_model->end_session($this->sess_job, $this->sess_id, $this->sess_runtime, 
											$this->sess_res, $this->sess_err, $this->sess_export);
	}//end run()

	//--------------------------------------------------------------------

	/**
	  * Get Cron Job
	  *
	  * @author David A Conway Jr.
	  * @date 1/27/13
	  * @return false if cron job doesn't exist
	  */
	function job() {
		// Set Job as Current Session
		$job = $this->sess_job;

		// No Job Set?
		if(empty($job)) {
			// Get Jobs from DB
			## TO DO ##
		}

		// Still No Job?
		if(empty($job)) {
			// No Job, No Log needed
			die;
		}

		// Job was started; let's log it
		$this->jrcron_model->start_session($this->sess_job, $this->sess_id, $this->sess_export);

		// Does the job exist?
		if(!method_exists($this, $job)) {
			// The called job doesn't exist; throw error
			throw(new Exception("A cron job named \"$job\" was called that does not exist."));
			return false;
		}

		// Run Extracted Cron Job
		$this->$job();
	}//end run_cronjob()

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	## Begin Cron Jobs
	//--------------------------------------------------------------------

	/**
	 * Hello World!
	 *
	 * @cron Hello World!
	 * @desc This is an example cron job. We simply grab some text from a common function and away
	 *       we go. ^_^
	 * 
	 *       Note the @cron item above; that denotes that this is a cron job. I'm going to attempt
	 *       to write a function later that will automatically grab these entries and display them
	 *       in the settings controller of the admin.
	 * @author David A Conway Jr.
	 * @date 1/28/13
	 */
	public function hello_world()
	{
		// Get Common Function
		echo $this->get_hello_world();
	}

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	## Common Functions
	//--------------------------------------------------------------------

	/**
	  * Get Hello World
	  *
	  * @author David A Conway Jr.
	  * @desc This is a common function. Use these functions if you have to repeat lines of
	  *       code in multiple cron jobs.
	  * @date 1/28/13
	  */
	function get_hello_world() {
		// Return Text for the Hello World Job
		return 'Hello World!';
	}

	//--------------------------------------------------------------------
	
}