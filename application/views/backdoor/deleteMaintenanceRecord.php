<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> » <?=anchor('/backdoor/details/'.$plan['id'], 'Maintenance Plan')?> » Delete Record</p>

<?php
	$data['heading'] = "Delete This Record?";

	$data['form_action'] = '/backdoor/deleteMaintenanceRecord/' . $plan['id'] .'/' . $record['id'];
	$data['extra'] = warning_msg("This action is permanent, it cannot be undone!");
	
	$this->table->add_row( 'Time In:', $record['time_in'] );
	$this->table->add_row( 'Time Out:', $record['time_out'] );
	$this->table->add_row( 'Service Date:', date( "l jS F Y",mysql_date_to_timestamp($record['date'])) );
	$this->table->add_row( 'Rating:', $record['rating'] );
	$this->table->add_row( 'Client Notes:', $record['client_notes'] );
	$this->table->add_row( 'Manager\'s Notes:', $record['manager_notes'] );

	$data['submit_text'] = "Confirm and Delete";
	$data['submit_name'] = "deleteRecord";

	$data['main'] = form_hidden('maintenance_plan_id', $plan['id']);
	$data['main'] .= $this->table->generate();

	$this->load->view("backdoor/table", $data);

