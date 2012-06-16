<h1>Contacts</h1>

<?php	
	$tmpl = array ( 'table_open'  => '<table border="0" cellpadding="0" cellspacing="0" id="contacts" class="contactDataTable display">' );
	$this->table->set_template($tmpl); 
	
	$this->table->set_heading('', 'Name', 'email', 'Primary', 'Secondary', 'Region');
	
	echo $this->table->generate($contacts);
?>