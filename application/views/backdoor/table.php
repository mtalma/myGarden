<?php

/* 
table view 
========== 
 
$class 			- the class for the div block (located at the start of the block table) 
$block_id 		- the id of the block 
$content_class - classes for the block content tag
$content_id		- ids for the block content tag
$heading 	 	- the heading for the block
$list 		 	- the navigation list location on the top right of the block
$form_name		- The name of the form
$form_id		- id of the form
$form_action	 - the url the form will submit to - if specified we wrap the block content in a form tag 
$form_target 	- set equal to _blank if you want a new window to open
$submit_text 	- the text that will be shown on the submit button 
$submit_name 	- the name of the submit button
$extra		 	- any extras such as warnings, errors or messages 
$main			- main block data
$tableactions_top 	 - places form control at the top left of the table
$tableactions 	 - places form control at the bottom left of the table
$pagination		 - places form control at the bottom right of the table
*/

	$block_class = (empty($class)) ? "block" : "block " . $class;
	$block_id = (empty($block_id)) ? "" : $block_id;
	$content_class = (empty($content_class)) ? "" : $content_class;
	$content_id = (empty($content_id)) ? "" : $content_id;
?>

<div class="<?=$block_class?>" id="<?=$block_id?>">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2><?php echo empty($heading) ? "None" : $heading; ?></h2>

		<?php 
			if( !empty( $list ) )
			{
				echo "<ul>";
				if( is_array($list) )
				{
					foreach( $list as $item ) { 
						echo "<li>";
						echo $item;
						echo "</li>";
					}
				}
				else
					echo "<li>" . $list . "</li>";
				echo "</ul>";
			}
		?>
	</div>		<!-- .block_head ends -->

	<div class="block_content <?=$content_class?>" id="<?=$content_id?>">

	<?php
		if( !empty($form_action) )
		{
			$attributes = array();
			$attributes["method"] = "post";
			if( !empty($form_id) )	$attributes["id"] = $form_id;
			if( !empty($form_name) )	$attributes["name"] = $form_name;
			if( !empty($form_target) )	$attributes["target"] = $form_target;
			
			echo form_open( $form_action, $attributes );
		}
	?>

	<?php
		if( !empty($extra) )
			echo $extra;
			
		if( !empty($tableactions_top) )
		 {
			 echo "<div class='tableactions'>";
			 echo $tableactions_top;
			 echo "</div><div class='clear'></div>";
		 }

		if( !empty($main) )
			echo $main;
	?>

	

	<?php
		 if( !empty($tableactions) )
		 {
			 echo "<div class='tableactions'>";
			 echo $tableactions;
			 echo "</div>";
		 }

		 if( !empty($pagination) )
		 {
			 echo "<div class='pagination right'>";
			 echo $pagination;
			 echo "</div>";
		 }

		

		if( !empty($form_action) )
		{
			if( !empty($submit_name) )
			{
				echo "<hr>";
				$text = empty($submit_text) ? "Submit" : $submit_text;
				$class = strlen($text) > 7 ? "submit long" : "submit mid";
				echo "<p>".form_submit( $submit_name, $text, "class='".$class."'")."</p>";

			}

			echo form_close();
		}

	?>

	</div>		<!-- .block_content ends -->

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>

