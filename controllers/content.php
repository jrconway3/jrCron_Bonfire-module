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
 * jrCron Content Context
 *
 * Allows the administrator to view and download Scraper CSV Exports.
 *
 * @package    Bonfire
 * @subpackage Modules_Jrcron
 * @category   Controllers
 * @author     jrConway
 * @link       https://github.com/jrconway3
 *
 */
class Content extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('jrCron.Content.View');

		$this->load->library('csv');

		$this->load->model('jrcron_model');

		$this->lang->load('jrcron');

		Template::set('toolbar_title', lang('jrcs_exports'));

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Lists all log files and allows you to change the log_threshold.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		if (has_permission('jrCron.Content.View'))
		{
			// Get All Cron Jobs With Latest Run
			Template::set('jobs', $this->jrcron_model->get_cron_jobs_w_latest() );

			// Render Template
			Template::render();
		}

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Lists all exported files for the specific cron job.
	 *
	 * @access public
	 * @param string $job_name : the name of the cron job to export files for
	 * @return void
	 */
	public function files($job_name)
	{
		if (has_permission('jrCron.Content.View'))
		{
			// Get Job
			Template::set('job', $this->jrcron_model->get_cron_job($job_name));

			// Get All Finished Exports
			Template::set('files', $this->jrcron_model->select('cron_job, finished_on, runtime, export_name')
					->where('runtime >', 0)
					->where('cron_job', $job_name)
					->order_by('finished_on', 'DESC')
					->find_all() );

			// Render Template
			Template::render();
		}

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Download a zip file containing all files on the page.
	 *
	 * @access public
	 * @param string $job_name : the name of the cron job to download a zip for (optional)
	 * @return void
	 */
	public function zip($job_name = '')
	{
		if (has_permission('jrCron.Content.View'))
		{
			// Load Zip Library
			$this->load->library('zip');

	        // Get Export Directory
	        $dir = dirname(dirname(BASEPATH)) . '/public/exports';

			// Job Name Specified?
			if($job_name != '') {
				// Set Zip File Name
				$zip = $job_name . '_' . date("j-n-Y") . '.zip';

				// Get Exported Files
				$exports = $this->jrcron_model->select('export_name, cron_job as job_name')
					->where('runtime >', 0)
					->where('cron_job', $job_name)
					->order_by('finished_on', 'DESC')
					->find_all();
			}
			else {
				// Set Zip File Name
				$zip = 'wcca_latest_' . date("j-n-Y") . '.zip';

				// Get Cron Jobs With File
				$exports = $this->jrcron_model->get_cron_jobs_w_file();
			}

			// Valid Exports?
			if(is_array($exports) && count($exports)) {
				// Loop Exports
				foreach($exports as $export) {
					// Does File Exist?
					if(!file_exists($dir . '/' . $export->job_name . '/' . $export->export_name . '.csv')) {
						continue;
					}

					// Add to Zip
					$this->zip->read_file($dir . '/' . $export->job_name . '/' . $export->export_name . '.csv');
				}
			}

			// Download Zip File
			$this->zip->download($zip);
		}

	}//end index()

}//end class
