<?php

	$data['heading'] = "Print Service Records";
	$data['form_action'] = "/backdoor/printRecords";
	$data['form_target'] = "target='_blank'";

	$this->table->set_heading('Select', 'Name', 'Region', 'Service Day');
	
	if( $plans )
	{
		foreach( $plans as $plan )
	    	$this->table->add_row( form_checkbox('ids[]', $plan['id'], FALSE ), $plan['client_name'], $plan['region'], $plan['day'] );
	}

	$data['main'] = "<p><label>Service Date:</label><br>";
	$data['main'] .= form_input(array('name' => 'date', 'id' => 'date', 'value' => date("Y-m-d"), 'class' => 'text date_picker' )) . "</p>";
	$data['main'] .= "<br>";
	$data['main'] .= $this->table->generate(); 

	$data['submit_text'] = "Select";
	$data['submit_name'] = "select";

	$this->load->view( "backdoor/table", $data );
?>
