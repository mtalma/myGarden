<?php

class Landing extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
    }
    
    function rentals()
    {    	
    	if( $this->input->post('rentals') )	// they have clicked for more info
    	{	
    		redirect( 'http://foliagedesign.tt/rentals', 'location');
    	}
    	else	// landing on the page
    	{	
    		$data = array();    		
			$this->load->view('landing/rentals', $data);
		}
    }
    
}