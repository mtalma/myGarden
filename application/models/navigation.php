<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigation extends CI_Model
{
	private static $menu;
	
	function __construct()
	{
		parent::__construct();
		
		// construct our menu here based on admin level
		self::$menu = array(
			'Dashboard' => base_url() . 'admin/dashboard',
			'Maintenance Plans' => base_url() . 'admin/maintenance_plans',
			'Maintenance Records' => base_url() . 'admin/maintenance_records');
	}
		
	function get_menu()
	{
		return self::$menu;
	}
}