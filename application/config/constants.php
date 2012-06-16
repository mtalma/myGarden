<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//table names
define( 'MAINTENANCE_TABLE_NAME', 'maintenance_plans' );
define( 'SERVICE_CYCLE_TABLE_NAME', 'service_cycle' );
define( 'SERVICE_DAYS_TABLE_NAME', 'service_days' );
define( 'TIME_SLOT_TABLE_NAME', 'time_slots' );
define( 'QUOTE_TABLE_NAME', 'quotes' );
define( 'STATUS_TABLE_NAME', 'status' );
define( 'REGION_TABLE_NAME', 'regions' );
define( 'SERVICES_TABLE_NAME', 'services' );
define( 'SERVICE_LIST', 'service_include_list' );
define( 'GARDEN_SIZE_TABLE_NAME', 'garden_size' );
define( 'QUOTE_TO_SERVICE_TABLE_NAME', 'quotes_to_services' );
define( 'USER_TABLE_NAME', 'users' );

//maintenance_plan status
define( 'PLAN_QUERY', 1 );
define( 'PLAN_PENDING', 2 );
define( 'PLAN_ACTIVATED', 3 );
define( 'PLAN_ONHOLD', 4 );

//emails types
define( 'EMAIL_CUSTOM', 0 );
define( 'EMAIL_SIGNUP', 1 );
define( 'EMAIL_ACTIVATE', 2 );
define( 'EMAIL_ONHOLD', 3 );
define( 'EMAIL_INVITE', 4 );
define( 'EMAIL_REFER_SIGNUP', 5 );
define( 'EMAIL_RECORD_ADD', 6 );

//user status
define( 'USER_SIGNED_UP', 0 );
define( 'USER_REFERRED', 1 );
define( 'USER_ADMIN', 245 );
define( 'USER_SUPER_ADMIN', 255 );

//referral status
define( 'REFERRAL_SENT', 0 );
define( 'REFERRAL_SIGNEDUP', 1 );
define( 'REFERRAL_CANCELED', 2 );
define( 'REFERRAL_ACTIVATED', 3 );
define( 'REFERRAL_DISCOUNT_APPLIED', 4 );

//awards for referring in a percentage discount
define( 'DISCOUNT_REFERRER_PERCENT', 100 );
define( 'DISCOUNT_REFERREE_PERCENT', 0 );

define('ga_email','matthew.talma@gmail.com');
define('ga_password','!rYj7dhN');
define('ga_profile_id','18319139');

define( 'VAT', 0.15 );
define( 'DEBUG_MODE', TRUE );
define( 'MAINTENANCE_MODE', TRUE );

/* End of file constants.php */
/* Location: ./application/config/constants.php */