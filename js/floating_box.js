 var header_height = 500;

$(document).ready(function() {
	$(window).scroll(function(){
	 
		var pinned_top = $(document).scrollTop() < header_height;
		var pinned_bottom = ( $(document).height() - $('#footer').height() - $('#quotemenu').height() - 40/*margins*/ < $(document).scrollTop()  );
		var floating = ( $(document).scrollTop() > header_height ) && !pinned_bottom;
		
		
		//$('#output').html( 'Pinned Top: ' + pinned_top + '<br /> Floating: ' + floating + '<br /> Pinned Bottom: ' + pinned_bottom + '<br /> Document: ' + $(document).scrollTop() );
		
		
		if( pinned_top ) {
			//pinned to the top
			$('#quotemenu').removeClass('pinned_bottom');
			$('#quotemenu').removeClass('floating');
			$('#quotemenu').addClass('pinned_top');
		}
		else if( floating ) {
			//scrolling with document
			$('#quotemenu').removeClass('pinned_top');
			$('#quotemenu').removeClass('pinned_bottom');
			$('#quotemenu').addClass('floating');
		}
		else if( pinned_bottom )
		{
			//pinned to the bottom
			$('#quotemenu').removeClass('floating');
			$('#quotemenu').removeClass('pinned_top');
			$('#quotemenu').addClass('pinned_bottom');
		}
	});
	
	//trigger scroll event
	$(window).scroll();
});