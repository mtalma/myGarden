<?php

class Maintenance_Record extends ActiveRecord\Model {	
	static $table_name = 'maintenance_record';
	
	static $belongs_to = array(
     	array('Maintenance_Plan', 'class' => 'Maintenance_Plan')
    );
    
    static public function get( $id = null )
	{
		$options = array('include' => array('maintenance_plan' => array('user') ) );
		$result = null;
		
		if( $id )
			$result = self::find_by_id($id,$options);
		else
			$result = self::find('all',$options);
			
		return $result;
	}
}

?>