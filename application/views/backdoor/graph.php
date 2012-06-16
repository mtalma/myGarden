<?php

$data['heading'] = "Maintenance Statistics";
$data['block_id'] = 'graph';

$data['content_class'] = 'tab_content';
$data['content_id'] = $name;
//$data['list'] = $names;

$data['main'] = "<table class='stats' rel='".$type."' cellpadding='0' cellspacing='0' width='100%'>\n";

$data['main'] .= "<thead>\n";
$data['main'] .= "<tr>\n";

foreach( $cols as $col )
	$data['main'] .= "<th scope='col'>".$col."</th>\n";

$data['main'] .= "</tr>\n";
$data['main'] .= "</thead>\n";
					
$data['main'] .= "<tbody>\n";

foreach( $values as $name =>$value )
{	
	$data['main'] .= "<tr>\n";
		$data['main'] .= "<th scope='row'>".$name."</th>\n";
		
	foreach( $value as $row )
		$data['main'] .= "<td>".$row."</td>\n";
	
	$data['main'] .= "</tr>\n";
}
		
$data['main'] .= "</tbody>\n";


$data['main'] .= "</table>\n";
$this->load->view( 'backdoor/table', $data );
table_data_clear($data);