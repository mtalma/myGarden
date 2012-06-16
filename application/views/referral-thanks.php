<div class="mainbaninfo">
		<h1>Invite Neighbours to myGarden</h1>
	</div>
	<div class="mainbanimg">
	</div>
	<div id="content">
		<h1>Invites Sent!</h1>
		<p class="intro">Thank you <?=$fname?>. We'll be sure to let you know when your neighbours sign up.</p>

		<div class="sent-container">
			<img src="<?=base_url()?>images/send-big.jpg">
			<ul>
				<?php
				if( count( $sent_emails ) > 0 )
				{
					foreach( $sent_emails as $email )
						echo "<li>".$email."</li>";
				}
				?>
			</ul>
		</div>
		<div class="clear"></div>
		<h2>Next Steps</h2>
		<p>If a neighbour your referred signs up, we'll send you an email letting you know. Once we have successfully activated their plan, our system will automatically credit your account one maintenance day. The more of your neighbours sign up, the more you save!</p>

		<p class="fineprint">* Not seeing all the emails you entered? It may be because those emails are already signed up for myGarden or they have already been referred.</p>
	</div>

<div id="sidebar">
	<h3>In this section:</h3>
<ul id="sec-nav">
	<li class=""><a href="<?=base_url()?>index.php/mygarden/list_services/<?=$hashed_id?>">My Service Reports</a></li>
	<li class="current"><a href="<?=base_url()?>index.php/mygarden/referral/<?=$hashed_id?>">Invite Neighbours</a></li>
</ul>



</div>
