/*------------------------------------------------------------
 *						ColorBox
/*-----------------------------------------------------------*/

$(document).ready(function(){
	$(".gallery").colorbox();
	
	//colourbox for service modal
	$(".servicebox").colorbox({
		width:"800px", height:"450px", iframe:false,
		onComplete:function(){
			//display if the service is added or not
			//$('#addedservice').hide();
		}
	});
});


/*------------------------------------------------------------
 *						Slider
/*-----------------------------------------------------------*/

$(document).ready(function(){

$('.hidebox').hide();
$('a.openbox').click(function() {$('div.contact-form').slideToggle(300);
return false;
});
});



$(document).ready(function(){

	$(".pricing .icon").hover(function() {
	  $(this).next("div").animate({opacity: "show", top: "-85"}, "slow");
	}, function() {
	  $(this).next("div").animate({opacity: "hide", top: "-95"}, "fast");
	});

});



$(document).ready(function() { 
         $('.dynamic_select').show(); 
        $('select#or').change(function() { 
                if ($("select#or").val() === 'Garden Maintenance'){ 
                        $('.dynamic_select').hide(); 
                } 
                else{ 
                        $('.dynamic_select').show(); 
                } 
   }); 
}); 