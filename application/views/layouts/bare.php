<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>myGarden Maintenance - <?= $template['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Matthew Talma">

    <!-- styles -->
    <link href="<?= base_url() ?>/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?= base_url() ?>/assets/css/global.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- touch icons -->
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/ico/favicon.ico">
  </head>

  <body>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">
 	       <?= $template['body'] ?>
 	    </div>
 	   </div>
      <hr>
      <footer>
        <p>&copy; Foliage Design 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/bootstrap.min.js"></script>

  </body>
</html>
