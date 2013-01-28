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

		Template::set('toolbar_title', lang('jrcron_settings'));

	}//end __construct()

	//--------------------------------------------------------------------

	/*
	 * Display the user list and manage the user deletions/banning/purge
	 *
	 * @access public
	 *
	 * @return  void
	 */
	public function index($offset=0)
	{
		$this->auth->restrict('jrCron.Settings.Edit');

		Template::render();

	}//end index()

	//--------------------------------------------------------------------

}//end Settings

// End of Admin User Controller
/* End of file settings.php */
/* Location: ./application/core_modules/controllers/settings.php */
