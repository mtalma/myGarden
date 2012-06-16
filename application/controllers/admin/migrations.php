<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrations extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
		$this->template->set_layout('bare');
	}
	
	function index()
	{		
		$data = array();
		
		if( $this->input->post('run') )
		{
			$version = $this->input->post('version');
			if( $version !== FALSE && is_numeric($version) )
			{
				$backup_path = $this->_db_backup($version);
				if( $backup_path )	// make sure we've backed up
				{
					$this->session->set_flashdata('path', $backup_path);
					$result = $this->migration->version($version);
					if( $result === TRUE )
						$this->session->set_flashdata('msg', 'Database not changed. Version ' . $version );
					else if( $result === FALSE )
						$this->session->set_flashdata('msg','Error');
					else
						$this->session->set_flashdata('msg', 'Database version is now at ' . $version);
						
					// refresh page so "list_tables" works
					redirect( "admin/migrations","location");
				}
			}
		}
		
		$data['path'] = $this->session->flashdata('path');
		$data['msg'] = $this->session->flashdata('msg');
		$this->load->database();
		$tables = $this->db->list_tables();
		$data['count'] = count($tables);
		$data['tables'] = $tables;
		$this->template->build('admin/migrations', $data);
	}
	
	function _db_backup($version)
	{
		$result = false;
		
		$this->load->helper('file');
		$filename = "MigrateTo" . $version . "-" . date('YmdHis') .".gz";
		$this->load->dbutil();
		$backup =& $this->dbutil->backup();
		
		if( $backup )
		{
			$path = APPPATH . 'migrations/backup/' . $filename;
			if( write_file( $path, $backup) )
				$result = $path;
		}
		
		return $result;
	}
}