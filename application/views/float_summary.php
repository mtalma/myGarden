<style>

#summary_box {
	background: #F8F7F4;
	border:1px solid #E9E8E6;
	height:450px;
	margin:20px 0 20px 15px;
	width:225px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	font-family: Georgia, "Times New Roman", Times, serif;
}

.pinned_top {
	position:absolute;
	top:460px;
}

.pinned_bottom {
	bottom: 300px;
	position:absolute;
	margin-bottom: 20px;
}
	
.floating {
	position:fixed;
	top:0;
}
</style>

 <script type="text/javascript">
 var header_height = 460;

$(window).scroll(function(){
	 
	var pinned_top = $(document).scrollTop() < header_height;
	var pinned_bottom = ( $(document).height() - $('#footer').height() - $('#summary_box').height() - 40/*margins*/ < $(document).scrollTop()  );
	var floating = ( $(document).scrollTop() > header_height ) && !pinned_bottom;
	
	
	//$('#output').html( 'Pinned Top: ' + pinned_top + '<br /> Floating: ' + floating + '<br /> Pinned Bottom: ' + $(document).scrollTop() );
	
	
	if( pinned_top ) {
		//pinned to the top
		$('#summary_box').removeClass('pinned_bottom');
		$('#summary_box').removeClass('floating');
		$('#summary_box').addClass('pinned_top');
	}
	else if( floating ) {
		//scrolling with document
		$('#summary_box').removeClass('pinned_top');
		$('#summary_box').removeClass('pinned_top');
		$('#summary_box').addClass('floating');
	}
	else if( pinned_bottom )
	{
		//pinned to the bottom
		$('#summary_box').removeClass('floating');
		$('#summary_box').removeClass('pinned_top');
		$('#summary_box').addClass('pinned_bottom');
	}
});

	function CalculateTotal()
	{	
		total_cost = 0;
		$("input[type=radio]:checked").each( function() {
			element_name = $(this).attr("name");
			if( $(this).val() == "add" )
				total_cost += $("ul#price_summary li[id=" + element_name + "]").attr("value");	
		})
		
		return total_cost;
	}
		 

		$("input[type=radio]").change(function(){
			element_name = $(this).attr("name");
			height = $('#summary_box').height();
			
			if( $(this).val() == "remove")	//remove button pressed
			{
				$("ul#price_summary li[id=" + element_name + "]").slideUp(400);
			}
			else if( ( $(this).val() == "add") ) 	//add button pressed
			{
				$("ul#price_summary li[id=" + element_name + "]").slideDown(400);
				
			}
			
			$('p.total_price').fadeOut( 100, function() {
				$('p.total_price').html( '<sup>$</sup>' + CalculateTotal() + '.00<span class="per_hour">/hour</span>' ).fadeIn(100);
			})
		})

</script>
<style>

#summary_box #header
{
	background:url("images/service_header_back.jpg") repeat-x;
	display:block;
	float:none;
	height:49px;
	margin:0;
	width:100%;
}
#summary_box h3
{
	color:#D61F30;
	font-size:20px;
	font-weight:normal;
	letter-spacing:0;
	line-height:20pt;
	margin:0;
	padding:10px 0;
	text-align:center;
	text-transform:none;
}

#summary_box p
{
	
	color:#614525;
	margin:10px 0 0 30px;
	padding:0;
	
}

#summary_box p.total_price
{
	display:block;
	font-size:18px;
	margin:15px 0;
	text-align:center;
}

#summary_box p.total_price span.per_hour
{
	color:#A19274;
	font-size:12px;
	font-variant:small-caps;
}

#summary_box ul
{
	margin:15px;
	border-bottom: 1px dotted #D0D0D0;
}

#summary_box li 
{
	border-top:1px dotted #D0D0D0;
	color:#89745C;
	font-size:13px;
	letter-spacing:0.6px;
	line-height:15px;
	margin-bottom:0;
	padding:10px 0;
	text-shadow:1px 1px 1px #D0D0D0;
}

#summary_box li span.item_price
{
	float: right;
}

</style>

<div id="summary_box" class="pinned_top">
         <div id="header"><h3>Summary</h3></div>
         <p class="total_price"></p>
         <ul id="price_summary">
         	{services}
            <li id="{short_name}" value="{price}">{name}<span class="item_price">${price}.00</span></li>
            {/services}
         </ul>
         <div id="output"></div>
</div>
