<?php

function display_error_page($desc)
{
	$CI =& get_instance();
	
	$data['desc'] = $desc;
	$data['title'] = "myGarden Error";
	$data['debug_info'] = $CI->_GetDebugInfo();
		
	$CI->parser->parse('header', $data);
	$CI->load->view('error_page', $data);
	$CI->load->view('footer');
}

// called when we add a maintenance record
function on_maintenance_record_add( $maintenance_id, $record_id )
{
	$CI =& get_instance();
	
	if( $CI->input->post('email') )		//only if we specified to email the client
	{	
		//we want to send an email to the user notifying them that they can rate their service
		$CI->load->model('Maintenance_Model');
		$CI->Maintenance_Model->Load( $maintenance_id );
		$maintenance_plan = $CI->Maintenance_Model->GetMaintenancePlan();
		$record = $CI->Maintenance_Model->GetMaintenanceRecord($record_id);
		$hashed_id = $CI->Maintenance_Model->GetHashedId();
		
		$data['link'] = base_url() . "index.php/mygarden/list_services/".$hashed_id."/".$record_id;
		$data['date'] = date( "l jS F",mysql_date_to_timestamp($record['date']));
		$data['start_time'] = $record['time_in'];
		$data['end_time'] = $record['time_out'];
		$data['fname'] = $maintenance_plan['user']['fname'];
		$data['cycle'] = $CI->Maintenance_Model->GetCycleText();
		
		$CI->load->model('Email_Model');
		$CI->Email_Model->initialize( $maintenance_plan['user']['email'], EMAIL_RECORD_ADD, $data );
		$CI->Email_Model->send_email();
	}
}

function table_data_clear( &$data )
{
	$data['class'] = "";
	$data['block_id'] = "";
	$data['heading'] = "";
	$data['list'] = "";
	$data['form_action'] = "";
	$data['extra'] = "";
	$data['main'] = "";
	$data['submit_text'] = "";
	$data['submit_name'] = "";
	$data['tableactions'] = "";
	$data['pagination'] = "";
	$data['form_name'] = "";
	$data['form_id'] = "";
	$data['form_target'] = "";
	$data['tableactions_top'] = "";
}

function load_tags($key)
{
	$tags = null;
	$CI =& get_instance();
	$CI->db->select('name, type');
	$CI->db->order_by("type", "desc"); //so that system tags are listed first
	$query = $CI->db->get_where('user_tags', array('id' => $key) );
	
	if( $query && $query->num_rows > 0 )
		$tags = $query->result_array();
	
	return $tags;
}

function mysql_date_to_timestamp($sql_date)
{
	$date = explode( '-', $sql_date );	//yy-mm-dd
	return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
}

function format_phone_number($number)
{
	$formatted = "Not Set";
	
	if( $number != '' )
  		$formatted = preg_replace("/^[\+]?[1]?[- ]?[\(]?([2-9][0-8][0-9])[\)]?[- ]?([2-9][0-9]{2})[- ]?([0-9]{4})$/", "(\\1) \\2-\\3", "868".$number);
  	
  	return $formatted;
}

function time_dropdown( $name, $default_hours = '', $default_mins = '', $default_period = '' )
{
	$hours = array();
	for( $i=1; $i<=12; $i++ )
	{
		$padded_hours = $i < 10 ? "0".$i : $i;
		$hours[$padded_hours] = $padded_hours;
	}

	$minutes = array();
	for( $i=0; $i<60; $i++ )
	{
		$padded_min = $i < 10 ? "0".$i : $i;
		$minutes[$padded_min] = $padded_min;
	}

	$period = array("AM","PM");

	return form_dropdown($name.'_hour', $hours, $default_hours, "class='time_picker'" ) . ":" . form_dropdown($name.'_minute', $minutes, $default_mins, "class='time_picker'" ) . form_dropdown($name.'_period', $period, $default_period, "class='time_picker'" );
}

function load_maintenance_plan_table( $plan_status = null )
{
	$tableHTML = "<div id='plan_list' class='empty_table'>";
	$status = $plan_status ? " and maintenance_plans.status='". $plan_status ."'" : "";

	$CI =& get_instance();

	$query = $CI->db->query("select maintenance_plans.id, users.id as user_id, CONCAT_WS(' ',users.first_name, users.last_name) as client_name, regions.name as region, service_cycle.cycle as cycle, service_days.day, time_slots.name as time, status.text as status from maintenance_plans,users, regions, service_cycle, service_days, time_slots, status where maintenance_plans.user_id=users.id and maintenance_plans.region=regions.id and maintenance_plans.service_cycle=service_cycle.id and maintenance_plans.service_day=service_days.id and maintenance_plans.service_time=time_slots.id and maintenance_plans.status=status.id" . $status . " ORDER BY maintenance_plans.signup_date DESC" );
	
	if( $query && $query->num_rows > 0 )
	{
		$CI->table->set_heading('<input type="checkbox" class="check_all">', 'Client', 'Region', 'Cycle', 'Service Day', 'Service Time', 'Status', '');
		$results = $query->result_array();
		foreach( $results as $id => $value )
			$CI->table->add_row( "<input type='checkbox' value=".$value['id']." name='ids[]'>", anchor( "backdoor/details/". $value['id'], $value['client_name'] ), $value['region'], $value['cycle'], $value['day'], $value['time'], $value['status'], anchor('/backdoor/editUser/'. $value['user_id'], 'User Details') );
			
		$tableHTML .= $CI->table->generate();
	}
	else
		$tableHTML .= "<p>No Data Found</p>";
	
	$tableHTML .= "</div>";
	
	return $tableHTML;
}

function load_maintenance_records( $id, $timestamp )
{
	$html = "";
	
	$CI =& get_instance();
	
	$CI->Maintenance_Model->Load( $id );
	
	$plan = $CI->Maintenance_Model->GetMaintenancePlan();
	
	$record_date = get_month_bounds($timestamp);
		
	$records = $CI->Maintenance_Model->GetMaintenanceRecords( $record_date['first'], $record_date['last'] );
	$records_total = $CI->Maintenance_Model->GetMaintenanceRecordTotal( $record_date['first'], $record_date['last'] );
	
	if( $records )
	{
		//main info for block
		$CI->table->set_heading('Service Date', 'Rating', 'Arrived', 'Time Spent', 'Cost', 'Actions' );
	
		foreach( $records as $record )
			$CI->table->add_row( date( "l jS F Y",mysql_date_to_timestamp($record['date'])), get_rating($record['rating']), $record['time_in'], $record['diff'],  "$". number_format( $record['cost'], 2, '.', ','), anchor('/backdoor/detailsMaintenanceRecord/'. $plan['id'] .'/'.$record['id'], 'Details'). " | " .anchor('/backdoor/editMaintenanceRecord/'. $plan['id'] .'/'.$record['id'], 'Edit'). " | " . anchor('/backdoor/deleteMaintenanceRecord/'. $plan['id'] .'/'.$record['id'], 'Delete') );
			
		if( $records_total != 0 )
			$CI->table->add_row( "", "", "", "<b>Invoice for ".$record_date['month']." ". $record_date['year'] .":</b>", "<b>$". number_format( $records_total, 2, '.', ',') . " + VAT</b>", "" );
		
		//set the id of this table
		$tmpl = array( 'table_open'  => '<table cellspacing="0" cellpadding="0" width="100%" class="sortable" id="records">' );
		$CI->table->set_template($tmpl); 
	
		$html = $CI->table->generate(); 
		$CI->table->clear();
	}
	
	return $html;
}

//checks if an email exist in the user table
function email_exist( $email )
{
	$CI =& get_instance();
	$query = $CI->db->query("select status from users where email='" . $email . "'" );
	if( $query->num_rows == 0 )	//email does not exist
		return false;

	return true;

}

function getUserStatus( $status )
{
	$status_str = "Unknown";

	if( $status == USER_SIGNED_UP )
		$status_str = "Sign Up";
	else if( $status == USER_REFERRED )
		$status_str = "Referred";

	return $status_str;
}

function getReferralStatus( $status )
{
	$status_str = "Unknown";

	if( $status == REFERRAL_SENT )
		$status_str = "Invitation Sent";
	else if( $status == REFERRAL_SIGNEDUP )
		$status_str = "Plan Pending - Waiting on Plan Activation";
	else if( $status == REFERRAL_CANCELED )
		$status_str = "Signed Up - Client from different region";
	else if( $status == REFERRAL_ACTIVATED )
		$status_str = "Plan Activated - Discount Pending";
	else if( $status == REFERRAL_DISCOUNT_APPLIED )
		$status_str = "Discount Applied";

	return $status_str;
}

function getRegionText( $regionId )
{
	$region = "Not Set";

	$CI =& get_instance();
	$query = $CI->db->get_where( REGION_TABLE_NAME, array('id' => $regionId), 1, 0);
	if( $query->num_rows )
		$region = $query->row()->name;

	return $region;
}

function rating($default_value = '')
{
	$stars = array( '0'=>'None Given', '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5 );
	return form_dropdown('rating', $stars, $default_value, "class='styled'");
}

function get_rating( $rating )
{
	$num_to_text = array("zero", "one", "two", "three", "four", "five");
	return $rating == 0 ? "None Given" : "<div id='rating' class='".$num_to_text[ $rating ]."'></div>";
}

function error_msg($msg)
{
	return "<div class='message errormsg'>$msg</div>";
}

function success_msg($msg)
{
	return "<div class='message success'>$msg</div>";
}

function warning_msg($msg)
{
	return "<div class='message warning'>$msg</div>";
}

function info_msg($msg)
{
	return "<div class='message info'>$msg</div>";
}

function time_ago( $date, $default_format = "l jS F Y", $level = 9 ) 
{
	$text = date( $default_format ,mysql_date_to_timestamp($date) );
    $date = strtotime($date);
    
    if ( $level >= 1 && $date > strtotime("yesterday") )
        $text = "today";
    else if ( $level >= 2 && $date <= strtotime("yesterday") && $date > strtotime("-2 days") )
        $text = "yesterday";
    else if ( $level >= 3 && $date <= strtotime("-2 days") && $date > strtotime("-1 week") )
        $text = 'this past ' . date('l', $date);
    else if ( $level >= 4 && $date <= strtotime("-1 week") && $date > strtotime("-2 week") )
        $text = 'recently on a ' . date('l', $date);
    else if ( $level >= 5 && $date <= strtotime("-2 week") && $date > strtotime("-4 weeks") )
    	$text = "a couple weeks ago on a " . date('l', $date);
    else if ( $level >= 6 && $date <= strtotime("-1 month") && $date > strtotime("-2 months") )
        $text = "last month";
    else if ( $level >= 7 && $date <= strtotime("-2 months") && $date > strtotime("-12 months") )
        $text = "a couple months ago";
    else if ( $level >= 8 && $date <= strtotime("-1 year") && $date > strtotime("-2 year"))
        $text = "last year";
    else if ( $level >= 9 && $date <= strtotime("-2 year") )
        $text = "more than two years ago";
        
    return $text;
}

function time_diff($difference, $format = '%d days %h hours %m mins') {
    if($difference < 0)
        return false;
    else{
   
        $min_only = intval(floor($difference / 60));
        $hour_only = intval(floor($difference / 3600));
       
        $days = intval(floor($difference / 86400));
        $difference = $difference % 86400;
        $hours = intval(floor($difference / 3600));
        $difference = $difference % 3600;
        $minutes = intval(floor($difference / 60));
        if($minutes == 60){
            $hours = $hours+1;
            $minutes = 0;
        }
       
        if($days == 0){
            $format = str_replace('days', '?', $format);
            $format = str_replace('Ds', '?', $format);
            $format = str_replace('%d', '', $format);
        }
        if($hours == 0){
            $format = str_replace('hours', '?', $format);
            $format = str_replace('Hs', '?', $format);
            $format = str_replace('%h', '', $format);
        }
        if($minutes == 0){
            $format = str_replace('minutes', '?', $format);
            $format = str_replace('mins', '?', $format);
            $format = str_replace('Ms', '?', $format);       
            $format = str_replace('%m', '', $format);
        }
       
        $format = str_replace('?,', '', $format);
        $format = str_replace('?:', '', $format);
        $format = str_replace('?', '', $format);
       
        $timeLeft = str_replace('%d', number_format($days), $format);       
        $timeLeft = str_replace('%ho', number_format($hour_only), $timeLeft);
        $timeLeft = str_replace('%mo', number_format($min_only), $timeLeft);
        $timeLeft = str_replace('%h', number_format($hours), $timeLeft);
        $timeLeft = str_replace('%m', number_format($minutes), $timeLeft);
           
        if($days == 1){
            $timeLeft = str_replace('days', 'day', $timeLeft);
            $timeLeft = str_replace('Ds', 'D', $timeLeft);
        }
        if($hours == 1 || $hour_only == 1){
            $timeLeft = str_replace('hours', 'hour', $timeLeft);
            $timeLeft = str_replace('Hs', 'H', $timeLeft);
        }
        if($minutes == 1 || $min_only == 1){
            $timeLeft = str_replace('minutes', 'minute', $timeLeft);
            $timeLeft = str_replace('mins', 'min', $timeLeft);
            $timeLeft = str_replace('Ms', 'M', $timeLeft);           
        }
           
      return $timeLeft;
    }
} 

//returns the first and last minute as well as month and year string of the given month (unix timestamp format)
function get_month_bounds( $month )
{
	$bound = array();
	
	$date = getdate( $month );
	$bound['month'] = $date['mon'];
	$bound['year'] = $date['year'];
			
	$bound['first'] = mktime(0, 0, 0, $bound['month'], 1, $bound['year']);
	$bound['last'] = mktime(23, 59, 0, $bound['month'], date('t', $bound['first']), $bound['year']);
	
	return $bound;
}
