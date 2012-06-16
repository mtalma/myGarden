<?php

$address = $maintenance_plan['user']['address'];
if( $maintenance_plan['user']['address2'] != '' )
{
	$address .= ", " . $maintenance_plan['user']['address2'];
}
$region = $maintenance_plan['user']['region'];	
$name = $maintenance_plan['user']['fname'] . " " .$maintenance_plan['user']['lname'];
$email = $maintenance_plan['user']['email'];
$home = format_phone_number( $maintenance_plan['user']['hphone'] );
$cell = "";
if( isset($maintenance_plan['user']['cphone']) && $maintenance_plan['user']['cphone'] != "" )
	$cell = format_phone_number( $maintenance_plan['user']['cphone'] );
else
	$cell = "None";
?>

<div class="mainbaninfo">
		<h1>Customize Your Plan</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">

<ul id="stepbar">
	<li>1. Customize</li>
	<li>2. Confirm &amp; Sign up</li>
	<li class="current">3. See you soon!</li>
</ul>

<h1>Thank You, <?=$maintenance_plan['user']['fname'];?></h1>
<p class="intro">We've received your request and sent you an email for your records.</p>

<div class="greyarea receipt">
	<h2>Your Package information</h2>
	
	<div class="receipt-left">
	<h3>Address</h3>
		<p><?=$address?><br />
		<?=$maintenance_plan['user']['region'];?>,<br />
		Trinidad, West Indies<br /></p>
	<h3>Info</h3>
		<p><span class="info-header">Name:</span> <?=$name;?><br /> 
		<span class="info-header">Email:</span> <?=$email;?><br />
		<span class="info-header">Phone:</span> <?=$home;?><br />
		<span class="info-header">Cell:</span> <?=$cell;?></p>
	</div>
	
	<div class="receipt-right">
	<h3>Maintenance Package</h3>
		<ul>
			<?php foreach($maintenance_plan['services'] as $service) { ?>
			<li><?=$service['name']?></li>
			<?php } ?>	
		</ul>	
	</div>
	<div class="total final"><span class="price-header">Monthly Total</span> <span class="price"><?="$" . number_format($maintenance_plan['monthlyCost'], 2);?></span></div>
<div class="clear"></div>	
</div>
<div class="greybottom"></div>
<div class="clear"></div>
<h2>We'll be in Contact</h2>
<p>Once we contact you and confirm your address, we'll start maintenance on your garden! If you have some time, contact us and tell us about the experience of signing up for your custom maintenance package. We love feedback.</p>


</div><!--End Content-->