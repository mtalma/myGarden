<?php

// inputs:
// $records: array of all records
// $record_total: total for the list of records
// $plan_id: maintenance plan id that the record is associated with
// $months: an array of all the months spanning record dates
// $selection: index of the chosen month

$data['heading'] = count($records) . " Service Records";
$data['block_id'] = 'records';

if( isset($plan_id) )
	$data['list'] = array( anchor('/backdoor/addMaintenanceRecord/'. $plan_id, 'Add Service Record') );

if( $records )
{
	//main info for block	
	$this->table->set_heading('Service Date', 'Rating', 'Arrived', 'Time Spent', 'Cost', 'Actions' );

	foreach( $records as $record )
	{
		$buttons['details'] = anchor('/backdoor/record/'.$record->id, 'Details');
		
		if( isset($plan_id) )
		{
			//$buttons['delete'] = anchor('/backdoor/deleteMaintenanceRecord/'.$record->id, 'Delete');
			$buttons['edit'] = anchor('/backdoor/editMaintenanceRecord/'.$plan_id.'/'.$record->id, 'Edit');
		}
		
		
		$this->table->add_row( 
			date( "l jS F Y", $record->date ),
			get_rating( $record->rating ),
			date( 'g:i A', $record->time_in ),
			time_diff( $record->get_time() ),
			"$". number_format( $record->get_cost(), 2, '.', ',' ),
			implode( " | ", $buttons )
		);
	}
		
	if( isset($record_total) )
		$this->table->add_row( "", "", "", "<b>Total:</b>", "<b>$". number_format( $record_total, 2, '.', ',') . " + VAT</b>", "" );
		
	$data['main'] = $this->table->generate(); 
	$this->table->clear();
}

$this->load->view( 'backdoor/table', $data );
table_data_clear($data);