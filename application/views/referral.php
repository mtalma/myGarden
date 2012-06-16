<div class="mainbaninfo">
		<h1>Invite Neighbours to myGarden</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">
	<p class="intro">We hope your garden is looking spectacular and you're enjoying myGarden. We'd love for you to spread the word to all your neighbours living in <?=$region?>. For every neighbour that signs up, you'll receive <em>a free garden maintenance day</em> on us!*</p>

	<div class="icon">
		<img src="<?=base_url()?>images/send.jpg">
		<h2>1. You Invite<h2>	
	</div>

	<div class="icon">
		<img src="<?=base_url()?>images/signup.jpg">
		<h2>2. They Sign Up</h2>
	</div>

	<div class="icon">
		<img src="<?=base_url()?>images/save.jpg">
		<h2>3. You Save</h2>
	</div>

	<div class="clear"></div>

	<?=form_open('/mygarden/referral/' . $hashed_id)?>
	<div class="greyarea contactform">

		<h2>Invite Up to 5 Neighbours</h2>
			
			<label class="required">Friend's Email</label>
			<input type="text" size="50" name="email1" class="std-input <?php echo form_error('email1') ? "error" : ""; ?>" value="<?=set_value('email1')?>">
			<?php echo form_error('email1'); ?>

			<label class="required">Friend's Email</label>
			<input type="text" size="50" name="email2" class="std-input <?php echo form_error('email2') ? "error" : ""; ?>" value="<?=set_value('email2')?>">
			<?php echo form_error('email2'); ?>

			<label class="required">Friend's Email</label>
			<input type="text" size="50" name="email3" class="std-input <?php echo form_error('email3') ? "error" : ""; ?>" value="<?=set_value('email3')?>">
			<?php echo form_error('email3'); ?>

			<label class="required">Friend's Email</label>
			<input type="text" size="50" name="email4" class="std-input <?php echo form_error('email4') ? "error" : ""; ?>" value="<?=set_value('email4')?>">
			<?php echo form_error('email4'); ?>

			<label class="required">Friend's Email</label>
			<input type="text" size="50" name="email5" class="std-input <?php echo form_error('email5') ? "error" : ""; ?>" value="<?=set_value('email5')?>">
			<?php echo form_error('email5'); ?>
			
			<div class="clear"></div>

			<p class="fineprint">* In order to save, your neighbour's garden must be located in <?=$region?> and they must signup and be activated with the email address provided above. No personal information will be disclosed about you in the email - <a class="servicebox" href="<?=base_url()?>index.php/mygarden/sample_invite/<?=$hashed_id?>">see sample email</a></p>
			
			<div class="clear"></div>
				<input type="submit" value="Send Invitations" class="form-but long" name="invite">
			<div class="clear"></div>
			
		</div>
	<div class="greybottom"></div>
	<?=form_close()?>
</div>

<div id="sidebar">
	<h3>In this section:</h3>
<ul id="sec-nav">
	<li class=""><a href="<?=base_url()?>index.php/mygarden/list_services/<?=$hashed_id?>">My Service Reports</a></li>
	<li class="current"><a href="<?=base_url()?>index.php/mygarden/referral/<?=$hashed_id?>">Invite Neighbours</a></li>
</ul>



</div>
