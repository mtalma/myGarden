<?php

	$data['heading'] = "View All Crews";
	$data['form_action'] = "/backdoor/printRecords";
		
	$data['main'] = '';
	
	if( $message )
		$data['main'] .= success_msg( $message );

	if( !empty($crews) )
	{
		$this->table->set_heading('Crew ID', 'Manager', 'Truck License', 'Action');
		foreach( $crews as $crew )
			$this->table->add_row( $crew->get_id(), $crew->get_manager(), $crew->get_license(), anchor('/backdoor/deleteCrew/'.$crew->get_id(), 'Delete') );
			
		$data['main'] .= $this->table->generate();
	}
	else
	{
		$data['main'] .= "<p>No Data Found</p>";
	}
		
	$data['list'] = array( 
			anchor('/backdoor/addcrews', 'Add a Crew' ),
		);

	$this->load->view( "backdoor/table", $data );
?>