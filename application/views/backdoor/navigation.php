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
				$class = stristr( $link, $page ) ? "class='active' " : "";
				echo "</li><li " . $class . "><a href=$link>$desc</a>";
			}
		}
	}
	
	echo "<ul id='nav'>";
	$current_page = $this->uri->segment(2);
	place_nav( $nav, $current_page );
	echo "</ul>";
?>