<?php

if( $plan['status_id'] == PLAN_PENDING )
{
	$phone_numbers = "home number: " .format_phone_number($plan['user']['hphone']);
	$phone_numbers .= $plan['user']['cphone'] ? " or at cell number: " .format_phone_number($plan['user']['cphone']) : "";
	echo warning_msg("Plan is PENDING - call " .$plan['user']['fname']. " " .$plan['user']['lname']. " at " .$phone_numbers. " to confirm plan details and address below:");
}
else if( $plan['status_id'] != PLAN_ACTIVATED )	//if the plan is not active
{
	echo warning_msg("Plan not ACTIVATED");
}
?>

<?php
	 table_data_clear($data);
	
	$data['heading'] = "Plan Details";
	$data['class'] = "small left";
	$data['list'] = anchor('/backdoor/editMaintenancePlan/'. $plan['id'], 'Edit Details');

	$this->table->add_row( 'Status:', '<em>' . $plan['status'] . '</em>');
	$this->table->add_row( 'Client Name:', $plan['user']['fname'] . " " . $plan['user']['lname']);
	$this->table->add_row( 'Home Phone:', format_phone_number( $plan['user']['hphone']));
	$this->table->add_row( 'Cell Phone:', format_phone_number( $plan['user']['cphone']));
	$this->table->add_row( 'Service Day:', $plan['service_day']);
	$this->table->add_row( 'Service Time:', $plan['service_time']);
	$this->table->add_row( 'Service Cycle:', $plan['service_cycle']);
	$this->table->add_row( 'Signed Up:', $plan['signup_date']);
	$this->table->add_row( 'Start Date:', $plan['start_date']);
	$this->table->add_row( 'Hourly Rate:', '$'.number_format($hourly_rate,2));
	$this->table->add_row( 'Transport Fee (Per Visit):', '$'.number_format($plan['transport_fee'],2));
	
	$data['main'] = $this->table->generate(); 
	$this->table->clear();
	
	$this->load->view( 'backdoor/table', $data );

?>

<?php
	table_data_clear($data);

	$data['heading'] = "Services";
	$data['class'] = "small right";
	$data['list'] = anchor('/backdoor/editServices/'. $plan['id'], 'Add/Remove Services');
	 
	$this->table->set_heading('Services', 'Quoted Price (per hour)');
	$services = $plan['services'];
	foreach( $services as $service => $desc )
		$this->table->add_row( $desc['name'], '$'.number_format($desc['quoted_price'],2));

	$data['main'] = $this->table->generate(); 
	$this->table->clear();
	$this->load->view( 'backdoor/table', $data );
	
?>

<?php
	table_data_clear($data);

	$data['heading'] = "Client Details";
	$data['class'] = "small right";
	$data['list'] = anchor('/backdoor/editUser/'. $plan['user']['id'], 'Edit Details');
	 
	$user = $plan['user'];
	$this->table->add_row( "Name", $user['fname'] . " " . $user['lname'] );
	$this->table->add_row( "Email", $user['email'] );
	$this->table->add_row( "Address", $user['address'] . ", " . $user['address2'] );
	$this->table->add_row( "Region", $user['region'] );

	$data['main'] = $this->table->generate(); 
	$this->table->clear();
	$this->load->view( 'backdoor/table', $data );
	
?>

<?php
	 table_data_clear($data);
	
	$data['heading'] = "Referrals";
	
	$this->table->set_heading('Email Address', 'Status', 'Updated');

	foreach( $referrals as $referral )
	{
		$status = getReferralStatus( $referral['status'] );
		$status = $referral['status'] == REFERRAL_DISCOUNT_APPLIED ? $status." - ".anchor('/backdoor/detailsMaintenanceRecord/'. $plan['id'] .'/'.$referral['record_id'], 'Maintenance Record') : $status;
		$this->table->add_row( $referral['email'], $status, date( "D j\<\s\u\p\>S\<\/\s\u\p\> M y - g:i:s a", $referral['timestamp']) );
	}

	$data['main'] = $this->table->generate();
	$this->table->clear();
	
	$this->load->view( 'backdoor/table', $data );

?>

