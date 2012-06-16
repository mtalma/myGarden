<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Initial extends CI_Migration {

	public function up()
	{	
		$this->db->trans_start();
		
		if ( ! $this->db->table_exists('email_content'))
		{	
			$this->dbforge->add_field('id', TRUE);
			$this->dbforge->add_field("title VARCHAR(64) NOT NULL");
			$this->dbforge->add_field("subject VARCHAR(64) NOT NULL");
			$this->dbforge->add_field("body TEXT NOT NULL");
			$this->dbforge->create_table('email_content');
		}
		
		if ( ! $this->db->table_exists('crews'))
		{	
			$this->dbforge->add_field('id');
			$this->dbforge->add_field("manager VARCHAR(64) NOT NULL");
			$this->dbforge->add_field("license VARCHAR(64) NOT NULL");
			$this->dbforge->create_table('crews');
		}
  
  		if ( ! $this->db->table_exists('email_log'))
		{
			$this->dbforge->add_field('id');
			$this->dbforge->add_field("email VARCHAR(64) NOT NULL");
			$this->dbforge->add_field("msg_id INT(11) NOT NULL");
			$this->dbforge->add_field("postmark_id VARCHAR(128) NOT NULL");
			$this->dbforge->add_field("type VARCHAR(128) NOT NULL");
			$this->dbforge->add_field("error INT(4) default 0");
			$this->dbforge->add_field("datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->create_table('email_log');
		}

		if ( ! $this->db->table_exists('garden_size'))
		{
			$this->dbforge->add_field("id");
			$this->dbforge->add_field("size VARCHAR(32) NOT NULL");
			$this->dbforge->add_field("description VARCHAR(32)");	//changed to description because DESC is a reserved keyword
			$this->dbforge->add_field("timeHours float NOT NULL");
			$this->dbforge->add_field("enabled tinyint(1)");
			$this->dbforge->create_table('garden_size');
		}
		
		if ( ! $this->db->table_exists('maintenance_plans'))
		{
			$this->dbforge->add_field("id");
			$this->dbforge->add_field("user_id tinyint(4) DEFAULT NULL");
			$this->dbforge->add_field("region tinyint(3) unsigned NOT NULL");
			$this->dbforge->add_field("transport_fee float unsigned NOT NULL");
			$this->dbforge->add_field("signup_date date NOT NULL");
			$this->dbforge->add_field("service_day tinyint(3) unsigned NOT NULL");
			$this->dbforge->add_field("status int(11) NOT NULL");
			$this->dbforge->add_field("service_cycle tinyint(4) NOT NULL");
			$this->dbforge->add_field("service_time tinyint(4) NOT NULL");
			$this->dbforge->add_field("garden_size tinyint(4) NOT NULL");
			$this->dbforge->add_field("start_date date DEFAULT NULL");
			$this->dbforge->add_field("hashed_id varchar(32) NOT NULL");
			$this->dbforge->create_table('maintenance_plans');
		}
		
		if( ! $this->db->table_exists('maintenance_record') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("maintenance_id int(11) NOT NULL");
			$this->dbforge->add_field("time_in time NOT NULL");
			$this->dbforge->add_field("time_out time NOT NULL");
			$this->dbforge->add_field("client_notes text");
			$this->dbforge->add_field("manager_notes text");
			$this->dbforge->add_field("date date NOT NULL");
			$this->dbforge->add_field("service_cost float unsigned NOT NULL");
			$this->dbforge->add_field("transport_cost float unsigned NOT NULL");
			$this->dbforge->add_field("discount_percentage float unsigned NOT NULL");
			$this->dbforge->add_field("rating tinyint(4) DEFAULT NULL");
			$this->dbforge->add_field("crew_id tinyint(3) unsigned DEFAULT NULL");
			$this->dbforge->create_table('maintenance_record');
		}
		
  		if( ! $this->db->table_exists('maintenance_to_services') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("maintenance_id int(11) NOT NULL");
			$this->dbforge->add_field("service_id int(11) NOT NULL");
			$this->dbforge->add_field("quoted_price int(11) NOT NULL");
			$this->dbforge->add_field("datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->create_table('maintenance_to_services');
		}
		
		if( ! $this->db->table_exists('referrals') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("referrer_user_id int(10) unsigned NOT NULL");
			$this->dbforge->add_field("referree_user_id int(10) unsigned NOT NULL");
			$this->dbforge->add_field("record_id tinyint(3) unsigned DEFAULT NULL");
			$this->dbforge->add_field("status tinyint(3) unsigned NOT NULL");
			$this->dbforge->add_field("updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->create_table('referrals');
		}
		
		if( ! $this->db->table_exists('regions') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("name varchar(32) NOT NULL");
			$this->dbforge->add_field("users int(10) unsigned NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("price float NOT NULL");
			$this->dbforge->add_field("enabled tinyint(1) NOT NULL DEFAULT '1'");
			$this->dbforge->create_table('regions');
		}
		
		if( ! $this->db->table_exists('services') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("name varchar(32) NOT NULL");
			$this->dbforge->add_field("short_name varchar(16) NOT NULL");
			$this->dbforge->add_field("description text NOT NULL");
			$this->dbforge->add_field("price int(11) NOT NULL");
			$this->dbforge->add_field("img_src text NOT NULL");
			$this->dbforge->add_field("img_src_large varchar(64) NOT NULL");
			$this->dbforge->add_field("recommended tinyint(1) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("list_order tinyint(4) NOT NULL DEFAULT '0'");		//changed because ORDER is reserved
			$this->dbforge->add_field("enabled tinyint(1) NOT NULL DEFAULT '1'");
			$this->dbforge->create_table('services');
		}
		
		if( ! $this->db->table_exists('service_cycle') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("cycle varchar(32) NOT NULL");
			$this->dbforge->add_field("enabled tinyint(1) NOT NULL");
			$this->dbforge->create_table('service_cycle');
		}
		
		if( ! $this->db->table_exists('service_days') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("day varchar(32) NOT NULL");
			$this->dbforge->add_field("enabled tinyint(1) NOT NULL");
			$this->dbforge->create_table('service_days');
		}

  		if( ! $this->db->table_exists('service_include_list') )
		{
			$this->dbforge->add_field("service_id tinyint(4) NOT NULL");
			$this->dbforge->add_field("list_item text NOT NULL");
			$this->dbforge->create_table('service_include_list');
		}
  
  		if( ! $this->db->table_exists('status') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("text varchar(32) NOT NULL");
			$this->dbforge->add_field("description text NOT NULL");
			$this->dbforge->create_table('status');
		}
  
  		if( ! $this->db->table_exists('time_slots') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("name varchar(32) NOT NULL");
			$this->dbforge->add_field("avg_start time NOT NULL");
			$this->dbforge->add_field("avg_end time NOT NULL");
			$this->dbforge->add_field("enabled tinyint(1) NOT NULL");
			$this->dbforge->create_table('time_slots');
		}
  
  		if( ! $this->db->table_exists('users') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("admin tinyint(4) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("sal varchar(3) NOT NULL DEFAULT ''");
			$this->dbforge->add_field("fname varchar(32) NOT NULL");
			$this->dbforge->add_field("lname varchar(32) NOT NULL");
			$this->dbforge->add_field("hphone varchar(10) NOT NULL DEFAULT ''");
			$this->dbforge->add_field("cphone varchar(10) NOT NULL");
			$this->dbforge->add_field("email varchar(40) NOT NULL");
			$this->dbforge->add_field("address varchar(32) NOT NULL");
			$this->dbforge->add_field("address2 varchar(32) DEFAULT NULL");
			$this->dbforge->add_field("region int(11) NOT NULL");
			$this->dbforge->add_field("directions text");
			$this->dbforge->add_field("password varchar(32) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("status tinyint(3) unsigned NOT NULL");
			$this->dbforge->create_table('users');
		}
  
  		if( ! $this->db->table_exists('user_tags') )
		{
  			$this->dbforge->add_field("id");
			$this->dbforge->add_field("name varchar(45) NOT NULL");
			$this->dbforge->add_field("description TEXT");
			$this->dbforge->add_field("type int(1) NOT NULL DEFAULT '0'");
			$this->dbforge->create_table('user_tags');
		}

		$this->_add_data();
		
		$this->db->trans_complete();
	}

	public function down()
	{		
		$this->db->trans_start();
		
		$this->dbforge->drop_table('email_content');
		$this->dbforge->drop_table('crews');
		$this->dbforge->drop_table('email_log');
		$this->dbforge->drop_table('garden_size');
		$this->dbforge->drop_table('maintenance_plans');
		$this->dbforge->drop_table('maintenance_record');
		$this->dbforge->drop_table('maintenance_to_services');
		$this->dbforge->drop_table('referrals');
		$this->dbforge->drop_table('regions');
		$this->dbforge->drop_table('services');
		$this->dbforge->drop_table('service_cycle');
		$this->dbforge->drop_table('service_days');
		$this->dbforge->drop_table('service_include_list');
		$this->dbforge->drop_table('status');
		$this->dbforge->drop_table('time_slots');
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('user_tags');
		
		$this->db->trans_complete();
	}
	
	private function _add_data()
	{
		//email content data
		$this->db->query("INSERT INTO `email_content` VALUES(1, 'Intro Email', 'Your New Garden Maintenance Plan', 'Hi {fname},<br><br>We''ve received your request for garden maintenance. Below is a summary of your package:<br>\r\n{service_list}<br>\r\nWe look forward to contacting you soon to choose a start date. If you''ve got any questions about this, please email us at <a href=''mailto:hello@foliagedesign.tt'' target=''_blank'' rel=''nofollow''>hello@foliagedesign.tt</a> or call us at 676-8752.<br><br>\r\nThanks for using myGarden!\r\n<br><br>\r\n-myGarden Team')");
		
		$this->db->query("INSERT INTO `email_content` VALUES(2, 'Plan Activation', 'Your Maintenance Plan is Now Active', 'Hi {fname},<br><br>\r\nWe''ve activated your custom garden maintenance plan. Our first maintenance is scheduled for {start_date}. We look forward to meeting you then.\r\n<br>\r\n<br>\r\nSome helpful tips for the first visit:\r\n<ul>\r\n<li>For the first visit, it would be great for us to have a chat before we start maintenance. If possible, try to be at home when the crew arrives.</li>\r\n<li>We log our arrival and departure times from your garden. We recommend having someone trustworthy sign off on our times. However, this is not a requirement.</li>\r\n<li>We always look for ways to improve our service, so we ask after each visit you rate our performance on a scale of 1-5; 1 being the lowest and 5, the highest.</li>\r\n</ul>\r\nHave more questions? Visit our frequently asked questions page <a href=''{url}/quote/index.php/quote/faq''>here</a> or give us a call at 676.8752.\r\n<br><br>\r\n-myGarden Team')");
		
		$this->db->query("INSERT INTO `email_content` VALUES(3, 'Plan On Hold', 'Your Plan is On Hold', 'Hi {fname},<br>\r\nWe''ve placed your maintenance plan on a temporary hold. No need to worry, this usually due to an unresolved issue that we are working on. If we haven''t already, we will contact you with more details as soon as possible.\r\n<br><br>\r\n-myGarden Team')");
		
		$this->db->query("INSERT INTO `email_content` VALUES(5, 'Neighbour Signed Up Notification', 'Your Neighbour Has Signed Up', 'Hi {fname},<br>\r\nWe thought you might like to know that one of your neighbours, referred to us by you, has signed up for a myGarden maintenance plan. Thank you so much for helping us grow!\r\n<br>\r\n<br>\r\nWe''ve credited your account one free maintenance day. We''ll apply this discount on our next visit - see you soon!\r\n<br>\r\n<br>\r\n- myGarden Team')");
		
		$this->db->query("INSERT INTO `email_content` VALUES(4, 'myGarden Invitation', 'myGarden Maintenance in {region}', 'Hi,<br>\r\nYour neighbour in {region} recently signed up for Foliage Design''s myGarden Maintenance and thought you might be interested. myGarden is a custom maintenance package you build online to suit your garden and budget. Build your package, sign up and start seeing results in as little as one week!\r\n<br>\r\n<br>\r\n<a href=''{url}/myGarden''>Get started here.</a>\r\n<br>\r\n<br>\r\n- myGarden Team')");
		
		$this->db->query("INSERT INTO `email_content` VALUES(6, 'Rate Record Notification', 'Completed Garden Maintenance', 'Hi {fname},<br><br>\r\nOur myGarden team has completed your {cycle} maintenance on your garden on {date}. Please take some to review the details of our visit and rate our performance.\r\n<br><br>\r\nArrival: {start_time}<br>\r\nDeparture: {end_time}\r\n<br><br>\r\n<a href=''{link}''>View details and rate performance</a>\r\n<br><br>\r\n-myGarden Team')");
		
		//garden size data
		$this->db->query("INSERT INTO `garden_size` VALUES(1, 'Small', '~1500 square feet', 0.8, 1)");
		$this->db->query("INSERT INTO `garden_size` VALUES(2, 'Medium', '~2000 square feet', 1, 1)");
		$this->db->query("INSERT INTO `garden_size` VALUES(3, 'Large', '~3000 square feet', 1.3, 1)");
		$this->db->query("INSERT INTO `garden_size` VALUES(4, 'Huge', '~4000 square feet', 2, 1)");
		
		//regions data
		$regions = array(
			  array('id'=>1,'name'=>'Alyce Glen','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>2,'name'=>'Aranguez','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>3,'name'=>'Arima','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>4,'name'=>'Arouca','users'=>0,'price'=>60,'enabled'=>0),
			  array('id'=>5,'name'=>'Bayshore','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>6,'name'=>'Bayside','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>7,'name'=>'Bel Air','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>8,'name'=>'Blue Range','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>9,'name'=>'Cascade','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>10,'name'=>'Chaguanas','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>11,'name'=>'Couva','users'=>0,'price'=>65,'enabled'=>0),
			  array('id'=>12,'name'=>'D&#039;Abadie','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>13,'name'=>'Diego Martin','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>14,'name'=>'El Socorro','users'=>0,'price'=>65,'enabled'=>0),
			  array('id'=>15,'name'=>'Ellerslie Park','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>16,'name'=>'Federation Park','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>17,'name'=>'Flagstaff','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>18,'name'=>'Freeport','users'=>0,'price'=>75,'enabled'=>0),
			  array('id'=>19,'name'=>'Fyzabad','users'=>0,'price'=>80,'enabled'=>0),
			  array('id'=>20,'name'=>'Gasparillo','users'=>0,'price'=>85,'enabled'=>0),
			  array('id'=>21,'name'=>'Glencoe','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>22,'name'=>'Goodwood Gardens','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>23,'name'=>'Goodwood Park','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>24,'name'=>'Gulf View','users'=>0,'price'=>75,'enabled'=>0),
			  array('id'=>25,'name'=>'Haleland Park','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>26,'name'=>'Hillsbore','users'=>0,'price'=>75,'enabled'=>0),
			  array('id'=>27,'name'=>'La Brea','users'=>0,'price'=>80,'enabled'=>0),
			  array('id'=>28,'name'=>'La Fontaine','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>29,'name'=>'La Riviera','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>30,'name'=>'Lange Park','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>31,'name'=>'Maraval','users'=>0,'price'=>50,'enabled'=>1),
			  array('id'=>32,'name'=>'Marine Villas','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>33,'name'=>'Moka','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>34,'name'=>'Palmiste','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>35,'name'=>'Pearl Gardens','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>36,'name'=>'Petit Valley','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>37,'name'=>'Pointe-a-Pierre','users'=>0,'price'=>75,'enabled'=>0),
			  array('id'=>38,'name'=>'Port of Spain','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>39,'name'=>'Princess Town','users'=>0,'price'=>70,'enabled'=>0),
			  array('id'=>40,'name'=>'Rio Claro','users'=>0,'price'=>75,'enabled'=>0),
			  array('id'=>41,'name'=>'San Fernando','users'=>0,'price'=>80,'enabled'=>0),
			  array('id'=>42,'name'=>'Santa Cruz','users'=>0,'price'=>50,'enabled'=>1),
			  array('id'=>43,'name'=>'Savannah Villas','users'=>0,'price'=>40,'enabled'=>0),
			  array('id'=>44,'name'=>'Spanish Court','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>45,'name'=>'St. Ann&#039;s','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>46,'name'=>'St. Augustine','users'=>0,'price'=>65,'enabled'=>0),
			  array('id'=>47,'name'=>'St. Clair','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>48,'name'=>'St. James','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>49,'name'=>'St. Joseph','users'=>0,'price'=>65,'enabled'=>0),
			  array('id'=>50,'name'=>'St. Joseph Village','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>51,'name'=>'Sumadh Gardens','users'=>0,'price'=>80,'enabled'=>0),
			  array('id'=>52,'name'=>'The Greens','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>53,'name'=>'The Point','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>54,'name'=>'The Towers','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>55,'name'=>'Trincity','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>56,'name'=>'Tunapuna','users'=>0,'price'=>65,'enabled'=>0),
			  array('id'=>57,'name'=>'Valsayn','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>58,'name'=>'Victoria Village','users'=>0,'price'=>55,'enabled'=>0),
			  array('id'=>59,'name'=>'Westmoorings','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>60,'name'=>'Woodbrook','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>61,'name'=>'Fairways','users'=>0,'price'=>55,'enabled'=>1),
			  array('id'=>62,'name'=>'Millennium Lakes','users'=>0,'price'=>55,'enabled'=>1)
		);
		
		foreach( $regions as $region )
			$this->db->insert('regions', $region); 
			
		//data for services
		$services = array(
		  array('id'=>1,'name'=>'Lawn Care','short_name'=>'lawn','description'=>'Lawn care may be the most important part of a garden maintenance program. Not only does it improve the overall look of your garden, but proper lawn care often raises your land value.','price'=>25,'img_src'=>'/images/services/lawn2.jpg','img_src_large'=>'/images/services/lawn2_large.jpg','recommended'=>0,'list_order'=>1,'enabled'=>1),
		  array('id'=>2,'name'=>'Pruning','short_name'=>'pruning','description'=>'The beauty, health and overall wellness of a garden is very dependent on pruning correctly and at the right time.','price'=>20,'img_src'=>'/images/services/pruning.jpg','img_src_large'=>'/images/services/pruning_large.jpg','recommended'=>0,'list_order'=>7,'enabled'=>1),
		  array('id'=>3,'name'=>'Weeding','short_name'=>'wedding','description'=>'Weeds are fast growing and generally unattractive plants. They can grow in any garden and are often difficult to control. We hand weed and when necessary use selective weedkillers to control weed growth and propagation.','price'=>25,'img_src'=>'/images/services/weed.jpg','img_src_large'=>'/images/services/weed_large.jpg','recommended'=>0,'list_order'=>6,'enabled'=>1),
		  array('id'=>16,'name'=>'Plant Disease Control','short_name'=>'chem','description'=>'By spraying plants with a fungicide and insecticide, we ensures that your garden is free of pests and diseases that can damage your plants.','price'=>25,'img_src'=>'/images/services/chem.jpg','img_src_large'=>'/images/services/chem_large.jpg','recommended'=>0,'list_order'=>1,'enabled'=>1),
		  array('id'=>9,'name'=>'Hedge Shaping','short_name'=>'hedge','description'=>'Hedges can be an important part of your landscape. We use special tools to maintain the neat look of hedges around your garden.','price'=>15,'img_src'=>'/images/services/hedge.jpg','img_src_large'=>'/images/services/hedge_large.jpg','recommended'=>0,'list_order'=>11,'enabled'=>1),
		  array('id'=>10,'name'=>'Edging Garden Beds','short_name'=>'edging','description'=>'The purpose of edging is to separate the flower bed, shrub bed or groundcover area from the lawn and keep grass out of the planting bed; garden edging may also be used to outline and define a specific area in the landscape.','price'=>18,'img_src'=>'/images/services/edge.jpg','img_src_large'=>'/images/services/edge_large.jpg','recommended'=>0,'list_order'=>8,'enabled'=>1),
		  array('id'=>11,'name'=>'Topiary Shaping','short_name'=>'topiary','description'=>'Shape your topiaries around your garden','price'=>25,'img_src'=>'/images/irrigation.jpg','img_src_large'=>'http://placehold.it/345x214&text=Foliage','recommended'=>0,'list_order'=>10,'enabled'=>0),
		  array('id'=>12,'name'=>'Lay Manure','short_name'=>'topup','description'=>'A layer of manure not only improves the overall appearance of your garden beds it also provides nutrients essential for healthy plant growth.','price'=>30,'img_src'=>'/images/services/manure.jpg','img_src_large'=>'/images/services/manure_large.jpg','recommended'=>0,'list_order'=>9,'enabled'=>1),
		  array('id'=>13,'name'=>'Raking leaves','short_name'=>'raking','description'=>'Rak leaves from lawn and dispose of them','price'=>25,'img_src'=>'/images/irrigation.jpg','img_src_large'=>'http://placehold.it/345x214&text=Foliage','recommended'=>0,'list_order'=>12,'enabled'=>0),
		  array('id'=>14,'name'=>'Watering','short_name'=>'water','description'=>'Everyone knows that water is essential for any plant, however over watering a garden can also be detrimental. Our staff will ensure that each plant in your garden is given the right amount of water.','price'=>25,'img_src'=>'/images/services/water.jpg','img_src_large'=>'/images/services/water_large.jpg','recommended'=>0,'list_order'=>2,'enabled'=>1)
		);
		
		foreach( $services as $service )
			$this->db->insert('services', $service); 
		
		//data for service cycle
		$service_cycles = array(
		  array('id'=>1,'cycle'=>'Weekly','enabled'=>1),
		  array('id'=>2,'cycle'=>'Bi-Weekly','enabled'=>1),
		  array('id'=>3,'cycle'=>'Monthly','enabled'=>1)
		);
		
		foreach( $service_cycles as $service_cycle )
			$this->db->insert('service_cycle', $service_cycle);
			
		//data for service days
		$service_days = array(
		  array('id'=>1,'day'=>'Monday','enabled'=>1),
		  array('id'=>2,'day'=>'Tuesday','enabled'=>1),
		  array('id'=>3,'day'=>'Wednesday','enabled'=>1),
		  array('id'=>4,'day'=>'Thursday','enabled'=>1),
		  array('id'=>5,'day'=>'Friday','enabled'=>1),
		  array('id'=>6,'day'=>'Saturday','enabled'=>0),
		  array('id'=>7,'day'=>'Sunday','enabled'=>0)
		);
		
		foreach( $service_days as $service_day )
			$this->db->insert('service_days', $service_day);
			
		//data for service include list
		$service_include_list = array(
		  array('service_id'=>1,'list_item'=>'Mowing the lawn with weed wacker or lawn mower depending on grass type'),
		  array('service_id'=>10,'list_item'=>'Prevents lawn from growing in garden beds'),
		  array('service_id'=>1,'list_item'=>'Early diagnosis of common lawn diseases'),
		  array('service_id'=>14,'list_item'=>'watering all areas of the garden on maintenance day'),
		  array('service_id'=>2,'list_item'=>'Essential for healthy growth of plants'),
		  array('service_id'=>14,'list_item'=>'Advice on correct watering methods'),
		  array('service_id'=>16,'list_item'=>'Prevents against pest, disease and weeds'),
		  array('service_id'=>16,'list_item'=>'Improves overall look and quality of all plants'),
		  array('service_id'=>12,'list_item'=>'Improves appearance of all garden beds'),
		  array('service_id'=>12,'list_item'=>'Provides nutrients essential for healthy plant growth'),
		  array('service_id'=>10,'list_item'=>'Makes garden beds more attractive'),
		  array('service_id'=>2,'list_item'=>'Controls growth of larger plants')
		);
		
		foreach( $service_include_list as $item )
			$this->db->insert('service_include_list', $item);
			
		// data for status
		$status = array(
		  array('id'=>1,'text'=>'Query','description'=>'A user has queried for a quotation price and left the system'),
		  array('id'=>2,'text'=>'Pending','description'=>'We have not yet activated the clients maintenance plan'),
		  array('id'=>3,'text'=>'Activated','description'=>'User in on the maintenance plan'),
		  array('id'=>4,'text'=>'On Hold','description'=>'the plan is on hold')
		);
		
		foreach( $status as $item )
			$this->db->insert('status', $item);
			
		//data for time slots
		$time_slots = array(
		  array('id'=>1,'name'=>'Early Morning','avg_start'=>'08:30:00','avg_end'=>'10:00:00','enabled'=>1),
		  array('id'=>2,'name'=>'Late Morning','avg_start'=>'10:30:00','avg_end'=>'12:00:00','enabled'=>1),
		  array('id'=>3,'name'=>'Early Afternoon','avg_start'=>'12:30:00','avg_end'=>'02:00:00','enabled'=>1),
		  array('id'=>4,'name'=>'Late Afternoon','avg_start'=>'02:30:00','avg_end'=>'04:00:00','enabled'=>1)
		);
		
		foreach( $time_slots as $time_slot )
			$this->db->insert('time_slots', $time_slot);
		
		// developement data
		if( ENVIRONMENT == 'development' )
		{
			//get development data
			include_once( APPPATH . 'migrations/data/maintenance_plans.php');
			include_once( APPPATH . 'migrations/data/maintenance_records.php');
			include_once( APPPATH . 'migrations/data/maintenance_to_services.php');
			include_once( APPPATH . 'migrations/data/users.php');
			include_once( APPPATH . 'migrations/data/crews.php');
			
			//insert the data
			foreach( $maintenance_plans as $maintenance_plan )
				$this->db->insert('maintenance_plans', $maintenance_plan);
				
			foreach( $maintenance_records as $maintenance_record )
				$this->db->insert('maintenance_record', $maintenance_record);
				
			foreach( $maintenance_to_services as $maintenance_to_service )
				$this->db->insert('maintenance_to_services', $maintenance_to_service );
				
			foreach( $users as $user )
				$this->db->insert('users', $user);
				
			foreach( $crews as $crew )
				$this->db->insert('crews', $crew);
			
		}
	}
}