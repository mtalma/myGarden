<code>
<?php
function display( $info )
{
	foreach( $info as $item => $desc )
		echo is_array($desc) ? display($desc) : $item . ": <span>" . $desc . "</span><br />";
}

display($quote_info);
?>
</code>