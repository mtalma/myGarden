<?php	

	$data['heading'] = "All Users";

	$this->table->set_heading('Name', 'Email', 'Status', 'Home', 'Cell', 'Region', '');
	
	$data['main'] = $this->table->generate($contacts);
	$this->load->view("backdoor/table", $data);
?>