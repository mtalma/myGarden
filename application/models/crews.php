<?php

class Crew
{
	var $id;
	var $manager;
	var $license;
	
	function __construct( $data )
	{
		$this->id = key_exists( 'id', $data ) ? $data['id'] : null;

		$this->manager = $data['manager'];
		$this->license = $data['license'];
	}
	
	function save()
	{
		if( $this->id )	//update
		{ 
			$CI =& get_instance();
			$CI->db->where('id', $this->id);
			$CI->db->update('crews', $this);
		}
		else
			$this->insert();
	}
	
	function insert()
	{
		$CI =& get_instance();
		$CI->db->insert('crews', $this); 
	}
	
	function get_id() { return $this->id; }
	function get_manager() { return $this->id ? $this->manager : 'Unknown'; }
	function get_license() { return $this->id ? $this->license : 'N/A'; }
}

class Crews extends CI_Model
{
	var $records;

    function __construct()
    {
        parent::__construct();
	}
	
	function load( $crew_id = null, $limit = 0, $offset = 0 )
	{
		$this->records = array();
		if( $crew_id )	$this->db->where('id', $crew_id);
		if( $limit ) $this->db->limit( $limit, $offset );
				
		$query = $this->db->get('crews');
		if( $query->num_rows() > 0 )
		{
			foreach ( $query->result_array() as $record )
				$this->records[ $record['id'] ] = new Crew( $record );
		}
		
		return $this->records;
	}
	
	function get_crew( $id )
	{
		if( !$this->records )	$this->load();	//lazy load
		if( !key_exists($id,$this->records) )
		{
			return null;
		}
		return $this->records[ $id ];
	}
}