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
<title><?=$title?></title>	

<!--jQuery-->
<script type="text/javascript" src="<?=base_url()?>js/jquery.min.js?v=1.4.2"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery-ui.custom.min.js?v=1.8"></script>
<script src="<?=base_url()?>js/jquery.colorbox-min.js" type="text/javascript"></script>

<!--Blog Feeds-->
<link rel="alternate" type="application/atom+xml" title="Projects and Rentals" href="<?=base_url()?>site/feed" />

<!--Stylesheets-->
<link href="<?=base_url()?>css/reset.css" rel="stylesheet" type="text/css" media="screen" />

<link href="<?=base_url()?>css/foliage.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?=base_url()?>css/sifr.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>css/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?=base_url()?>css/ie6.css'?>" /><![endif]-->

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

<body class="<?=isset($body_class) ? $body_class : ''?>">

<div id="container">

<div id="header">


	<h1 id="logo"><a href="http://foliagedesign.tt/">http://foliagedesign.tt/</a></h1>

	<div id="search-contact">


		<h2>(868) 676-8752 | <?php echo safe_mailto('hello@foliagedesign.tt', 'Email Us'); ?></h2>


<div id="searchbox"><form method="post" action="http://foliagedesign.tt/"  >
<div class='hiddenFields'>
<input type="hidden" name="ACT" value="19" />
<input type="hidden" name="XID" value="a02605303c1177aeadc961259eed176d6ada85c6" />
<input type="hidden" name="RP" value="search/results" />
<input type="hidden" name="NRP" value="" />
<input type="hidden" name="RES" value="" />
<input type="hidden" name="status" value="" />
<input type="hidden" name="weblog" value="" />
<input type="hidden" name="search_in" value="everywhere" />
<input type="hidden" name="where" value="all" />

<input type="hidden" name="site_id" value="1" />
</div>

<input type="text" id="search" name="keywords" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Search':this.value;" value="Search" /></form></div>

	</div>



	<ul id="nav">
		<li ><a href="/">Home</a></li>
		<li><a href="/services/">Landscape Services</a></li>
		<li class="current"><a href="/mygarden/">myGarden</a></li>
		<li ><a href="/projects/">Projects</a></li>
		<li ><a href="/rentals/">Rentals</a></li>
		<li ><a href="/about/">About</a></li>
	</ul>

</div><!--End Header-->