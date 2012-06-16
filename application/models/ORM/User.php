<?php

class User extends ActiveRecord\Model {
	static $has_one = array (
		array( 'meta', 'class' => 'meta' )
	);
}

?>