<?php
//call on navigation array
function place_nav( $nav, $page )
{
?>
	<? foreach( $nav as $desc => $link): ?>
		<? if( is_array( $link ) ): ?>
			<li class='dropdown'>
			<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
				<?= $desc ?>
				<b class='caret'></b>
			</a>
			<ul class='dropdown-menu'>
				<? place_nav($link, $page) ?>
			</ul>
			</li>				
		<? else: ?>
			<? $class = stristr( $link, $page ) ? " class='active'" : ""; ?>
			<li<?=$class?>><a href=<?=$link?>><?=$desc?></a>
		<? endif ?>
	<? endforeach ?>
<?php } ?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="#">myGarden</a>
      <div class="btn-group pull-right">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="icon-user"></i> <?= $this->session->userdata('username') ?>
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Profile</a></li>
          <li class="divider"></li>
          <li><a href="<?= base_url() . "auth/logout" ?>">Sign Out</a></li>
        </ul>
      </div>
      <div class="nav-collapse">

		<ul class='nav'>
		<? place_nav( $nav, current_url() ) ?>
		</ul>
		
	</div><!--/.nav-collapse -->
    </div>
  </div>
</div>