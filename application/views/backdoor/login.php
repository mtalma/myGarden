<?=doctype()?>

<html>
<head>
<title>Login to Backdoor</title>

<style type="text/css" media="all">
		@import url("<?php echo $path ?>/css/style.css");
		@import url("<?php echo $path ?>/css/jquery.wysiwyg.css");
		@import url("<?php echo $path ?>/css/facebox.css");
		@import url("<?php echo $path ?>/css/visualize.css");
		@import url("<?php echo $path ?>/css/date_input.css");
</style>

<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
<!--[if lt IE 8]><style type="text/css" media="all">@import url("css/ie.css");</style><![endif]-->

<!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->

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

	<script type="text/javascript" src="<?php echo $path ?>/js/jquery.pngfix.js"></script>
	<script type="text/javascript" src="<?php echo $path ?>/js/custom.js"></script>

</head>

<body class="login">
<div id="hld">
		<div class="wrapper">		<!-- wrapper begins -->
			<div class="block small center login">
				<div class="block_head">
					<div class="bheadl"></div>
					<div class="bheadr"></div>
					<h2>Foliage Design</h2>
					<ul>
						<li><a href="<?php echo base_url(); ?>">back to the site</a></li>
					</ul>
				</div>		<!-- .block_head ends -->
				<div class="block_content">
					<?php if($message != '' ) { ?>
						<div class="message errormsg"><p><?=$message?></p></div>
					<?php } ?>
					<?=form_open('/backdoor/login/')?>
						<p>
							<label>Email:</label> <br />
							<?=form_input('email','', 'class="text"')?>
						</p>
						<p>
							<label>Password:</label> <br />
							<?=form_password('pass','', 'class="text"')?>
						</p>
						<p>
							<?=form_submit("submitLogin", "Login", 'class="submit"')?> &nbsp; 
							<input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme">Remember me</label>
						</p>
					<?=form_close()?>
				</div>		<!-- .block_content ends -->
				<div class="bendl"></div>
				<div class="bendr"></div>		
			</div>		<!-- .login ends -->
		</div>						<!-- wrapper ends -->
	</div>		<!-- #hld ends -->
</body>
</html>
