<?php

class Maintenance_Plan extends ActiveRecord\Model {

	static $table = 'maintenance_plans';

	static $has_many = array(
		array('Works'),
		array('Services', 'through' => 'Works'),
		array('Maintenance_Records', 'class' => 'Maintenance_Record')
	);
	
	static $belongs_to = array(
		array('user'),
		array('region'),
		array('service_cycle', 'class' => 'Service_Cycle'),
		array('status')
	);
	
	static public function get( $id = null )
	{
		//user id is NULL on maintenance plans that are 'quotes'
		$options = array(	'conditions' => 'user_id IS NOT NULL', 
							'include' => array('user', 'region', 'status', 'service_cycle', 'services') );
		$result = null;
		
		if( $id )
			$result = self::find_by_id($id,$options);
		else
			$result = self::find('all',$options);
			
		return $result;
	}
}
    