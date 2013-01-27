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

	public function __construct() 
	{ 
		parent::__construct();
		
		$this->load->model('jrcron_model');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$this->load->helper('typography');
	}

	//--------------------------------------------------------------------
	
}