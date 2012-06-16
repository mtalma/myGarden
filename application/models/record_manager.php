<?php

class Record 
{
	var $id;
	var $maintenance_id;
	var $client;
	var $time_in;	//stored as unix_timstamp
	var $time_out;	//stored as unix_timstamp
	var $client_notes;
	var $manager_notes;
	var $date;		//stored as unix_timstamp
	var $service_cost;
	var $transport_cost;
	var $discount_percentage;
	var $rating;
	var $crew_id;
	
	function __construct( $data )
	{
		$this->id = $data['record_id'];
		$this->maintenance_id = $data['maintenance_id'];
		$this->client = $data['fname'] . " " . $data['lname'];
		$this->time_in = strtotime($data['time_in']);
		$this->time_out = strtotime($data['time_out']);
		$this->client_notes = $data['client_notes'];
		$this->manager_notes = $data['manager_notes'];
		$this->date = strtotime($data['date']);
		$this->service_cost = $data['service_cost'];
		$this->transport_cost = $data['transport_cost'];
		$this->discount_percentage = $data['discount_percentage'];
		$this->rating = $data['rating'];
		$this->crew_id = $data['crew_id'];
	}
	
	//save this record
	function save()
	{
		assert($this->id);
		$this->_unix_to_time();
		$CI =& get_instance();
		$CI->db->where('id', $this->id);
		$CI->db->update('maintenance_record', $this);
	}
	
	function _unix_to_time()
	{
		$this->time_in = date('G-i-s',$this->time_in);
		$this->time_out = date('G-i-s',$this->time_out);
		$this->date = date( 'Y-m-d', $this->date);
	}
	
	function get_plan_id() { return $this->maintenance_id; }
	function get_start() { return $this->time_in; }
	function get_end() { return $this->time_out; }
	function get_time() { return $this->time_out - $this->time_in; }
	function get_client_notes() { return $this->client_notes; }
	function get_manager_notes() { return $this->manager_notes; }
	function get_service_date() { return $this->date; }
	function get_service_cost() { return $this->service_cost; }
	function get_transport_cost() { return $this->transport_cost; }
	function get_discount_percentage() { return $this->discount_percentage; }
	function get_rating() { return $this->rating; }
	function get_crew_id() { return $this->crew_id; }
	
	function get_cost( $apply_discount = true )
	{
		$discount = $apply_discount ? $this->get_discount_amount() : 0;
		return $this->get_subtotal() - $discount;
	}
	
	function get_discount_amount()
	{
		return $this->get_subtotal() * $this->discount_percentage/100;
	}
	
	function get_subtotal()
	{
		return $this->service_cost + $this->transport_cost;
	}
}

class Record_manager extends CI_Model 
{
	var $records;

    function __construct()
    {
        parent::__construct();
	}
	
	//if plan_id not supplied we will load all records
	function load( $plan_id = null, $limit = 0, $offset = 0, $start_date = null, $end_date = null )
	{
		$this->records = array();
		if( $start_date && $end_date )	$this->db->where('UNIX_TIMESTAMP(date) >=', $start_date)->where('UNIX_TIMESTAMP(date) <=', $end_date);		
		if( $plan_id )	$this->db->where('maintenance_id', $plan_id);
		if( $limit ) $this->db->limit( $limit, $offset );
		
		$this->db->select('*, maintenance_record.id as record_id');
		$this->db->join('maintenance_plans', 'maintenance_plans.id = maintenance_record.maintenance_id', 'left');
		$this->db->join('users', 'users.id = maintenance_plans.user_id', 'left');
		$this->db->order_by('date', 'asc');
		
		$query = $this->db->get('maintenance_record');
		if( $query->num_rows() > 0 )
		{
			foreach ( $query->result_array() as $record )
				$this->records[ $record['record_id'] ] = new Record( $record );
		}
		
		return $this->records;
	}
	
	function load_by_id( $id )
	{
		$record = null;
		$this->records = array();
	
		$this->db->select('*, maintenance_record.id as record_id');
		$this->db->join('maintenance_plans', 'maintenance_plans.id = maintenance_record.maintenance_id', 'left');
		$this->db->join('users', 'users.id = maintenance_plans.user_id', 'left');
		$this->db->where('maintenance_record.id', $id);
		$this->db->order_by('date', 'asc');
		
		$query = $this->db->get('maintenance_record');
		if( $query->num_rows() == 1 )
			$record = new Record( $query->row_array() );
		
		return $record;
	}
	
	// helper function
	function load_by_month( $timestamp )
	{
		$month = get_month_bounds( $timestamp );
		return $this->load( null, null, null, $month['first'], $month['last'] );
	}
	
	function filter_by_date( $start_date, $end_date )
	{
		$filtered = array();
		
		foreach( $this->records as $record )
		{
			if( $record->date >= $start_date && $record->date <= $end_date )
				$filtered[ $record->id ] = $record;
		}
		
		$this->records = $filtered;
		return $this->records;
	}
	
	function filter_by_crew( $crew )
	{
		$filtered = array();
		
		foreach( $this->records as $record )
		{
			if( $record->crew_id == $crew )
				$filtered[ $record->id ] = $record;
		}
		
		$this->records = $filtered;
		return $this->records;
	}
	
	function save( $id )
	{
		if( key_exists($id, $this->records) )
			$record[ $id ]->save();
	}
	
	function delete( $id )
	{
		if( key_exists($id, $this->records) )
		{
			// remove from database
			$this->db->delete('maintenance_record', array('id' => $id));
			unset( $this->records[ $id ] );
		}
	}
	
	function get_record( $id = null )
	{
		$record = null;
		
		if ( $id && key_exists($id, $this->records) )
			$record = $this->records[ $id ];
			
		return $record;
	}
	
	function get_records()
	{
		return $this->records;
	}
	
	function total_count()
	{
		return $this->db->count_all('maintenance_record');
	}
	
	function sum_costs( $discounts = true )
	{		
		$sum = 0;
		foreach( $this->records as $record )
			$sum += $record->get_cost( $discounts );
			
		return $sum;
	}
	
	function get_last()
	{
		$id = null;
		$max = 0;
		
		foreach( $this->records as $record )
		{
			if( $record->date > $max ) 
			{
				$max = $record->date;
				$id = $record->id;
			}
		}
		
		return $id ? $this->records[$id] : null; 
	}
	
	function get_months()
	{
		$months = array();
		
		foreach ( $this->records as $record )
		{
			$record_date = getdate($record->date);
			$month = $record_date['mon'];
			$year = $record_date['year']; 
				
		    $timestamp = mktime(0, 0, 0, $month, 1, $year);
		    $months[$timestamp] = date('M Y', $timestamp);
		}
				
		ksort($months);
		return array_unique($months);
	}
	
	private function _sum_cost( $records )
	{
		$sum = 0;
		foreach( $records as $record )
			$sum += $record->get_cost( true );
			
		return $sum;
	}
	
	function get_by_months( $crew_id = null )
	{
		$records = array();
		
		$this->db->select("DATE_FORMAT(date, '%b %Y') as month", false);
		$this->db->select("COUNT(*) as records");
		$this->db->select("SUM( (1-discount_percentage/100) * (service_cost+transport_cost) ) as cost", false);
		if( $crew_id ) $this->db->where( 'crew_id', $crew_id );
		$this->db->group_by('month');
		$this->db->order_by('date', 'asc');
		$query = $this->db->get('maintenance_record');
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $point )
			{
				$records['months'][] = $point['month'];
				$records['records'][] = $point['records'];
				$records['cost'][] = number_format( $point['cost'], 2, '.', '');
			}
		}
		
		return $records;
	}

}
?>