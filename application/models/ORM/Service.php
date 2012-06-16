<?php

class Service extends ActiveRecord\Model {
	static $table_name = 'services';
	
	static $has_many = array(
		array('works')
	);
}

?>