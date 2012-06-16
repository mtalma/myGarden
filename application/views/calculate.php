<div class="mainbaninfo">
		<h1>Customize Your Plan</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">

<ul id="stepbar">
	<li>1. Customize</li>
	<li class="current">2. Confirm &amp; Sign up</li>
	<li>3. See you soon!</li>
</ul>
<h1>Your Maintenance Package</h1>
<p class="intro">Review your maintenance package and monthly estimate below and sign up.</p>
<div class="greyarea">
	<h2>Estimated monthly total</h2>
	
	<table class="totals">
		<tr class="header">
			<td class="left">Service</td>
			<td class="right">Rate</td>
		</tr>
		
		<?php foreach($services as $service) { ?>
		<tr>
			<td><?=$service['name']?></td>
			<td class="right"><?="$" . number_format($service['quoted_price'], 2);?></td>
		</tr>
		<?php } ?>
		
		<tr class="header">
			<td class="left">Item</td>
			<td class="right">Totals</td>
		</tr>
		
		<tr>
			<td>Est. Monthly Service Charges <strong>({hoursPerVisit} hrs/visit x {visitsPerMonth} visits)</strong>
			</td>
			<td class="right"><?="$" . number_format($monthlyServiceRate, 2);?></td>
		</tr>
		<tr>
			<td>Transportation Fees</td>
			<td class="right"><?="$" . number_format($transportFee, 2);?></td>
		</tr>
		<tr class="subtotal">
			<td>Subtotal</td>
			<td class="right"><?="$" . number_format($transportFee+$monthlyServiceRate, 2);?></td>
		</tr>
		<tr class="subtotal">
			<td>Tax (15% VAT)</td>
			<td class="right"><?="$" . number_format(0.15*($transportFee+$monthlyServiceRate), 2 );?></td>
		</tr>
		<tr class="total final">
			<td>Monthly Total</td>
			<td class="right"><?="$" . number_format(1.15*($transportFee+$monthlyServiceRate), 2 );?></td>
		</tr>
		
		
	</table>

</div>
<div class="greybottom"></div>
<a name="errors"></a>
<h2>Look good? Let's get started!</h2>
<p>Enter your details here and we'll start maintenance in as little as a week.</p>
<?php
$hidden = array('maintenance_id' => $maintenance_id );
echo form_open('/mygarden/calculate', '', $hidden );
?>
<div class="greyarea contactform">

<ul class="errors"><?php echo validation_errors(); ?></ul>

<div class="input-wrapper">
<span class="twoup med">
	<label class="required">First Name*</label>
	<input class="std-input <?php echo form_error('fname') ? "error" : ""; ?>" type="text" name="fname" value="<?=set_value('fname')?>" size="50" />
</span>
<span class="twoup med last">
	<label class="required">Last Name*</label>
	<input class="std-input <?php echo form_error('lname') ? "error" : ""; ?>" type="text" name="lname" value="<?=set_value('lname')?>" size="50" />
</span>	
	</div>
	
	<label class="required">Email Address*</label>
	<input class="std-input <?php echo form_error('email') ? "error" : ""; ?>" type="text" name="email" value="<?=set_value('email')?>" size="50" />
<div class="input-wrapper">	
<span class="twoup med">
	<label class="required">Home Phone Number*</label>
	<input class="std-input <?php echo form_error('phone') ? "error" : ""; ?>" type="text" name="phone" value="<?=set_value('phone')?>" size="50" />
</span>	
<span class="twoup med last">	
	<label class="">Cell Phone Number</label>
	<input class="std-input <?php echo form_error('cell') ? "error" : ""; ?>" type="text" name="cell" value="<?=set_value('cell')?>" size="50" />
</span>
</div>	
	<label class="required">Address 1*</label>
	<input class="std-input <?php echo form_error('address') ? "error" : ""; ?>" type="text" name="address" value="<?=set_value('address')?>" size="50" />
	
	<label class="required">Address 2</label>
	<input class="std-input <?php echo form_error('address2') ? "error" : ""; ?>" type="text" name="address2" value="<?=set_value('address2')?>" size="50" />
	
	<label class="">Directions</label>
	<textarea name="directions" cols="50" rows="12"></textarea>
	
	<div class="clear"></div>
	<p class="fineprint">The following fields were set on the previous page and can't be changed unless you start over.</p>
<div class="input-wrapper">	
<span class="twoup med">
	<label>Region</label>
	<input type="text" size="50" value="<?php echo $region; ?>" class="std-input" disabled=''>
</span>	
<span class="twoup med last">	
	<label>Country</label>
	<input type="text" size="50" value="Trinidad"class="std-input" disabled=''>
</span>
</div>
	
	<div class="clear"></div>
		<input type="submit" name="start" class="form-but long" value="Start Your Service!" />
	<div class="clear"></div>
	
</div>
<div class="greybottom"></div>
<div class="clear"></div>
<?php echo form_close(); ?>


</div>