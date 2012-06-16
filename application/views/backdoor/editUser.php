<p class="breadcrumb"><?=anchor('/backdoor/users', 'All Users')?> » Edit <?=$user['fname']?></p>

<?php

	$this->load->view("backdoor/tags", $user );

	$data['heading'] = "Edit User Details";
	$data['form_action'] = '/backdoor/editUser/' . $user['id'];
	$data['extra'] = validation_errors() ? validation_errors() : $response;
	$data['submit_text'] = "Change";
	$data['submit_name'] = "editUserSubmit";

	$data['main'] = '<p><label>First Name</label><br>'. form_input('fname',  $user['fname'], "class='text small'" );
	$data['main'] .= '<p><label>Last Name</label><br>'. form_input('lname',  $user['lname'], "class='text small'" );
	$data['main'] .= '<p><label>Email</label><br>'. form_input('email',  $user['email'], "class='text small'" );
	$data['main'] .= '<p><label>House</label><br>'. form_input('hphone',  $user['hphone'], "class='text small'" );
	$data['main'] .= '<p><label>Cell</label><br>'. form_input('cphone',  $user['cphone'], "class='text small'" );
	$data['main'] .= '<p><label>Address</label><br>'. form_input('address',  $user['address'], "class='text small'" );
	$data['main'] .= '<p><label>Address2</label><br>'. form_input('address2',  $user['address2'], "class='text small'" );

	//add an element for none
	array_unshift( $regions, "Not Set");

	$data['main'] .= '<p><label>Region</label><br>'. form_dropdown( 'region', $regions, $user['region_id'], "class='styled'" );
	$data['main'] .= '<p><label>Directions</label><br>'. form_textarea('directions',  $user['directions'], "class='wysiwyg'" );

	$data['main'] .= form_hidden('id', $user['id']);
	$data['main'] .= form_hidden('status', $user['status_id']);

	$this->load->view("backdoor/table", $data);
?>