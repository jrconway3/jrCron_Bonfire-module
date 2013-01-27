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
			// get top 5 modules
			/*$this->db->group_by('module');
			Template::set('top_modules', $this->jrcron_model->select('module, COUNT(module) AS jrcron_count')
					->where('jrcrons.deleted', 0)
					->limit(5)
					->order_by('jrcron_count', 'DESC')
					->find_all() );

			// get top 5 users and usernames
			$this->db->join('users', 'jrcrons.user_id = users.id', 'left');
			$query = $this->db->select('username, user_id, COUNT(user_id) AS jrcron_count')
					->where('jrcrons.deleted', 0)
					->group_by('user_id')
					->order_by('jrcron_count','DESC')
					->limit(5)
					->get($this->jrcron_model->get_table());
			Template::set('top_users', $query->result());

			Template::set('users', $this->user_model->find_all());
			Template::set('modules', module_list());
			Template::set('jrcrons', $this->jrcron_model->find_all());*/
			Template::render();
		}

	}//end index()

}//end class
