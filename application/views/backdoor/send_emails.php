<style>
form#filter_form {
    width: 100%;
    border: 1px solid #D7D2BE;
    padding: 10px;
    background: #F3F2ED;
    clear: both;
    -moz-border-radius: 5px;
    border-radius: 5px;
    margin-bottom: 20px;
}

form#filter_form label {
    float: none;
    color: #482803;
    padding: 5px;
}

form#filter_form select {
    background: #fff;
    border: 1px solid #D7D2BE;
    float: none;
    font: auto;
    margin: 0px; 
    width: 140px;
}
</style>

<?php
	
    /*echo form_open('/backdoor/send_emails', 'id="filter_form"');
    //filters
    echo form_fieldset('Filters');
    echo "<label for='regions'>Regions:<label>" . form_dropdown( 'regions', $regions, '', 'id="regions"' );
    echo "<label for='services'>Services:<label>" . form_dropdown( 'services', $services, '', 'id="services"' );
    echo "<label for='service_cycle'>Cycle:<label>" . form_dropdown( 'service_cycle', $cycle, '', 'id="cycle"' );
    echo "<label for='service_days'>Service Day:<label>" . form_dropdown( 'service_days', $days, '', 'id="days"' );
    echo "<label for='status'>Status:<label>" . form_dropdown( 'status', $status, '', 'id="status"' );
    echo "<label for='garden_size'>Size:<label>" . form_dropdown( 'garden_size', $size, '', 'id="size"' );
    echo "<label for='time_slots'>Time:<label>" . form_dropdown( 'time_slots', $slots, '', 'id="slots"' );
    echo "<label for='users'>User:<label>" . form_dropdown( 'users', $clients, '', 'id="users"' );
    echo form_fieldset_close(); 
    echo form_submit('submit', 'Apply');
    echo form_close();*/

	$data['heading'] = "Send Emails";

	$data['form_action'] = '/backdoor/send_emails';
	$data['extra'] = "<p>Select the clients to send emails to in the following table:</p>";
	$data['submit_text'] = "Select";
	$data['submit_name'] = "submit";

	$this->table->set_heading('Select', 'Name', 'Email', 'Status');
	foreach( $users as $row )
	{
	    $this->table->add_row( form_checkbox('ids[]', $row['id'], FALSE ),$row['fname']." ".$row['lname'], $row['email'], getUserStatus( $row['status'] ) );
	}
	$data['main'] =  $this->table->generate();

	$this->load->view( "backdoor/table", $data );
?>
