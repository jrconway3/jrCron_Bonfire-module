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
 * jrCron Reports Context
 *
 * Allows the administrator to view the jrcron logs.
 *
 * @package    Bonfire
 * @subpackage Modules_Reports
 * @category   Controllers
 * @author     jrConway
 * @link       https://github.com/jrconway3/jrCron_Bonfire-module
 *
 */
class Reports extends Admin_Controller
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

		$this->auth->restrict('Site.Reports.View');
		$this->auth->restrict('jrCron.Settings.View');

		$this->lang->load('jrcron');

		Template::set('toolbar_title', lang('jrcron_title'));

		Assets::add_js(Template::theme_url('js/bootstrap.js'));
		Assets::add_js($this->load->view('reports/activities_js', null, true), 'inline');

		Assets::add_js( array ( Template::theme_url('js/jquery.dataTables.min.js' )) );
		Assets::add_js( array ( Template::theme_url('js/bootstrap-dataTables.js' )) );
		Assets::add_css( array ( Template::theme_url('css/datatable.css') ) ) ;
		Assets::add_css( array ( Template::theme_url('css/bootstrap-dataTables.css') ) ) ;


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
		if (has_permission('Jrcron.User.View')
				|| has_permission('Jrcron.Module.View')
				|| has_permission('Jrcron.Date.View'))
		{
			// get top 5 modules
			$this->db->group_by('module');
			Template::set('top_modules', $this->jrcron_model->select('module, COUNT(module) AS jrcron_count')
					->where('activities.deleted', 0)
					->limit(5)
					->order_by('jrcron_count', 'DESC')
					->find_all() );

			// get top 5 users and usernames
			$this->db->join('users', 'activities.user_id = users.id', 'left');
			$query = $this->db->select('username, user_id, COUNT(user_id) AS jrcron_count')
					->where('activities.deleted', 0)
					->group_by('user_id')
					->order_by('jrcron_count','DESC')
					->limit(5)
					->get($this->jrcron_model->get_table());
			Template::set('top_users', $query->result());

			Template::set('users', $this->user_model->find_all());
			Template::set('modules', module_list());
			Template::set('activities', $this->jrcron_model->find_all());
			Template::render();
		}
		else if(has_permission('Jrcron.Own.View'))
		{
			$this->jrcron_own();

		}

	}//end index()


	//--------------------------------------------------------------------

}//end class
