/*------------------------------------------------------------
 *						Services Page
/*-----------------------------------------------------------*/

$(document).ready(function() {

	requiredServices = 4;
	SyncServices();	

	$("form").submit(function() 
	{
		selectedServices = $("input:checked").length;
		$("p#error").fadeOut(100)
		if( selectedServices < requiredServices )
		{
			diff = requiredServices - selectedServices;
			$("p#error").text( "* A minimum of " + requiredServices + " services are required, please select " + diff + " more.").fadeIn(100);
			return false;
		}

		return true;
	});
	
	//syncs all the services with the checkboxes - since these are persistent with page-reloads
	function SyncServices()
	{	
		$('input#hidden').each( function() {
			var service = $(this).attr('name');
				
			if( !$( this ).is(':checked') )
			{
				//checkbox is checked - add service
				UpdateService(service, true, true );
			}
			else
				UpdateService(service, false, true );
		});
	}
	
	//initialize the modal box
	$(document).bind('cbox_complete', function(){ 
		add = $('.modal_choicebutton').children('#modal_add');
		remove = $('.modal_choicebutton').children('#modal_remove');
		service = $('.modal_choicebutton').attr('id');
		
		if( $('div#' + service +'.choicebutton').children('a.add').hasClass('inactive') )
		{
			$('#addedservice').show();
			remove.removeClass('inactive');
			remove.addClass('active');
			
			add.removeClass('active');
			add.addClass('inactive');
		}
		else
		{
			$('#addedservice').hide();
			remove.removeClass('active');
			remove.addClass('inactive');
			
			add.removeClass('inactive');
			add.addClass('active');
		}
	});
	
	//modal box button pressed
	$('#colorbox').click( function(e) {
	
		parent = $(e.target).parent();
		add = parent.children('#modal_add');
		remove = parent.children('#modal_remove');
				
		if( $(e.target).attr('id') == 'modal_add' && add.hasClass('active') )
		{	
			remove.removeClass('inactive');
			remove.addClass('active');
			
			add.removeClass('active');
			add.addClass('inactive');
			
			$('#addedservice').show();
			
			UpdateService( add.parent().attr('id'), false, false );
			setTimeout($.fn.colorbox.close,100);
		}
		
		if( $(e.target).attr('id') == 'modal_remove' && remove.hasClass('active') )
		{	
			remove.removeClass('active');
			remove.addClass('inactive');
			
			add.removeClass('inactive');
			add.addClass('active');
			
			$('#addedservice').hide();
			
			UpdateService( remove.parent().attr('id'), true, false );
			setTimeout($.fn.colorbox.close,100);
		}
		
		e.preventDefault();
	});

	//main page button pressed
	$('.choicebutton').click( function(e) {	
		
		status = $(this).parent().children('div#details').children('span#status');
		add = $(this).children('#add');
		remove = $(this).children('#remove');
		target = $(e.target).attr('class');

		//remove button pressed
		if( target == remove.attr('class') && remove.hasClass('active') )
		{	
			UpdateService( remove.parent().attr('id'), true, false );
		}
		
		//add button pressed
		if( target == add.attr('class') && add.hasClass('active') )
		{
			UpdateService( add.parent().attr('id'), false, false );	
		}
		
		e.preventDefault();
		return false;
	});
	
	function UpdateService( item, remove, init )
	{
		$('.choicebutton').each( function() {

			if( $(this).attr('id') == item )
			{
				add_button = $(this).children('#add');
				remove_button = $(this).children('#remove');
				status_icon = $(this).parent().children('div#details').children('span#status');
				checkbox = $(this).parent().children('input#hidden');
				
				if( remove )
				{
					remove_button.removeClass('active');
					remove_button.addClass('inactive');
					add_button.removeClass('inactive');
					add_button.addClass('active');
					status_icon.removeClass('checked');
					
					if( !init )
						checkbox.removeAttr('checked');
				}
				else
				{
					remove_button.removeClass('inactive');
					remove_button.addClass('active');
					add_button.removeClass('active');
					add_button.addClass('inactive');
					status_icon.addClass('checked');
					
					if( !init )
						checkbox.attr('checked','checked');

				}
			}
		});
		
		$('div#quote').children("p").fadeOut( 100, function() {
			total = CalculateTotal();
			if( total == 0 )
				$(this).html( "<p class='message'>No Services Added</p>" );
			else
				$(this).html( "<p><span class='number'>$" + total + "</span><span class='hour'>/HOUR</span></p>" );
				

			$(this).fadeIn(100);
		});
		
		$('ul#quote-list').children('li').each( function() {
			
			if( $(this).attr('id') == item )
			{ 
				if( init )
					remove ? $(this).hide() : $(this).show();
				else
				{
					remove ? $(this).slideUp(400) : $(this).slideDown(400, function() { $(window).scroll(); });
				}
			}
				
		});
	}
	
	function CalculateTotal()
	{	
		total_cost = 0;
		$("a#add").each( function() {
			//inactive means its pressed
			if( $(this).hasClass('inactive') )
			{	
				total_cost += parseInt( $(this).parent().parent().children('div#details').children('span.price').attr('id') );	
			}
		})
		
		return total_cost;
	}
});