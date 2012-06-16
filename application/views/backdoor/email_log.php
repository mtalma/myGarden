<?php	

	$data['heading'] = "Email Log";
	
	$this->table->set_heading('Sent To', 'Message Type', 'Sent Date', 'View', 'Error');

	if( count( $email_log ) > 0 )
	{
		foreach( $email_log as $row )
		{
			$link = $row['postmark_id'] ? anchor( "https://postmarkapp.com/servers/15022/messages/".$row['postmark_id'] ,'View Email', "target='_blank'") : $row['postmark_id'];
			$this->table->add_row( $row['email'], $row['title'], $row['datetime'], $link, $row['error'] );
		}
	}

	$data['main'] = $this->table->generate();

	$this->load->view( "backdoor/table", $data );
?>
