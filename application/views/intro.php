<script src="<?=base_url()?>js/services.js" type="text/javascript" ></script>

<div class="mainbaninfo">
		<h1>Customize Your Plan</h1>
	</div>
	<div class="mainbanimg">
	</div>
<div id="content">

<ul id="stepbar">
	<li class="current">1. Customize</li>
	<li>2. Confirm &amp; Sign up</li>
	<li>3. See you soon!</li>
</ul>
<h1>Get Going in Five Minutes</h1>
<div class="clear"></div>

<?php echo form_open('mygarden/calculate', array('id' => 'services') ); ?>
<div class="greyarea">
	<h2>First, Some Details</h2>
	<p>We'll use these details to give you an estimate of your monthly bill.</p>
	
	<span class="input-wrapper">
	
		<span class="twoup med">
			<?php if($regions){ ?>
			<label class="required">Region*</label><br />
				<select name="region">
					{regions}
					<option name="{name}" value="{id}">{name}</option>
					{/regions}
				</select>
			<?php } ?>
		</span>	
		
		<span class="twoup lg">
			<?php if($garden_size){ ?>
			<label class="required">Garden Size*</label>
				<select name="gardenSize">
					{garden_size}
					<option name="{size}" value="{id}">{size} ({desc})</option>
					{/garden_size}
				</select>
			<?php } ?>
		</span>	
	</span>	
	
	<span class="input-wrapper">
	
		<span class="threeup sm">
			<?php if($service_days){ ?>
			<label class="required">Service Day*</label>
				<select name="serviceDay">
					{service_days}
					<option name="{day}" value="{id}">{day}</option>
					{/service_days}
				</select>
			<?php } ?>
		</span>	
		
		<span class="threeup lg">
			<?php if($service_times){ ?>
			<label class="required">Service Time*</label>
				<select name="serviceTime">
					{service_times}
					<option name="{name}" value="{id}">{name}: {avg_start} - {avg_end}</option>
					{/service_times}
				</select>
			<?php } ?>		
			</span>	

		<span class="threeup sm">
			<?php if($service_cycle){ ?>
			<label class="required">Service Cycle*</label>
				<select name="serviceCycle">
					{service_cycle}
					<option name="{cycle}" value="{id}">{cycle}</option>
					{/service_cycle}
				</select>
			<?php } ?>
		</span>	
	</span>	
	<div class="clear"></div>

		<p class="fineprint">* We only service a few regions at the moment, however we are expanding. If your region isn't listed, please check back soon. Other options may be limited.</p>
		
</div>
<div class="greybottom"></div>
<h2>Then Choose Your Service</h2>
<p>Add 3 or more services that are right for your garden and see your price update live on the left. You can see which services have been added by the green check box.</p>
<ul id="servlist">
	<?php 
	$no_margin = "";
	$count = 1;
	foreach($services as $service): 
	?>
	<li class="<?=$no_margin?>">
		<div id="details" class="infobar">
			<span id="<?=$service['price']?>" class="price"><em>$<?=$service['price']?></em>/hour</span>
			<span id="status" class="status checked"></span>
		</div>
		<img src="<?=base_url() . $service['img_src']?>" width="207" height="130">
		
		<h3><a class="servicebox" href="<?=base_url()?>index.php/mygarden/service_modal/<?=$service['id']?>"><?=$service['name']?></a></h3>
		
		<?php 
			$css = "";
			if( $this->input->post('submit') )
			{
				if( $this->input->post( $service['short_name'] ) )
					$css = "checked='checked'";
			}
			else if( $service['recommended'] )
				$css = "checked='checked'";
		?>
		<input type="checkbox" id="hidden" name="<?=$service['short_name']?>" value="add" <?=$css?> />
		<div id="<?=$service['short_name']?>" class="choicebutton">
			<a id='add' class="add inactive" href="#">Add</a>
			<a id='remove' class="minus active" href="#">Remove</a>
		</div>
		
	</li>
	<?php 
		$no_margin = ++$count%3 ? "" :"nomargin";
	endforeach;
	?>
	</ul>
	
	<div class="clear"></div>
	<p id="error" class="fineprint floatRight" style="display: none;"></p>
	<input type="submit" name="submit" class="form-but long" value="Save and Continue" />
</div>	
</form>



