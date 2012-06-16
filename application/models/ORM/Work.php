<?php

class Work extends ActiveRecord\Model {

	static $table = 'work';
	
	static $belongs_to = array(
		array('Maintenance_Plan', 'class' => 'Maintenance_Plan'),
		array('Service')
	);
}

?>