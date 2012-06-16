<script type='text/javascript'>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			minTime: 8,
			maxTime: 18,
			
			editable: 'yes',
			
			events: '<?=base_url()?>index.php/backdoor/getServiceDatesJSON',
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
		});
		
	});

</script>
<style type='text/css'>
	
	.block table.fc-border-separate tbody tr:hover, .block table.fc-header tbody tr:hover {
		background: none;
	}
	
	.block table.fc-border-separate tr td, .block table tr th
	{
		border-bottom: none;
	}
	
	.block table.fc-border-separate tr.fc-last th, .block table.fc-border-separate tr.fc-last td {
		border-bottom: 1px solid #DDD;
	}
	
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}
	
	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>

<div id="loading">Loading...</div>
<?php
	$data['heading'] = "Schedule Calender";
	$data['main'] = "<div id='calendar'></div>";

	$this->load->view( "backdoor/table", $data );

?>