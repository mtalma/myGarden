<?=doctype()?>
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="Shortcut Icon" href="/favicon.ico"/>
<meta name="author" content="FortySeven Media" />
<meta name="copyright" content="&copy; <?=date('Y')?> Foliage Design" />
<meta name="keywords" content="foliage design, trinidad and tobago, santa cruz, water features, irrigation, lighting, garden maintenance" />
<meta name="description" content="A list of the great services we offer to our clients." />
<title>Foliage Design Plant Rentals</title>	

<style type="text/css" media="screen">

	.slides_container {
		width:635px;
		display:none;
		padding-top: 25px;
	}

	.slides_container div {
		width:635px;
		height:613px;
		display:block;
	    z-index: 0;
	}
</style>

<!--jQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/slides.min.jquery.js"></script>

<link href="<?=base_url()?>css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>css/landing.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?=base_url()?>css/button.css" rel="stylesheet" type="text/css" media="all" />

<script src="<?=base_url()?>js/cufon-yui.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/Gill_Sans_400.font.js" type="text/javascript"></script>
<script type="text/javascript">
	Cufon.replace('h1, ul.features li, form.action, .footer p');
</script>

<script>
	$(function(){
		$('#slides').slides({
			preload: true,
			generateNextPrev: false,
			effect: 'slide',
			play: 7000,
			slideSpeed: 350
		});
	});
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27923493-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>
<div class="col-left">
	<a class="logo" href="http://foliagedesign.tt">Foliage Design</a>
	
	<div id="slides">
		<div class="slides_container">
				<div><img src="<?=base_url()?>images/landing/hero1.jpg"></div>
				<div><img src="<?=base_url()?>images/landing/hero2.jpg"></div>
				<div><img src="<?=base_url()?>images/landing/hero3.jpg"></div>
			</div>
		</div>
	</div>
	
</div>

<div class="col-right">
	<h1>Plant rentals that transform your office space.</h1>
	<ul class="features">
		<li>Free Installation<small>All plants installed with no upfront cost to you.</small></li>
		<li>Free Transportation<small>Transportation is on the house for all plants.</small></li>
		<li>Weekly Maintenance<small>Watering, shining, fertilizing & pruning are part
	of the package.</small></li>
		<li>Plant Rotation<small>We replace older plants with fresh new ones.</small></li>
		<li>Experienced Staff<small>Our uniformed staff make sure every plant is
	always healthy and looking great.</small></li>
	</ul>
	<?php
		echo form_open('landing/rentals', array('class' => 'action') );
	?>
		<button type="submit" class="button red" name="rentals" value="more" id="more">See More Plants and Pricing</button>
		<small>tel: <em>(868) 676-8752</em> fax: <em>(868) 676-0132</em></small>
		
	<?=form_close()?>
</div>

<div class="footer">
<p>Our clients include:</p>
<ul>
<li><img src="<?=base_url()?>images/landing/angostura.jpg"></li>
<li><img src="<?=base_url()?>images/landing/coke.jpg"></li>
<li><img src="<?=base_url()?>images/landing/first-citizen.jpg"></li>
<li class="last"><img src="<?=base_url()?>images/landing/wasa.jpg"></li>
</ul>
<div class="clear"></div>
</div>

</body>
</html>