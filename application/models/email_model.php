<?php

define( 'DEBUG_ADDRESS', 'matthew@foliagedesign.tt' );
define( 'FROM_ADDRESS', 'hello@foliagedesign.tt' );
define( 'FROM_ADDRESS_DESC', 'Foliage Design' );

define( 'ERROR_NO_ERROR', 0 );
define( 'ERROR_NOT_INIT', 1 );
define( 'ERROR_NOT_SENT', 2 );
define( 'ERROR_MSG_NOT_LOADED', 3 );

class Email_Model extends CI_Model {

	var $id;
	var $email;
	var $msg_id;
	var $extra_data;
	var $error;

    function __construct()
    {
        parent::__construct();

		$this->error = ERROR_NO_ERROR;
	}

	function initialize( $email_address, $msg_id, $extra_data = null )
	{
		$this->email = $email_address;
		$this->msg_id = $msg_id;
		$this->extra_data = $extra_data;
	}
	
	function get_error( $error_id )
	{
		$desc = "Unknown Error";
		
		switch( $error_id )
		{
			case ERROR_NO_ERROR:
				$desc = "Sent";
				break;
			case ERROR_NOT_INIT:
				$desc = "Email not initialized";
				break;
			case ERROR_NOT_SENT:
				$desc = "Email not sent";
				break;
			case ERROR_MSG_NOT_LOADED:
				$desc = "Message not loaded";
				break;
		}
		
		return $desc;
	}

	function send_email( $use_postmark = true )
	{
		$result = null;
		
		if( DEBUG_MODE )
		{
			$this->email = DEBUG_ADDRESS;
			$use_postmark = false; //don't use postmark because we may be offline
		}

		if( isset($this->email) && isset($this->msg_id) )
		{
			//load the message type
			$message_data = $this->load_message( $this->msg_id );
			if( $message_data != null )
			{
				//vars that can be used in the message body
				$data['body'] = $message_data->body;
				$data['url'] = base_url();

				if( $this->extra_data )
					$data = array_merge( $data, $this->extra_data );

				$email_html = $this->parser->parse('email_template', $data , TRUE );
				$email_text = $this->parser->parse_string($message_data->body, $data, TRUE);
				$email_subject = $this->parser->parse_string($message_data->subject, $data, TRUE);

				if( $use_postmark )
				{
					$this->load->library('postmark');
					$this->postmark->to( $this->email );
					$this->postmark->reply_to( FROM_ADDRESS, FROM_ADDRESS_DESC );
					$this->postmark->tag('myGarden');
					$this->postmark->subject( $email_subject );
					$this->postmark->message_html( $email_html );
					$this->postmark->message_plain( strip_tags($this->_replaceLinks($email_text)) );

					$result = $this->postmark->send();

					if( $result == null )
						$this->error = ERROR_NOT_SENT;	//some error occured - postmark will have more info
				}
				else
				{
					$CI =& get_instance();
					$config['mailtype'] = 'html';
					$config['protocol'] = 'sendmail';
					$config['charset'] = 'iso-8859-1';
					$config['wordwrap'] = TRUE;
					
					$CI->email->from( FROM_ADDRESS, FROM_ADDRESS_DESC );
					$CI->email->to( $this->email );
					$CI->email->subject( $email_subject );
					$CI->email->message( $email_html );
					$result = $CI->email->send();

					if( !$result )
						$this->error = ERROR_NOT_SENT;	//could not send
				}
			}
			else
				$this->error = ERROR_MSG_NOT_LOADED;	//could not load message
		}
		else
			$this->error = ERROR_NOT_INIT;	//not initialized

		$this->log_email($result);
	}

	function load_message( $id )
	{
		$row = null;

		$query = $this->db->query( "select * from email_content where id='" . $id . "'" );
		//ASSERT( $query->num_rows() == 1 );
		if ( $query->num_rows() == 1 )
			$row = $query->row(); 

		return $row;
	}
	
	function log_email( $result = null )
	{
		$data = array(
               'id' => '' ,
               'email' => $this->email,
               'msg_id' => $this->msg_id,
			   'error' => $this->error,
			   'postmark_id' => $result
            );

		$this->db->insert('email_log', $data); 
	}
	
	function _replaceLinks( $html )
	{                
		return preg_replace("/<a.*?href=(\"|')?(.*?)(\"|')?>(.*?)<\/a>/i", "$4: $2", $html);
	}

	function get_logs( $start = 0, $count = 0 )
	{
		$logs = null;
		
		$limit = "";
		if( $count )
			$limit = "LIMIT " . $start . ", " . $count ;

		$query_str = "select email_log.email, email_content.title, email_log.datetime, email_log.postmark_id, email_log.error from email_log, email_content where email_log.msg_id=email_content.id ORDER BY datetime DESC " . $limit;

		$query = $this->db->query($query_str);
		if( $query && $query->num_rows() > 0  )
		{
			$logs = $query->result_array();
			for( $i=0; $i<count($logs); $i++ ) //replace error id with error desc
				$logs[$i]['error'] = $this->get_error($logs[$i]['error']);
		}

		return $logs;
	}
	
	function get_log_count()
	{
		$count = $this->db->query("select count(*) as count from email_log")->row_array();
		return $count['count'];
	}
}

?>
