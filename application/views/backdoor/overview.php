<script>
$(document).ready(function() {

	$('#loader').hide();

	function show_loading( bShow )
	{
		if( bShow )
			$('#loader').show();
		else
			$('#loader').hide();
	}

	$('#all_plans').click(function() {
		$(this).parents('ul').find('li a.active').removeClass("active");
		$(this).addClass("active");

		get_plans();
		return false;
	});

	$('#active_plans').click(function() {
		$(this).parents('ul').find('li a.active').removeClass("active");
		$(this).addClass("active");

		get_plans( 3 );
		return false;
	});

	$('#pending_plans').click(function() {
		$(this).parents('ul').find('li a.active').removeClass("active");
		$(this).addClass("active");

		get_plans( 2 );
		return false;
	});

	function get_plans( status ) 
	{
		var p = {};
		if( status ) {
			p['status'] = status;
		}

		$("#plan_list").fadeOut('fast', function() {
			show_loading(true);
			$("#plan_list").load("load_plan",p,function(str){
				show_loading(false);
				$("#plan_list").fadeIn('fast');
			 });
		});
	}
});
</script>


<?php	
	$data['heading'] = "Maintenance Plans";
	 
	$data['list'] = array( 
			anchor('#', 'All', array('id'=>'all_plans', 'class'=> $plan_type == '' ? 'active' : '') ),
			anchor('#', 'Activated', array('id'=>'active_plans', 'class'=> $plan_type == PLAN_ACTIVATED ? 'active' : '')), 
			anchor('#', 'Pending', array('id'=>'pending_plans', 'class'=> $plan_type == PLAN_PENDING ? 'active' : '')) 
		);
		
	$data['form_action'] = "#";

	$data['main'] =  $table; 
	$data['main'] .= "<div id='loader' class='loading'></div>";

	$this->load->view( "backdoor/table", $data );
?>
