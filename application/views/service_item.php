
<div id="quote-modal">
	<h1><?=$service['name']?></h1>
	<?php if( $service['recommended'] ) { ?><span class="recommend">*Recommended</span><?php } ?>
	<div class="clear"></div>
	<div class="left-info">
	
		<p class="price">$<?=$service['price']?><span>/hour</span></p>
		<p><?=$service['description']?></p>
		
		<ul>
			<?php foreach($service['service_list'] as $num => $item): ?>
        		<li><?=$item['list_item']?></li>
        	<?php endforeach; ?>  
		</ul>
		
		<div id="<?=$service['short_name']?>" class="modal_choicebutton">
			<a id='modal_add' class="add inactive" href="#">Add</a>
			<a id='modal_remove' class="minus active" href="#">Remove</a>
		</div>
	</div>
	
	<div class="right-img">
		<img src="<?=base_url() . $service['img_src_large']?>" alt="Alt"/>
		<div id="addedservice" class="addedservice">This service has been added!</div>
	</div>
</div>