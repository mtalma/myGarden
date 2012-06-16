<?php

class Mygarden extends CI_Controller {

	var $allowed_ip;

	public function __construct()
    {
    	parent::__construct();
    	
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->load->library('email', 'url', 'email_library');
		$this->load->helper('mygarden');

		//these ip's gain access to the site during maintenance mode
		$this->allowed_ip = array( '::1' );
	}
	
	function _remap($method)
	{
	    if( MAINTENANCE_MODE && !in_array( $_SERVER['REMOTE_ADDR'], $this->allowed_ip ) )
		{
			//page options
			$data['load_summary_view'] = FALSE;
			$data['side_bar_section'] = 2;
			
			$data['title'] = "Under Maintenance | Foliage Design";
			$data['body_class'] = 'site_maintenance_custom';
					
			//load views
			$this->load->view('header', $data);
			$this->parser->parse('site_maintenance', $data);
			$this->load->view('footer');
		}
		else
		{
        	$this->$method();
        }
	}
	
	function index()
	{	
		$data['title'] = "Welcome to myGarden | Foliage Design";
		$data['body_class'] = 'plan-custom';
		
		$this->load->model('Maintenance_Model');
		$data['debug_info'] = $this->_GetDebugInfo();
		$data['services'] = $this->Maintenance_Model->GetServices();
		$data['regions'] = $this->Maintenance_Model->GetRegions();
		$data['service_days'] = $this->Maintenance_Model->GetServiceDays();
		$data['service_times'] = $this->Maintenance_Model->GetServiceTimes();
		$data['service_cycle'] = $this->Maintenance_Model->GetServiceCycles();
		$data['garden_size'] = $this->Maintenance_Model->GetGardenSizes();

		//page options
		$data['load_summary_view'] = TRUE;
		$data['side_bar_section'] = 2;
				
		//load views
		$this->load->view('header', $data);
		$this->parser->parse('intro', $data);
		$this->load->view('sidebar', $data);
		$this->load->view('footer');
	}
	
	function faq()
	{
		$data['title'] = "myGarden FAQ | Foliage Design";
		$data['body_class'] = 'plan-custom';
		$data['load_summary_view'] = FALSE;
		$data['side_bar_section'] = 3;
		$data['debug_info'] = $this->_GetDebugInfo();
		
		$this->load->view('header', $data);
		$this->parser->parse('faq', $data);
		$this->load->view('sidebar', $data);
		$this->load->view('footer');
	}
	
	function service_modal()
	{
		$service_id = $this->uri->segment(3);	//service id
		$services = $this->Maintenance_Model->GetServices();

		$data['service'] = "";
		foreach( $services as $service )
		{
			if( $service['id'] == $service_id )
				$data['service'] = $service;
		}
		
		$this->load->view('service_item', $data);
	}

	function email_check($email)
	{
		$bValid = false;

		if( email_exist($email) )
		{
			//if the email exists, it has to be a referral
			$query = $this->db->query("select status from users where email='".$email."'");
			if( $query->row()->status == USER_REFERRED )
				$bValid = true;
		}
		else
			$bValid = true;

		if( !$bValid )
			$this->form_validation->set_message('email_check', 'This Email Address is already taken.');

		return $bValid;
	}
	
	function calculate()
	{
		
		if( $this->input->post('submit') == FALSE )	//so that we don't show these errors when coming from home
		{
			$this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[24]|xss_clean');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[24]|xss_clean');
			$this->form_validation->set_rules('phone', 'Home Phone', 'trim|alpha_dash|required|min_length[7]|xss_clean');
			$this->form_validation->set_rules('cell', 'Cell Phone', 'trim|alpha_dash|min_length[7]|xss_clean');	
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_email_check');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[6]|max_length[64]|xss_clean'); 

		}
		
		//if validation fails
		if( $this->form_validation->run() == FALSE )
		{		
			$this->load->model('Maintenance_Model');
			
			if( $this->input->post('maintenance_id') )	//if we have a maintenance id, then load from db
			{
				$maintenance_id = $this->input->post('maintenance_id');
			}
			else	//we have come from the previous page - so save and store id in a hidden field
			{
				//if we've come here directly, go back home
				if( $this->input->post('submit') == FALSE )
				{
					redirect( base_url(), 'location');	//go back home
					exit();
				}
							
				//save other info
				$this->Maintenance_Model->SetRegion( $this->input->post('region') );
				$this->Maintenance_Model->SetSignupDate( mdate( "%Y%m%d%H%i%s", now() ) );
				$this->Maintenance_Model->SetServiceDay( $this->input->post('serviceDay') );
				$this->Maintenance_Model->SetServiceTime( $this->input->post('serviceTime') );
				$this->Maintenance_Model->SetGardenSize( $this->input->post('gardenSize') );
				$this->Maintenance_Model->SetServiceCycle( $this->input->post('serviceCycle') );
				$this->Maintenance_Model->SetStatus( 1 );	//status: Query
				
				//generate a hashed id
				$this->Maintenance_Model->GenerateHashedId();
				
				//save and store our id
				$maintenance_id = $this->Maintenance_Model->Save();
				
				$services = $this->Maintenance_Model->GetServices();
				foreach( $services as $item => $service )
				{
					//if the user has selected this service
					if( $this->input->post( $service['short_name'] ) == "add" ) 
						$this->Maintenance_Model->SaveSelectedService( $service['id'], $service['price'] );
				}
			}
			
			$result = $this->Maintenance_Model->Load( $maintenance_id );
			
			if( !$result )
			{
				redirect( base_url(), 'location');	//go back home
				exit();
			}
			
			
			$data['region'] = $this->Maintenance_Model->GetRegionText();
			$data['monthlyServiceRate'] = $this->Maintenance_Model->GetHourlyRate()*$this->Maintenance_Model->GetHoursPerVisit()*$this->Maintenance_Model->GetVisitsPerMonth();
			$data['hoursPerVisit'] = $this->Maintenance_Model->GetHoursPerVisit();
			$data['visitsPerMonth'] = $this->Maintenance_Model->GetVisitsPerMonth();
			$data['transportFee'] = $this->Maintenance_Model->GetTransportFee()*$this->Maintenance_Model->GetVisitsPerMonth();
			$data['services'] = $this->Maintenance_Model->GetSelectedServices();
			
			$data['debug_info'] = $this->_GetDebugInfo();
			$data['title'] = "Welcome to myGarden";
			$data['side_bar_section'] = 2;
			$data['body_class'] = 'plan-custom';
			$data['load_summary_view'] = FALSE;
			$data['maintenance_id'] = $maintenance_id;
						
			$this->parser->parse('header', $data);
			$this->parser->parse('calculate', $data);
			$this->load->view('sidebar');
			$this->load->view('footer');
		}
		else	//form validated
		{
			$id = $this->input->post('maintenance_id');
			if( !$id )	//if we dont have a maintenance id then...
			{
				redirect( base_url(), 'location');	//...go back home
				exit();
			}
			
			$this->load->model('User_Model');

			$this->load->model('Maintenance_Model');
			$this->Maintenance_Model->Load( $id );
			$region = $this->Maintenance_Model->GetRegion();	//assuming that the user has the same region as maintenance plan
					
			//if email doesn't exist then we have a new user that was not referred
			$email = $this->input->post('email');
			$query = $this->db->query("select id, status from users where email='".$email."'");
			if( $query->num_rows() > 0 && $query->row()->status == USER_REFERRED )
			{
				$referree_id = $query->row()->id;

				$this->User_Model->SetId( $query->row()->id );
				$this->User_Model->SetFirstName( $this->input->post('fname') );
				$this->User_Model->SetLastName( $this->input->post('lname') );
				$this->User_Model->SetHomePhone( $this->input->post('phone') );
				$this->User_Model->SetCellPhone( $this->input->post('cell') );
				$this->User_Model->SetEmail( $this->input->post('email') );
				$this->User_Model->SetAddress( $this->input->post('address'), $this->input->post('address2') );
				$this->User_Model->SetRegion( $region['id'] );
				$this->User_Model->SetDirections( $this->input->post('directions') );
				$this->User_Model->SetStatus( USER_SIGNED_UP );	//now we are signed up

				$this->User_Model->Update();

				//also update referral table
				$query = $this->db->query("select users.region from users, referrals where referrals.referrer_user_id=users.id and referrals.referree_user_id='".$query->row()->id."' limit 1");
				if( $region['id'] == $query->row()->region )	//do we have the same region as the referer
					$status = REFERRAL_SIGNEDUP;
				else
					$status = REFERRAL_CANCELED;

				$this->db->query("update referrals set status='".$status."' where referree_user_id='".$referree_id."'");

			}
			else
			{
				$this->User_Model->ConstructNewFromPost();	//constructs a new user
				$this->User_Model->SetRegion( $region['id'] );
				$this->User_Model->Insert();
			}


			$this->Maintenance_Model->SetUserId( $this->User_Model->GetId() );
			$this->Maintenance_Model->SetStatus(2);	//"Pending"
			$this->Maintenance_Model->Save();

			$maintenance_plan = $this->Maintenance_Model->GetMaintenancePlan();
			
			//this is for the service list in the intro email
			$data['service_list'] = "<ul>";
			foreach( $maintenance_plan['services'] as $service)
				$data['service_list'] .= "<li>".$service['name']."</li>";
			$data['service_list'] .= "</ul>";

			$data['fname'] = $maintenance_plan['user']['fname'];
	
			$this->load->model('Email_Model');
			$this->Email_Model->initialize( $maintenance_plan['user']['email'], EMAIL_SIGNUP, $data );
			$this->Email_Model->send_email();
			
			$hashed_id = $this->Maintenance_Model->GetHashedId();

			//redirect to summary page
			redirect( '/mygarden/summary/' . $hashed_id, 'location');
		}	
		
	}
	
	function summary()
	{
		//summary landing page
		$this->load->model('Maintenance_Model');
		$this->Maintenance_Model->LoadByHashedId( $this->uri->segment(3) );
		$this->load->model('User_Model');
		
		$maintenance_plan = $this->Maintenance_Model->GetMaintenancePlan();
		ASSERT( $maintenance_plan );
		
		$data['title'] = "Welcome to myGarden";
		$data['side_bar_section'] = 2;
		$data['debug_info'] = $this->_GetDebugInfo();
		
		$data['body_class'] = 'plan-custom';
		$data['load_summary_view'] = FALSE;
		
		$data['maintenance_plan'] = $maintenance_plan;
		
		$this->parser->parse('header', $data);
		$this->load->view('summary', $data);
		$this->load->view('sidebar', $data);
		$this->load->view('footer');
	}

	function sample_invite()
	{
		$bValid = false;
		$hash_id = $this->uri->segment(3);
		if( $hash_id )
		{
			$this->load->model('Maintenance_Model');

			$this->Maintenance_Model->LoadByHashedId( $hash_id );
			$maintenance_plan = $this->Maintenance_Model->GetMaintenancePlan();
			
			if( !is_null($maintenance_plan['user']) )
			{
				$bValid = true;
				$this->load->model('Email_Model');
				$message_data = $this->Email_Model->load_message( EMAIL_INVITE );
				
				$data['url'] = "http://" . $_SERVER['SERVER_NAME'];
				$data['fname'] = $maintenance_plan['user']['fname'];
				$data['region'] = $maintenance_plan['user']['region'];

				echo $email_text = $this->parser->parse_string($message_data->body, $data, TRUE);
			}
		}

		if( !$bValid )
			echo "We're sorry, an unexpected error occurred";
	}
	
	function list_services()
	{
		$hash_id = $this->uri->segment(3);
		$record_id = $this->uri->segment(4);
		
		$data['title'] = "myGarden Rate your Service";
		$data['body_class'] = 'plan-custom referral';
		$data['debug_info'] = $this->_GetDebugInfo();
		
		if( $hash_id )
		{
			$this->load->model('Maintenance_Model');
			
			if( $this->Maintenance_Model->LoadByHashedId( $hash_id ) )
			{
				$data['hash_id'] = $hash_id;
			
				if( $record_id ) // if record id is given then display rating page
				{	
					if( $this->input->post('rate') )
					{
						// update our record
						$values = array( 
							'client_notes' => $this->input->post('feedback'),
							'rating' => $this->input->post('rating')
						);
		
						$this->db->where( 'id', $record_id )->update('maintenance_record', $values);
						$this->session->set_flashdata('response', 'Service record rating saved');
						redirect( base_url() . 'index.php/mygarden/list_services/'.$hash_id, 'location');
					}
					
					$data['record'] = $this->Maintenance_Model->GetMaintenanceRecord($record_id);
					
					if( $data['record'] )
					{
						$data['record_id'] = $record_id;
					
						$this->parser->parse('header', $data);
						$this->load->view('rate_service', $data);
						$this->load->view('footer');
					}
					else
					{
						// record not found
						display_error_page('Service record #'.$record_id.' was not found for maintenance plan: '.$hash_id);
					}
				}
				else // if not record if then just display list of records
				{
					$data['records'] = $this->Maintenance_Model->GetMaintenanceRecords();
					$data['response'] = $this->session->flashdata('response');
					
					$this->parser->parse('header', $data);
					$this->load->view('list_services', $data);
					$this->load->view('footer');
				}
			}
			else
			{
				// maintenance plan not found
				display_error_page('Maintenance plan not found with id: ' .$hash_id);
			}
		}
		else
			redirect( base_url(), 'location');
	}

	function referral()
	{
		$bValid = false;
		$hash_id = $this->uri->segment(3);

		if( $hash_id )
		{
			$this->load->model('Maintenance_Model');

			$this->Maintenance_Model->LoadByHashedId( $hash_id );
			$maintenance_plan = $this->Maintenance_Model->GetMaintenancePlan();

			//this plan must be signed up and be activated to send referrals
			if( !is_null($maintenance_plan['user']) && $maintenance_plan['status_id'] == PLAN_ACTIVATED )
			{
				$bValid = true;

				$data['fname'] = $maintenance_plan['user']['fname'];
				$data['region'] = $maintenance_plan['user']['region'];
				$data['hashed_id'] = $hash_id;

				$this->form_validation->set_error_delimiters('<p class="error-note">', '</p>');
				$this->form_validation->set_rules('email1', 'Email', 'trim|valid_email|xss_clean');
				$this->form_validation->set_rules('email2', 'Email', 'trim|valid_email|xss_clean');
				$this->form_validation->set_rules('email3', 'Email', 'trim|valid_email|xss_clean');
				$this->form_validation->set_rules('email4', 'Email', 'trim|valid_email|xss_clean');
				$this->form_validation->set_rules('email5', 'Email', 'trim|valid_email|xss_clean');
				$this->form_validation->set_message('valid_email', 'email field must contain a valid email address.');

				$bEmailsEntered = false;

				if( $this->input->post('invite') )	//submit pressed
				{
					$emails = array();
					$sent_emails = array();
					if( $this->input->post('email1') ) $emails[] = $this->input->post('email1');
					if( $this->input->post('email2') ) $emails[] = $this->input->post('email2');
					if( $this->input->post('email3') ) $emails[] = $this->input->post('email3');
					if( $this->input->post('email4') ) $emails[] = $this->input->post('email4');
					if( $this->input->post('email5') ) $emails[] = $this->input->post('email5');

					$bEmailsEntered = count($emails) > 0;

					if( $this->form_validation->run() && $bEmailsEntered )
					{
						foreach( $emails as $email )
						{
							//check to see if we already have this email - either signed up or referred already
							if( !email_exist( $email ) )
							{
								$this->load->model('Email_Model');
								$this->Email_Model->initialize( $email, EMAIL_INVITE, $data );
								$this->Email_Model->send_email();
	
								//enter email as a user
								$this->db->query("insert into users (id, email, status) VALUES (NULL, '".$email."', ".USER_REFERRED.")");
	
								//record that we've sent these invites
								$this->db->query("insert into referrals (id, referrer_user_id, referree_user_id) VALUES (NULL, '".$maintenance_plan['user']['id']."', '".mysql_insert_id()."')");
	
								//store the emails we've sent for confirmation
								$sent_emails[] = $email;
							}
						}
	
						$data['sent_emails'] = $sent_emails;
	
						$data['title'] = "myGarden Invites Sent";
						$data['body_class'] = 'plan-custom referral';
						$data['debug_info'] = $this->_GetDebugInfo();
			
						$this->parser->parse('header', $data);
						$this->load->view('referral-thanks', $data);
						$this->load->view('footer');
					}
				}

				//display the front page
				if( $this->form_validation->run() == FALSE || !$bEmailsEntered )
				{
					$data['title'] = "myGarden Referrals";
					$data['body_class'] = 'plan-custom referral';
					$data['debug_info'] = $this->_GetDebugInfo();
							
					$this->parser->parse('header', $data);
					$this->load->view('referral', $data);
					$this->load->view('footer');
				}
			}
		}

		if( !$bValid )	//the supplied hashed id is not valid
		{
			redirect( base_url(), 'location');
		}

	}
	
	function _GetDebugInfo()
	{
		if( DEBUG_MODE )
		{
			$debug['quote_info'] = $this->Maintenance_Model->GetMaintenancePlan();
			return $debug['quote_info'] ? $this->load->view('debug_info', $debug, TRUE) : "";
		}
		
		return "";
	}
}
?>