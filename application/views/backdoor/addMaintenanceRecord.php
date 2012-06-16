<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> » <?=anchor('/backdoor/details/'.$plan['id'], 'Maintenance Plan')?> » Add Record</p>
	
<?php
	$data['heading'] = "Enter Details";
	$data['form_action'] = '/backdoor/addMaintenanceRecord/' . $plan['id'];
	$data['extra'] = $response;
	$data['submit_text'] = "Add Record";
	$data['submit_name'] = "addRecord";
	 
	$data['main'] = "<p><label>Time In:</label><br>" . time_dropdown('time_in', $this->input->post('time_in_hour'), $this->input->post('time_in_minute'), $this->input->post('time_in_period'), 'class="styled"')."</p>";
	$data['main'] .= "<p><label>Time Out:</label><br>" . time_dropdown('time_out', $this->input->post('time_out_hour'), $this->input->post('time_out_minute'), $this->input->post('time_out_period'), $this->input->post('time_out'), 'class="styled"')."</p>";
	$data['main'] .= "<p>".form_checkbox("email", "yes", TRUE, "class='checkbox'")." <label for='email'>Email Record Notification</label><span class='note'>Emails the user letting them know of our recent maintenance service</span></p>";
	
	if( isset( $crews ) )
		$data['main'] .= "<p><label>Crew:</label><br>" . form_dropdown('crew', $crews, $crews[0], "class='styled'" ) . "</p>";
	
	$data['main'] .= "<p><label>Service Date:</label><br>" . form_input(array('name' => 'service_date', 'id' => 'service_date', 'value' => $this->input->post('service_date'), 'class' => 'text date_picker') )."</p>";
	$data['main'] .= "<p><label>Rating:</label><br>" . rating( $this->input->post('rating') )."</p>";
	$data['main'] .= "<p><label>Client Notes:</label><br>" . form_textarea('client_notes', $this->input->post('client_notes'), "class='wysiwyg'")."</p>";
	$data['main'] .= "<p><label>Manager Notes:</label><br>" . form_textarea('manager_notes', $this->input->post('manager_notes'), "class='wysiwyg'")."</p>";

	$data['main'] .= form_hidden('maintenance_plan_id', $plan['id']);

	$this->load->view( "backdoor/table", $data );
