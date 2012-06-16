<div class="report">
	<?php
	foreach($plans as $plan ) {
	?>
		<h1>myGrarden Service Report</h1>
		<h2><?=date("D j<\s\u\p\>S</\s\u\p> F Y")?></h2>
		<ul class="service_details">
		    <li>Client: <?=$plan['user']['fname']." ".$plan['user']['lname']?></li>
		    <li>Address: <?=$plan['user']['address']." ".$plan['user']['address2']?></li>
		    <li>Region: <?=$plan['region']?></li>
		</ul>
		
		<h2><?=$plan['service_cycle']?> Service <span>Notes</span></h2>
		<?php
		$services = $plan['services'];
		echo "<ul id='services'>";
		foreach( $services as $service => $desc )
			echo "<li>" . $desc['name'] . "<span class='line'></span></li>";
		echo "</ul>";
		?>
		
		<p class="time_in">Time In:</p>
		<p class="time_out">Time Out:</p>
		
		<div class="clear"></div>
		
		<p>Manager Notes:</p>
		<p class="note"></p>
		<div class="text_box"></div>
		
		<p>Client Notes:</p>
		<p class="note"><p class="note"></p>
		<div class="text_box"></div>
		
		<p class="left quality">Quality of Service (tick):</p>
		<ul>
			<li>Very Poor</span> 
			<li>Poor</span> 
			<li>Average</span> 
			<li>Good</span> 
			<li>Excellent</span>
		</ul>
		
		<p class="signature left">Maintenance Manager</p>
		<p class="signature left last"><?=$plan['user']['fname']." ".$plan['user']['lname']?></p>
		
		<div class="clear break"></div>
	<?php
	}
	?>
</div>

