<?php

class Quote_Model extends CI_Model {

	var $id;
	var $ip_address;
	var $timestamp;
	var $referer;
	var $service_price;
	var $region;
	var $status;
	var $service_day;
	var $service_time;
	var $garden_size;
	var $service_cycle;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function CreateNew()
	{
		$mysql_datestring = "%Y%m%d%H%i%s";
		
		$this->ip_address = $this->session->userdata('ip_address');
		
		//generate ID for this session
		$this->id = md5( time(). $this->ip_address );
		$this->session->set_userdata( 'id', $this->id );
		
		$referer = $this->input->server('HTTP_REFERER');		
		
		$this->timestamp = mdate( $mysql_datestring, now() ); 
		$this->referer = $referer;
		$this->service_price = 0;
		$this->service_day = "Monday";
		$this->service_time = 1;
		$this->garden_size = 1;
		$this->service_cycle = 1;
	
		$this->region = 0;
		$this->status = 1;	//just arrived at the frontpage to the quote system
			
		$this->Save();
	}
	
	function LoadFromSessionData()
	{
		
		//try to load a quote from the session data
        $quote_id = $this->session->userdata('id');
            
        log_message('debug', "Attempting to load Quote Id: " . $quote_id );
    	if( $quote_id )
    	{
    		//just because the id exists doesn't mean that a database entry does, so check it
        	$query = $this->db->get_where( QUOTE_TABLE_NAME, array('id' => $quote_id), 1, 0);
        	if( $query->num_rows > 0 )
        	{
        		ASSERT( $query->num_rows == 1 );
    			$row = $query->row();
    		    		
    			$this->id = $row->id;
    			$this->ip_address = $row->ip_address;
    			$this->timestamp = $row->timestamp;
    			$this->referer = $row->referer;
    			$this->service_price = $row->service_price;
    			$this->service_day = $row->service_day;
				$this->service_time = $row->service_time;
				$this->garden_size = $row->garden_size;
				$this->service_cycle = $row->service_cycle;
    			$this->region = $row->region;
    			$this->status = $row->status;
    			
    			log_message('debug', "Loaded Quote Id: " . $quote_id );
    			return TRUE;	//loaded
    		}
    	}
    	
    	log_message('debug', 'Cannot find Id: ' . $quote_id);	//not loaded
    	return FALSE;	
	}
	
	function Load( $id )
	{
		$query = $this->db->get_where( QUOTE_TABLE_NAME, array('id' => $id), 1, 0);
		if( $query->num_rows > 0 )
        {
			ASSERT( $query->num_rows == 1 );
			$row = $query->row();
					
			$this->id = $row->id;
			$this->ip_address = $row->ip_address;
			$this->timestamp = $row->timestamp;
			$this->referer = $row->referer;
			$this->service_price = $row->service_price;
			
			$this->service_day = $row->service_day;
			$this->service_time = $row->service_time;
			$this->garden_size = $row->garden_size;
			$this->service_cycle = $row->service_cycle;
			
			$this->region = $row->region;
			$this->status = $row->status;
		
			return TRUE;	//loaded
    	}
		return FALSE;	
	}
	
	function Save()
	{
    	$query = $this->db->get_where( QUOTE_TABLE_NAME, array('id' => $this->id), 1, 0);
    	if( $query->num_rows > 0 )
    	{
    		ASSERT( $query->num_rows == 1 );
 			//do an update
    		$this->db->where('id', $this->id);
			$this->db->update( QUOTE_TABLE_NAME, $this);
			
			log_message('debug', "Updating Quote Id: " .$this->id );
 		}
 		else
 		{
 			//insert a new data entry
 			$this->db->insert( QUOTE_TABLE_NAME, $this);
 			
 			log_message('debug', "Saving new Quote Id: " . $this->id );
 		}
	}
	
	//saves a service that was selected by the user (quote id)
	function SaveSelectedService( $service_item )
	{
		//only save if this pair does not exist
		$query = $this->db->get_where( QUOTE_TO_SERVICE_TABLE_NAME, array( 'quote_id' => $this->id, 'service_id' => $service_item ), 1, 0);
		if( $query->num_rows == 0 )
 			$this->db->insert( QUOTE_TO_SERVICE_TABLE_NAME, array( 'quote_id' => $this->id, 'service_id' => $service_item ) );
	}
	
	function _LoadStatusText()
	{
		$query = $this->db->get_where( STATUS_TABLE_NAME, array('id' => $this->status), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->text;
    	}
	}
	
	function _LoadRegionText()
	{
		$query = $this->db->get_where( REGION_TABLE_NAME, array('id' => $this->region), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->name;
    	}
	}
	
	function GetQuote()
	{
		$display_datestring = "%l %j%S %F %o - %h:%i %a";
		
		$data = array(
			'id' => $this->id,
			'ip_address' => $this->ip_address,
			'timestamp' => mdate( $display_datestring, mysql_to_unix( $this->timestamp ) ),
			'referer' => $this->referer,
			'service_price' => $this->GetServicePrice(),
			'maintenance_time' => $this->GetMaintenanceTime(),
			'visitsPerMonth' => $this->GetVisitsPerMonth(),
			'transportFee' => $this->GetRegionPrice(),
			'serviceDay' => $this->service_day,
			'serviceTime' => $this->service_time,
			'serviceCycle' => $this->service_cycle,
			'servicePrice' => $this->service_price,
			'gardenSize' => $this->garden_size,
			'region' => $this->_LoadRegionText(),
			'region_id' => $this->region,
			'status' => $this->_LoadStatusText()
		);
		
		return $data;
	}
	
	function GetAllServices()
	{
		$services = array();
		$this->db->order_by("order", "asc"); 
		$query_services = $this->db->get_where( SERVICES_TABLE_NAME, array( 'enabled' => '1' ) );
				
		ASSERT($query_services->num_rows() > 0);
		if ($query_services->num_rows() > 0)
		{
			$services = $query_services->result_array();
			foreach ($services as $item => $service)
			{
				//get the service list for this service
				$this->db->select('list_item');
				$query_service_list = $this->db->get_where( SERVICE_LIST, array('service_id' => $service['id']) );
				$services[$item]['service_list'] = $query_service_list->result_array();
			}
		} 
		
		return $services;
	}
	
	//returns all the services that are included with this quote
	function GetSelectedServices()
	{
		$query_str = "select services.name, services.price from services, quotes_to_services where quotes_to_services.quote_id='".$this->id."' and quotes_to_services.service_id = services.id";
		$query = $this->db->query($query_str);
		
		return $query->result_array();
	}
	
	function GetRegions()
	{
		$query = $this->db->query('SELECT * FROM ' . REGION_TABLE_NAME . ' where enabled="1"' );
		ASSERT($query->num_rows() > 0);
		return $query->result_array();
	}
	
	function GetRegion()
	{
		$this->_LoadRegionText();
	}
	
	function SetServicePrice( $service_price )
	{
		assert( $service_price != FALSE );
		$this->service_price = $service_price;
	}
	
	function SetServiceDay( $service_day )
	{
		assert( $service_day != FALSE );
		$this->service_day = $service_day;
	}
	
	function SetServiceTime( $service_time )
	{
		assert( $service_time != FALSE );
		$this->service_time = $service_time;
	}
	
	function SetGardenSize( $garden_size )
	{
		assert( $garden_size != FALSE );
		$this->garden_size = $garden_size;
	}
	
	function SetServiceCycle( $service_cycle )
	{
		assert( $service_cycle != FALSE );
		$this->service_cycle = $service_cycle;
	}
	
	function GetMaintenanceTime()
	{
		$query = $this->db->get_where( GARDEN_SIZE_TABLE_NAME, array('id' => $this->garden_size ) );
		ASSERT($query->num_rows() > 0);
		return $query->row()->timeHours;
	}
	
	function GetVisitsPerMonth()
	{
		$visitsPerMonth = 0;
		switch( $this->service_cycle )
		{
			case 1: //weekly
				$visitsPerMonth = 4;
				break;
			
			case 2: //bi-monthly
				$visitsPerMonth = 2;
				break;
				
			case 3:	//montly
				$visitsPerMonth = 1;
				break;
				
			case 4: //once every two months
				$visitsPerMonth = 0.5;
				break;
		}
		
		return $visitsPerMonth;
	}
	
	function GetRegionPrice()
	{
		$query = $this->db->get_where( REGION_TABLE_NAME, array('id' => $this->region), 1, 0);
    	ASSERT($query->num_rows == 1);
    	return $query->row()->price;
	}
	
	function GetFormatedServicePrice()
	{
		return number_format($this->service_price, 2, '.', ',');
	}
	
	function GetServicePrice()
	{
		return $this->service_price;
	}
	
	function GetId()
	{
		ASSERT( $this->id != '' );
		return $this->id;
	}
	
	function SetRegion( $region )
	{
		$this->region = $region;
	}
	
	function SetStatus( $status )
	{
		$this->status = $status;
	}
}