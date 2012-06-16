<script>
$(document).ready(function() {
	$('#custom_email').hide();
	
	$("input[@name='email_id']").change(function(){
   		alert( 'hello' );
	});
});
</script>

<?php
	$data['heading'] = "Send Email Confirm";
	$data['form_action'] = '/backdoor/send_emails';
	$data['submit_text'] = "Send Emails";
	$data['submit_name'] = "send";

	$data['main'] = "<p>An email will be sent to the following clients: </p>";
	$data['main'] .= "<ul>";
	foreach($users as $user)
	{
		$data['main'] .= "<li>".$user['fname']." ".$user['lname'].": ".$user['email']."</li>";
		$data['main'] .=  form_hidden( "user_ids[]", $user['id'] );
	}
	$data['main'] .= "</ul>";

	$data['main'] .= "<p>Select an Email to send: </p>";

	foreach($emails as $email)
	{
		$data['main'] .= "<p>".form_radio('email_id',$email['id'])." <label>".$email['title']."</label></p>";
	}
	$data['main'] .= "<p>".form_radio( 'email_id', EMAIL_CUSTOM )." <label>Custom</label></p>";
	$data['main'] .= "<div id='custom_email'><p><label>Subject:</label><br>".form_input('subject', $this->input->post('subject'), "class='text big'")."</p>";
	$data['main'] .= "<p><label>Body:</label><br>".form_textarea('body',$this->input->post('body'), "class='wysiwyg'")."</p></div>";

	$this->load->view( "backdoor/table", $data );
?>
