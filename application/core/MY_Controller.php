<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticated_Controller extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->helper('inflector');

		if( !$this->ion_auth->logged_in())	//if we are not logged in
		{
			$this->session->set_flashdata('message', "You need to be logged in to view this page");
			redirect( base_url(). 'auth/login', 'refresh');	//redirect to log in page
		}
		else	//we are logged in
		{
			//build the navigation partial
			$this->load->model('navigation');
			$this->template->set_partial('navigation', 'layouts/navigation_menu', array('nav' => $this->navigation->get_menu()));
			
			//build the breadcrumb
			$this->template->set_breadcrumb('Home','admin');
			$this->template->set_breadcrumb(humanize($this->router->fetch_class()),'admin/'.$this->router->fetch_class());
			if( $this->router->fetch_method() != 'index' )
				$this->template->set_breadcrumb(humanize($this->router->fetch_method()));
				
			$this->template->set_partial('breadcrumbs', 'layouts/breadcrumbs');
			
			//print_r( $this->session->all_userdata() );
		}
	}

}

class Admin_Controller extends Authenticated_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		if (!$this->ion_auth->is_admin())
		{
			//redirect them to the login page
			$this->session->set_flashdata('message', "You need Admin rights to view this page");
			redirect( base_url(). 'auth/login', 'refresh');
		}
	}
}