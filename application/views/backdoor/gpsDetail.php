<?php

$handle = @fopen($path, "r");
if( $handle )
{
	$contents = stream_get_contents($handle);
	fclose($handle);
	
	$lines = explode( "\n", $contents );
	$gps = array();
	
	foreach( $lines as $line )
	{
		$chunks = explode( ",", $line, 6 );
		
		//only extract GPRMC data for now
		if( $chunks[0] == "\$GPRMC" )
			$gps[] = $line;
	}

	echo "Extracted " . count( $gps ) . " data points.<br>";
	
	$count = 1;
	foreach( $gps as $line )
	{
		
		$chunks = explode( ",", $line);
		if( $chunks[2] == 'A' ) //valid point
		{
			$long = explode( ".", $chunks[3] );
			
			$long_mins = $long[0]%100;
			$long_hours = floor( $long[0]/100 );
			$long_secs = $long[1] / 10000 * 60;
			
			$lat = explode( ".", $chunks[5] );
			
			$lat_mins = $lat[0]%100;
			$lat_hours = floor( $lat[0]/100 );
			$lat_secs = $lat[1] / 10000 * 60;
			
			$long_dec = $long_hours + $long_mins/60 + $long_secs/(60*60);
			$lat_dec = $lat_hours + $lat_mins/60 + $lat_secs/(60*60);
			
			if( $chunks[4] == "S" )
				$long_dec = -$long_dec;
			
			if( $chunks[6] == "W" )
				$lat_dec = -$lat_dec;
				
			$speedKMetersHour = $chunks[7] * 1.85200;
			
			$bearing = $chunks[8];
			
			$hours = $chunks[1][0] . $chunks[1][1];
			$mins = $chunks[1][2] . $chunks[1][3];
			$secs = $chunks[1][4] . $chunks[1][5];
			
			$day = $chunks[9][0] . $chunks[9][1];
			$month = $chunks[9][2] . $chunks[9][3];
			$year = $chunks[9][4] . $chunks[9][5];
			
			echo "Long: " . $long_dec . "<br>";
			echo "Lat: " . $lat_dec . "<br>";
			echo "Bearing: " . $bearing . "<br>";
			echo "Date: " . date("D d M Y - g:i:s A", mktime($hours, $mins, $secs, $month, $day, $year)) . "<br>";
			echo "Speed: " . $speedKMetersHour . "km/h<br><br>";
		}
		
	}
}
else
{
	echo "File open error";
}


?>