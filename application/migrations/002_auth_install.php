<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Auth_Install extends CI_Migration {

	public function up()
	{
		$this->db->trans_start();

		//first create table meta
		if( ! $this->db->table_exists('meta') )
		{
			$this->dbforge->add_field('id');
			$this->dbforge->add_field("user_id int(11) NOT NULL");
			$this->dbforge->add_field("sal varchar(3) NOT NULL DEFAULT ''");
			$this->dbforge->add_field("address varchar(32) NOT NULL");
			$this->dbforge->add_field("address2 varchar(32) DEFAULT NULL");
			$this->dbforge->add_field("region int(11) NOT NULL");
			$this->dbforge->add_field("status int(4) NOT NULL");
			$this->dbforge->add_field("directions text");
			$this->dbforge->create_table('meta');
		}
		
		//get these values and add them to meta
		$query = $this->db->get('users');
		foreach ($query->result() as $user)
		{
			$meta = array();
		    $meta['user_id'] = $user->id;
		    $meta['sal'] = $user->sal;
		    $meta['address'] = $user->address;
		    $meta['address2'] = $user->address2;
		    $meta['region'] = $user->region;
		    $meta['status'] = $user->status;
		    $meta['directions'] = $user->directions;
		    
		    $this->db->insert('meta', $meta);
		}
		
		//drop columns from users
		$this->dbforge->drop_column('users', 'sal');
		$this->dbforge->drop_column('users', 'admin');
		$this->dbforge->drop_column('users', 'address');
		$this->dbforge->drop_column('users', 'address2');
		$this->dbforge->drop_column('users', 'region');
		$this->dbforge->drop_column('users', 'status');
		$this->dbforge->drop_column('users', 'directions');
		
		//change fields
		$fields = array(
				'hphone' => array('name' => 'phone', 'type' => 'VARCHAR', 'constraint' => '32'),
				'cphone' => array('name' => 'cphone', 'type' => 'VARCHAR', 'constraint' => '32', 'null' => TRUE ),
				'fname' => array('name' => 'first_name', 'type' => 'VARCHAR', 'constraint' => '32'),
				'lname' => array('name' => 'last_name', 'type' => 'VARCHAR', 'constraint' => '32'),
				'password' => array('name' => 'password', 'type' => 'VARCHAR', 'constraint' => '40'),
		);
		
		$this->dbforge->modify_column('users', $fields);
		
		//add columns to users
		$fields = array(
			'group_id' => array('type' => 'mediumint', 'constraint' => '8', 'default' => '2'),
			'ip_address' => array('type' => 'char', 'constraint' => '16'),
			'username' => array('type' => 'VARCHAR', 'constraint' => '15'),
			'salt' => array('type' => 'VARCHAR', 'constraint' => '40'),
			'activation_code' => array('type' => 'VARCHAR', 'constraint' => '40'),
			'forgotten_password_code' => array('type' => 'VARCHAR', 'constraint' => '40', 'null' => TRUE),
			'remember_code' => array('type' => 'VARCHAR', 'constraint' => '40'),
			'created_on' => array('type' => 'INT', 'constraint' => '11'),
			'last_login' => array('type' => 'INT', 'constraint' => '11'),
			'active' => array('type' => 'tinyint', 'constraint' => '1'),
			'forgotten_password_time' => array('type' => 'INT', 'constraint' => '11'),
			'company' => array('type' => 'VARCHAR', 'constraint' => '100')
		);
		
		$this->dbforge->add_column('users', $fields);
		
		//create and insert a default user
		$default_user = array(	'first_name'=>'Matthew',
								'last_name'=>'Talma',
								'phone'=>'868-353-8401',
								'cphone'=>null,
								'email'=>'matthew@foliagedesign.tt',
								'password'=>'09ec6202a1ccf84a0b2785ff70b8c20c0cbd4968',
								'group_id'=>2,
								'ip_address'=>0,
								'username'=>'matthew',
								'salt'=>'',
								'activation_code'=>'',
								'forgotten_password_code'=>null,
								'remember_code'=>'',
								'created_on'=>1338534160,
								'last_login'=>1338534160,
								'active'=>1,
								'forgotten_password_time'=>0,
								'company'=>'Foliage Design');
								
		$this->db->insert('users', $default_user);
		$user_id = $this->db->insert_id();
		
		//add groups table
  		if( ! $this->db->table_exists('groups') )
		{
			$this->dbforge->add_field('id');
			$this->dbforge->add_field("name varchar(20) NOT NULL");
			$this->dbforge->add_field("description varchar(100) NOT NULL");
			$this->dbforge->create_table('groups');
			
			//insert default groups
			$this->db->query("INSERT INTO `groups` VALUES(1, 'admin', 'Administrator')");
			$this->db->query("INSERT INTO `groups` VALUES(2, 'members', 'General User')");
		}
	
		//add users_groups table
  		if( ! $this->db->table_exists('users_groups') )
		{
			$this->dbforge->add_field('id');
			$this->dbforge->add_field("user_id mediumint(8) NOT NULL");
			$this->dbforge->add_field("group_id mediumint(8) NOT NULL");
			$this->dbforge->create_table('users_groups');
			
			//insert default groups
			$this->db->query("INSERT INTO `users_groups` VALUES(1, ".$user_id.", 1)");
			$this->db->query("INSERT INTO `users_groups` VALUES(2, ".$user_id.", 2)");
		}
		
		//change some fields in maintenance record to work with ORM
		$fields = array(
				'region' => array('name' => 'region_id', 'type' => 'tinyint', 'constraint' => '3'),
				'service_day' => array('name' => 'status_id', 'type' => 'tinyint', 'constraint' => '3'),
				'status' => array('name' => 'service_day_id', 'type' => 'tinyint', 'constraint' => '11'),
				'service_cycle' => array('name' => 'service_cycle_id', 'type' => 'tinyint', 'constraint' => '4'),
				'service_time' => array('name' => 'service_time_id', 'type' => 'tinyint', 'constraint' => '4'),
				'garden_size' => array('name' => 'garden_size_id', 'type' => 'tinyint', 'constraint' => '4')
		);
		
		$this->dbforge->modify_column('maintenance_plans', $fields);
		
		$fields = array(
				'maintenance_id' => array('name' => 'maintenance_plan_id', 'type' => 'int', 'constraint' => '11')
		);
		
		$this->dbforge->modify_column('maintenance_records', $fields);
	
		$this->db->trans_complete();	

	}
	
	public function down()
	{
	
		$this->db->trans_start();
		
		$fields = array(
				'maintenance_plan_id' => array('name' => 'maintenance_id', 'type' => 'int', 'constraint' => '11')
		);
		
		$this->dbforge->modify_column('maintenance_records', $fields);
		
		//change back the fields in maintenance_plans
		$fields = array(
				'region_id' => array('name' => 'region', 'type' => 'tinyint', 'constraint' => '3'),
				'service_day_id' => array('name' => 'status', 'type' => 'tinyint', 'constraint' => '3'),
				'status_id' => array('name' => 'service_day', 'type' => 'tinyint', 'constraint' => '11'),
				'service_cycle_id' => array('name' => 'service_cycle', 'type' => 'tinyint', 'constraint' => '4'),
				'service_time_id' => array('name' => 'service_time', 'type' => 'tinyint', 'constraint' => '4'),
				'garden_size_id' => array('name' => 'garden_size', 'type' => 'tinyint', 'constraint' => '4')
		);
		
		$this->dbforge->modify_column('maintenance_plans', $fields);

		$this->dbforge->drop_table('users_groups');
		$this->dbforge->drop_table('groups');
		
		//drop columns from users
		$this->dbforge->drop_column('users', 'group_id');
		$this->dbforge->drop_column('users', 'ip_address');
		$this->dbforge->drop_column('users', 'username');
		$this->dbforge->drop_column('users', 'salt');
		$this->dbforge->drop_column('users', 'activation_code');
		$this->dbforge->drop_column('users', 'forgotten_password_code');
		$this->dbforge->drop_column('users', 'forgotten_password_time');
		$this->dbforge->drop_column('users', 'remember_code');
		$this->dbforge->drop_column('users', 'created_on');
		$this->dbforge->drop_column('users', 'last_login');
		$this->dbforge->drop_column('users', 'company');
		$this->dbforge->drop_column('users', 'active');
		
		//rename fields
		$fields = array(
				'phone' => array('name' => 'hphone', 'type' => 'VARCHAR', 'constraint' => '32'),
				'first_name' => array('name' => 'fname', 'type' => 'VARCHAR', 'constraint' => '32'),
				'last_name' => array('name' => 'lname', 'type' => 'VARCHAR', 'constraint' => '32'),
		);
		
		$this->dbforge->modify_column('users', $fields);
		
		//add columns
		$fields = array(
			'sal' => array('type' => 'VARCHAR', 'constraint' => '4'),
			'admin' => array('type' => 'tinyint', 'constraint' => '4', 'default' => '0'),
			'address' => array('type' => 'VARCHAR', 'constraint' => '32'),
			'address2' => array('type' => 'VARCHAR', 'constraint' => '32'),
			'region' => array('type' => 'INT', 'constraint' => '11'),
			'status' => array('type' => 'INT', 'constraint' => '4'),
			'directions' => array('type' => 'TEXT'),
		);
		
		$this->dbforge->add_column('users', $fields);

		$query = $this->db->get('meta');
		foreach ($query->result() as $meta)
		{
			$user = array();
			$user['sal'] = $meta->sal;
		    $user['address'] = $meta->address;
		    $user['address2'] = $meta->address2;
		    $user['region'] = $meta->region;
		    $user['status'] = $meta->status;
		    $user['directions'] = $meta->directions;

		    $this->db->where('id', $meta->user_id);
			$this->db->update('users', $user);
		}
		
		$this->dbforge->drop_table('meta');
		
		$this->db->trans_complete();
	}
	
}