<script>
$(document).ready(function() {

	load_google_analytics();

	function show_loading( bShow )
	{
		if( bShow )
			$('#loader').show();
		else
			$('#loader').hide();
	}
	
	function load_google_analytics() 
	{
		$("#container").fadeOut('fast', function() {
			show_loading(true);
			$("#container").load("<?=base_url()?>backdoor/load_google_analytics", function(str) {
				show_loading(false);
				$("#container").fadeIn('fast');
			});
		});
	}
});
</script>

<div id='loader' class='loading' style='display:none; height: 600px;'></div>
<div id='container'></div>