<?php echo form_open('quote/calculate', array('id' => 'signup') ); ?>

<link rel="stylesheet" href="<?php echo base_url(); ?>styles/forms/form.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/wufoo.js"></script>

<div class="info">
	<h2>Sign up</h2>
	<div>Enter your details here and we'll start maintenance in as little as a week</div>
</div>

<ul>

<li id="name" class="leftHalf">
	<label class="desc" id="title1" for="Field1">
		Name
	<span id="req_1" class="req">*</span>
	</label>
	<span>
		<input id="fname" name="fname" type="text" class="field text fn" value="<?php echo set_value('fname'); ?>" size="8" tabindex="3" />
		<label for="fname">First</label>
	</span>
	<span>
		<input id="lname" name="lname" type="text" class="field text ln" value="<?php echo set_value('lname'); ?>" size="14" tabindex="4" />
		<label for="fname">Last</label>
	</span>
	<?php echo form_error('fname'); ?>
	<?php echo form_error('lname'); ?>
	</li>


<li id="email_address" class="rightHalf">
	<label class="desc" id="title9" for="Field9">
	Email
	<span id="req_9" class="req">*</span>
	</label>
	<div>
		<input id="email" name="email" type="text" class="field text medium" value="<?php echo set_value('email'); ?>" maxlength="255" tabindex="5" /> 
		<label for="email">eg: hello@foliagedesign.tt</label>
	</div>
	<?php echo form_error('email'); ?>
	</li>


<li id="house_num" class="phone leftHalf">
	<label class="desc" id="title10" for="Field10">
		House Number
	</label>
	<span>
		<input disabled id="house_pre" name="house_pre" type="text" class="field text disabled" value="868" size="3" maxlength="3" tabindex="6" />
		<label for="house_pre">###</label>
	</span>
	<span class="symbol">-</span>
	<span>
		<input id="house_num1" name="house_num1" type="text" class="field text" value="<?php echo set_value('house_num1'); ?>" size="3" maxlength="3" tabindex="7" />
		<label for="house_num1">###</label>
	</span>
	<span class="symbol">-</span>
	<span>
	 	<input id="house_num2" name="house_num2" type="text" class="field text" value="<?php echo set_value('house_num2'); ?>" size="4" maxlength="4" tabindex="8" />
		<label for="house_num2">####</label>
	</span>
	<?php echo form_error('house_num1'); ?>
	<?php echo form_error('house_num2'); ?>
	</li>


<li id="cell_num" class="phone rightHalf">
	<label class="desc" id="title113" for="Field113">
		Cell Phone Number
	<span id="req_10" class="req">*</span>
	</label>
	<span>
		<input disabled id="cell_pre" name="cell_pre" type="text" class="field text disabled" value="868" size="3" maxlength="3" tabindex="9" />
		<label for="cell_pre">###</label>
	</span>
	<span class="symbol">-</span>
	<span>
		<input id="cell_num1" name="cell_num1" type="text" class="field text" value="<?php echo set_value('cell_num1'); ?>" size="3" maxlength="3" tabindex="10" />
		<label for="cell_num1">###</label>
	</span>
	<span class="symbol">-</span>
	<span>
	 	<input id="cell_num2" name="cell_num2" type="text" class="field text" value="<?php echo set_value('cell_num2'); ?>" size="4" maxlength="4" tabindex="11" />
		<label for="cell_num2">####</label>
	</span>
	<?php echo form_error('cell_num1'); ?>
	<?php echo form_error('cell_num2'); ?>
	</li>


<li id="address" class="complex">
	<label class="desc" id="title217" for="Field217">
		Address
	</label>
	<div>
		<span class="full">
		<input id="address1" name="address1" type="text" class="field text addr" value="<?php echo set_value('address1'); ?>" tabindex="12" />
		<label for="address1">Street Address</label>
		</span>
		<span class="full">
		<input id="address2" name="address2" type="text" class="field text addr" value="<?php echo set_value('address2'); ?>" tabindex="13" />
		<label for="address2">Address Line 2</label>
		</span>
		<span class="left">
		<input disabled id="region" name="region" type="text" class="field text addr disabled" value="<?php echo $region; ?>" tabindex="14" />
		<label for="region">Region</label>
		</span>
		<span class="right">
		<input disabled id="country" name="country" type="text" class="field text addr disabled" value="Trinidad" tabindex="15" />
		<label for="country">Country</label>
		</span>
	</div>
	<?php echo form_error('address1'); ?>
	<?php echo form_error('address2'); ?>
	</li>

</ul>
<?php echo form_submit('startMaintenance', 'Start Service', "class='but-float-right form-but'" ); ?>
<?php echo form_close(); ?>
