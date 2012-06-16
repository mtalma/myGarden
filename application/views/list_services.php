
<div class="mainbaninfo">
		<h1>myGarden Services</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">
	<h1>Service Reports</h1>
	<?=isset($response) ? '<p>'.$response.'</p>' : ''?>
	<p class="intro">Below is a listing of all the maintenance services we have carried out on your garden. Click on any service to rate our performance.</p>

	<div class="clear"></div>

	<ul>
	<?php
		if( count($records) > 0 )
		{
			foreach( $records as $record )
			echo '<li>' . anchor( 'mygarden/list_services/'.$hash_id.'/'.$record['id'], date( "l jS F Y",mysql_date_to_timestamp($record['date'])) . ' - ' . $record['diff'] )  . '</li>';
		}
	?>
	</ul>
</div>

<div id="sidebar">
	<h3>In this section:</h3>
<ul id="sec-nav">
	<li class="current"><a href="<?=base_url()?>index.php/mygarden/list_services/<?=$hash_id?>">My Service Reports</a></li>
	<li class=""><a href="<?=base_url()?>index.php/mygarden/referral/<?=$hash_id?>">Invite Neighbours</a></li>
</ul>



</div>
