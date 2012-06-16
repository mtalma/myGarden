<?php

	$data['heading'] = "View Service Records";

	$this->table->set_heading('Client', 'Date', 'Rating', 'Client Notes', 'Manager Notes', 'Cost', '');
		
	if( $records )
	{
		foreach( $records as $record )
	    	$this->table->add_row( anchor('/backdoor/details/'.$record['plan_id'], $record['client'] ), time_ago($record['date'], 'd-M-Y', 2), get_rating($record['rating']), character_limiter($record['client_notes'], 20), character_limiter($record['manager_notes'], 20), '$' . number_format( $record['cost'], 2, '.', ','), anchor('/backdoor/detailsMaintenanceRecord/'. $record['plan_id'] .'/'.$record['id'], 'Details'). " | " .anchor('/backdoor/editMaintenanceRecord/'. $record['plan_id'] .'/'.$record['id'], 'Edit'). " | " . anchor('/backdoor/deleteMaintenanceRecord/'. $record['plan_id'] .'/'.$record['id'], 'Delete') );
	}

	$data['main'] = $this->table->generate(); 
	$data['pagination'] = $pagination;

	$this->load->view( "backdoor/table", $data );
?>