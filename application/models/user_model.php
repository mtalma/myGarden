<?php

class User_Model extends CI_Model {

	var $id;
	var $fname;
	var $lname;
	var $hphone;
	var $cphone;
	var $email;
	var $address;
	var $address2;
	var $region;
	var $directions;
	var $status;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    //sets the data here from the post variable in input->post
    function ConstructNewFromPost()
    {	
		ASSERT( count( $_POST ) > 0 );	//we have input
		
		ASSERT($this->input->post('fname') != FALSE);
    	$this->fname = ucfirst( $this->input->post('fname') );
		
		ASSERT($this->input->post('lname') != FALSE);
    	$this->lname = ucfirst( $this->input->post('lname') );
		
		ASSERT($this->input->post('email') != FALSE);
    	$this->email = $this->input->post('email');
		
		//home phone number
		ASSERT($this->input->post('phone') != FALSE);
    	$this->hphone = $this->input->post('phone');
		
		//cell phone number
    	$this->cphone = $this->input->post('cell');
		
		ASSERT($this->input->post('address') != FALSE);
    	$this->address = $this->input->post('address');
		
		//these can be left blank
		$this->address2 = $this->input->post('address2');
    	$this->directions = $this->input->post('directions');

		$this->status = USER_SIGNED_UP;
    }
    
    function SetId( $id )
    {
    	$this->id = $id;
    }
    
    function SetFirstName( $fname )
    {
    	$this->fname = $fname;
    }
    
    function SetLastName( $lname )
    {
    	$this->lname = $lname;
    }
    
    function SetHomePhone( $hphone )
    {
    	$this->hphone = $hphone;
    }
    
    function SetCellPhone( $cphone )
    {
    	$this->cphone = $cphone;
    } 
    
    function SetEmail( $email )
    {
    	$this->email = $email;
    }
    
    function SetAddress( $address1, $address2 )
    {
    	$this->address = $address1;
    	$this->address2 = $address2;
    }
    
    function SetRegion( $region )
    {
    	$this->region = $region;
    }
    
    function SetDirections( $directions )
    {
    	$this->directions = $directions;
    }

	function SetStatus( $status )
	{
		$this->status = $status;
	}

	function Update()
	{
		ASSERT($this->id);
		if( $this->id )
		{
			$this->db->where( 'id', $this->id );
			$this->db->update( USER_TABLE_NAME, $this); 
		}

		return $this->id;
	}
    
    function Insert()
    {	
		$this->db->insert( USER_TABLE_NAME, $this);
		$this->id = mysql_insert_id();
		
		return $this->id;
    }
    
    function Load( $id )
    {
    	//load a user with the supplied id
		$query = $this->db->get_where( USER_TABLE_NAME, array('id' => $id), 1, 0);  
		ASSERT( $query->num_rows == 1 );
		
		$user_data = $query->row();
		
		$this->id = $user_data->id;
		$this->fname = $user_data->fname;
		$this->lname = $user_data->lname;
		$this->hphone = $user_data->hphone;
		$this->cphone = $user_data->cphone;
		$this->email = $user_data->email;
		$this->address = $user_data->address;
		$this->address2 = $user_data->address2;
		$this->region = $user_data->region;
		$this->directions = $user_data->directions;
		$this->status = $user_data->status;
	}
	
	function LoadRegionText()
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
	
	function GetId()
	{
		ASSERT( $this->id != '' );
		return ($this->id == '' ? FALSE : $this->id);
	}
	
	function GetUser()
	{
		$user = array();
		
		if( $this->id != '' )	//if we are loaded
		{
			$user['id'] = $this->id;
			$user['fname'] = $this->fname;
			$user['lname'] = $this->lname;
			$user['hphone'] = $this->hphone;
			$user['cphone'] = $this->cphone;
			$user['hphone_formatted'] = $this->_format_phone_number( $this->hphone );
			$user['cphone_formatted'] = $this->_format_phone_number( $this->cphone );
			$user['email'] = $this->email;
			$user['address'] = $this->address;
			$user['address2'] = $this->address2;
			$user['region'] = $this->LoadRegionText();
			$user['region_id'] = $this->region;
			$user['directions'] = $this->directions;
			$user['status_id'] = $this->status;
			$user['status'] = getUserStatus( $this->status );
		}
		
		return $user;
	}
	
	//format a phone number
	function _format_phone_number($number)
	{
	  return preg_replace("/^[\+]?[1]?[- ]?[\(]?([2-9][0-8][0-9])[\)]?[- ]?([2-9][0-9]{2})[- ]?([0-9]{4})$/", "(\\1) \\2-\\3", $number);
	}
}
?>