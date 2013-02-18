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
 * jrCron Settings Context
 *
 * Manages the user functionality on the admin pages.
 *
 * @package    Bonfire
 * @subpackage Modules_Jrcron
 * @category   Controllers
 * @author     jrConway
 * @link       https://github.com/jrconway3
 *
 */
class Settings extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Setup the required permissions
	 *
	 * @return void
	 */
	public function __construct()
    {
		parent::__construct();

		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('jrCron.Settings.View');

		$this->load->model('jrcron_model');

		$this->lang->load('jrcron');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	  * Display the cron job list and manage the job settings.
	  *
	  * @access public
	  * @return void
	  */
	public function index($offset=0)
	{
		$this->auth->restrict('jrCron.Settings.Manage');

		// Do we have any actions?
		if ($this->input->post('activate'))    $action = '_activate';
		if ($this->input->post('deactivate'))  $action = '_deactivate';

		if (isset($action))
		{
			$checked = $this->input->post('checked');

			if (!empty($checked))
			{
				foreach($checked as $cron_id)
				{
					$this->$action($cron_id);
				}
			}
			else
			{
				Template::set_message(lang('jrcs_no_checked'), 'error');
			}
		}

		$where = array();
		$show_deleted = FALSE;

		// Filters
		$filter = $this->input->get('filter');
		switch($filter)
		{
			case 'active':
				$where['job_enabled'] = 1;
				break;
			case 'inactive':
				$where['job_enabled'] = 0;
				break;
			default:
				$where['deleted'] = 0;
				break;
		}

		// Set Cron Jobs
		Template::set('jobs', $this->jrcron_model->get_cron_jobs($where, $this->limit, $offset));

		// Start Pagination
		$this->load->library('pagination');

		// Get Cron Jobs Count
		$total_jobs = $this->jrcron_model->get_cron_jobs_count($where);

		// Set Cron Jobs
		Template::set('jobs', $this->jrcron_model->get_cron_jobs($where));

		// Get URls
		Template::set('current_url', current_url());
		Template::set('filter', $filter);

		// Set Title
		Template::set('toolbar_title', lang('jrcs_settings'));

		// Render Page
		Template::render();
	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Edit a Job
	 *
	 * @access public
	 * @return void
	 */
	public function edit($job_name='')
	{
		$this->load->helper('date');

		// No Cron Job?
		if (empty($job_name))
		{
			Template::set_message(lang('jrcs_no_name'), 'error');
			redirect(SITE_AREA .'/settings/jrcron');
		}

		// Manage Cron Jobs
		$this->auth->restrict('jrCron.Settings.Manage');

		// Get Cron Job Data
		$job = $this->jrcron_model->get_cron_job($job_name);

		// Invalid Cron Job?
		if (empty($job->job_name))
		{
			Template::set_message(lang('jrcs_invalid_job'), 'error');
			redirect(SITE_AREA .'/settings/jrcron');
		}

		// Saving Data?
		if ($this->input->post('save'))
		{
			// Save Cron Job
			if ($this->save_cron($job_name))
			{
				// Successfully Saved
				Template::set_message(lang('jrcs_edit_success'), 'success');

				// Redirect Back to the Page to Refresh Data
				redirect($this->uri->uri_string());
			}
		}

		// Set Job Values
		Template::set('job', $job);
        Template::set('toolbar_title', lang('jrcs_edit'));

        // Set Page
		Template::set_view('settings/cron_form');

		// Render Page
		Template::render();
	}//end edit()

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Save the cron job
	 *
	 * @access private
	 * @param int $job_name : The name of the cron job
	 * @return bool
	 */
	private function save_cron($job_name='')
	{
		$_POST['job'] = $job_name;
		$this->form_validation->set_rules('limit_time', lang('jrcs_time'), 'trim|min_length[2]|is_numeric');
		$this->form_validation->set_rules('limit_memory', lang('jrcs_memory'), 'trim|min_length[2]|is_numeric');

		if ($this->form_validation->run($this) === FALSE)
		{
			return FALSE;
		}

		// Compile our core user elements to save.
		$data = array(
			'job_file'     => $this->input->post('job_file'),
			'limit_time'   => $this->input->post('limit_time'),
			'limit_memory' => $this->input->post('limit_memory')
		);

		// Update Cron Job
		$return = $this->jrcron_model->edit_cron_job($job_name, $data);

		// Any modules needing to save data?
		Events::trigger('save_cron', $this->input->post());

		// Valid String?
		return $return;

	}//end save_cron()

	//--------------------------------------------------------------------
	// ACTIVATION METHODS
	//--------------------------------------------------------------------

	/**
	 * Activates selected users accounts.
	 *
	 * @access private
	 * @param int $job : name of cron job to activate
	 * @return void
	 */
	private function _activate($job)
	{
		$this->jrcron_model->edit_cron_job($job, array("job_enabled" => 1));
	}//end _activate()

	//--------------------------------------------------------------------

	/**
	 * Deactivates selected users accounts.
	 *
	 * @access private
	 * @param int $job : name of cron job to activate
	 * @return void
	 */
	private function _deactivate($job)
	{
		$this->jrcron_model->edit_cron_job($job, array("job_enabled" => 0));
	}//end _deactivate()

}//end Settings

// End of Admin User Controller
/* End of file settings.php */
/* Location: ./application/core_modules/controllers/settings.php */
