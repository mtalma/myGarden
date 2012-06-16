<?php

class Backdoor extends CI_Controller {

	var $data;

	public function __construct()
    {
    	parent::__construct();
	
		$this->load->library('table');
		$this->load->library('email', '', 'email_library');
		$this->load->helper('mygarden');
		
		//pagination config
		$config['cur_tag_open'] = "<a class='active' href='#'>";
		$config['cur_tag_close'] = "</a>";
		$config['prev_link'] = "&laquo";
		$config['next_link'] = "&raquo";
		$config['first_tag_close'] = '';
		$config['last_tag_open'] = '';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';
		$config['prev_tag_open'] = '';
		$config['num_tag_open'] = '';
		$this->load->library('pagination', $config);

		$this->form_validation->set_error_delimiters('<div class="message errormsg">', '</div>');

		//user data
		$this->data['logged_user']['fname'] = $this->session->userdata('fname');
		$this->data['logged_user']['lname'] = $this->session->userdata('lname');
		$this->data['logged_user']['id'] = $this->session->userdata('id');
		
		//get the index page
		$index_page = index_page();
		$index_page = $index_page == '' ? '' : $index_page . '/';
		
		$this->data['nav'] = array(	
		'Maintenance Plans' => base_url() . $index_page . 'backdoor/overview',
		array( 
			'Print Service Records' => base_url() . $index_page . 'backdoor/printRecords',
			'View Service Records' => base_url() . $index_page . 'backdoor/records',
			'View Crews' => base_url() . $index_page . 'backdoor/viewCrews' 
		),

		'Users' => base_url() . $index_page . 'backdoor/users',
		'Emails' => base_url() . $index_page . 'backdoor/email_log');
		//'Marketing' => base_url() . $index_page . 'backdoor/marketing');
		//array( 
		//	'Log' => base_url() . $index_page . 'backdoor/email_log' ));
			//'Send' => base_url() . $index_page . 'backdoor/send_emails' ) );

		$this->output->enable_profiler(DEBUG_MODE);
	}

	function _remap( $method, $params = array() )
	{
		//print_r($params);
	    /*if( !$this->session->userdata('auth') || $method == 'login' )	//not logged in 
	    {
	    	if( $method != 'login' )
	    	{
	    		$url = $method . '/' .  implode( '/', $params );
	    		$this->session->set_userdata( 'redirect', $url );
	    	}
	    	$this->login();
		}
		else*/
        $this->$method();
	}

	function index()
	{
		redirect( "backdoor/overview","location");
	}

	function login() 
	{
		$this->data['path'] = base_url() . "adminus";
		$this->data['email'] = $this->input->post('email', TRUE);
		$this->data['pass'] = md5($this->input->post('pass', TRUE));
		$this->data['message'] = "";
				
		if( $this->input->post('submitLogin') )	//attempt login
		{
			$query = $this->db->get_where('users', array('email' => $this->data['email'], 'password' => $this->data['pass'] ));
			if ($query->num_rows() > 0) 
			{
				assert( $query->num_rows() == 1);
				$row = $query->row();
				//user found
				$userdata = array(
					'fname' => $row->fname,
					'lname' => $row->lname,
					'id' => $row->id,
					'auth' => TRUE
				);
				   
				$this->session->set_userdata($userdata);
				$redirect = $this->session->userdata('redirect');
				$this->session->unset_userdata('redirect');
				redirect( "backdoor/" . $redirect,"location");
			}
			else
				$this->data['message'] = "Email and password combination not found";
		}

		//show login form
		$this->data['title'] = "Login";
		$this->load->view('backdoor/login', $this->data);
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect( "backdoor","location");
	}
	
	function getServiceDatesJSON()
	{
		$start = $_GET['start'];
		$end = $_GET['end'];
				
		$this->output->enable_profiler(false);
		
		$this->load->model('Maintenance_Model');
		$events = null;
		$events = $this->Maintenance_Model->getMaintenanceServicesBetweenDates( $start, $end );
		
		echo json_encode($events);
	}
	
	function viewCrews()
	{
		$this->load->model('Crews');
		$this->data['crews'] = $this->Crews->load();
		
		$this->data['message'] = $this->session->flashdata('message');
		
		$this->load->view('backdoor/header', $this->data);
		$this->load->view('backdoor/viewCrews', $this->data);
		$this->load->view('backdoor/footer', $this->data);
	}
	
	function addCrews()
	{
		$this->load->model('Crews');
		
		if( $this->input->post('addCrew') && $this->form_validation->run('crews') )	//if we've submitted the form and validated
		{
			$crew = new Crew($this->input->post());
			$crew->insert();
			$this->session->set_flashdata('message', 'New Crew Added Successfully');
			redirect( '/backdoor/viewCrews', 'location');
		}
		else
		{
			$this->load->view('backdoor/header', $this->data);
			$this->load->view('backdoor/addCrews', $this->data);
			$this->load->view('backdoor/footer', $this->data);
		}
	}
	
	function marketing()
	{
		$this->load->helper('text');
		
		//google analytics
		$params = array( 'email' => ga_email, 'password' => ga_password, 'token' => null );
		$this->load->library('Gapi', $params);
		
		$dimensions = array('browser','browserVersion', 'referralPath', 'country', 'city', 'latitude', 'longitude');
		$metrics = array('pageviews','visits', 'timeOnSite');
		//requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)
		$this->data['results'] = $this->gapi->requestReportData( ga_profile_id, $dimensions, $metrics, null, null, '2009-04-30', '2011-12-30' );
		
		$this->load->view('backdoor/header', $this->data);
		//$this->load->view('backdoor/ga_loading', $this->data);
		$this->load->view('backdoor/marketing_stats', $this->data);
		$this->load->view('backdoor/footer', $this->data);
	}
	
	// load google analytics into view
	function load_google_analytics()
	{
		
		
		//$this->load->view('backdoor/marketing_stats', $this->data);
	}
	

	function email_log()
	{
	    $this->data['title'] = "Emails";
		$this->data['message'] = "";
		
		$this->load->model('Email_Model');

		$config['base_url'] = base_url() . 'index.php/backdoor/email_log';
		$config['per_page'] = 10;
		$config['total_rows'] = $this->Email_Model->get_log_count();
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		$start = intval($this->uri->segment(3));
	    $count = $config['per_page'];
	    	    
		$this->data['email_log'] = $this->Email_Model->get_logs( $start, $count );

		$this->load->view('backdoor/header', $this->data);
		$this->load->view('backdoor/email_log', $this->data);
		$this->load->view('backdoor/footer', $this->data);
	}
	
	function serviceRecords()
	{
		$this->load->helper('text');
	    $this->data['title'] = "View Service Records";
	    
		$config['base_url'] = base_url() . 'index.php/backdoor/serviceRecords';
		$config['per_page'] = 10;
		$config['total_rows'] = $this->Maintenance_Model->GetMaintenanceRecordCount();
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		$start = intval($this->uri->segment(3));
	    $count = $config['per_page'];
	    	    
		$this->data['records'] = $this->Maintenance_Model->GetAllMaintenanceRecords( $start, $count );

		$this->load->view('backdoor/header', $this->data);
		$this->load->view('backdoor/serviceRecords', $this->data);
		$this->load->view('backdoor/footer', $this->data);
	}

	function send_emails()
	{

		$this->data['title'] = "Emails Confirm";
		$this->data['users'] = "";
		$query = "";
		$ids = $this->input->post('ids');
		if( $ids && count($ids) > 0 )
		{
			$query = "select id, fname, lname, email from users where ";
			$i = 0;
			foreach( $ids as $id )
			{
				$query .= "id='".$id."' ";
				if( $i != count($ids)-1 )
					$query .= "or ";
				$i++;
			}

			$result = $this->db->query( $query );
			$this->data['users'] = $result->result_array();

			$emails = $this->db->query( "select id, title from email_content" );
			$this->data['emails'] = $emails->result_array();

			$this->load->view('backdoor/header', $this->data);
			$this->load->view('backdoor/send_emails_confirm', $this->data);
			$this->load->view('backdoor/footer', $this->data);
		}
		else if( $this->input->post('send') )	//send the emails out
		{
			$ids = $this->input->post('user_ids');
			$query = "select id, fname, lname, email from users where ";
			$i = 0;
			foreach( $ids as $id )
			{
				$query .= "id='".$id."' ";
				if( $i != count($ids)-1 )
					$query .= "or ";
				$i++;
			}
						
			$result = $this->db->query( $query );
			$users = $result->result_array();

			$email_id = $this->input->post('email_id');
			$subject = "";
			$body = "";
			if( $email_id != EMAIL_CUSTOM )
			{
				$emails = $this->db->query( "select subject, body from email_content where id='".$email_id."'" );
				$subject = $emails->row()->subject;
				$body = $emails->row()->body;
			}
			else
			{
				$subject = $this->input->post('subject');
				$body = $this->input->post('body');
			}

			$this->load->model('Email_Model');
			$this->load->model('Maintenance_Model');
			
			foreach( $users as $user )
			{
				$this->Maintenance_Model->Load( $user['id'] );
				$maintenance_plan = $this->Maintenance_Model->GetMaintenancePlan();

				$this->data['service_list'] = "<ul>";
				foreach( $maintenance_plan['services'] as $service)
					$this->data['service_list'] .= "<li>".$service['name']."</li>";
				$this->data['service_list'] .= "</ul>";

				$this->data['fname'] = $maintenance_plan['user']['fname'];

				$this->Email_Model->initialize( $user['email'], $email_id, $this->data );
				$this->Email_Model->send_email();
			}

			redirect( '/backdoor/send_emails', 'location');
		}
		else	//landing page
		{
			$this->data['title'] = "Emails";
			$this->data['message'] = "";

			$all_field = array( '0' => 'All' );
			$this->data['regions'] = array_merge( $all_field, $this->_make_map( "select id, name from regions", 'name' ) );
			$this->data['services'] = array_merge( $all_field, $this->_make_map( "select id, name from services", 'name' ) );
			$this->data['cycle'] = array_merge( $all_field, $this->_make_map( "select id, cycle from service_cycle", 'cycle' ) );
			$this->data['days'] = array_merge( $all_field, $this->_make_map( "select id, day from service_days", 'day' ) );
			$this->data['status'] = array_merge( $all_field, $this->_make_map( "select id, text from status", 'text' ) );
			$this->data['size'] = array_merge( $all_field, $this->_make_map( "select id, size from garden_size", 'size' ) );
			$this->data['slots'] = array_merge( $all_field, $this->_make_map( "select id, name from time_slots", 'name' ) );
			$this->data['clients'] = array_merge( $all_field, $this->_make_map( "SELECT id, CONCAT_WS(' ', fname, lname) as name from users", 'name' ) );
			
			$query = $this->db->query("select id, fname, lname, email, status from users");
			$this->data['users'] = $query->result_array();
	
			$this->load->view('backdoor/header', $this->data);
			$this->load->view('backdoor/send_emails', $this->data);
			$this->load->view('backdoor/footer', $this->data);
		}
	}

	//takes any query and constructs associative array with two columns (usually id and data)
	function _make_map( $query, $data_name, $index_name = 'id'  )
	{
		$ret = array();
		$result = $this->db->query( $query );
		if($result->num_rows() > 0)		//make sure we have results
		{	
			foreach( $result->result_array() as $row )
				$ret[ $row[ $index_name ] ] = $row[ $data_name ];
		}
		return $ret;
	}

	function records()
	{		
		$this->load->model('Record_Manager');

		$this->data['records'] = $this->Record_Manager->load();
		$this->data['records_total'] = $this->Record_Manager->sum_costs();
		
		$months = array( 0 => 'All Records' ) + $this->Record_Manager->get_months();
		$this->data['months'] = $months;
		$this->data['selection'] = array_pop( array_keys($months) );
		
		$this->data['ajax_view'] = 'records';
		
		$this->load->model('Crews');
		$crews = $this->Crews->load();
		$this->data['crews']['all'] = 'All';
		
		//$graph['names'][] = anchor('/backdoor/records/#all','All Crews');
		foreach( $crews as $crew )
		{
			$this->data['crews'][$crew->get_id()] = $crew->get_manager();
			//$graph['names'][] = anchor('/backdoor/records/#crew'.$crew->get_id(),$crew->get_manager());
		}
		
		//build data for the graph
		$values = $this->Record_Manager->get_by_months();
		$graph['type'] = 'line';
		$graph['cols'] = $values['months'];
		$graph['values']['Cost'] = $values['cost'];
		$graph['values']['Records'] = $values['records'];
		
		$graph['name'] = 'all';
				
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view( 'backdoor/graph', $graph );
		$this->load->view('backdoor/record_filters', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function record()
	{
		$this->load->model('Record_Manager');
		$this->load->model('Crews');
		$this->data['record'] = $this->Record_Manager->load_by_id( $this->uri->segment(3) );
		$this->data['crew'] = $this->Crews->get_crew( $this->data['record']->get_crew_id() );
				
		$this->load->view('backdoor/header', $this->data);
		$this->load->view('backdoor/record', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function load_records()
	{
		$this->load->model('Record_Manager');
		$month = $this->input->post('month');
		$crew = $this->input->post('crew');
		$view = $this->input->post('view');
		$plan_id = $this->input->post('id');
		
		//$this->session->set_userdata('record_pref', array( 'id' => $id, 'month' => $month ) );
		$this->output->enable_profiler(false);
		
		if( $month )
		{
			$month_bounds = get_month_bounds( $month );
			$this->data['records'] = $this->Record_Manager->load( $plan_id, 0, 0, $month_bounds['first'], $month_bounds['last'] );
		}
		else
			$this->data['records'] = $this->Record_Manager->load( $plan_id );
		
		if( $crew != 'all' )
			$this->data['records'] = $this->Record_Manager->filter_by_crew( $crew );
			
		$this->data['record_total'] = $this->Record_Manager->sum_costs();
		$this->data['months'] = $this->Record_Manager->get_months();
		$this->data['ajax_view'] = $view;
		$this->data['plan_id'] = $plan_id;
		
		$this->load->view('backdoor/ajax/'.$view, $this->data);
	}
	
	function search_tags()
	{
		$this->output->enable_profiler(false);
		$term = $this->input->post('term');
		
		$CI = &get_instance();
		$ret = null;
		$result = $CI->db->query( "select DISTINCT name from user_tags where name like '%" . $term . "%'" );

		if($result->num_rows() > 0)		//make sure we have results
		{	
			foreach( $result->result_array() as $row)
			{
				$ret[] = $row['name'];
			}
				
		}

		// Return data
		echo json_encode( $ret );
	}
	
	function add_tag()
	{
		$tag = $this->input->post('tag');
		$key = $this->input->post('key');
		
		$CI = &get_instance();
		$CI->db->insert( 'user_tags', array( 'key' => $key, 'name' => $tag ) );
		echo $CI->db->affected_rows() > 0;
	}
	
	function delete_tag()
	{
		$tag = $this->input->post('tag');
		$key = $this->input->post('key');
		
		$CI = &get_instance();
		$CI->db->delete( 'user_tags', array( 'key' => $key, 'name' => $tag ) );
		echo $CI->db->affected_rows() > 0;
	}

	function load_plan()
	{
		$this->output->enable_profiler(false);
		$status = $this->input->post('status');
		$this->session->set_userdata('plan_type', $status);
		echo load_maintenance_plan_table( $status );
	}
	
	function overview()
	{
		//load the maintenance table
		$this->data['plan_type'] = $this->session->userdata('plan_type');
		$this->data['table'] = load_maintenance_plan_table( $this->data['plan_type'] );
		
		$this->session->unset_userdata('record_pref');
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/overview');
		$this->load->view('backdoor/footer');
	}
	
	function users()
	{
		$contacts = array();
		
		$query = $this->db->query( 'select * from users' );
		if( $query && $query->num_rows > 0 )
		{
			foreach ($query->result_array() as $rawData)
			{
				$firstname = $rawData['fname'];
				$lastname = $rawData['lname'];
				$email = $rawData['email'];
				$status = getUserStatus($rawData['status']);
				$home_phone = format_phone_number($rawData['hphone']);
				$cell_phone = format_phone_number($rawData['cphone']);
				$region = getRegionText($rawData['region']);
				$edit_link = anchor("backdoor/editUser/".$rawData['id'], 'Edit');
				
				$contacts[] = array( $firstname ." ". $lastname, $email, $status, $home_phone, $cell_phone, $region, $edit_link );
			}
		}
		
		$this->data['contacts'] = $contacts;
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/users');
		$this->load->view('backdoor/footer');
	}
	
	function uploadGPSData()
	{
		$this->data['title'] = "Upload GPS Truck Data";
		$this->data['errors'] = "";
		$this->data['gps_data'] = "";
		
		if( $this->input->post('submit') )
		{
			$config['upload_path'] = './uploads/gps/';
			$config['allowed_types'] = 'log';
			
			$this->load->library('upload', $config);
		
			if ( !$this->upload->do_upload())
			{
				$this->data['errors'] = $this->upload->display_errors();
			}	
			else
			{
				$gps_data = $this->upload->data();
				//add an entry to the database
				$info = array( 'id' => '', 'path' => $gps_data['full_path'], 'timestamp' => mdate( "%Y%m%d%H%i%s", now() ) );
				$this->db->insert('gps_data', $info );
				$this->browseGPS("File uploaded successfully");
			}
		}
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/uploadGPSData');
		$this->load->view('backdoor/footer');
	}
	
	function browseGPS( $msg = "" )
	{
		$this->data['title'] = "GPS Truck Data";
		$this->data['message'] = $msg;
		$links= "";
		
		$query = $this->db->query( "select * from gps_data" );
		if ($query->num_rows() > 0)
		{
			foreach( $query->result() as $row )
			{
				$links[] = anchor( "backdoor/showMap/".$row->id, $row->path );
			}
		}
		
		$this->data['links'] = $links;
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/gpsData');
		$this->load->view('backdoor/footer');
	}
	
	function gpsDetail()
	{
		$this->data['title'] = "GPS Truck Data";
		$this->load->model('GPSData');
		$this->GPSData->LoadAndProcessLogFile($this->uri->segment(3));
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/gpsDetail');
		$this->load->view('backdoor/footer');
	}
	
	function createGPSXML()
	{
		$this->load->model('GPSData');
		$this->GPSData->LoadAndProcessLogFile($this->uri->segment(3));
		$this->data['gps'] = $this->GPSData->getPoints();

		$this->load->view('backdoor/createGPSXML', $this->data);
	}
	
	function showMap()
	{
		$this->data['gps_id'] = $this->uri->segment(3);
		$this->load->view('backdoor/showMap', $this->data);
	}
	
	function details()
	{
		$id = $this->uri->segment(3);
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $id );
		$this->load->model('Record_Manager');
		$this->data['title'] = "Plan Details";

		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['next_service_date'] = $this->Maintenance_Model->GetNextServiceDateTimestamp();
		$this->data['hourly_rate'] = $this->Maintenance_Model->GetHourlyRate();
		
		$records = $this->Record_Manager->load($id);
		$months = $this->Record_Manager->get_months();
		$record['months'] = array( 0 => 'All Records' ) + $months;
		
		$record_pref = $this->session->userdata('record_pref');
		if( $record_pref && is_array($record_pref) && $record_pref['id'] == $id )	$timestamp = $record_pref['month'];
		else	$timestamp = empty($months) ? 0 : array_pop( array_keys($months) );	//get last record month
		
		$month = get_month_bounds($timestamp);
	
		$record['records'] = $this->Record_Manager->filter_by_date($month['first'], $month['last']);
		$record['selection'] = $timestamp;
		$record['month'] = $month['month'];
		$record['year'] = $month['year'];
		$record['total'] = $this->Record_Manager->sum_costs();
		$record['ajax_view'] = 'client_records';
		
		$this->load->model('Crews');
		$crews = $this->Crews->load();
		$this->data['crews']['all'] = 'All';
		foreach( $crews as $crew )
			$this->data['crews'][$crew->get_id()] = $crew->get_manager();

		//get client's referrals
		$query = $this->db->query("SELECT users.email, referrals.status, referrals.record_id, UNIX_TIMESTAMP(referrals.updated) as timestamp from users, referrals where referrals.referree_user_id=users.id and referrals.referrer_user_id='".$this->data['plan']['user']['id']."'");
		$this->data['referrals'] = $query->result_array();
				
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/breadcrumbs');
		
		if( $this->data['plan']['status_id'] == PLAN_ACTIVATED )
			$this->load->view('backdoor/record_filters', $record);
			
		$this->load->view('backdoor/details', $this->data);
		$this->load->view('backdoor/footer');
	}

	//this page will list all active maintenance plans and allow user to select which records to print
	function printRecords()
	{
		if( $this->input->post('select') && $this->input->post('ids') )
		{
			$this->output->enable_profiler(false);
			$ids = $this->input->post('ids');

			$plans = array();
			$this->load->model('Maintenance_Model');
			foreach( $ids as $id )
			{
				$this->Maintenance_Model->Load( $id );
				$plans[] = $this->Maintenance_Model->GetMaintenancePlan();
			}
			
			//service date for all records
			$date = explode( '-', $this->input->post('date') );	//yy-mm-dd
			$this->data['date'] = mktime(0, 0, 0, $date[1], $date[2], $date[0]);

			$this->data['title'] = "Print Service Records";
			$this->data['plans'] = $plans;
			$this->parser->parse('backdoor/report', $this->data);
		}
		else
		{
			$this->data['title'] = "Select Plans to Print";
			$query = $this->db->query("select maintenance_plans.id, CONCAT_WS(' ', users.fname, users.lname) as client_name, regions.name as region, service_days.day from maintenance_plans, users, regions, service_days where maintenance_plans.user_id=users.id and maintenance_plans.region=regions.id and maintenance_plans.service_day=service_days.id and maintenance_plans.status=3");
			$this->data['plans'] = $query ? $query->result_array() : NULL;
	
			$this->load->view('backdoor/header', $this->data);
			$this->load->view('backdoor/printRecords', $this->data);
			$this->load->view('backdoor/footer', $this->data);
		}
	}

	function _convert_to_24hr( $hour12, $period )
	{
		$hour24 = $hour12;
		//0 - AM, 1 - PM
		if( $period == 0 && $hour12 == 12 )
			$hour24 = 0;
		else if( $period == 1 )
			$hour24 = $hour12 == 12 ? 12 : $hour12 + 12;

		return $hour24;
	}

	function addMaintenanceRecord()
	{
		$this->data['response'] = '';
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $this->uri->segment(3) );
		$this->load->model('Crews');

		if( $this->input->post('addRecord') )
		{
			if( $this->input->post('service_date') )
			{
				//get up to date transport fee
				$query = $this->db->query("select transport_fee from maintenance_plans where id='".$this->input->post('maintenance_plan_id')."'");
				$transport_fee = $query->row()->transport_fee;

				$in_hour = $this->_convert_to_24hr($this->input->post('time_in_hour'),$this->input->post('time_in_period'));
				$out_hour = $this->_convert_to_24hr($this->input->post('time_out_hour'),$this->input->post('time_out_period'));
	
				$time_in = mktime( $in_hour, $this->input->post('time_in_minute'), 0, 0, 0, 0);
				$time_out = mktime( $out_hour, $this->input->post('time_out_minute'), 0, 0, 0, 0);
				$date = explode( '-', $this->input->post('service_date') );	//yy-mm-dd
				$date_timestamp = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
	
				if(  $time_out <= $time_in )
					$this->data['response'] = error_msg("Time In cannot be after Time Out");
				else if( $date_timestamp > now() )
					$this->data['response'] = error_msg("Service date cannot be in the future");
				else
				{
					//see if we should apply a discount here
					$discount_percent = 0;
					$discount_query = $this->db->query("select referrals.id from referrals, maintenance_plans where referrals.referrer_user_id=maintenance_plans.user_id and maintenance_plans.id='".$this->input->post('maintenance_plan_id')."' and referrals.status='".REFERRAL_ACTIVATED."' limit 1");
					if( $discount_query->num_rows() == 1 )
					{
						$discount_percent = DISCOUNT_REFERRER_PERCENT;
						$this->db->query("update referrals set status='".REFERRAL_DISCOUNT_APPLIED."' where id='".$discount_query->row()->id."' limit 1");
					}

					//date
					$service_day = date("Y-m-d", mktime(0, 0, 0, $date[1], $date[2], $date[0]));
	
					$values = array( 
						'maintenance_id' => $this->input->post('maintenance_plan_id'),
						'time_in' => $in_hour.$this->input->post('time_in_minute')."00",
						'time_out' => $out_hour.$this->input->post('time_out_minute')."00",
						'client_notes' => $this->input->post('client_notes'),
						'manager_notes' => $this->input->post('manager_notes'),
						'date' => $service_day,
						'service_cost' => round( ($time_out-$time_in)/3600 * $this->Maintenance_Model->GetHourlyRate(), 2 ),
						'transport_cost' => $transport_fee,
						'discount_percentage' => $discount_percent,
						'rating' => $this->input->post('rating'),
						'crew_id' => $this->input->post('crew')
					);
	
					$this->db->insert('maintenance_record', $values);
					$new_record_id = mysql_insert_id();

					//store where we applied the discount
					if( $discount_query->num_rows() == 1 )
						$this->db->query("update referrals set record_id='".$new_record_id."' where id='".$discount_query->row()->id."' limit 1");
						
					$this->session->unset_userdata('record_pref');
						
					on_maintenance_record_add( $this->input->post('maintenance_plan_id'), $new_record_id );
				}
	
				//redirect to maintenance plan page
				if( $this->data['response'] == '' )
					redirect( "backdoor/details/".$this->uri->segment(3),"location" );
			}
			else
				$this->data['response'] = error_msg("Service date not set");
		}
		
		$this->data['title'] = "Add Maintenance Record";
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['next_service_date'] = $this->Maintenance_Model->GetNextServiceDateTimestamp();
		$this->data['records'] = $this->Maintenance_Model->GetMaintenanceRecords();
		
		//fix up the crew array
		$crews = $this->Crews->load();
		$this->data['crews'][0] = 'Unknown';
		foreach( $crews as $crew )
			$this->data['crews'][$crew->get_id()] = $crew->get_manager();

		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/addMaintenanceRecord', $this->data);
		$this->load->view('backdoor/footer');
	}

	function editMaintenanceRecord()
	{
		$this->data['response'] = '';
		$this->load->model('Maintenance_Model');
		$this->load->model('Record_Manager');
		
		$plan_id = $this->uri->segment(3);
		
		$this->Maintenance_Model->Load( $plan_id );
		$this->Record_Manager->load( $plan_id );

		if( $this->input->post('saveRecord') )
		{
			$in_hour = $this->_convert_to_24hr($this->input->post('time_in_hour'),$this->input->post('time_in_period'));
			$out_hour = $this->_convert_to_24hr($this->input->post('time_out_hour'),$this->input->post('time_out_period'));

			$time_in = mktime( $in_hour, $this->input->post('time_in_minute'), 0, 0, 0, 0);
			$time_out = mktime( $out_hour, $this->input->post('time_out_minute'), 0, 0, 0, 0);

			$date = explode( '-', $this->input->post('service_date') );	//yy-mm-dd
			$date_timestamp = mktime(0, 0, 0, $date[1], $date[2], $date[0]);

			if(  $time_out <= $time_in )
				$this->data['response'] = error_msg("Time In cannot be after Time Out");
			else if( $date_timestamp > now() )
				$this->data['response'] = error_msg("Service date cannot be in the future");
			else
			{
				//date
				$service_day = date("Y-m-d", mktime(0, 0, 0, $date[1], $date[2], $date[0]));

				$values = array( 
					'maintenance_id' => $this->input->post('maintenance_plan_id'),
					'time_in' => $in_hour.$this->input->post('time_in_minute')."00",
					'time_out' => $out_hour.$this->input->post('time_out_minute')."00",
					'client_notes' => $this->input->post('client_notes'),
					'manager_notes' => $this->input->post('manager_notes'),
					'date' => $service_day,
					'service_cost' => round( ($time_out-$time_in)/3600 * $this->Maintenance_Model->GetHourlyRate(), 2 ),
					'rating' => $this->input->post('rating')
				);

				$this->db->where('id', $this->uri->segment(4));
				$this->db->update('maintenance_record', $values); 
			}

			//redirect to maintenance plan page
			if( $this->data['response'] == '' )
				redirect( "backdoor/details/".$this->uri->segment(3),"location" );
		}

		$this->data['title'] = "Edit Maintenance Record";
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['record'] = $this->Record_Manager->get_record();

		//query ourselves so we can break up the time
		$query = $this->db->query("select id, DATE_FORMAT(time_in, '%l') as time_in_hours, DATE_FORMAT(time_in, '%i') as time_in_mins, DATE_FORMAT(time_in, '%p') as time_in_period, DATE_FORMAT(time_out, '%l') as time_out_hours, DATE_FORMAT(time_out, '%i') as time_out_mins,DATE_FORMAT(time_out, '%p') as time_out_period, date, rating, client_notes, manager_notes from maintenance_record where maintenance_id='".$this->uri->segment(3)."' and id='".$this->uri->segment(4)."'");
		if( $query->num_rows() == 1 )
		{
			$this->data['record'] = $query->row_array();

			//function expects the period (AM, PM) to be numeric
			$this->data['record']['time_in_period'] = $this->data['record']['time_in_period'] == "AM" ? 0 : 1;
			$this->data['record']['time_out_period'] = $this->data['record']['time_out_period'] == "AM" ? 0 : 1;
		}

		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/editMaintenanceRecord', $this->data);
		$this->load->view('backdoor/footer');
	}

	function deleteMaintenanceRecord()
	{
		$this->load->model('Record_Manager');
		if( $this->input->post('deleteRecord') )
		{
			//delete the maintenance record
			$record_id = $this->uri->segment(4);
			$this->db->query("delete from maintenance_record where id='".$record_id."'");

			redirect( "backdoor/details/".$this->uri->segment(3),"location" );
		}
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $this->uri->segment(3) );
		$this->data['title'] = "Delete Maintenance Record";
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['record'] = $this->Maintenance_Model->GetMaintenanceRecord( $this->uri->segment(4) );

		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/deleteMaintenanceRecord', $this->data);
		$this->load->view('backdoor/footer');
	}

	function detailsMaintenanceRecord()
	{
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $this->uri->segment(3) );
		$this->data['title'] = "Details of Maintenance Record";
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['record'] = $this->Maintenance_Model->GetMaintenanceRecord( $this->uri->segment(4) );

		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/detailsMaintenanceRecord', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function _extractFromArray( $array, $name )
	{
		$result = array();
		foreach( $array as $row )
			$result[ $row['id'] ] = $row[ $name ];
			
		return $result;
	}
	
	function editMaintenancePlan()
	{
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $this->uri->segment(3) );
		$plan = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['response'] = '';
		$this->data['title'] = "Edit Maintenance Plan";
		
		if( $this->input->post('editMaintenanceSubmit') )
		{
			$bSave = true;

			if( $this->input->post('status') == PLAN_ACTIVATED ) //we are attempting to activate the plan
			{
				//make sure we have set the start date
				if( !$this->input->post('start_date') )
				{
					$this->data['response'] = error_msg("To activate plan, you must set a start date");
					$bSave = false;
				}
				else
				{
					//set the service day to the same as the start day
					$date = explode( '-', $this->input->post('start_date') );	//yy-mm-dd
					$service_day = date("N", mktime(0, 0, 0, $date[1], $date[2], $date[0]));
					$this->Maintenance_Model->SetServiceDay( $service_day );
					$this->Maintenance_Model->SetStartDate( $this->input->post('start_date') );

					//check to see if we have been referred
					$query = $this->db->query("select referrals.id as referral_id, CONCAT_WS(' ', users.fname, users.lname) as name, users.email, maintenance_plans.id as maintenance_id, maintenance_plans.region from users, maintenance_plans, referrals where referrals.referree_user_id='".$plan['user']['id']."' and referrals.referrer_user_id=maintenance_plans.user_id and referrals.referrer_user_id=users.id and referrals.status='".REFERRAL_SIGNEDUP."'");
					
					//must be in the same region to get discount
					if( $query->num_rows() == 1 && $query->row()->region == $plan['region_id'] )
					{
						$referral_id = $query->row()->referral_id;
						$referrer_name = $query->row()->name;
		
						//update this referral
						$this->db->query("update referrals set status='".REFERRAL_ACTIVATED."' where id='".$referral_id."' LIMIT 1");

						//send the referrer an email letting them know
						$this->load->model('Email_Model');
						$data['fname'] = $plan['user']['fname'];
						$this->Email_Model->initialize( $query->row()->email, EMAIL_REFER_SIGNUP, $data );
						$this->Email_Model->send_email();

						//output a message
						$this->data['response'] = info_msg( $plan['user']['fname'] . " was referred to us by ".$referrer_name.". A discount will be applied to ".$referrer_name."'s plan");
					}
				}
			}
			
			if( $bSave )
			{

				$this->Maintenance_Model->SetServiceTime( $this->input->post('service_time') );
				$this->Maintenance_Model->SetServiceCycle( $this->input->post('service_cycle') );
				$this->Maintenance_Model->SetGardenSize( $this->input->post('garden_size') );

				if( $this->input->post('status') != PLAN_ACTIVATED ) //not activating plan
					$this->Maintenance_Model->SetServiceDay( $this->input->post('service_day') );
				if(  $this->input->post('start_date') )
					$this->Maintenance_Model->SetStartDate( $this->input->post('start_date') );

				//if the plan status has changed
				if( $plan['status_id'] != $this->input->post('status') )
				{
					$this->Maintenance_Model->SetStatus( $this->input->post('status') );

					if( $this->input->post('email') == 'yes' )
					{
						$this->load->model('Email_Model');
						$data['fname'] = $plan['user']['fname'];

						//the status of the plan has changed and we are told to email the client
						switch( $this->input->post('status') )
						{
						case PLAN_QUERY: //Query - do nothing
							break;
						case PLAN_PENDING:	//Pending
							break;
						case PLAN_ACTIVATED: //Activated
							{
								//set status to Activated
								$data['start_date'] = date( 'l jS F', $this->Maintenance_Model->GetNextServiceDateTimestamp() );
								$this->Email_Model->initialize( $plan['user']['email'], EMAIL_ACTIVATE, $data );
								$this->Email_Model->send_email();
								break;
							}
						case PLAN_ONHOLD: //On Hold
							{
								$this->Email_Model->initialize( $plan['user']['email'], EMAIL_ONHOLD, $data );
								$this->Email_Model->send_email();
								break;
							}
						default:
							break;
						}
					}
				}

				if( $this->data['response'] )
					$this->data['response'] .= success_msg('Maintenance Plan Updated');
				else
					$this->data['response'] = success_msg('Maintenance Plan Updated');
				
				$this->Maintenance_Model->Save();
			}
		}
		
		
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		
		$this->data['statusData'] = $this->Maintenance_Model->GetStatusArray();
		//$this->data['regions'] = $this->Maintenance_Model->GetRegions();
		$this->data['service_days'] = $this->_extractFromArray( $this->Maintenance_Model->GetServiceDays(true), 'day' );
		$this->data['service_time'] = $this->_extractFromArray( $this->Maintenance_Model->GetServiceTimes(true), 'name' );
		$this->data['service_cycle'] = $this->_extractFromArray( $this->Maintenance_Model->GetServiceCycles(true), 'cycle' );
		$this->data['garden_size'] = $this->_extractFromArray( $this->Maintenance_Model->GetGardenSizes(true), 'size');
		$this->data['regions'] = $this->Maintenance_Model->GetAllRegions();
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/editMaintenance', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function editServices()
	{
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->Load( $this->uri->segment(3) );
		$this->data['title'] = "Edit Maintenance";
		
		//changes have been submitted to be made
		if( $this->input->post('serviceEditSubmit') )
		{
			$plan = $this->Maintenance_Model->GetMaintenancePlan();
			$maintenance_id = $this->Maintenance_Model->GetId();
			$services = $plan['services'];
			$plan_service_ids = array();
			foreach( $services as $service )
				$plan_service_ids[] = $service['id'];
			$remove = array_diff($plan_service_ids, $_POST);
			
			$excluded_ids = $this->Maintenance_Model->getExcludedServiceIds();
			$all_services = $this->db->query( "select id, price from services where enabled='1'" );
			$all_service_ids = array();
			$prices = array();
			foreach( $all_services->result() as $service )
			{
				$all_service_ids[] = $service->id;
				$prices[ $service->id ] = $service->price;
			}
			$add = array_intersect($excluded_ids, $_POST);
			
			//first remove services
			if( count($remove) > 0 )
			{
				$query = "delete from maintenance_to_services where maintenance_id='".$maintenance_id."' AND";
				$i = 0;
				foreach($remove as $id)
				{
					$query .= " service_id=".$id;
					if( $i != count($remove)-1 )
						$query .= " OR";
					$i++;
				}
				
				$this->db->query( $query );
			}
			
			//now add services
			if( count($add) > 0 )
			{
				$query = "insert into maintenance_to_services values ";
				$i = 0;
				foreach($add as $id)
				{
					$query .= "('".$maintenance_id."','".$id."','".$prices[ $id ]."',CURRENT_TIMESTAMP)";
					if( $i != count($add)-1 )
						$query .= ", ";
					$i++;
				}
				
				$this->db->query( $query );
			}
		}	
		
		$this->data['plan'] = $this->Maintenance_Model->GetMaintenancePlan();
		$this->data['excluded'] = $this->Maintenance_Model->getExcludedServices();
		$this->data['hourly_rate'] = $this->Maintenance_Model->GetHourlyRate();
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/editServices', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function editUser()
	{
		$this->load->model('User_Model');
		$this->data['response'] = "";
		
		$this->form_validation->set_rules('fname', 'First Name', 'trim|min_length[3]|max_length[24]|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|min_length[3]|max_length[24]|xss_clean');
		$this->form_validation->set_rules('hphone', 'Home Phone', 'trim|exact_length[7]|xss_clean');
		$this->form_validation->set_rules('cphone', 'Cell Phone', 'trim|exact_length[7]|xss_clean');	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|min_length[6]|max_length[64]|xss_clean');
		$this->form_validation->set_rules('address2', 'Address', 'trim|xss_clean');
		$this->form_validation->set_rules('directions', 'Directions', 'trim|xss_clean');
		
		if( $this->input->post("editUserSubmit") && $this->form_validation->run() )
		{
			$this->User_Model->SetId( $this->input->post('id') );
			$this->User_Model->SetFirstName( $this->input->post('fname') );
			$this->User_Model->SetLastName( $this->input->post('lname') );
			$this->User_Model->SetHomePhone( $this->input->post('hphone') );
			$this->User_Model->SetCellPhone( $this->input->post('cphone') );
			$this->User_Model->SetEmail( $this->input->post('email') );
			$this->User_Model->SetAddress( $this->input->post('address'), $this->input->post('address2') );
			$this->User_Model->SetRegion( $this->input->post('region') );
			$this->User_Model->SetDirections( $this->input->post('directions') );
			$this->User_Model->SetStatus( $this->input->post('status') );
			
			$this->User_Model->Update();
			
			$this->data['response'] = success_msg("User updated");
		}
		
		$user_id = $this->uri->segment(3);
		$this->User_Model->Load( $user_id );
		$this->data['user'] = $this->User_Model->GetUser();
		$this->data['title'] = "Edit User";
		$this->data['regions'] = $this->Maintenance_Model->GetAllRegions();
		$this->data['existing_tags'] = load_tags( $user_id );
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/editUser', $this->data);
		$this->load->view('backdoor/footer');
	}
	
	function calenderView()
	{
		$this->data['title'] = "Backdoor";
		
		$this->parser->parse('backdoor/header', $this->data);
		$this->load->view('backdoor/calender');
		$this->load->view('backdoor/footer');
	}
}

?>