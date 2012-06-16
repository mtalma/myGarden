<ul class="breadcrumb">
	<? $last_key = end( array_keys( $template['breadcrumbs'] ) ) ?>
	<? foreach( $template['breadcrumbs'] as $key => $breadcrumb ): ?>
		<? if( $key == $last_key ): ?>
			<li class="active"><?= $breadcrumb['name'] ?></li>
		<? else: ?>
			<li>
				<a href="<?= base_url() . $breadcrumb['uri'] ?>"><?= $breadcrumb['name'] ?></a> <span class="divider">/</span>
			</li>		
		<? endif ?>
	<? endforeach ?>
</ul>