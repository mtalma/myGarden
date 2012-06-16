<?php

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
foreach( $gps as $row) 
{
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'lat="' . parseToXML($row->m_lat) . '" ';
  echo 'lng="' . parseToXML($row->m_long) . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';


?>