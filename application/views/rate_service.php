<script src="<?=base_url()?>js/jquery.ui.stars.min.js" type="text/javascript"></script>
<link href="<?=base_url()?>css/jquery.ui.stars.css" rel="stylesheet" type="text/css" media="all" />

<script>
$(document).ready(function() {

	$("#stars-wrapper2").stars({
		inputType: "select",
		captionEl: $("#stars-cap")
	});
	
});
</script>

<div class="mainbaninfo">
		<h1>Rate your Service</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">
	<p class="intro">We visited your garden <?=time_ago($record['date'])?>, please rate our service below:</p>

	<div class="clear"></div>

	<?=form_open('/mygarden/list_services/' . $hash_id . '/' . $record_id)?>
	<div class="greyarea contactform">
			
			<h4>Service Date:</h4>
			<p><?=date( "l jS F Y",mysql_date_to_timestamp($record['date']))?></p>
			
			<h4>Service Time:</h4>
			<p>Arrival: <b><?=$record['time_in']?></b> Departure: <b><?=$record['time_out']?></b> <br>
			Maintenance Time: <b><?=$record['diff']?></b></p>

			
			<h4>Quality of Service: <span id="stars-cap"></span></h4>
			<div id="stars-wrapper2">
				<select name="rating">
					<option value="1" <?=$record['rating'] == 1 ? 'selected="selected"' : "" ?>>Very poor</option>
					<option value="2" <?=$record['rating'] == 2 ? 'selected="selected"' : "" ?>>Poor</option>
					<option value="3" <?=$record['rating'] == 3 ? 'selected="selected"' : "" ?>>Average</option>
					<option value="4" <?=$record['rating'] == 4 ? 'selected="selected"' : "" ?>>Good</option>
					<option value="5" <?=$record['rating'] == 5 ? 'selected="selected"' : "" ?>>Excellent</option>
				</select>
			</div>
			
			<div class="clear"></div>
			<br>
			
			<h4>Service Feedback</h4>
			<p class="fineprint">Tell us what you liked or disliked about this service specifically so we can improve</p>
			<textarea rows="12" cols="50" name="feedback"><?=$record['client_notes']?></textarea>
			
			<div class="clear"></div>
			
			<h4>Manager Notes</h4>
			<p class="fineprint">The maintenance manager will frequently make notes on your garden</p>
			<textarea disabled="disabled" rows="12" cols="50" name="notes"><?=$record['manager_notes']?></textarea>
			
			<div class="clear"></div>
			
			<p class="fineprint">If any of the information listed here is incorrect please contact our office via <a href="mailto:hello@foliagedesign.tt">email</a> or by phone at 676-8752 between the hours of 8:00am - 4:00pm</p>
			
			
			<div class="clear"></div>
				<input type="submit" value="Save Rating" class="form-but long" name="rate">
			<div class="clear"></div>
			
		</div>
	<div class="greybottom"></div>
	<?=form_close()?>
</div>

<div id="sidebar">
	<h3>Manage your myGarden:</h3>
<ul id="sec-nav">
	<li class="current">
		<a href="<?=base_url()?>index.php/mygarden/list_services/<?=$hash_id?>">My Service Reports</a>
		<ul>
			<li class="current"><a href="<?=base_url()?>index.php/mygarden/list_services/<?=$hash_id?>/<?=$record_id?>">Rate Report</a></li>
		</ul>
	</li>
	<li>
		<a href="<?=base_url()?>index.php/mygarden/referral/<?=$hash_id?>">Invite Neighbours</a>
	</li>
</ul>



</div>
