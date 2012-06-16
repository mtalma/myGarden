<?=doctype()?>

<?php $path = base_url() . "adminus"; ?>

<html>
<head>
<title>Foliage Design Backdoor</title>

<style type="text/css" media="all">
		@import url("<?=$path?>/css/style.css");
		@import url("<?=$path?>/css/jquery.wysiwyg.css");
		@import url("<?php echo $path ?>/css/facebox.css");
		@import url("<?php echo $path ?>/css/visualize.css");
		@import url("<?php echo $path ?>/css/date_input.css");
		@import url("<?php echo $path ?>/css/fullcalendar.css");
		@import url("<?php echo $path ?>/css/jquery.tagit.css");
		@import url("<?php echo $path ?>/css/ui-lightness/jquery-ui-1.8.16.custom.css");
</style>

<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
<!--[if lt IE 8]><style type="text/css" media="all">@import url("<?php echo $path ?>/css/ie.css");</style><![endif]-->

<!--[if IE]><script type="text/javascript" src="<?php echo $path ?>/js/excanvas.js"></script><![endif]-->

	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.img.preload.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.filestyle.mini.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.date_input.pack.js"></script>

	<script type="text/javascript" src="<?php echo $path ?>/js/facebox.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.visualize.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.visualize.tooltip.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.select_skin.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/ajaxupload.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/fullcalendar.min.js"></script>
	
	<!--<script type="text/javascript" src="<?php echo $path ?>/js/jquery.ui.position.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.ui.autocomplete.js"></script>-->
	
	<script type="text/javascript" src="<?php echo $path ?>/js/jquery-ui-1.8.16.all.min.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/tag-it.js"></script>

	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.pngfix.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/custom.js"></script>
</head>

<body>
	<div id="hld">
		<div class="wrapper">		<!-- wrapper begins -->
			<div id="header">
				<div class="hdrl"></div>
				<div class="hdrr"></div>
				<h1><a href="<?=base_url()?>index.php/backdoor/">Foliage Design</a></h1>
				
				<?php $this->load->view('backdoor/navigation', $nav); ?>

				<p class="user">Hello, <a href="<?=base_url()?>index.php/backdoor/editUser/<?=$logged_user['id']?>"><?=$logged_user['fname']?></a> | <a href="<?=base_url()?>index.php/backdoor/logout">Logout</a></p>
			</div>		<!-- #header ends -->
