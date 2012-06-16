<div id="sidebar">

	<h3>In This Section</h3>

<ul id="sec-nav">
	<li <?php echo( $side_bar_section == 1 ? "class='current'" : "" ); ?>><a href="/myGarden">Overview</a></li>
	<li <?php echo( $side_bar_section == 2 ? "class='current'" : "" ); ?>><a href="<?=base_url()?>">Build Your Plan</a></li>
	<li <?php echo( $side_bar_section == 3 ? "class='current'" : "" ); ?>><a href="<?=base_url('index.php/mygarden/faq/')?>">F.A.Q.</a></li>
</ul>

<?php if($load_summary_view) { ?>
<script src="<?=base_url()?>js/floating_box.js" type="text/javascript" ></script>
<div id="quotemenu">
	<div class="summary">Summary</div>
	<ul id="quote-list">
			<?php foreach($services as $service): ?>
			<li id="<?=$service['short_name']?>"><?=$service['name']?><div class="price">$<?=$service['price']?></div></li>
			<?php endforeach; ?>
	</ul>
	<div id="quote" class="quote">
		<p><span class="number"></span><span class="hour">/HOUR</span></p>
	</div>
</div>
<?php } ?>

</div><!--End Sidebar-->