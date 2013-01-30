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

		Template::set('toolbar_title', lang('jrcron_exports'));

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
			// Get All Finished Exports
			Template::set('exports', $this->jrcron_model->select('cron_job, finished_on, runtime, export_name')
					->where('runtime >', 0)
					->order_by('finished_on', 'DESC')
					->find_all() );

			// Render Template
			Template::render();
		}

	}//end index()

}//end class
