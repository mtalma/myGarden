<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->spark('php-activerecord/0.0.2');
		$this->load->library('table');
		
		//$this->template->set_breadcrumb('Dashboard','admin/dashboard');
	}
	
	function index()
	{
		$data['records'] = Maintenance_Plan::get();
		$this->template->set_partial('list', 'admin/maintenance_plans/list', $data);
		
		
		$this->template->build('admin/dashboard', $data);
	}
}