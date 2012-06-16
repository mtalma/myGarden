<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> » <?=anchor('/backdoor/details/'.$plan['id'], 'Maintenance Plan')?> » Edit Plan</p>

<?php
	$data['heading'] = "Edit Maintenance Plan";
	$data['form_action'] = '/backdoor/editMaintenancePlan/' . $plan['id'];
	$data['extra'] = $response;
	
	$data['main'] = "<p><label>Status:</label><br>" . form_dropdown( 'status', $statusData, $plan['status_id'], 'class="styled"' )."<span class='note'>You can only add service reports to <em>Activated</em> plans</span></p>";
	$data['main'] .= "<p>".form_checkbox("email", "yes", TRUE, "class='checkbox'")." <label for='email'>Email Status Change to User</label><span class='note'>Emails the user letting them know their new maintenance plan status</span></p>";
	$data['main'] .= "<p><label>Service Day:</label><br>" . form_dropdown( 'service_day', $service_days , $plan['service_day_id'], 'class="styled"' )."</p>";
	$data['main'] .= "<p><label>Service Time:</label><br>" . form_dropdown( 'service_time', $service_time, $plan['service_time_id'], 'class="styled"')."</p>";
	$data['main'] .= "<p><label>Service Cycle:</label><br>" . form_dropdown( 'service_cycle', $service_cycle, $plan['service_cycle_id'], 'class="styled"')."</p>";
	$data['main'] .= "<p><label>Garden Size:</label><br>" . form_dropdown( 'garden_size', $garden_size, $plan['garden_size_id'], 'class="styled"')."</p>";
	$data['main'] .= "<p><label>Signup Date:</label><br>" . form_input(array( 'name' => 'signup_date', 'disabled' => '' ,'value' => $plan['signup_date'], 'class' => 'text date_picker'))."<span class='note'>The date the client signed up online for the maintenance plan. This value cannot be changed.</span></p>";
	$data['main'] .= "<p><label>Start Date:</label><br>" . form_input(array('name' => 'start_date', 'id' => 'start_date','value' => $plan['start_date'], 'class' => 'text date_picker' ))."<span class='note'>This is the date that maintenance will start for this client. This must be set to activate the plan.</span></p>";

	$data['submit_name'] = "editMaintenanceSubmit";
	$data['submit_text'] = "Update";
	$this->load->view("backdoor/table", $data);

?>
