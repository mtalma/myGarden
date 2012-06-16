<script>
$(document).ready(function() {

	get_records(<?=$selection?>,'all');

	function show_loading( bShow )
	{
		if( bShow )
			$('#loader').show();
		else
			$('#loader').hide();
	}

	$('#month_filter').change( function(e) {
		start_month = $('#month_filter').val();
		crew_id = $('#crew_filter').val();
		get_records( start_month, crew_id );
		return false;
	});
	
	$('#crew_filter').change( function(e) {
		start_month = $('#month_filter').val();
		crew_id = $('#crew_filter').val();
		get_records( start_month, crew_id );
		return false;
	});

	function get_records( month, crew_id ) 
	{
		var p = {};
		p['view'] = '<?=$ajax_view?>';
		p['id'] = '<?=isset($plan['id'])?$plan['id']:0?>';
		if( month && crew_id ) {
			p['month'] = month;
			p['crew'] = crew_id;
		}

		$("#record_container").fadeOut('fast', function() {
			show_loading(true);
			$("#record_container").load("<?=base_url()?>index.php/backdoor/load_records", p, function(str) {
				show_loading(false);
				$("#record_container").fadeIn('fast');
			});
		});
	}
});
</script>

<?php

$attributes = array('class' => 'filter', 'id' => 'record_filters');
echo form_open('#', $attributes);
echo '<p><label>Month</label>' . form_dropdown('month_filter', $months, $selection, "id='month_filter' class='styled'") . '</p>';
echo '<p><label>Crew</label>' . form_dropdown('crew_filter', $crews, 'all', "id='crew_filter' class='styled'") . '</p>';

echo "<br>";
echo form_close();

echo "<div id='loader' class='loading' style='display:none'></div>";

echo "<div id='record_container'></div>";