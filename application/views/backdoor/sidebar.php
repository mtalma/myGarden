</div>

<div id="sidebar">
<h3>Welcome <?=$user?></h3>
<?php
	
	//call on navigation array
	function place_nav( $nav, $page )
	{
		foreach( $nav as $desc => $link)
		{
			if( is_array( $link ) )
			{
				echo "<ul>";
				place_nav($link, $page);
				echo "</ul>";
				echo "</li>";
			}
			else
			{
				$class = stristr( $link, $page ) ? "class='current' " : "";
				echo "</li><li " . $class . "><a href=$link>$desc</a>";
			}
		}
	}
	
	echo "<ul id='sec-nav'>";
	place_nav( $nav, $this->uri->segment(2) );
	echo "</ul>";
?>

</div>

<div class="clearfooter"></div>
</div><!--End Container-->