<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_Model extends CI_Model {

	var $id;
	var $user_id;
	var $region;
	var $transport_fee;
	var $signup_date;
	var $start_date;
	var $status;
	var $service_time;
	var $service_cycle;
	var $garden_size;
	var $service_day;
	var $hashed_id;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function Save()
	{
		//if we have an id then we update the maintenance plan
		if( $this->id )
		{
			$this->db->where('id', $this->id);
			$this->db->update( MAINTENANCE_TABLE_NAME, $this); 
		}
		else	//new entry, do an insertion
		{
			$this->db->insert( MAINTENANCE_TABLE_NAME, $this);
			$this->id = mysql_insert_id();
		}
		
		return $this->id;
    }
    
    function Load( $id )
    {
    	//load a maintenance plan with the supplied id
		$query = $this->db->get_where( MAINTENANCE_TABLE_NAME, array('id' => $id), 1, 0);  
		if( $query->num_rows == 0 )
		{
			return false;
		}
		
		$data = $query->row();
		$this->id = $data->id;
		$this->user_id = $data->user_id;
		$this->region = $data->region;
		$this->transport_fee = $data->transport_fee;
		$this->signup_date = $data->signup_date;
		$this->start_date = $data->start_date;
		$this->service_day = $data->service_day;
		$this->service_time = $data->service_time;
		$this->service_cycle = $data->service_cycle;
		$this->garden_size = $data->garden_size;
		$this->status = $data->status;
		$this->hashed_id = $data->hashed_id;
		
		return true;
	}
	
	function GenerateHashedId()
	{
		//an obfuscated id
		$this->hashed_id = md5( time() . rand(0,1000) );
		return $this->hashed_id;
	}
	
	function GetHashedId()
	{
		return $this->hashed_id;
	}
	
	function LoadByHashedId( $hashed_id )
	{
		$maintenance = null;
		
		$query = $this->db->query("select id from " . MAINTENANCE_TABLE_NAME . " where hashed_id='". $hashed_id ."'" );
		if( $query->num_rows > 0 )
			$maintenance = $this->Load( $query->row()->id );
			
		return $maintenance;
	}
	
	/*function LoadByUserId( $user_id )
	{
		$bHasPlan = false;

		$query = $this->db->query( "select id from ". MAINTENANCE_TABLE_NAME . " where user_id=".$user_id );
		if( $query->num_rows > 0 )
		{
			$this->Load( $query->row()->id );
			$bHasPlan = true;
		}

		return $bHasPlan;
	}*/
	
	function GetMaintenancePlan()
	{
		if( $this->id )	//if plan has been loaded
		{
			$maintenance_plan = array();
			
			$maintenance_plan['id'] = $this->id;	//the user's maintenance plan
			
			//get user information if we got a user_id (may not have a user)
			if( $this->user_id )
			{
				$CI =& get_instance();
				$CI->load->model('User_Model');
				$CI->User_Model->Load( $this->user_id );
				$maintenance_plan['user'] = $CI->User_Model->GetUser();
			}
			
			$maintenance_plan['region'] = $this->_LoadRegionText( $this->region );
			$maintenance_plan['region_id'] = $this->region;
			$maintenance_plan['transport_fee'] = $this->transport_fee;
			$maintenance_plan['signup_date'] = $this->signup_date;
			$maintenance_plan['start_date'] = $this->start_date;
			
			$status_array = $this->GetStatusArray();
			$maintenance_plan['status'] = $status_array[ $this->status ];
			$maintenance_plan['status_id'] = $this->status;
			$maintenance_plan['service_time'] = $this->LoadSlotText( $this->service_time );
			$maintenance_plan['service_time_id'] = $this->service_time;
			$maintenance_plan['service_cycle'] = $this->LoadCycleText( $this->service_cycle );
			$maintenance_plan['service_cycle_id'] = $this->service_cycle;
			$maintenance_plan['garden_size'] = $this->_LoadGardenSizeText( $this->garden_size );
			$maintenance_plan['garden_size_id'] = $this->garden_size;
			$maintenance_plan['service_day'] = $this->LoadServiceDayText( $this->service_day );
			$maintenance_plan['service_day_id'] = $this->service_day;
			
			//get services included in this maintenance and quoted prices (not current)
			$query_str = "select services.id, services.name, services.short_name, services.description, maintenance_to_services.quoted_price from services, maintenance_to_services where maintenance_to_services.maintenance_id='".$this->id."' and maintenance_to_services.service_id = services.id";
			$query = $this->db->query($query_str);
			$maintenance_plan['services'] = $query->result_array();
			$maintenance_plan['monthlyCost'] = $this->getMonthlyCost();
			$maintenance_plan['last_record'] = $this->GetLastMaintenanceRecord();
			
			return $maintenance_plan;
		}
		
		return FALSE;
	}
	
	function GetStatusArray()
	{
		$ret = array();
		
		$status = $this->db->query("select id, text from status");
		foreach( $status->result() as $row )
		{
			$ret[ $row->id ] = $row->text;
		}
		
		return $ret;
	}
	
	function getExcludedServiceIds()
	{
		$included_services_query = "select services.id, services.name, services.short_name, services.description, maintenance_to_services.quoted_price from services, maintenance_to_services where maintenance_to_services.maintenance_id='".$this->id."' and maintenance_to_services.service_id = services.id";
		$included_services = $this->db->query($included_services_query);
		
		$all_services_query = "select id from services where enabled='1'";
		$all_services = $this->db->query($all_services_query);
		
		$result_all = array();
		foreach( $all_services->result() as $row )
		{
			$result_all[] = $row->id;
		}
		
		$result_included = array();
		foreach( $included_services->result() as $row )
		{
			$result_included[] = $row->id;
		}
		
		return array_diff($result_all, $result_included );
	}
	
	function getExcludedServices()
	{
		$this->db->order_by("order", "asc"); 
		$excluded_id = $this->getExcludedServiceIds();
		if( count( $excluded_id ) != 0 )
		{
			$i = 0;
			$query = "select id, name, short_name, price from services where";
			foreach( $excluded_id as $id )
			{
				$query .= " id=".$id;
				if( $i != count( $excluded_id )-1 )
					$query .= " OR";
				$i++;
			}
			
			return $this->db->query($query)->result_array();
		}
		
		return NULL;
	}
	
	function _LoadGardenSizeText()
	{
		$query = $this->db->get_where( GARDEN_SIZE_TABLE_NAME, array('id' => $this->garden_size), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->size;
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
	
	function LoadCycleText()
	{
		$query = $this->db->get_where( SERVICE_CYCLE_TABLE_NAME, array('id' => $this->service_cycle), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->cycle;
    	}
	}
    
    function LoadServiceDayText()
    {
		$query = $this->db->get_where( SERVICE_DAYS_TABLE_NAME, array('id' => $this->service_day), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->day;
    	}
	}
	
	function LoadSlotText()
	{
		$query = $this->db->get_where( TIME_SLOT_TABLE_NAME, array('id' => $this->service_time), 1, 0);
    	if( $query->num_rows == 0 )
    		return "Not Set";
    	else
    	{
    		ASSERT($query->num_rows == 1);
    		return $query->row()->name;
    	}
	}
	
	//returns all region names in an associative array with the id being the key
	function GetAllRegions()
	{
		$regionNames = array();
		
		$query = $this->db->query('SELECT * FROM ' . REGION_TABLE_NAME );
		ASSERT($query->num_rows() > 0);
		foreach( $query->result() as $region )
		{
			$regionNames[ $region->id ] = $region->name;
		}
		return $regionNames;
	}
	
	function GetRegions()
	{
		$this->db->order_by("name", "asc"); 
		$query = $this->db->get_where( REGION_TABLE_NAME, array( 'enabled' => '1' ) );
		ASSERT($query->num_rows() > 0);
		return $query->result_array();
	}
	
	//gets region of this maintenance plan
	function GetRegion()
	{
		$query = $this->db->get_where( REGION_TABLE_NAME, array( 'id' => $this->region ) );
		ASSERT($query->num_rows() == 1);
		$region = $query->result_array();
		
		return $region[0];
	}
	
	//gets and returns all services that are enabled
	function GetServices()
	{
		$services = array();
		$this->db->order_by("list_order", "asc"); 
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
	
	function SetServices( $services )
	{
		ASSERT( $this->id );	//id must be set for this to work
		if( $this->id && count($services) > 0 )
		{
			$query = "insert into maintenance_to_services values ";
			$i = count( $services );
			foreach( $services as $item => $service )
			{
				$i--;
				$query .= "(".$this->id.",".$service['id'].",".$service['price'].",".mdate( '%Y%m%d%H%i%s', now() ).") ";
				if( $i != 0 )
					$query .= ", ";
			}
			
			$this->db->query($query);
		}
	}

	function GetMaintenanceRecords( $start = null, $end = null )
	{
		$records = false;
		if( $this->id )
		{
			$sWhere = "";
			if( $start && $end )
				$sWhere = " AND UNIX_TIMESTAMP(date) >= " . $start . " AND UNIX_TIMESTAMP(date) <= " . $end . " ";
				
			$sql = "select id, DATE_FORMAT(time_in, '%l:%i %p') as time_in, DATE_FORMAT(time_out, '%l:%i %p') as time_out, TIME_FORMAT( TIMEDIFF(time_out, time_in), '%H hrs, %i mins') as diff, date, rating, ((service_cost+transport_cost)*(1-(discount_percentage/100))) as cost from maintenance_record where maintenance_id='".$this->id."' ".$sWhere." ORDER BY date ASC";
									
			$query = $this->db->query($sql);
			if( $query->num_rows() > 0 )
				$records = $query->result_array();
		}

		return $records;
	}
	
	function GetMaintenanceRecordCount()
	{
		$count = $this->db->query("select count(*) as count from maintenance_record")->row_array();
		return $count['count'];
	}
	
	function GetAllMaintenanceRecords( $start = 0, $count = 0 )
	{
		$records = false;
		
		$limit = "";
		if( $count )
			$limit = "LIMIT " . $start . ", " . $count ;
		
		$sql = "select maintenance_record.id, maintenance_plans.id as plan_id, CONCAT_WS(' ',users.fname, users.lname) as client, DATE_FORMAT(maintenance_record.time_in, '%l:%i %p') as time_in, DATE_FORMAT(maintenance_record.time_out, '%l:%i %p') as time_out, TIME_FORMAT( TIMEDIFF(maintenance_record.time_out, maintenance_record.time_in), '%H hrs, %i mins') as diff, maintenance_record.client_notes, maintenance_record.manager_notes, maintenance_record.date, maintenance_record.rating, ((maintenance_record.service_cost+maintenance_record.transport_cost)*(1-(maintenance_record.discount_percentage/100))) as cost from maintenance_record, maintenance_plans, users where maintenance_record.maintenance_id = maintenance_plans.id AND maintenance_plans.user_id = users.id ORDER BY maintenance_record.date DESC " . $limit;
			
		$query = $this->db->query($sql);
		if( $query->num_rows() > 0 )
			$records = $query->result_array();
			
		return $records;
	}

	function GetMaintenanceRecord( $id )
	{
		$record = false;
		if( $this->id )
		{
			$query = $this->db->query("select id, DATE_FORMAT(time_in, '%l:%i %p') as time_in, DATE_FORMAT(time_out, '%l:%i %p') as time_out, TIME_FORMAT( TIMEDIFF(time_out, time_in), '%H hrs, %i mins') as diff, date, rating, client_notes, manager_notes, service_cost, transport_cost, discount_percentage from maintenance_record where maintenance_id='".$this->id."' and id='".$id."'");
			if( $query->num_rows() == 1 )
				$record = $query->row_array();
		}

		return $record;
	}
	
	function GetMaintenanceRecordTotal( $start = null, $end = null )
	{
		$total = 0;
		$sWhere = "";
		if( $start && $end )
			$sWhere = " AND UNIX_TIMESTAMP(date) >= " . $start . " AND UNIX_TIMESTAMP(date) <= " . $end . " ";
					
		$query = $this->db->query("select SUM((service_cost+transport_cost)*(1-(discount_percentage/100))) as total from maintenance_record where maintenance_id='".$this->id."' ".$sWhere);
		if( $query->num_rows() > 0 )
			$total = $query->row()->total;
			
		return $total;
	}
	
	function GetLastMaintenanceRecord()
	{
		$record = null;
		
		if( $this->id )
		{
			$query = $this->db->query("select id, DATE_FORMAT(time_in, '%l:%i %p') as time_in, DATE_FORMAT(time_out, '%l:%i %p') as time_out, TIME_FORMAT( TIMEDIFF(time_out, time_in), '%H hrs, %i mins') as diff, UNIX_TIMESTAMP(date) as date, rating, client_notes, manager_notes, service_cost, transport_cost, discount_percentage from maintenance_record where maintenance_id='".$this->id."' order by date DESC LIMIT 1");
			if( $query->num_rows() == 1 )
				$record = $query->row_array();
		}
		
		return $record;
	}

	
	function GetMaintenanceRecordMonths()
	{
		$months = array();
		
		if( $this->id )
		{
			$query = $this->db->query("select UNIX_TIMESTAMP(date) as date from maintenance_record where maintenance_id='".$this->id."' ORDER BY date ASC");
			
			foreach ( $query->result() as $record )
			{
				$record_date = getdate($record->date);
				$month = $record_date['mon'];
				$year = $record_date['year'];
				
			    $timestamp = mktime(0, 0, 0, $month, 1, $year);
			    $months[$timestamp] = date('M Y', $timestamp);
			}
			
		}
				
		return array_unique($months);
	}

	//saves a service that was selected by the user
	function SaveSelectedService( $id, $price )
	{
		//this will only save if maintenance_id and service_id are unique
		@$this->db->insert( 'maintenance_to_services', array( 'maintenance_id' => $this->id, 'service_id' => $id, 'quoted_price' => $price ) );
	}
	
	//gets services associated with this maintenance plan with the quoted price
	function GetSelectedServices()
	{
		$query_str = "select services.id, services.name, services.short_name, services.description, maintenance_to_services.quoted_price from services, maintenance_to_services where maintenance_to_services.maintenance_id='".$this->id."' and maintenance_to_services.service_id = services.id";
			$query = $this->db->query($query_str);
		
		return $query->result_array();
	}
	
	function GetHourlyRate()
	{
		$price = 0;
		$services = $this->GetSelectedServices();
		
		foreach( $services as $item => $service )
		{
			$price += $service['quoted_price'];
		}
		
		return $price;
	}
    
    function SetUserId( $user_id )
    {
    	$this->user_id = $user_id;
    }

	function GetUserId()
	{
		return $this->user_id;
	}
    
    function SetSignupDate( $date )
    {
    	$this->signup_date = $date;
    }
    
    function SetStartDate( $date )
    {
    	$this->start_date = $date;
    }
    
    function getCycleUnixTimestamp( $visitsPerMonth = NULL )
    {
    	//determine whether we are weekly, monthly etc			
		switch( isset($visitsPerMonth) ? $visitsPerMonth : $this->GetVisitsPerMonth() )
    	{
			case 4: //weekly
				$cycle_time = 7*24*60*60;
				break;
			
			case 2: //bi-monthly
				$cycle_time = 2*7*24*60*60;
				break;
				
			case 1:	//monthly
				$cycle_time = 4*7*24*60*60;
				break;
				
			case 0.5: //once every two months
				$cycle_time = 8*7*24*60*60;
				break;
		}
		
		return $cycle_time;
    }
    
    function GetNextServiceDateTimestamp()
    {
    	$next_date_timestamp = 0;
    	
    	//make sure we are loaded, active and have a start date
    	if( $this->id && $this->status == PLAN_ACTIVATED && $this->start_date )
    	{
			if( $this->start_date )
				$start = mysql_date_to_timestamp( $this->start_date );
			else
			{
				$query = $this->db->query("select UNIX_TIMESTAMP(start_date) from ". MAINTENANCE_TABLE_NAME ." where id='".$this->id."'");
				$row = $query->row_array();
				$start = $row['UNIX_TIMESTAMP(start_date)'];
			}

    		if( now() < $start )
    			$next_date_timestamp = $start;
    		else
    		{
    			$cycle_time = $this->getCycleUnixTimestamp();

				$diff = now() - $start;
    			$cycles = ceil( $diff/$cycle_time );
				$time = ($cycles*$cycle_time)/(7*24*60*60);	//in weeks
    			$next_date_timestamp = strtotime("+".$time." week", $start );
    		}
    	}
    		
    	return $next_date_timestamp;
    }
    
    function md5toColor( $md5Val )
    {
    	return "#".substr( $md5Val, 0, 6 );
    }
    
    //start and end must be unixtimestamps
    function getMaintenanceServicesBetweenDates( $start, $end )
    {
    	$start_abs = $start;
    	$service = array();
    	
    	//select all maintenance plans that are active
    	$query = $this->db->query("select maintenance_plans.id, maintenance_plans.hashed_id, regions.name as region, UNIX_TIMESTAMP(maintenance_plans.start_date) as start_date, maintenance_plans.service_cycle, CONCAT_WS(' ',users.fname, users.lname) as name, TIME_TO_SEC(time_slots.avg_start) as time from maintenance_plans, users, time_slots, regions where maintenance_plans.status='".PLAN_ACTIVATED."' and maintenance_plans.user_id=users.id and maintenance_plans.service_time=time_slots.id and maintenance_plans.region=regions.id");
		
		foreach( $query->result() as $plan )
		{
			$maintenance_start = $plan->start_date;
			$visitsPerMonth = $this->GetVisitsPerMonth($plan->service_cycle);
			$cycle_time = $this->getCycleUnixTimestamp($visitsPerMonth);
			$start = $start_abs;
			
			// find the first occurrence of the maintenance plan for this period
			if( $start > $maintenance_start )
			{
				while ( ( ($start - $maintenance_start) % $cycle_time ) != 0 ) 
					$start = strtotime("+1 day", $start);
			}
			else	// maintenance start is somewhere in the period
				$start = $maintenance_start;
				
				while ( $start <= $end )
				{
					$temp = (int)$plan->time + 3600;
					
				    $service[] = array( 'id' => $plan->id, 'title' => $plan->name . " - " . $plan->region, 'start' => date('c',strtotime('+'.$plan->time.' seconds',$start)), 'end' => date('c',strtotime( '+'.$temp.' seconds', $start) ), 'url' => base_url().'index.php/backdoor/details/'.$plan->id, 'color' => $this->md5toColor($plan->hashed_id), 'allDay' => false );
				    $start += $cycle_time;
				}
			
		}
    	
		return $service;

    }
    
	//sets the region and also sets the transport fee
    function SetRegion( $region )
    {
		$query = $this->db->query("select price from regions where id='".$region."'");
		$this->transport_fee = $query->row()->price;
    	$this->region = $region;
    }
    
    function GetRegionText()
    {
    	$query = $this->db->get_where( REGION_TABLE_NAME, array('id' => $this->region), 1, 0);
    	ASSERT($query->num_rows == 1);
    	return $query->row()->name;
    }
    
    //returns transport fee for each visit
    function GetTransportFee()
	{		
		$query = $this->db->get_where( REGION_TABLE_NAME, array('id' => $this->region), 1, 0);
    	ASSERT($query->num_rows == 1);
    	return $query->row()->price;
	}
    
    //takes an integer from 1-5 (1 = 'Monday' and 5 = 'Friday')
    function SetServiceDay( $service_day )
    {
    	$this->service_day = $service_day;
    }
    
    function SetStatus( $status )
    {
    	$this->status = $status;
    }
    
    function SetServiceTime( $service_time )
    {
    	$this->service_time = $service_time;
    }
    
    function SetServiceCycle( $service_cycle )
    {
    	$this->service_cycle = $service_cycle;
    }
    
    function GetVisitsPerMonth( $cycle = NULL )
	{
		$visitsPerMonth = 0;
		switch( isset($cycle) ? $cycle : $this->service_cycle )
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
	
	function GetCycleText( $cycle = NULL )
	{
		$text = 'error';
		switch( isset($cycle) ? $cycle : $this->service_cycle )
		{
			case 1: //weekly
				$text = 'weekly';
				break;
			
			case 2: //bi-monthly
				$text = 'bi-monthly';
				break;
				
			case 3:	//montly
				$text = 'monthly';
				break;
				
			case 4: //once every two months
				$text = 'once every two months';
				break;
		}
		
		return $text;
	}
    
    function SetGardenSize( $garden_size )
    {
    	$this->garden_size = $garden_size;
    }
    
    function getMonthlyCost()
    {
    	return ( $this->GetVisitsPerMonth() * ( $this->GetHoursPerVisit() * $this->GetHourlyRate() + $this->GetTransportFee() ) ) * (1 + VAT);
    }
    
    function GetHoursPerVisit()
	{
		$query = $this->db->get_where( GARDEN_SIZE_TABLE_NAME, array('id' => $this->garden_size ) );
		ASSERT($query->num_rows() > 0);
		return $query->row()->timeHours;
	}
    
    function GetId()
    {
    	return $this->id;
    }
    
    function GetServiceDays( $bAll = false )
    {
    	$result = "";
    	$str = 'SELECT * FROM service_days';
    	$str .= $bAll ? '' : ' where enabled="1"';
    	$query = $this->db->query( $str );
		if( $query->num_rows() > 0 )
			$result = $query->result_array();
		
		return $result;
    }
    
	function GetServiceTimes( $bAll = false )
	{
		$result = "";
    	$str = "SELECT id, name, enabled, DATE_FORMAT( avg_start, '%l:%i' ) AS avg_start, DATE_FORMAT( avg_end, '%l:%i' ) AS avg_end FROM time_slots";
    	$str .= $bAll ? '' : ' where enabled="1"';
    	$query = $this->db->query( $str );
		if( $query->num_rows() > 0 )
			$result = $query->result_array();
		
		return $result;
	}
	
	function GetServiceCycles( $bAll = false )
	{
		$result = "";
    	$str = 'SELECT * FROM service_cycle';
    	$str .= $bAll ? '' : ' where enabled="1"';
    	$query = $this->db->query( $str );
		if( $query->num_rows() > 0 )
			$result = $query->result_array();
		
		return $result;
	}
	
	function GetGardenSizes( $bAll = false )
	{
		$result = "";
    	$str = 'SELECT * FROM garden_size';
    	$str .= $bAll ? '' : ' where enabled="1"';
    	$query = $this->db->query( $str );
		if( $query->num_rows() > 0 )
			$result = $query->result_array();
		
		return $result;
	}
}
?>