<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance_plans extends Admin_Controller {

	var $id;
	var $data;
	
	function __construct()
	{
		parent::__construct();
		$this->load->spark('php-activerecord/0.0.2');
		$this->load->library('table');
				
		$this->id = $this->uri->segment(4);	//if no uri segment, we get all plans
		$records = Maintenance_Plan::get($this->id);
		$this->data['records'] = is_array( $records ) ? $records : array($records);
	}

	function index()
	{
        $this->template->build('admin/maintenance_plans/list', $this->data);
	}
	
	function view()
	{
		if( empty($this->id) )
			redirect("admin/maintenance_plans/list");

		$this->template->build('admin/maintenance_plans/view', $this->data);
	}
	
	function edit()
	{
		if( empty($this->id) )
			redirect( "admin/maintenance_plans/list");	
		
        $this->template->build('admin/maintenance_plans/edit', $this->data);
	}
	
	function delete()
	{
		if( empty($this->id) )
			redirect("admin/maintenance_plans/list");	
			
		$this->template->build('admin/maintenance_plans/delete', $this->data);
	}
}