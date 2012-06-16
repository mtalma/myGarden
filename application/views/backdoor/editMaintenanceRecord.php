<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> » <?=anchor('/backdoor/details/'.$plan['id'], 'Maintenance Plan')?> » Edit Record</p>
	
<?php

	$data['heading'] = "Edit";
	$data['form_action'] = '/backdoor/editMaintenanceRecord/' . $plan['id'] .'/'. $record['id'];
	$data['extra'] = $response;
	$data['submit_text'] = "Save Record";
	$data['submit_name'] = "saveRecord";
	
	$data['main'] = "<p><label>Time In:</label><br>" . time_dropdown('time_in', $record['time_in_hours'], $record['time_in_mins'], $record['time_in_period']) . "</p>";
	$data['main'] .= "<p><label>Time Out:</label><br>" . time_dropdown('time_out', $record['time_out_hours'], $record['time_out_mins'], $record['time_out_period']) . "</p>";
	$data['main'] .= "<p><label>Service Date:</label><br>" . form_input(array('name' => 'service_date', 'id' => 'service_date', 'value' => $record['date'], 'class' => 'text date_picker' )) . "</p>";
	$data['main'] .= "<p><label>Rating:</label><br>" . rating( $record['rating']) . "</p>";
	$data['main'] .= "<p><label>Client Notes:</label><br>" . form_textarea('client_notes', $record['client_notes']/*, "class='wysiwyg'"*/) . "</p>";
	$data['main'] .= "<p><label>Manager Notes:</label><br>" . form_textarea('manager_notes', $record['manager_notes']/*, "class='wysiwyg'"*/) . "</p>";

	$data['main'] .= form_hidden('maintenance_plan_id', $plan['id']);

	$this->load->view("backdoor/table", $data);
